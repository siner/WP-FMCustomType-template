WP-CustomType Template
======================

This is a simple plugin template for the creation of a Custom Type in WordPress.

Before activating plugin
------------------------

Just change the three defines to use the name you want.

    define( 'CUSTOM_TYPE_NAME' , 'Tests' );
    define( 'CUSTOM_TYPE_SINGULAR_NAME' , 'Test' );
    define( 'CUSTOM_TYPE_SLUG' , 'tests' );

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
