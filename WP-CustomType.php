<?php
/*
Plugin Name: Custom Post Template
Plugin URI: http://www.franmoreno.com/custom-post-template
Description: Custom posts Template
Author: Fran Moreno
Version: 1
Author URI: http://www.franmoreno.com/
*/

define( 'CUSTOM_TYPE_NAME' , 'Tests' );
define( 'CUSTOM_TYPE_SINGULAR_NAME' , 'Test' );
define( 'CUSTOM_TYPE_SLUG' , 'tests' );


add_action('init', 'ctt_tests_register');
 
function ctt_tests_register() {
 
	$labels = array(
		'name' => __( CUSTOM_TYPE_NAME ),
		'singular_name' => __( CUSTOM_TYPE_SINGULAR_NAME ),
		'add_new' => __( 'Add New' ),
		'add_new_item' => __( 'Add new ' . CUSTOM_TYPE_SINGULAR_NAME ),
		'edit_item' => __( 'Edit ' . CUSTOM_TYPE_SINGULAR_NAME ),
		'new_item' => __( 'New ' . CUSTOM_TYPE_SINGULAR_NAME),
		'view_item' => __( 'View ' . CUSTOM_TYPE_SINGULAR_NAME),
		'search_items' => __( 'Search ' . CUSTOM_TYPE_SINGULAR_NAME),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail')
	  ); 
 
	register_post_type( CUSTOM_TYPE_SLUG , $args );
	flush_rewrite_rules();
}


/* Taxonomy */

register_taxonomy("Types", array( CUSTOM_TYPE_SLUG ), array("hierarchical" => true, "label" => "Types", "singular_label" => "Type", "rewrite" => false));


/* Columns */
add_action('manage_posts_custom_column',  'ctt_' . CUSTOM_TYPE_SLUG . '_custom_columns');
add_filter('manage_edit-' . CUSTOM_TYPE_SLUG . '_columns', 'ctt_' . CUSTOM_TYPE_SLUG . '_edit_columns');
 
function ctt_tests_edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Title',
    CUSTOM_TYPE_SLUG . '_thumbnail' => 'Thumbnail',
  ); 
  return $columns;
}


function ctt_tests_custom_columns($column){
  global $post;
 
  switch ($column) {
    case CUSTOM_TYPE_SLUG . '_thumbnail':
      $width = (int) 75;
      $height = (int) 75;
      $thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
      
      // image from gallery
      $attachments = get_children( array('post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
      if ($thumbnail_id):
          $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
      elseif ($attachments):
      	foreach ( $attachments as $attachment_id => $attachment ) :
        	$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
      	endforeach;
      endif;
      
      if ( isset($thumb) && $thumb ) :
          echo $thumb;
      else :
          echo __('None');
      endif;
      
    break;
  }
}

/* Meta Boxes */

add_action( 'admin_init' , 'ctt_' . CUSTOM_TYPE_SLUG . '_admin_init');
 
function ctt_tests_admin_init(){
  add_meta_box( 'example-meta', 'Example', 'ctt_' . CUSTOM_TYPE_SLUG . '_example', CUSTOM_TYPE_SLUG , 'normal', 'low');
}
 
function ctt_tests_example(){
  global $post;
  $custom = get_post_custom($post->ID);
  $example = sizeof($custom)>0 ? $custom["tests_example"][0] : '';
  ?>
  <p><label>Example:</label><br/>
  <textarea cols="50" rows="5" name="tests_example"><?php echo $example; ?></textarea></p>
  <?php
}


add_action('save_post', 'ctt_' . CUSTOM_TYPE_SLUG . '_save_details');

function ctt_tests_save_details(){
  global $post;

	if (!is_array($post) && $post->post_type == CUSTOM_TYPE_SLUG)
	{
		$custom_meta_fields = array( CUSTOM_TYPE_SLUG . '_example' );
		
		foreach( $custom_meta_fields as $custom_meta_field ):
		if(isset($_POST[$custom_meta_field]) && $_POST[$custom_meta_field] != ""):
			update_post_meta($post->ID, $custom_meta_field, $_POST[$custom_meta_field]);
		endif;
		endforeach;
	}
}

?>