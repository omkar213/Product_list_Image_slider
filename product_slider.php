<?php
/*
Plugin Name:  Product Slider
Plugin URI:   https://www.wpbeginner.com 
Description:  Plugins is used for display Product List with Image Slider.
Version:      1.0
Author:       KRUSHNA53
Author URI:   https://krushna53.com/
*/

//Note: To run this image slider , you should have to put multiple images for single product in the gallery section
// To run this plugin write this shortcode for example [product_slider count="6"] here "count" refer as the number of 
// you want display in slider

function custom_shortcode($atts) {
  $all_ids = get_posts ( array(
    // $all_ids is use to get all the product posted or listed in the product section
      'post_type' => 'product',
      'numberposts' => 9, /* Only 9 products is added in slider*/ // number of products should be passed from the shortcode created by user [products_slider products="12"]
      'post_status' => 'publish',
      'fields' => 'ids',
    ) );
     $html = '';
    //passing every product in the individual_product_slider() to slide the images of the product
    foreach ( $all_ids as $product_id ) {
     $html .= individual_product_slider($product_id); 
    } 
    echo $html;
}
//This function takes a product_id as parameter to fetches images related to single product
function individual_product_slider($product_id){
  $product = new WC_product($product_id);
  $attachment_ids = $product->get_gallery_image_ids(); //get_gallery_image_ids this function 
  $product_name = $product->get_title();
  $html = '<div class="Main_container">';
  $html .= '<div class="inner-container"><h3>'.($product_name) .'</h3>';
    do_shortcode('[add_to_cart_url id="'.$product_id.'"]'); 
  $html .= '<div class="slideshow">';

// foreach loop is use loop every product in this image slider
//This section also contain the html part for our slider
  foreach( $attachment_ids as $attachment_id )  {
    $original_image_url = wp_get_attachment_url( $attachment_id ); 
    $html .= '<div>
    <div class="slide">
        <img src="'. $original_image_url .'" />
        </div>
    </div>';
  }
  $html .= '</div>';
  $add_to_cart_url = site_url() . '/'. do_shortcode('[add_to_cart_url id="'.$product_id.'"]');
  $html .= '<button type="button"><a href="'.$add_to_cart_url.'">Add to Cart</a></button></div></div>';
  return $html;
}


//This is where all the css and js file imported , we advise you should not disrupt the sequence we defind in this function
function custom_plugin_slick_slider() {

  wp_enqueue_script('slick','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js',[],false,true);
  wp_enqueue_style('slick_theme_css','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css');
  wp_register_style('product_slider', plugins_url('product-slider/css/product_slider.css'));
  wp_enqueue_style('product_slider',plugins_url('product-slider/css/product_slider.css'),[],false,true);
  wp_enqueue_style('slick_css','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css');
  wp_register_script('product_slider',plugins_url('product-slider/js/product_slider.js')); 
  wp_enqueue_script('product_slider',plugins_url('product-slider/js/product_slider.js'), [],false,true);
}

add_action( 'wp_enqueue_scripts', 'custom_plugin_slick_slider');

add_shortcode( 'product_slider', 'custom_shortcode');

?>
