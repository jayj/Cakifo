<?php
/**
 * Shortcodes bundled for use within the theme.  These shortcodes are not meant to be used with the post content
 * editor.  Their purpose is to make it easier for users to filter hooks without having to know too much PHP code
 * and to provide access to specific functionality in other (non-post content) shortcode-aware areas.
 *
 * @package Cakifo
 * @subpackage Functions
 */

/**
 * Registers new shortcodes
 *
 * @since Cakifo 1.0.0
 */
function cakifo_register_shortcodes() {
	add_shortcode( 'rss-link',              'cakifo_rss_link_shortcode' );
	add_shortcode( 'twitter-username',      'cakifo_twitter_shortcode' );
	add_shortcode( 'entry-delicious-link',  'cakifo_entry_delicious_link_shortcode' );
	add_shortcode( 'entry-digg-link',       'cakifo_entry_digg_link_shortcode' );
	add_shortcode( 'entry-facebook-link',   'cakifo_entry_facebook_link_shortcode' );
	add_shortcode( 'entry-twitter-link',    'cakifo_entry_twitter_link_shortcode' );
	add_shortcode( 'entry-googleplus-link', 'cakifo_entry_googleplus_link_shortcode' );
	add_shortcode( 'entry-format',          'cakifo_entry_format_shortcode' );

	/* Replace some Hybrid Core shortcodes */
	remove_shortcode( 'entry-published' );
	add_shortcode( 'entry-published', 'cakifo_entry_published_shortcode' );

	remove_shortcode( 'comment-published' );
	add_shortcode( 'comment-published', 'cakifo_comment_published_shortcode' );
}

add_action( 'init', 'cakifo_register_shortcodes', 15 );

/**
 * RSS link shortcode
 *
 * @since Cakifo 1.0.0
 * @param array $attr
 * @return string The RSS link
 */
function cakifo_rss_link_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'text'   => __( 'RSS', 'cakifo' ),
		'before' => '',
		'after'  => '',
	), $attr, 'rss-link' );

	return $attr['before'] . '<a href="' . esc_url( get_bloginfo( 'rss2_url' ) ) . '" class="rss-link">' .  $attr['text'] . '</a>' . $attr['after'];
}

/**
 * Twitter username and/or link to profile.
 *
 * Taken from my Twitter Profile Field plugin
 *
 * @link http://wordpress.org/extend/plugins/twitter-profile-field/ Twitter Profile Field
 * @param array $attr
 * @return string The Twitter username or username with a link to the profile.
 * @since Cakifo 1.0.0
 */
function cakifo_twitter_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'username' => hybrid_get_setting( 'twitter_username' ),
		'text'     => __( 'Follow me on Twitter', 'cakifo' ),
		'link'     => true,
		'before'   => '',
		'after'    => '',
	), $attr, 'cakifo-twitter-username' );

	if ( empty( $attr['username'] ) )
		return;

	if ( $attr['link'] !== true )
		return $attr['username'];
	else
		return $attr['before'] . '<a href="http://twitter.com/' . esc_attr( $attr['username'] ) . '" class="twitter-profile">' . $attr['text'] . '</a>' . $attr['after'];
}

/**
 * Delicious link shortcode
 *
 * @param array $attr
 * @since Cakifo 1.0.0
 */
function cakifo_entry_delicious_link_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr, 'entry-delicious-link' );

	return $attr['before'] . '<a href="http://delicious.com/save" onclick="window.open(\'http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url=\'+encodeURIComponent(\'' . get_permalink() . '\')+\'&amp;title=\'+encodeURIComponent(\'' . the_title_attribute( 'echo=0' ) . '\'),\'delicious\', \'toolbar=no,width=550,height=550\'); return false;" class="delicious-share-button">' . __( 'Save on Delicious', 'cakifo' ) . '</a>' . $attr['after'];

}

/**
 * Digg link shortcode
 *
 * @note This won't work from your computer (http://localhost). Must be a live site.
 *
 * @param array $attr
 * @since Cakifo 1.0.0
 */
function cakifo_entry_digg_link_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr, 'entry-digg-link' );

	$url = 'http://digg.com/submit?url=' . urlencode( get_permalink( get_the_ID() ) );

	return $attr['before'] . '<a href="' . esc_url( $url ) . '" title="' . esc_attr__( 'Digg this entry', 'cakifo' ) . '" class="digg-share-button">' . __( 'Digg', 'cakifo' ) . '</a>' . $attr['after'];
}

/**
 * Facebook share link shortcode.
 *
 * @note This won't work from your computer (http://localhost). Must be a live site.
 * @link http://developers.facebook.com/docs/reference/plugins/like/
 * @param array $attr
 * @since Cakifo 1.0.0
 */
function cakifo_entry_facebook_link_shortcode( $attr ) {

	static $first = true;

	$attr = shortcode_atts( array(
		'href'        => get_permalink(),
		'layout'      => 'standard', // standard, button_count, box_count
		'action'      => 'like', // like, recommend
		'send'        => 'false', // true, false
		'faces'       => 'false', // true, false
		'colorscheme' => 'light', // light, dark
		'locale'      => get_locale(), // Language of the button - ex: da_DK, fr_FR. This only works for the first button at the page
		'width'       => '',
		'before'      => '',
		'after'       => '',
	), $attr, 'entry-facebook-link' );

	// Set default locale
	$locale = ( isset( $attr['locale'] ) ) ? $attr['locale'] : 'en_US';

	// Only add the script once
	$script = ( $first ) ? "<div id='fb-root'></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = '//connect.facebook.net/$locale/all.js#xfbml=1';fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>" : '';

	$first = false;

	$output = '<div class="fb-like"
				data-href="' . esc_url( $attr['href'] ) . '"
				data-send="' . esc_attr( $attr['send'] ) . '"
				data-layout="' . esc_attr( $attr['layout'] ) . '"
				data-width="' . intval( $attr['width'] ) . '"
				data-show-faces="' . esc_attr( $attr['faces'] ) . '"
				data-action="' . esc_attr( $attr['action'] ) . '"
				data-colorscheme="' . esc_attr( $attr['colorscheme'] ) . '">
			</div>';

	return $attr['before'] . $output . $attr['after'] . $script;
}

/**
 * Twitter share shortcode
 *
 * @param array $attr
 * @since Cakifo 1.0.0
 */
function cakifo_entry_twitter_link_shortcode( $attr ) {

	static $first = true;

	$attr = shortcode_atts( array(
		'href'   => get_permalink(),
		'text'   => the_title_attribute( 'echo=0' ),
		'layout' => 'horizontal', // horizontal, vertical, non
		'via'    => '',
		'before' => '',
		'after'  => '',
	), $attr, 'entry-twitter-link' );

	// Only add the script once
	$script = ( $first ) ? "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>" : "";

	$first = false;

	// Build the query
	$query_args = array(
		'url'      => esc_url( $attr['href'] ),
		'counturl' => esc_url( $attr['href'] ),
		'text'     => esc_attr( $attr['text'] ),
		'count'    => esc_attr( $attr['layout'] ),
	);

	/* Use the shortlink as the share URL if it exists. */
	$shortlink = wp_get_shortlink();

	if ( ! empty( $shortlink ) ) {
		$query_args['url'] = esc_url( $shortlink );
	}

	/* Set 'via' attribute. */
	if ( ! empty( $attr['via'] ) ) {
		$query_args['via'] = esc_attr( $attr['via'] );
	}

	return $attr['before'] . '<a href="https://twitter.com/share?' . http_build_query( $query_args, '', '&amp;' ) . '" class="twitter-share-button"></a>' . $attr['after'] . $script;
}

/**
 * Google +1 shortcode
 *
 * @link https://developers.google.com/+/plugins/+1button/ Google+ button
 * @param array $attr
 * @since Cakifo 1.2.0
 */
function cakifo_entry_googleplus_link_shortcode( $attr ) {

	static $first = true;

	$attr = shortcode_atts( array(
		'href'       => get_permalink(),
		'layout'     => 'standard', // small, medium, standard, tall
		'annotation' => 'bubble', // Bubble, inline, none
		'count'      => 'true', // @deprecated Use annotation instead
		'align'      => 'left', // left, right
		'callback'   => '',
		'before'     => '',
		'after'      => '',
	), $attr, 'entry-googleplus-link' );

	// The count parameter is deprecated. Use annotation="none" instead
	if ( $attr['count'] !== 'true' ) {
		$attr['annotation'] = 'none';
	}

	// Only add the script once
	$script = ( $first ) ? "<script>(function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();</script>" : "";

	$first = false;

	$output = '<div class="g-plusone"
				data-size="' . esc_attr( $attr['layout'] ) . '"
				data-annotation="' . esc_attr( $attr['annotation'] ) . '"
				data-align="' . esc_attr( $attr['align'] ) . '"
				data-href="' . esc_url( $attr['href'] ) . '"
				data-callback="' . $attr['callback'] . '">
			</div>';

	return $attr['before'] . $output . $attr['after'] . $script;
}

/**
 * Displays the published date of an individual post in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @param array $attr
 * @since Cakifo 1.1.0
 */
function cakifo_entry_published_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'format' => get_option( 'date_format' ),
		'before' => '',
		'after'  => '',
	), $attr, 'entry-published' );

	$published = '<time class="published" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . get_the_date( $attr['format'] ) . '</time>';

	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays the published date of an individual comment in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @param array $attr
 * @since Cakifo 1.1.0
 */
function cakifo_comment_published_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr, 'comment-published' );

	$published = '<time class="published" datetime="' . esc_attr( get_comment_date( 'c' ) ) . '">' . get_comment_date() . '</time>';

	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays the post format of the current post
 *
 * @param array $attr
 * @since Cakifo 1.3.0
 */
function cakifo_entry_format_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr, 'entry-format' );

	return $attr['before'] . get_post_format() . $attr['after'];
}

?>
