# Cakifo

> Cakifo is a free WordPress theme built with from the rock-solid [Hybrid Core theme framework](http://themehybrid.com/hybrid-core "Hybrid Core"), so it provides a great starting point with many useful features..

This theme is a parent theme. What this means is that to customize it, you should be creating a child theme. Is it a theme framework? Some people might call it that. All you need to know is that it's a solid, yet flexible, starting point for any blog.

The theme is not done yet but it's close!

## Demo

No demo available yet!

But here's a preview
![Cakifo preview](http://i.imgur.com/rUY1z.png)

## What needs to be done

* The header/logo upload can be done better
* Code overhaul
* Testing, browser testing, testing
* Code overhaul
* Demo

## Child themes

Since Cakifo is a parent theme, you'll want to create a child theme if you plan on making any customizations. *Don't know how to make a child theme?* It's relatively simple. Just follow the below steps.

* Create a theme folder in your /wp-content/themes directory called cakifo-child (or something else - you decide)
* Then, create a style.css file within your theme folder
* At the top of your style.css file, add the below information

<pre>
/**
 * Theme Name: Cakifo child
 * Theme URI: http://link-to-your-site.com
 * Description: Describe what your theme should be like.
 * Version: 1.0
 * Author: Your Name
 * Author URI: http://link-to-your-site.com
 * Tags: Add, Whatever, Tags, You, Want
 * Template: cakifo
 */
</pre>

This will give you a blank design. If you want to import the Cakifo parent theme style, simply append this code after the above information:

<pre>
@import url( '../cakifo/style.css' );

/* Custom code goes below here. */
</pre>

See more about [Child Themes at the Codex](http://codex.wordpress.org/Child_Themes)

#### Functions.php

You can make more than just style changes in a child theme. Unlike style.css, the functions.php of a child theme does not override its counterpart from the parent. Instead, it is loaded in addition to the parent's functions.php. (Specifically, it is loaded right before the parent's file.)

Adding functions or changing things in the parent theme will be lost when it's updated. Fortunately Cakifo is very user child theme friendly. It has a lot of hooks and filter to make it easy for you to change the functionality of the parent theme *in your child theme*. See the [Hybrid Core Hooks guide](http://themehybrid.com/hybrid-core/hooks) for more information.

##### Example
An example child theme is included in the download. But I'll give an example here as well

Let's say you want to change the speed of the slider. That's very easy. In your child theme functions.php do this

<pre>
function my_slider_args( $args ) {
	$args['slideSpeed'] = 500; // Speed of the sliding animation in milliseconds
	$args['play'] = 'false'; // Disables auto start - Remember the quotes
	
	return $args;
}

add_filter( 'cakifo_slider_args', 'my_slider_args' );
</pre>

Or you want to remove comments from pages. Easy

<pre>
	add_filter( 'show_singular_comments', '__return_false' ); 
</pre>

## Support
I can help a bit with support for this theme. But Theme Hybrid has a Theme Club where you'll get the benefit of access to all of the tutorials and documentation and complete read/write access to the support forums.
The support package exists to get you up and running as quickly as possible, and they have a great community of people willing to help each other out.

[See guides for Hybrid Core](http://themehybrid.com/hybrid-core)