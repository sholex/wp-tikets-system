<?php

//namespace faqPostType;


class faqPostType
{
    public $taxonomy = TICKETS_TAXONOMY;
	public $options;

    public function __construct()
    {
	    $this->options = $this->options = get_option( TICKETS_OPTION_FIELD );
	    $this->faq_slug = $this->options['faq_posts_prefix'];

        //создаем тип записей
        add_action( 'init', [$this, 'register_faq_post_type'] );

        //фильтр для корректного отображения структуры
        add_filter('rewrite_rules_array', [$this, 'faq_rewrite_rules']);

        //html в конце урл вопроса
        add_action( 'registered_post_type', [$this, 'faq_permastruct_html'], 10, 2 );

	    add_filter('post_type_link', [$this, 'faq_permalink'], 1, 2);
    }


    public function faq_rewrite_rules($rules) {
        $newRules  = [];
        $newRules[$this->faq_slug.'/(.+)/(.+?).html$'] = 'index.php?faq=$matches[2]';

        return array_merge($newRules, $rules);
    }

    //добавим *.html для вопросов
    public function faq_permastruct_html( $post_type, $args ) {
        if ( $post_type === TICKETS_POST_TYPE )
            add_permastruct( $post_type, "{$args->rewrite['slug']}/%$post_type%.html", $args->rewrite );
    }

    /**
     * Создаем тип записи "вопросы"
     * Создаем таксономию для рубрик вопросов
     */
    function register_faq_post_type() {

        $faq_slug = $this->faq_slug;

        register_taxonomy(TICKETS_TAXONOMY, array($faq_slug), array(
            'label'                 => 'Раздел вопроса', // определяется параметром $labels->name
            'labels'                => array(
                'name'              => 'Разделы вопросов',
                'singular_name'     => 'Раздел вопроса',
                'search_items'      => 'Искать Раздел вопроса',
                'all_items'         => 'Все Разделы вопросов',
                'parent_item'       => 'Родит. раздел вопроса',
                'parent_item_colon' => 'Родит. раздел вопроса:',
                'edit_item'         => 'Ред. Раздел вопроса',
                'update_item'       => 'Обновить Раздел вопроса',
                'add_new_item'      => 'Добавить Раздел вопроса',
                'new_item_name'     => 'Новый Раздел вопроса',
                'menu_name'         => 'Раздел вопроса',
            ),
            'description'           => 'Рубрики для раздела вопросов', // описание таксономии
            'public'                => true,
            'show_in_nav_menus'     => false, // равен аргументу public
            'show_ui'               => true, // равен аргументу public
            'show_tagcloud'         => false, // равен аргументу show_ui
            'hierarchical'          => true,
            'rewrite'               => array('slug'=>$faq_slug, 'hierarchical'=>false, 'with_front'=>false, 'feed'=>false ),
            'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
        ) );

        // тип записи - вопросы - voprosy
        register_post_type(TICKETS_POST_TYPE, array(
            'label'               => 'Вопросы',
            'labels'              => array(
                'name'          => 'Вопросы',
                'singular_name' => 'Вопрос',
                'menu_name'     => 'Вопросы',
                'all_items'     => 'Все вопросы',
                'add_new'       => 'Добавить вопрос',
                'add_new_item'  => 'Добавить новый вопрос',
                'edit'          => 'Редактировать',
                'edit_item'     => 'Редактировать вопрос',
                'new_item'      => 'Новый вопрос',
            ),
            'description'         => '',
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_rest'        => false,
            'rest_base'           => '',
            'show_in_menu'        => true,
            'exclude_from_search' => false,
            //	'capability_type'     => TICKETS_POST_TYPE,
            'capabilities' => array(
	            'manage_categories'=> 'manage_faqcat',
	            'edit_terms'=> 'manage_faqcat',
	            'delete_terms'=> 'manage_faqcat',
	            'assign_categories' => 'edit_faq',

                'create_posts' => 'create_faqs',
                'edit_post' => 'edit_faq',
                'edit_posts' => 'edit_faqs',
                'edit_others_posts' => 'edit_other_faqs',
                'publish_posts' => 'publish_faqs',
                'read_post' => 'read_faq',
                'read_private_posts' => 'read_private_faqs',
                'edit_published_posts' => 'edit_published_faqs',
                'delete_post' => 'delete_faq'
            ),
            'map_meta_cap'        => true,
            'hierarchical'        => false,
            'rewrite'             => array( 'slug'=>$faq_slug.'/%faqcat%', 'with_front'=>false, 'pages'=>false, 'feeds'=>false, 'feed'=>false ),
            'has_archive'         => $faq_slug,
            'query_var'           => true,
            'supports'            => array( 'title', 'editor','thumbnail','excerpt','custom-fields','comments', ),
            'taxonomies'          => array( TICKETS_TAXONOMY ),
        ) );

	    flush_rewrite_rules();
    }


	## Отфильтруем ЧПУ произвольного типа

	public function faq_permalink( $permalink, $post ){
		// выходим если это не наш тип записи: без холдера %products%
		if( strpos($permalink, '%faqcat%') === false )
			return $permalink;

		// Получаем элементы таксы
		$terms = get_the_terms($post, TICKETS_TAXONOMY);
		// если есть элемент заменим холдер
		if( ! is_wp_error($terms) && !empty($terms) && is_object($terms[0]) )
			$term_slug = array_pop($terms)->slug;
		// элемента нет, а должен быть...
		else
			$term_slug = 'no-faqcat';

		return str_replace('%faqcat%', $term_slug, $permalink );
	}


}