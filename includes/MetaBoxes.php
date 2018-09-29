<?php



class MetaBoxes {


	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}

	/**
	 * Добавляем дополнительный блок.
	 */
	public function add_meta_box( $post_type ){

		// Устанавливаем типы постов к которым будет добавлен блок
		$post_types = array('faq');

		if ( in_array( $post_type, $post_types )) {
			add_meta_box(
				'user_data',
				'Данные посетителя',
				array( $this, 'render_cf7_user_fields' ),
				$post_type,
				'advanced',
				'high'
				);

			add_meta_box(
				'answer_data',
				'Блок эксперта',
				array( $this, 'render_expert_fields' ),
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Сохраняем данные при сохранении поста.
	 *
	 * @param int $post_id ID поста, который сохраняется.
	 */
	public function save( $post_id ) {
		//echo '<pre>';
		//print_r($_POST);exit;


		// Проверяем права пользователя.
//		if ( 'page' == $_POST['post_type'] ) {
//
//			if ( ! current_user_can( 'edit_page', $post_id ) )
//				return $post_id;
//
//		} else {
//
//			if ( ! current_user_can( 'edit_post', $post_id ) )
//				return $post_id;
//		}

		// OK, все чисто, можно сохранять данные.

		// Очищаем поле input.
		// Обновляем данные.
		if (isset($_POST['cf7_faq'])){
			foreach ($_POST['cf7_faq'] as $meta => $value){
				update_post_meta( $post_id, $meta, $value );
			}
		}

		update_post_meta( $post_id, 'expert_answer', $_POST['expert_answer'] );

	}


	/**
	 * Код дополнительного блока эксперта.
	 *
	 * @param WP_Post $post Объект поста.
	 */
	public function render_expert_fields( $post )
	{
		$field_value = get_post_meta( $post->ID, 'expert_answer', true );

		$settings = array(
			'teeny' => true,
			'textarea_rows' => 10,
			'tabindex' => 0,
			'media_buttons' => 0,
			'textarea_name' => 'expert_answer'
		);
		wp_editor($field_value, 'expert_answer',  $settings);
	}



	/**
	 * Код дополнительного блока.
	 *
	 * @param WP_Post $post Объект поста.
	 */
	public function render_cf7_user_fields( $post ) {

		// Добавляем nonce поле, которое будем проверять при сохранении.
		wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );

		echo '<table includes="form-table"><tbody>';

		$this->cf7_metabox(5, $post);

//		$cf7_user_fields = $this->cf7_user_fields;
//		if(is_array($cf7_user_fields)){
//			foreach ($cf7_user_fields as $label_title => $field){
//				$this->add_row($label_title, $field['input_type'], $field['input_name'], $post);
//			}
//		}

		echo '</tbody></table>';

//		echo '<pre>';
//		print_r(wpcf7_contact_form( 5 )->form_scan_shortcode());
//		echo '</pre>';


	}

	public function add_input($type, $name, $post)
	{

		$field_value = get_post_meta( $post->ID, $name, true );

		printf(
			'<input type="'.$type.'" id="'.$name.'" includes="regular-text ltr" name="cf7_faq['.$name.']" value="%s" />',
			esc_attr( $field_value )
		);
	}

	public function add_label($field_name, $title)
	{
		echo '<label for="'.$field_name.'">'.$title.'</label>';
	}

	public function add_row($label_title, $input_type, $input_name, $post){
		echo '<tr>'.PHP_EOL;
		echo '<th>';
		$this->add_label($input_name, $label_title);
		echo '</th>'.PHP_EOL;

		echo '<td>';
		$this->add_input($input_type, $input_name, $post);
		echo '</td>'.PHP_EOL;
		echo '</tr>'.PHP_EOL;
	}

	public function cf7_metabox($cf7_form_id, $post){
		$cf7_user_fields = wpcf7_contact_form( $cf7_form_id )->form_scan_shortcode();



		if(is_array($cf7_user_fields)){
			foreach ($cf7_user_fields as $field){

				if(isset($field->name) && $field->basetype != 'submit'){
					$this->add_row($field->name, 'text', $field->name, $post);
				}

			}
		}

	}

}
