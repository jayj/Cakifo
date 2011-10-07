<?php
/**
 * Custom Tweet Button for WordPress
 * 
 * This is my fork of Nicolas Gallagher original "Custom Tweet Button for WordPress"
 * It's a fully customisable HTML and CSS Tweet Button for WordPress built using PHP
 *
 * @version 1.0
 * @author Nicolas Gallagher and Jesper J
 * @link http://nicolasgallagher.com/custom-tweet-button-for-wordpress/
 
	Copyright 2010-2011 Nicolas Gallagher
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function cakifo_tweet_button( $args = array() ) {
    
	// Count how many times the function has been referenced
	static $i = 0;
    $i++;

	// Set up default values
	$defaults = array(
		'before' => '',
		'after' => '',
		'href' => wp_get_shortlink(),
		'via' => hybrid_get_setting( 'twitter_username' ),
		'text' => the_title_attribute( 'echo=0' ),
		'related' => '',
		'layout' => 'vertical', // none, horizontal, vertical
		'counturl' => get_permalink(),
	);
	
	// Merge them
	$args = wp_parse_args( $args, $defaults );
	
	// Set up variables
	$post_id = get_queried_object_id();
	$url = trailingslashit( $args['href'] );
	$counturl = trailingslashit( $args['counturl'] );
	$cache_interval = 60;
    $refresh_interval = 3660;
    $retweet_count = null;
    $count = 0;
	$counter = '';
	
	// Retweet data (Twitter API)
	$retweet_meta = get_post_meta( $post_id, "retweet_{$i}_cache", true );

	if ( $retweet_meta != '' ) {
		$retweet_pieces = explode( ':', $retweet_meta );
		$retweet_timestamp = (int) $retweet_pieces[0];
		$retweet_count = (int) $retweet_pieces[1];
	}
	
	// Expire retweet cache
	if ( $retweet_count === null || time() > $retweet_timestamp + $cache_interval ) :
	
		$retweet_response = wp_remote_get( 'http://urls.api.twitter.com/1/urls/count.json?url=' . $counturl );
		
		if ( ! is_wp_error( $retweet_response ) ) :

			$retweet_data = json_decode( wp_remote_retrieve_body( $retweet_response ), true );

			if ( isset( $retweet_data['count'] ) && isset( $retweet_data['url'] ) && $retweet_data['url'] === $counturl ) :

				if ( (int) $retweet_data['count'] >= $retweet_count || time() > $retweet_timestamp + $refresh_interval ) :

					$retweet_count = $retweet_data['count'];

					if ( $retweet_meta == '' )
						add_post_meta( $post_id, "retweet_{$i}_cache", time() . ':' . $retweet_count );
					else
						update_post_meta( $post_id, "retweet_{$i}_cache", time() . ':' . $retweet_count );
		
				endif;

			endif;

		endif; // $retweet_response

	endif; // expire retweet cache

	/**
	 * Check for "retweet_count_start" custom field
	 *
	 * Manually set the starting number of retweets for a post that existed before the Tweet Button was created
	 * the number can be roughly calculated by subtracting the twitter API's retweet count
	 * from the estimated number of retweets according to the topsy, backtype, or tweetmeme services
	 */
	$retweet_count_start = get_post_meta( $post_id, "retweet_{$i}_count_start", true );
	
	/* Calculate the total count to display */
	$count = $retweet_count + (int) $retweet_count_start;

	if ( $count > 9999 ) {
		$count = $count / 1000;
		$count = number_format( $count, 1 ) . 'K';
	} else {
		$count = number_format( $count );
	}

	/**
	 * Construct the tweet button query string
	 */
	$twitter_params = '?text=' . urlencode( $args['text'] ) . '+-';
	$twitter_params .= '&amp;url=' . urlencode( $url );
	$twitter_params .= '&amp;counturl=' . urlencode( $args['counturl'] );
	$twitter_params .= ( $args['via'] ) ? '&amp;via=' . $args['via'] : '';
	$twitter_params .= ( $args['related'] ) ? '&amp;related=' . $args['related'] : '';
	
	if ( $args['layout'] != 'none' )
		$counter = '<a class="twitter-count" href="http://twitter.com/search?q=' . urlencode( $url ) . '" target="_blank">' . $count . '</a>';
		
	// HTML for the tweet button
	$twitter_share = '
		<div class="twitter-share twitter-button-size-' . sanitize_html_class( $args['layout'] ) . '">
			<a class="twitter-button" rel="external nofollow" title="Share this on Twitter" href="http://twitter.com/share' . $twitter_params . '" target="_blank">Tweet</a>' . $counter . '
		</div>
	';

	return $args['before'] . $twitter_share . $args['after'];
}