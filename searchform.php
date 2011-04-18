<?php
/**
 * Search Form Template
 *
 * The search form template displays the search form.
 *
 * @package Cakifo
 * @subpackage Template
 */
?>

<?php
	// Set the value on the search input
	if ( is_search() )
		$value = 'value="' . esc_attr( get_search_query() ) . '"'; // Search query for the search page
	elseif ( is_404() )
		$value = 'value="' . esc_attr( basename($_SERVER['REQUEST_URI']) ) . '"'; // Requested URI for 404 page
	else
		$value= 'placeholder="' . esc_attr__( 'Search this site...', hybrid_get_textdomain() ) . '"'; // Or Search this site... as placeholder
?>

<div class="search">

    <form method="get" class="search-form" action="<?php echo trailingslashit( home_url() ); ?>">
        <div>
            <input class="search-text" type="text" name="s" <?php echo $value; ?> />
            <input class="search-submit" type="submit" value="<?php esc_attr_e( 'Search', hybrid_get_textdomain() ); ?>" />
        </div>
    </form> <!-- .search-form -->

</div> <!-- .search -->