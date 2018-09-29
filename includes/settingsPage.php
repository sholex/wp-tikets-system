<?php


class settingsPage {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	public $options;


	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
		// This page will be under "Settings"
//		add_options_page(
//			'Settings Admin',
//			'My Settings',
//			'manage_options',
//			$this->TICKETS_MENU_SLUG,
//			array( $this, 'create_admin_page' )
//		);

		add_submenu_page(
			'edit.php?post_type='.TICKETS_POST_TYPE,
			'Настройки системы Вопросов',
			'Настройки',
			'manage_options',
			TICKETS_MENU_SLUG,
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set includes property
		$this->options = get_option( TICKETS_OPTION_FIELD );
		?>
		<div class="wrap">
			<h1>Настройки системы Вопросов</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'my_option_group' );
				do_settings_sections( TICKETS_MENU_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init()
	{
		register_setting(
			'my_option_group', // Option group
			TICKETS_OPTION_FIELD, // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_section_id', // ID
			'', // Title
			array( $this, 'print_section_info' ), // Callback
			TICKETS_MENU_SLUG // Page
		);

		add_settings_field(
			'faq_post_prefix', // ID
			'Prefix', // Title
			array( $this, 'faq_posts_prefix' ), // Callback
			TICKETS_MENU_SLUG, // Page
			'setting_section_id' // Section
		);

		add_settings_field(
			'cf7_form_id', // ID
			'CF7 form id', // Title
			array( $this, 'cf7_id_number_callback' ), // Callback
			TICKETS_MENU_SLUG, // Page
			'setting_section_id' // Section
		);

		add_settings_field(
			'cf7_form_tags', // ID
			'CF7 form tags', // Title
			array( $this, 'cf7_tags_callback' ), // Callback
			TICKETS_MENU_SLUG, // Page
			'setting_section_id' // Section
		);

		add_settings_field(
			'title',
			'Шаблон заголовка',
			array( $this, 'title_callback' ),
			TICKETS_MENU_SLUG,
			'setting_section_id'
		);

		add_settings_field(
			'expert_answer',
			'Шаблон контента',
			array( $this, 'content_callback' ),
			TICKETS_MENU_SLUG,
			'setting_section_id'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{

		$new_input = array();
		if( isset( $input['faq_posts_prefix'] ) )
			$new_input['faq_posts_prefix'] = $input['faq_posts_prefix'];

		if( isset( $input['cf7_form_id'] ) )
			$new_input['cf7_form_id'] = absint( $input['cf7_form_id'] );

		if( isset( $input['title'] ) )
			$new_input['title'] = sanitize_text_field( $input['title'] );

		if( isset( $input['expert_answer'] ) )
			$new_input['expert_answer'] = $input['expert_answer'];


		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		//print 'Enter your settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function cf7_id_number_callback()
	{
		printf(
			'<input type="text" id="cf7_form_id" name="'.TICKETS_OPTION_FIELD.'[cf7_form_id]" value="%s" />',
			isset( $this->options['cf7_form_id'] ) ? esc_attr( $this->options['cf7_form_id']) : ''
		);


	}

	public function cf7_tags_callback()
    {
        if ($this->options['cf7_form_id']){
            $cf7_form = WPCF7_ContactForm::get_instance( $this->options['cf7_form_id'] );
            echo $cf7_form ? $cf7_form->suggest_mail_tags() : 'form not found';
        }

    }

	/**
	 * Get the settings option array and print one of its values
	 */
	public function title_callback()
	{
		printf(
			'<input type="text" id="title" includes="regular-text ltr" name="'.TICKETS_OPTION_FIELD.'[title]" value="%s" />',
			isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
		);
	}

	public function faq_posts_prefix()
    {
	    printf(
		    '<input type="text" id="faq_posts_prefix" includes="regular-text ltr" name="'.TICKETS_OPTION_FIELD.'[faq_posts_prefix]" value="%s" />',
		    isset( $this->options['faq_posts_prefix'] ) ? esc_attr( $this->options['faq_posts_prefix']) : ''
	    );
    }

	/**
	 * Get the settings option array and print one of its values
	 */
	public function content_callback()
	{
		$settings = array(
			'teeny' => true,
			'textarea_rows' => 10,
			'tabindex' => 0,
			'media_buttons' => 1,
			'textarea_name' => TICKETS_OPTION_FIELD.'[expert_answer]'
		);
		wp_editor($this->options['expert_answer'], 'expert_answer',  $settings);
	}
}