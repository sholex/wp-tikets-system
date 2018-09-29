<?php
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_59cdfdebb291e',
		'title' => 'Доктор',
		'fields' => array (
			array (
				'key' => 'field_59cdfdf93fa1d',
				'label' => 'Аватарка',
				'name' => 'doc_avatar',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'includes' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'preview_size' => 'thumbnail',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'user_role',
					'operator' => '==',
					'value' => 'doctor',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;
/**
 * ACF Custom Fields
 */
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_59cceabc38e87',
		'title' => 'Блок эксперта',
		'fields' => array (
			array (
				'key' => 'field_59cceac4eace4',
				'label' => 'Ответ эксперта',
				'name' => 'expert_answer',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'includes' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'faq',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'excerpt',
			1 => 'custom_fields',
			2 => 'discussion',
			3 => 'revisions',
			4 => 'author',
			5 => 'featured_image',
			6 => 'send-trackbacks',
		),
		'active' => 1,
		'description' => '',
	));

endif;