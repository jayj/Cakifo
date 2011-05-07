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
	
	/* Replace shortcodes */
	remove_shortcode( 'entry-published' );
	remove_shortcode( 'comment-published' );
	add_shortcode( 'entry-published', 'cakifo_entry_published_shortcode' );
	add_shortcode( 'comment-published', 'cakifo_comment_published_shortcode' );
}

/** 
 * RSS link shortcode
 *
 * @since 1.0
 */
function cakifo_rss_link_shortcode( $atts ) {

	extract( shortcode_atts( array(   
		'before' => '',
		'after' => '',
 	), $atts) );

	return $before . '<a href="' . get_bloginfo( 'rss2_url' ) . '" class="rss-link">' .  __( 'RSS', hybrid_get_textdomain() ) . '</a>' . $after;
}

/**
 * Twitter username and/or link. 
 * Taken from my Twitter Profile Field plugin
 *
 * @link http://wordpress.org/extend/plugins/twitter-profile-field/
 * @since 1.0
 */
function cakifo_twitter_shortcode( $atts ) {

	extract( shortcode_atts( array(   
    	'link' => true,
		'before' => '',
		'after' => '',
		'username' => hybrid_get_setting( 'twitter_username' ),
		'text' => __( 'Follow me on Twitter', hybrid_get_textdomain() )
 	), $atts) );

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
 */
function cakifo_entry_delicious_link_shortcode( $atts ) {

	extract( shortcode_atts( array(   
		'before' => '',
		'after' => '',
 	), $atts) );

	return $before . '<a href="http://delicious.com/save" onclick="window.open(\'http://delicious.com/save?v=5&amp;noui&amp;jump=close&amp;url=\'+encodeURIComponent(\'' . get_permalink() . '\')+\'&amp;title=\'+encodeURIComponent(\'' . the_title_attribute( 'echo=0' ) . '\'),\'delicious\', \'toolbar=no,width=550,height=550\'); return false;">' . __( 'Delicious', hybrid_get_textdomain() ) . '</a>' . $after;
}

/**
 * Digg link shortcode.
 * @note This won't work from your computer (http://localhost). Must be a live site.
 *
 * @since 1.0
 */
function cakifo_entry_digg_link_shortcode( $atts ) {

	extract( shortcode_atts( array(   
		'before' => '',
		'after' => '',
 	), $atts) );

	$url = esc_url( 'http://digg.com/submit?phase=2&amp;url=' . urlencode( get_permalink( get_the_ID() ) ) . '&amp;title="' . urlencode( the_title_attribute( 'echo=0' ) ) );
	
	return $before . '<a href="' . $url . '" title="' . __( 'Digg this entry', hybrid_get_textdomain() ) . '">' . __( 'Digg', hybrid_get_textdomain() ) . '</a>' . $after;
}

/**
 * Facebook share link shortcode.
 * @note This won't work from your computer (http://localhost). Must be a live site.
 *
 * @link http://developers.facebook.com/docs/reference/plugins/like/
 * @since 1.0
 */
function cakifo_entry_facebook_link_shortcode( $atts ) {

	extract( shortcode_atts( array(   
		'before' => '',
		'after' => '',
		'href' => get_permalink(),
		'layout' => 'standard', // standard, button_count, box_count
		'action' => 'like' ,// like, recommend
		'width' => 450,
		'height' => 25,
		'colorscheme' => 'light', // light, dark
		'locale' => get_locale(), // Language of the button - ex: da_DK, fr_FR
 	), $atts) );

	// Set the height to 62px if the layout is box_count and the height is lower than 62px
	if ( $layout == 'box_count' && $height < 60 )
		$height = 62;
	
	return $before . '<iframe src="http://www.facebook.com/plugins/like.php?href=' . urlencode( $href ) . '&amp;layout=' . esc_attr( $layout ) . '&amp;width=' . intval( $width ) . '&amp;action=' . esc_attr( $action ) . '&amp;font&amp;colorscheme=' . esc_attr( $colorscheme ) . '&amp;height=' . intval( $height ) . '&amp;locale=' . esc_attr( $locale ) . '" class="facebook-share-button" style="width:' . intval( $width ) . 'px; height:' . intval( $height ) . 'px;" allowTransparency="true" scrolling="no"></iframe>' . $after;
}

/**
 * Twitter link shortcode.
 *
 * @since 1.0
 */
function cakifo_entry_twitter_link_shortcode( $atts ) {

	extract( shortcode_atts( array(   
		'before' => '',
		'after' => '',
		'href' => get_permalink(),
		'text' => the_title_attribute( 'echo=0' ),
		'layout' => 'horizontal', // horizontal, vertical, none
		'width' => 55,
		'height' => 20,
		'via' => hybrid_get_setting( 'twitter_username' ),	
 	), $atts) );

	// Set the height to 62px if the layout is vertical and the height is lower than 62px
	if ( $layout == 'vertical' && $height < 62 )
		$height = 62;

	// Set width to 110px if the layout is horizontal and the width is lower than 55px
	if ( $layout == 'horizontal' && $width <= 55 )
		$width = 110;

	return $before . '<iframe src="http://platform.twitter.com/widgets/tweet_button.html?url=' . urlencode( $href ) . '&amp;via=' . esc_attr( $via ) . '&amp;text=' . esc_attr( $text ) . '&amp;count=' . esc_attr( $layout ) . '" class="twitter-share-button" style="width:' . intval( $width ) . 'px; height:' . intval( $height ) . 'px;" allowtransparency="true" scrolling="no"></iframe>' . $after;
}

/**
 * Displays the published date of an individual post in HTML5 format.
 * It replaces the default Hybrid Core shortcode. The name will be the same
 *
 * @since 1.1
 * @param array $attr
 */
function cakifo_entry_published_shortcode( $attr ) {

	$attr = shortcode_atts( array(
		'before' => '',
		'after' => '',
		'format' => get_option( 'date_format' ),
		'pubdate' => true,
	), $attr );

	// Pubdate attribute can be removed with [entry-published pubdate="something"] 
	$pubdate = ( $attr['pubdate'] === true ) ? 'pubdate' : '';

	$published = '<time class="published" datetime="' . get_the_date( 'c' ) . '" ' . $pubdate . '>' . get_the_date( $attr['format'] ) . '</time>';

	return $attr['before'] . $published . $attr['after'];
}

/**
 * Displays the published date of an individual comment in HTML5 format.
 * It replaces the default Hybrid Core shortcode. The name will be the same
 *
 * @since 1.1
 * @param array $attr
 */
function cakifo_comment_published_shortcode( $attr ) {

	$attr = shortcode_atts( array(
		'before' => '',
		'after' => ''
	), $attr );

	$published = '<time class="published" datetime="' . get_comment_date( 'c' ) . '" pubdate>' . get_comment_date() . '</time>';

	return $attr['before'] . $published . $attr['after'];
}


?>