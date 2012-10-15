<?php
/**
 * Additional shortcodes for use within the theme.
 *
 * @package Cakifo
 * @subpackage Functions
 */

/**
 * Registers new shortcodes
 *
 * @since Cakifo 1.0
 */
function cakifo_register_shortcodes() {
	add_shortcode( 'rss-link', 'cakifo_rss_link_shortcode' );
	add_shortcode( 'twitter-username', 'cakifo_twitter_shortcode' );
	add_shortcode( 'entry-delicious-link', 'cakifo_entry_delicious_link_shortcode' );
	add_shortcode( 'entry-digg-link', 'cakifo_entry_digg_link_shortcode' );
	add_shortcode( 'entry-facebook-link', 'cakifo_entry_facebook_link_shortcode' );
	add_shortcode( 'entry-twitter-link', 'cakifo_entry_twitter_link_shortcode' );
	add_shortcode( 'entry-googleplus-link', 'cakifo_entry_googleplus_link_shortcode' );
	add_shortcode( 'entry-format', 'cakifo_entry_format_shortcode' );

	/* Replace some Hybrid Core shortcodes */
	remove_shortcode( 'entry-title' );
	add_shortcode( 'entry-title', 'cakifo_entry_title_shortcode' );

	remove_shortcode( 'entry-published' );
	add_shortcode( 'entry-published', 'cakifo_entry_published_shortcode' );

	remove_shortcode( 'comment-published' );
	add_shortcode( 'comment-published', 'cakifo_comment_published_shortcode' );
}

add_action( 'init', 'cakifo_register_shortcodes', 15 );

/**
 * RSS link shortcode
 *
 * @param  array  $attr
 * @return string  The RSS link
 * @since Cakifo 1.0
 */
function cakifo_rss_link_shortcode( $attr ) {
	extract( shortcode_atts( array(
		'before' => '',
		'after'  => '',
		'text'   => __( 'RSS', 'cakifo' ),
	), $attr ) );

	return $before . '<a href="' . esc_url( get_bloginfo( 'rss2_url' ) ) . '" class="rss-link">' .  $text . '</a>' . $after;
}

/**
 * Twitter username and/or link to profile.
 *
 * Taken from my Twitter Profile Field plugin
 *
 * @link (http://wordpress.org/extend/plugins/twitter-profile-field/, Twitter Profile Field)
 * @param  array  $attr
 * @return string  The Twitter username or username with a link to the profile.
 * @since Cakifo 1.0
 */
function cakifo_twitter_shortcode( $attr ) {
	extract( shortcode_atts( array(
		'link'     => true,
		'before'   => '',
		'after'    => '',
		'username' => hybrid_get_setting( 'twitter_username' ),
		'text'     => __( 'Follow me on Twitter', 'cakifo' )
	), $attr ) );

	if ( empty( $username ) )
		return;

	if ( $link !== true )
		return $username;
	else
		return $before . '<a href="http://twitter.com/' . esc_attr( $username ) . '" class="twitter-profile">' . $text . '</a>' . $after;
}

/**
 * Delicious link shortcode
 *
 * @param array  $attr
 * @since Cakifo 1.0
 */
function cakifo_entry_delicious_link_shortcode( $attr ) {
	extract( shortcode_atts( array(
		'before' => '',
		'after'  => '',
	), $attr) );

	return $before . '<a href="http://delicious.com/save" onclick="window.open(\'http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url=\'+encodeURIComponent(\'' . get_permalink() . '\')+\'&amp;title=\'+encodeURIComponent(\'' . the_title_attribute( 'echo=0' ) . '\'),\'delicious\', \'toolbar=no,width=550,height=550\'); return false;" class="delicious-share-button">' . __( 'Delicious', 'cakifo' ) . '</a>' . $after;
}

/**
 * Digg link shortcode
 *
 * @note This won't work from your computer (http://localhost). Must be a live site.
 *
 * @param array  $attr
 * @since Cakifo 1.0
 */
function cakifo_entry_digg_link_shortcode( $attr ) {
	extract( shortcode_atts( array(
		'before' => '',
		'after'  => '',
	), $attr) );

	$url = 'http://digg.com/submit?phase=2&amp;url=' . urlencode( get_permalink( get_the_ID() ) ) . '&amp;title="' . urlencode( the_title_attribute( 'echo=0' ) );

	return $before . '<a href="' . esc_url( $url ) . '" title="' . __( 'Digg this entry', 'cakifo' ) . '" class="digg-share-button">' . __( 'Digg', 'cakifo' ) . '</a>' . $after;
}

/**
 * Facebook share link shortcode.
 *
 * @note This won't work from your computer (http://localhost). Must be a live site.
 * @link http://developers.facebook.com/docs/reference/plugins/like/
 *
 * @param array  $attr
 * @since Cakifo 1.0
 */
function cakifo_entry_facebook_link_shortcode( $attr ) {

	static $first = true;

	extract( shortcode_atts( array(
		'before'      => '',
		'after'       => '',
		'href'        => get_permalink(),
		'layout'      => 'standard', // standard, button_count, box_count
		'action'      => 'like', // like, recommend
		'width'       => 450,
		'faces'       => 'false', // true, false
		'colorscheme' => 'light', // light, dark
		'locale'      => get_locale(), // Language of the button - ex: da_DK, fr_FR
	), $attr) );

	// Set default locale
	$locale = ( isset( $locale ) ) ? $locale : 'en_US';

	// Only add the script once
	$script = ( $first == true ) ? "<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) {return;}js = d.createElement(s); js.id = id;js.src = '//connect.facebook.net/$locale/all.js#xfbml=1';fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>" : "";

	$first = false;

	$text = '<div class="fb-like" data-href="' . esc_url( $href ) . '" data-send="false" data-layout="' . esc_attr( $layout ) . '" data-width="' . intval( $width ) . '" data-show-faces="' . esc_attr( $faces ) . '" data-action="' . esc_attr( $action ) . '" data-colorscheme="' . esc_attr( $colorscheme ) . '"></div>';

	return $before . $text . $after . $script;
}

/**
 * Twitter link shortcode
 *
 * @param array  $attr
 * @since Cakifo 1.0
 */
function cakifo_entry_twitter_link_shortcode( $attr ) {
	extract( shortcode_atts( array(
		'before' => '',
		'after'  => '',
		'href'   => get_permalink(),
		'text'   => the_title_attribute( 'echo=0' ),
		'layout' => 'horizontal', // horizontal, vertical, none
		'via'    => hybrid_get_setting( 'twitter_username' ),
		'width'  => 55, // Only need to use if there's no add_theme_support( 'cakifo-twitter-button' )
		'height' => 20, // Only need to use if there's no add_theme_support( 'cakifo-twitter-button' )
	), $attr) );

	// Load the PHP tweet button script if the theme supports it
	if ( current_theme_supports( 'cakifo-twitter-button' ) ) :

		return cakifo_tweet_button( array(
			'before' => $before,
			'after'  => $after,
			'layout' => $layout,
			'href'   => $href,
			'text'   => $text,
			'layout' => $layout,
			'via'    => $via,
		) );

	// Else, load the Twitter iframe
	else :

		// Set the height to 62px if the layout is vertical and the height is the default value
		if ( $layout == 'vertical' && $height == 20 )
			$height = 62;

		// Set width to 110px if the layout is horizontal and the width is the default value
		if ( $layout == 'horizontal' && $width == 55 )
			$width = 110;

		// Build the query
		$query_args = array(
			'url'   => $href,
			'via'   => esc_attr( $via ),
			'text'  => esc_attr( $text ),
			'count' => esc_attr( $layout )
		);

		return $before . '<iframe src="http://platform.twitter.com/widgets/tweet_button.html?' . http_build_query( $query_args, '', '&amp;' ) . '" class="twitter-share-button" style="width:' . intval( $width ) . 'px; height:' . intval( $height ) . 'px;" scrolling="no" seamless></iframe>' . $after;

	endif;
}

/**
 * Google +1 shortcode
 *
 * @link (http://www.google.com/+1/button/, Google+ button)
 * @param array  $attr
 * @since Cakifo 1.2
 */
function cakifo_entry_googleplus_link_shortcode( $attr ) {

	static $first = true;

	extract( shortcode_atts( array(
		'before'   => '',
		'after'    => '',
		'href'     => get_permalink(),
		'layout'   => 'standard', // small, medium, standard, tall
		'callback' => '',
		'count'    => 'true' // true, false
	), $attr) );

	// Only add the script once
	$script = ( $first == true ) ? "<script>(function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();</script>" : "";

	$first = false;

	$text = '<div class="g-plusone" data-size="' . $layout . '" data-count="' . $count . '" data-href="' . $href . '" data-callback="' . $callback . '"></div>';

	return $before . $text . $after . $script;
}

/**
 * Displays a post's title with a link to the post.
 * This version allows you to overwrite the tag.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @param array $attr
 * @since Cakifo 1.5
 */
function cakifo_entry_title_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'permalink' => true,
		'tag'       => 'h1'
	), $attr );

	$tag = $attr['tag'];
	$class = sanitize_html_class( get_post_type() ) . '-title entry-title';

	if ( false == (bool) $attr['permalink'] )
		$title = the_title( "<{$tag} class='{$class}'>", "</{$tag}>", false );
	else
		$title = the_title( "<{$tag} class='{$class}'><a href='" . get_permalink() . "'>", "</a></{$tag}>", false );

	if ( empty( $title ) && ! is_singular() )
		$title = "<{$tag} class='{$class}'><a href='" . get_permalink() . "'>" . __( '(Untitled)', 'cakifo' ) . "</a></{$tag}>";

	return $title;
}

/**
 * Displays the published date of an individual post in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @param array $attr
 * @since Cakifo 1.1
 */
function cakifo_entry_published_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before'  => '',
		'after'   => '',
		'format'  => get_option( 'date_format' ),
		'pubdate' => true,
	), $attr );

	// Pubdate attribute can be removed with [entry-published pubdate="something"]
	$pubdate = ( $attr['pubdate'] === true ) ? 'pubdate' : '';

	$published = '<time class="published" datetime="' . get_the_date( 'c' ) . '" ' . $pubdate . '>' . get_the_date( $attr['format'] ) . '</time>';

	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays the published date of an individual comment in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @param array  $attr
 * @since Cakifo 1.1
 */
function cakifo_comment_published_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr );

	$published = '<time class="published" datetime="' . get_comment_date( 'c' ) . '" pubdate>' . get_comment_date() . '</time>';

	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays the post format of the current post
 *
 * @param array  $attr
 * @since Cakifo 1.3
 */
function cakifo_entry_format_shortcode( $attr ) {
	$attr = shortcode_atts( array(
		'before' => '',
		'after'  => ''
	), $attr );

	return $attr['before'] . get_post_format() . $attr['after'];
}

?>
