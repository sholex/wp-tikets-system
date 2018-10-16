<?php

class ContentFilters {

	private $options;

	public function __construct() {
		$this->options = get_option( TICKETS_OPTION_FIELD );


		add_filter( 'the_content', array($this, 'add_cf7_form'),9 );
		add_filter( 'the_content', array($this, 'add_doctor_answer_in_content') );
	}


	public function add_cf7_form( $content ) {
		$cf7_form_ID = $this->options['cf7_form_id'];
		if(!$cf7_form_ID) return $content;

		if ( is_single() && TICKETS_POST_TYPE == get_post_type() ) {
			return $content;

		} else {
			$data = 'Оцените статью: '. do_shortcode('[ratings]').PHP_EOL;
			//$data .= do_shortcode('[contact-form-7 id="'.$cf7_form_ID.'"').PHP_EOL;
			return $content.$data;
		}
	}


	public function add_doctor_answer_in_content( $content ) {
		global $post;

		if ( is_single() && TICKETS_POST_TYPE == get_post_type() ) {
			$expert_answer = get_post_meta($post->ID, 'expert_answer');
			return $content.$expert_answer[0];
		} else {
			return $content;
		}
	}



}