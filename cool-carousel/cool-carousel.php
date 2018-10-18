<?php 
/******************
Plugin Name: Cool Carousel 
URI: ....pending....
Description: Cool carousel compontent 
Version: 1.0
Author: Ben Grzybowski
Author URI: https://bgrzdesigns.com
License: GPLv2 or later 

Comments: Made with help from Rakhitha Nimesh, at https://1stwebdesigner.com/ 
**********************/ 

/**** Begin Activation and Deactivation ****/
function cool_carousel_activation(){}
register_activation_hook(__FILE__, 'cool_carousel_activation'); 
function cool_carosuel_deactivation() {}
register_deactivation_hook(__FILE__, 'cool_carousel_deactivation');
/**** End Activation and Deactivation ****/

/**** Begin Enqueing Scripts ****/
add_action('wp_enqueue_scripts', 'cool_carousel_scripts');
function cool_carousel_scripts() {
    wp_register_script('carousel', plugins_url('cool-carousel/js/carousel.js'), __FILE__);
    wp_enqueue_script('carousel');
}
/**** End Enqueing Scripts ****/

/**** Begin Enqueing Styles ****/
add_action('wp_enqueue_scripts', 'cool_carousel_style');
function cool_carousel_style(){
    wp_register_style('carousel_style', plugins_url('cool-carousel/css/style.css'));
    wp_enqueue_style('carousel_style'); 
}
/**** End Enqueing Styles ****/

/**** Start Add shortcode that will display carousel ****/
add_shortcode('carousel', 'display_carousel'); 
function display_carousel($attr, $content) {
    
    extract(shortcode_atts(array('id'=>''),$attr));
    $gallery_images = get_post_meta($id, "_cool_carousel_gallery_images", true);
    $gallery_images = ($gallery_images != '') ? json_decode($gallery_images) : array();
    $plugin_url = plugins_url(); 
    
    $html = '
    <div class="carousel">
    <figure> ';
    
    foreach ($gallery_images as $gal_img) {
        if ($gal_img != ''){
            $html .= '<img src="' . $gal_img . '"/>';
        }
    }
    
    $html .= '
    </figure>
    <br>
    <br>
    <br>
    <div id="car-nav">
    <button class="nav prev" id="prev">Prev</button>
    <button class="nav next" id="next">Next</button>
    </div>
    ';
    
    return $html;
}

/**** End Add shortcode ****/

/**** Begin Custom Post Type ****/

add_action('init', 'register_carousel');
    
function register_carousel() {
    $labels = array(
        'menu_name' => _x('Cool Carousel', 'cool_carousel'),
    );
    
        $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Cool Carousel',
        'supports' => array('title', 'editor'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );
    
        register_post_type('cool_carousel', $args);
}
/*** remove the deafault WYSIWYG editor from the post type ***/ 

add_action('init', 'init_remove_support',100);
function init_remove_support(){
    $post_type = 'cool_carousel';
    remove_post_type_support( $post_type, 'editor');
}

/**** End Custom Post Type ****/

/***** Begin Add Shortcode to WordPress UI ******/
add_filter('manage_edit-cool_carousel_columns', 'set_custom_edit_cool_carousel_columns');
add_action('manage_cool_carousel_posts_custom_column', 'custom_cool_carousel_column', 10, 2);

function set_custom_edit_cool_carousel_columns($columns) {
    return $columns
    + array('slider_shortcode' => __('Shortcode'));
}

function custom_cool_carousel_column($column, $post_id) {

    $slider_meta = get_post_meta($post_id, "_cool_carousel_meta", true);
    $slider_meta = ($slider_meta != '') ? json_decode($slider_meta) : array();

    switch ($column) {
        case 'slider_shortcode':
            echo "[carousel id='$post_id'/]";
            break;
    }
}

/***** End Add Shortcode to WordPress UI ******/

/**** Begin Adding Custom fields for custom post Type ****/

add_action('add_meta_boxes', 'cool_carousel_meta_box'); 

function cool_carousel_meta_box() {
    add_meta_box("cool-carousel-images", 
                 "Carousel Images", 
                 'cool_carousel_view_images_box', 
                 "cool_carousel", 
                 "normal");
}

function cool_carousel_view_images_box() {
    global $post; 
    
    $gallery_images = get_post_meta($post->ID, "_cool_carousel_gallery_images", true);
    $gallery_images = ($gallery_images != '') ? json_decode($gallery_images) : array(); 
    
      $html =  '<input type="hidden" name="cool_carousel_box_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';
    
    
    $html .= '<table class="form-table">';

    $html .= "
          <tr>
            <th style=''><label for='Upload Images'>Image 1</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text' value='" . $gallery_images[0] . "'  /></td>
          </tr>
          <tr>
            <th style=''><label for='Upload Images'>Image 2</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text' value='" . $gallery_images[1] . "' /></td>
          </tr>
          <tr>
            <th style=''><label for='Upload Images'>Image 3</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text'  value='" . $gallery_images[2] . "' /></td>
          </tr>
          <tr>
            <th style=''><label for='Upload Images'>Image 4</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text' value='" . $gallery_images[3] . "' /></td>
          </tr>
          <tr>
            <th style=''><label for='Upload Images'>Image 5</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text' value='" . $gallery_images[4] . "' /></td>
          </tr>
          <tr>
            <th style=''><label for='Upload Images'>Image 6</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text' value='" . $gallery_images[5] . "' /></td>
          </tr>
          <tr>
            <th style=''><label for='Upload Images'>Image7</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text' value='" . $gallery_images[6] . "' /></td>
          </tr>
          <tr>
            <th style=''><label for='Upload Images'>Image 8</label></th>
            <td><input name='gallery_img[]' id='cool_carousel_upload' type='text' value='" . $gallery_images[7] . "' /></td>
          </tr>

        </table>";

        echo $html;

}
/**** End Custom Fields  ****/

/**** Begin Save Custom Fields to Database script  ****/

add_action('save_post', 'cool_carousel_save_content');

function cool_carousel_save_content($post_id) {


    // verify nonce
    if (!wp_verify_nonce($_POST['cool_carousel_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('cool_carousel' == $_POST['post_type'] && current_user_can('edit_post', $post_id)) {

        /* Save Slider Images */
        //echo "<pre>";print_r($_POST['gallery_img']);exit;
        $gallery_images = (isset($_POST['gallery_img']) ? $_POST['gallery_img'] : '');
        $gallery_images = strip_tags(json_encode($gallery_images));
        update_post_meta($post_id, "_cool_carousel_gallery_images", $gallery_images);
       
    } else {
        return $post_id;
    }
}
/**** End Save Custom Fields  ****/
?>