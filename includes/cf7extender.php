<?php



class cf7extender
{
	public $options;


	/**
	 * cf7extender constructor.
	 *
	 */
    public function __construct()
    {
	    $this->options = $this->options = get_option( TICKETS_OPTION_FIELD );

	    wpcf7_add_form_tag('faqcats', [$this, 'faqcats_handler']);

//	    add_filter( 'wpcf7_posted_data', [$this, 'save_faq_posted_data']);

	    add_action( 'wpcf7_mail_sent', array($this, 'handle_form_submission') );

    }


	/**
	 * create faq post on form submit
	 */
	public function handle_form_submission( $contact_form ) {

		$submission = WPCF7_Submission::get_instance();

		if ( $submission ) {
			$posted_data = $submission->get_posted_data();
			$this->save_faq_posted_data($posted_data);
		}
	}

    /*
    * Добавляем шорткод [faqcats] для вывода рубрик
    */
    function faqcats_handler(){
        $html = '';

        $categories = get_categories( array('taxonomy' => 'faqcat','hide_empty'   => 0,) );

        if( $categories ) {
            $html .= '<select name="faqcats" required><option>Выберите рубрику</option>';

            foreach ( $categories as $cat ) {
                $html .= '<option value="'.$cat->slug.'">'.$cat->name.'</option>';
            }
            $html .= '</select>';

            return $html;
        }
    }



	/*
	 * Публикуем создаем черновик вопроса из контактной формы CF7
	 *
	 */

	public function save_faq_posted_data( $posted_data ) {

		$cf7_form_ID = $this->options['cf7_form_id'];

		if ($posted_data['_wpcf7'] == $cf7_form_ID){//условие срабатывает только для определенной формы

			$args        = array(
				'post_title'   => $this->prepare_data(TICKETS_TITLE_TEMPLATE_FIELD, $posted_data),
				'post_content' => $this->prepare_data(TICKETS_CONTENT_TEMPLATE_FIELD, $posted_data),
				'post_status'  => 'draft',           // Choose: publish, preview, future, draft, etc.
				'post_type'    => TICKETS_POST_TYPE,  //'post',page' or use a custom post type if you want to
			);
			$post_id     = wp_insert_post( $args );


			if($post_id){

				$cf_form_fields = wpcf7_contact_form( $cf7_form_ID )->collect_mail_tags();

				foreach ( $cf_form_fields as $field ) {
					if(isset($posted_data[$field])){
						$value = is_array($posted_data[$field]) ? implode(',', $posted_data[$field]) : $posted_data[$field];
						update_post_meta($post_id, $field, $value);
					}

				}

//				if(isset($posted_data['faqcats'])){
//					wp_set_object_terms($post_id, array( $posted_data['faqcats']), 'faqcat');
//				}

			}

		}
		return $posted_data;

	}

	public function prepare_data( $field, array $posted_data)
	{
		$content = $this->options[$field];

		foreach ($posted_data as $field => $value){
			$value = is_array($value)? implode(',', $value) : $value;
			$content = str_replace('['.$field.']', $value, $content);
		}
		return $content;
	}

}