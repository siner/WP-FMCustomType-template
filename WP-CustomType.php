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


add_action('init', 'ctt_register');
 
function ctt_register() {
 
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
add_action('manage_posts_custom_column',  'ctt_custom_columns');
add_filter('manage_edit-' . CUSTOM_TYPE_SLUG . '_columns', 'ctt_edit_columns');
 
function ctt_edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Title',
    CUSTOM_TYPE_SLUG . '_thumbnail' => 'Thumbnail',
  ); 
  return $columns;
}


function ctt_custom_columns($column){
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


// Add the Meta Box
function ctt_add_meta_box() {
    add_meta_box(
		'ctt_meta_box', // $id
		CUSTOM_TYPE_SINGULAR_NAME . ' Meta Box', // $title
		'ctt_show_meta_box', // $callback
		CUSTOM_TYPE_SLUG, // $page
		'normal', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'ctt_add_meta_box');

// Field Array
$prefix = CUSTOM_TYPE_SLUG . '_';
$custom_meta_fields = array(
	array(
		'label'=> 'Text Input',
		'desc'	=> 'A description for the field.',
		'id'	=> $prefix.'text',
		'type'	=> 'text'
	),
	array(
		'label'=> 'Textarea',
		'desc'	=> 'A description for the field.',
		'id'	=> $prefix.'textarea',
		'type'	=> 'textarea'
	),
	array(
		'label'=> 'Checkbox Input',
		'desc'	=> 'A description for the field.',
		'id'	=> $prefix.'checkbox',
		'type'	=> 'checkbox'
	),
	array(
		'label'=> 'Select Box',
		'desc'	=> 'A description for the field.',
		'id'	=> $prefix.'select',
		'type'	=> 'select',
		'options' => array (
			'one' => array (
				'label' => 'Option One',
				'value'	=> 'one'
			),
			'two' => array (
				'label' => 'Option Two',
				'value'	=> 'two'
			),
			'three' => array (
				'label' => 'Option Three',
				'value'	=> 'three'
			)
		)
	),
	array (  
    'label' => 'Radio Group',  
    'desc'  => 'A description for the field.',  
    'id'    => $prefix.'radio',  
    'type'  => 'radio',  
    'options' => array (  
        'one' => array (  
            'label' => 'Option One',  
            'value' => 'one'  
        ),  
        'two' => array (  
            'label' => 'Option Two',  
            'value' => 'two'  
        ),  
        'three' => array (  
            'label' => 'Option Three',  
            'value' => 'three'  
        )  
    )  
	),
	array (  
    'label' => 'Checkbox Group',  
    'desc'  => 'A description for the field.',  
    'id'    => $prefix.'checkbox_group',  
    'type'  => 'checkbox_group',  
    'options' => array (  
        'one' => array (  
            'label' => 'Option One',  
            'value' => 'one'  
        ),  
        'two' => array (  
            'label' => 'Option Two',  
            'value' => 'two'  
        ),  
        'three' => array (  
            'label' => 'Option Three',  
            'value' => 'three'  
        )  
    )  
	)

);




// The Callback
function ctt_show_meta_box() {
global $custom_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($custom_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
					// text
					case 'text':  
				    echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /> 
			        		<br /><span class="description">'.$field['desc'].'</span>';  
						break; 
					// textarea  
					case 'textarea':  
					    echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea> 
					        <br /><span class="description">'.$field['desc'].'</span>';  
					break; 
					// checkbox  
					case 'checkbox':  
					    echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/> ';
 					    echo '<span class="description">'.$field['desc'].'</span>';   
					break; 
					// select  
					case 'select':  
					    echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';  
					    foreach ($field['options'] as $option) {  
					        echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';  
					    }  
					    echo '</select><br /><span class="description">'.$field['desc'].'</span>';  
					break;
					// radio  
					case 'radio':  
					    foreach ( $field['options'] as $option ) {  
					        echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' /> 
					                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';  
					    }  
					    echo '<span class="description">'.$field['desc'].'</span>';  
					break;
					// checkbox_group  
					case 'checkbox_group':  
					    foreach ($field['options'] as $option) {  
					        echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' /> 
					                <label for="'.$option['value'].'">'.$option['label'].'</label><br />';  
					    }  
					    echo '<span class="description">'.$field['desc'].'</span>';  
					break;
				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}


// Save the Data  
function ctt_save_meta($post_id) {  
    global $custom_meta_fields;  
  
    // verify nonce  
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))  
        return $post_id;  
    // check autosave  
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  
        return $post_id;  
    // check permissions  
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can('edit_page', $post_id))  
            return $post_id;  
        } elseif (!current_user_can('edit_post', $post_id)) {  
            return $post_id;  
    }  
  
    // loop through fields and save the data  
    foreach ($custom_meta_fields as $field) {  
        $old = get_post_meta($post_id, $field['id'], true);  
        $new = $_POST[$field['id']];  
        if ($new && $new != $old) {  
            update_post_meta($post_id, $field['id'], $new);  
        } elseif ('' == $new && $old) {  
            delete_post_meta($post_id, $field['id'], $old);  
        }  
    } // end foreach  
}  
add_action('save_post', 'ctt_save_meta');

?>