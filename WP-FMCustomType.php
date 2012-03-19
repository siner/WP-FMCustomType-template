<?php
/*
Plugin Name: Fran Moreno Custom Post Template
Plugin URI: http://www.fmctt.com/
Description: Custom posts Template
Author: Fran Moreno
Version: 1
Author URI: http://www.fmctt.com/
*/


/* Include the general functions */
include_once('functions.php');

add_action('init', 'fmctt_test_register');

function fmctt_testposts_register() {

	$labels = array(
		'name' => __( 'Testposts' ),
		'singular_name' => __( 'Testpost' ),
		'add_new' => __( 'Add Nuevo' ),
		'add_new_item' => __( 'Add new Testpost' ),
		'edit_item' => __( 'Edit Testpost' ),
		'new_item' => __( 'New Testpost'),
		'view_item' => __( 'View Testpost'),
		'search_items' => __( 'Search Testpost'),
		'not_found' =>  __('Can\'t find anything'),
		'not_found_in_trash' => __('Can\'t find anything in Trash'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail','editor')
	  ); 

	register_post_type( 'testposts' , $args );
}

function fmctt_testposts_flush() 
{
    fmctt_testposts_register();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'fmctt_testposts_flush' );



$testposts_meta_fields = array(
	array(
		'label'=> 'Text Input',
		'desc'	=> 'A description for the field.',
		'id'	=> 'testposts_text',
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
		'id'	=> 'testposts_checkbox',
		'type'	=> 'checkbox'
	),
	array(
		'label'=> 'Select Box',
		'desc'	=> 'A description for the field.',
		'id'	=> 'testposts_select',
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
		'id'    => 'testposts_radio',  
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
		'id'    => 'testposts_checkbox_group',  
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




// Add the Meta Box
function testposts_add_meta_box() {
		global $testposts_meta_fields;
    add_meta_box(
		'testposts_meta_box', // $id
		'Testposts Meta Box', // $title
		'fmctt_show_meta_box', // $callback
		'testposts', // $page
		'normal', // $context
		'high',
		$testposts_meta_fields); // $priority
}
add_action('add_meta_boxes', 'testposts_add_meta_box');


// Save the Data  
function testposts_save_meta($post_id) {  
    global $testposts_meta_fields;
    fmctt_save_meta($post_id,$testposts_meta_fields);  
}  
add_action('save_post', 'testposts_save_meta');



?>