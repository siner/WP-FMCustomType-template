WP-FMCustomType Template
======================

This is a simple plugin template for the creation of a Custom Type in WordPress.

Before Install
--------------

Change testpost, testposts and Testpost by your custom post name.  


Frontend Examples
-----------------

First of all you have to get the post custom fields:


`$cf = get_post_custom();`

__Text:__ `echo $cf['tests_text'][0];`  
__Textarea:__ `echo $cf['tests_textarea'][0];`  
__Checkbox:__ `echo $cf['tests_checkbox'][0];`  
__Select:__ `echo $cf['tests_select'][0];`  
__Radio:__ `echo $cf['tests_radio'][0];`  
__Checkbox group:__  

    $values = unserialize($cf['tests_checkbox_group'][0]);
    foreach ($values as $val):
      echo $val;
    endforeach;
