WP-CustomType Template
======================

This is a simple plugin template for the creation of a Custom Type in WordPress.

Before activating plugin
------------------------

Just change the three defines to use the name you want.

`define( 'CUSTOM_TYPE_NAME' , 'Tests' );
define( 'CUSTOM_TYPE_SINGULAR_NAME' , 'Test' );
define( 'CUSTOM_TYPE_SLUG' , 'tests' );`

Frontend Examples
-----------------

Some examples for the front-end side:

`<?php $cf = get_post_custom();?>
<p><strong>Text: </strong><?php echo $cf['tests_text'][0]; ?></p>
<p><strong>Textarea: </strong><?php echo $cf['tests_textarea'][0]; ?></p>
<p><strong>Checkbox: </strong><?php echo $cf['tests_checkbox'][0]; ?></p>
<p><strong>Select: </strong><?php echo $cf['tests_select'][0]; ?></p>
<p><strong>Radio: </strong><?php echo $cf['tests_radio'][0]; ?></p>
<p><strong>Checkbox group: </strong>
<?php $values = unserialize($cf['tests_checkbox_group'][0]); ?>
<ul>
	<?php foreach ($values as $val): ?>
		<li><?php echo $val; ?></li>
	<?php endforeach; ?>
</ul>
</p>`

