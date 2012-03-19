<?php

// The Callback
function fmctt_show_meta_box( $post , $args ) {

// Use nonce for verification
echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	$custom_meta_fields = $args['args'];


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
function fmctt_save_meta( $post_id , $custom_meta_fields ) {  
  
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



