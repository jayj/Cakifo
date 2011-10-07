<?php
/**
 * Additional shortcodes for use within the theme.
 *
 * @package Cakifo
 * @subpackage Functions
 */

/**
 * Registers new shortcodes.
 *
 * @since 1.0
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
	remove_shortcode( 'entry-published' );
	remove_shortcode( 'comment-published' );
	//remove_shortcode( 'entry-title' );
	remove_shortcode( 'entry-author' );
	
	add_shortcode( 'entry-published', 'cakifo_entry_published_shortcode' );
	add_shortcode( 'comment-published', 'cakifo_comment_published_shortcode' );
	//add_shortcode( 'entry-title', 'cakifo_entry_title_shortcode' );
	add_shortcode( 'entry-author', 'cakifo_entry_author_shortcode' );
}

/** 
 * RSS link shortcode
 *
 * @since 1.0
 * @param array $atts
 */
function cakifo_rss_link_shortcode( $atts ) {
	extract( shortcode_atts( array(   
		'before' => '',
		'after' => '',
		'text' => __( 'RSS', hybrid_get_textdomain() ),
 	), $atts ) );

	return $before . '<a href="' . esc_url( get_bloginfo( 'rss2_url' ) ) . '" class="rss-link">' .  $text . '</a>' . $after;
}

/**
 * Twitter username and/or link.
 *
 * Taken from my Twitter Profile Field plugin
 *
 * @link http://wordpress.org/extend/plugins/twitter-profile-field/
 * @since 1.0
 * @param array $atts
 */
function cakifo_twitter_shortcode( $atts ) {
	extract( shortcode_atts( array(   
    	'link' => true,
		'before' => '',
		'after' => '',
		'username' => hybrid_get_setting( 'twitter_username' ),
		'text' => __( 'Follow me on Twitter', hybrid_get_textdomain() )
 	), $atts ) );

	if ( empty( $username ) )
		return;

	if ( ! $link )
		return $username;
	else
		return $before . '<a href="http://twitter.com/' . esc_attr( $username ) . '" class="twitter-profile">' . $text . '</a>' . $after;
}

/**
 * Delicious link shortcode.
 *
 * @since 1.0
 * @param array $atts
 */
function cakifo_entry_delicious_link_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'before' => '',
		'after' => '',
 	), $atts) );

	return $before . '<a href="http://delicious.com/save" onclick="window.open(\'http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url=\'+encodeURIComponent(\'' . get_permalink() . '\')+\'&amp;title=\'+encodeURIComponent(\'' . the_title_attribute( 'echo=0' ) . '\'),\'delicious\', \'toolbar=no,width=550,height=550\'); return false;" class="delicious-share-button">' . __( 'Delicious', hybrid_get_textdomain() ) . '</a>' . $after;
}

/**
 * Digg link shortcode.
 *
 * @note This won't work from your computer (http://localhost). Must be a live site.
 * @since 1.0
 * @param array $atts
 */
function cakifo_entry_digg_link_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'before' => '',
		'after' => '',
 	), $atts) );

	$url = 'http://digg.com/submit?phase=2&amp;url=' . urlencode( get_permalink( get_the_ID() ) ) . '&amp;title="' . urlencode( the_title_attribute( 'echo=0' ) );
	
	return $before . '<a href="' . esc_url( $url ) . '" title="' . __( 'Digg this entry', hybrid_get_textdomain() ) . '" class="digg-share-button">' . __( 'Digg', hybrid_get_textdomain() ) . '</a>' . $after;
}

/**
 * Facebook share link shortcode.
 *
 * @note This won't work from your computer (http://localhost). Must be a live site.
 * @link http://developers.facebook.com/docs/reference/plugins/like/
 * @since 1.0
 * @param array $atts
 */
function cakifo_entry_facebook_link_shortcode( $atts ) {
	
	static $first = true;

	extract( shortcode_atts( array(
		'before' => '',
		'after' => '',
		'href' => get_permalink(),
		'layout' => 'standard', // standard, button_count, box_count
		'action' => 'like', // like, recommend
		'width' => 450,
		'faces' => 'false', // true, false
		'colorscheme' => 'light', // light, dark
		'locale' => get_locale(), // Language of the button - ex: da_DK, fr_FR
 	), $atts) );
	
	// Set default locale
	$locale = ( isset( $locale ) ) ? $locale : 'en_US';

	// Only add the script once
	$script = ( $first == true ) ? "<script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) {return;}js = d.createElement(s); js.id = id;js.src = '//connect.facebook.net/$locale/all.js#xfbml=1';fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>" : "";

	$first = false;

	$text = '<div class="fb-like" data-href="' . esc_url( $href ) . '" data-send="false" data-layout="' . esc_attr( $layout ) . '" data-width="' . intval( $width ) . '" data-show-faces="' . esc_attr( $faces ) . '" data-action="' . esc_attr( $action ) . '" data-colorscheme="' . esc_attr( $colorscheme ) . '"></div>';

	return $before . $text . $after . $script;
}

/**
 * Twitter link shortcode.
 *
 * @since 1.0
 * @param array $atts
 */
function cakifo_entry_twitter_link_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'before' => '',
		'after' => '',
		'href' => get_permalink(),
		'text' => the_title_attribute( 'echo=0' ),
		'layout' => 'horizontal', // horizontal, vertical, none
		'via' => hybrid_get_setting( 'twitter_username' ),
		'width' => 55, // Only need to use if there's no add_theme_support( 'cakifo-twitter-button' )
		'height' => 20, // Only need to use if there's no add_theme_support( 'cakifo-twitter-button' )
 	), $atts) );

	// Load the PHP tweet button script if the theme supports it
	if ( current_theme_supports( 'cakifo-twitter-button' ) ) :

		return cakifo_tweet_button( array(
			'before' => $before,
			'after' => $after,
			'layout' => $layout,
			'href' => $href,
			'text' => $text,
			'layout' => $layout,
			'via' => $via,
		) );

	// Load the Twitter iframe
	else :

		// Set the height to 62px if the layout is vertical and the height is the default value
		if ( $layout == 'vertical' && $height == 20 )
			$height = 62;

		// Set width to 110px if the layout is horizontal and the width is the default value
		if ( $layout == 'horizontal' && $width == 55 )
			$width = 110;

		return $before . '<iframe src="http://platform.twitter.com/widgets/tweet_button.html?url=' . urlencode( $href ) . '&amp;via=' . esc_attr( $via ) . '&amp;text=' . esc_attr( $text ) . '&amp;count=' . esc_attr( $layout ) . '" class="twitter-share-button" style="width:' . intval( $width ) . 'px; height:' . intval( $height ) . 'px;" allowtransparency="true" scrolling="no"></iframe>' . $after;
	
	endif;
}

/**
 * Google +1 shortcode
 *
 * @link http://www.google.com/+1/button/
 * @since 1.2
 * @param array $atts
 */
function cakifo_entry_googleplus_link_shortcode( $atts ) {

	static $first = true;

	extract( shortcode_atts( array(
		'before' => '',
		'after' => '',
		'href' => get_permalink(),
		'layout' => 'standard', // small, medium, standard, tall
		'callback' => '',
		'count' => 'true' // true, false
 	), $atts) );

	// Only add the script once
	$script = ( $first == true ) ? "<script>(function() {var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;po.src = 'https://apis.google.com/js/plusone.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);})();</script>" : "";

	$first = false;

	$text = '<div class="g-plusone" data-size="' . $layout . '" data-count="' . $count . '" data-href="' . $href . '" data-callback="' . $callback . '"></div>';

	return $before . $text . $after . $script;
}

/**
 * Displays the published date of an individual post in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @since 1.1
 * @param array $atts
 */
function cakifo_entry_published_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'before' => '',
		'after' => '',
		'format' => get_option( 'date_format' ),
		'pubdate' => true,
	), $atts );

	// Pubdate attribute can be removed with [entry-published pubdate="something"] 
	$pubdate = ( $atts['pubdate'] === true ) ? 'pubdate' : '';

	$published = '<time class="published" datetime="' . get_the_date( 'c' ) . '" ' . $pubdate . '>' . get_the_date( $atts['format'] ) . '</time>';

	return $atts['before'] . $published . $atts['after'];
}

/**
 * Displays the published date of an individual comment in HTML5 format.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @since 1.1
 * @param array $atts
 */
function cakifo_comment_published_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'before' => '',
		'after' => ''
	), $atts );

	$published = '<time class="published" datetime="' . get_comment_date( 'c' ) . '" pubdate>' . get_comment_date() . '</time>';

	return $atts['before'] . $published . $atts['after'];
}

/**
 * Displays an individual post's author with a link to his or her archive.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @since 1.3
 * @param array $atts
 */
function cakifo_entry_author_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'rel' => 'author',
		'before' => '',
		'after' => '',
	), $atts );

	$author = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '">' . get_the_author_meta( 'display_name' ) . '</a></span>';

	return $atts['before'] . $author . $atts['after'];
}

/**
 * Displays the post format of the current post
 *
 * @since 1.3
 * @param array $atts
 */
function cakifo_entry_format_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'before' => '',
		'after' => ''
	), $atts );

	return $atts['before'] . get_post_format() . $atts['after'];
}

/**
 * Displays a post's title with a link to the post.
 *
 * It replaces the default Hybrid Core shortcode. The name is still the the same
 *
 * @since 1.3
 * @param array $atts
 */
/*function cakifo_entry_title_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'heading' => 'h1'
	), $atts );

	global $post;

	// $atts['heading']

	$title = the_title( "<{$atts['heading']} class='" . esc_attr( $post->post_type ) . "-title entry-title'><a href='" . get_permalink() . "' title='" . the_title_attribute( 'echo=0' ) . "' rel='bookmark'>", "</a></{$atts['heading']}>", false );

	if ( 'link_category' == get_query_var( 'taxonomy' ) )
		$title = false;

	// If there's no post title, return a clickable '(No title)'.
	if ( empty( $title ) && ! is_singular() && 'link_category' !== get_query_var( 'taxonomy' ) )
		$title = "<{$atts['heading']}  class='entry-title no-entry-title'><a href='" . get_permalink() . "' rel='bookmark'>" . __( '(Untitled)', hybrid_get_textdomain() ) . "</a></{$atts['heading']}>";

	return $title;
}*/

?>