<?php
/*
Plugin Name: FAQ Tickets System
Plugin URI: https://sholex.by
Description: Добавляет тикет систему на сайт.
Author: Алексей Шоломицкий
Author URI: https://vk.com/sholexxx
Version: 0.5
*/




//define('TICKETS_CONNECTOR_ROOT', dirname(__FILE__));
//define('TICKETS_URL', plugins_url('/', __FILE__));
//define('TICKETS_BASE_NAME', plugin_basename(__FILE__));

define('TICKETS_PATH', plugin_dir_path(__FILE__)); //use for include files to other files
define('TICKETS_POST_TYPE', 'faq');
define('TICKETS_TAXONOMY', 'faqcat');
define('TICKETS_MENU_SLUG', 'tickets-settings');
define('TICKETS_OPTION_FIELD', 'faq_tickets_settings');
define('TICKETS_CONTENT_TEMPLATE_FIELD', 'faq_content_template');
define('TICKETS_TITLE_TEMPLATE_FIELD', 'faq_title_template');




include 'includes/cf7extender.php';
include 'includes/faqPostType.php';
include 'includes/faqUsers.php';
include 'includes/MetaBoxes.php';
include 'includes/notifications.php';
include 'includes/settingsPage.php';

//функция автозагруки, загружающая классы из папки classes:
//function loadFromClasses($ClassName) {
//	include TICKETS_CONNECTOR_ROOT .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.$ClassName.'.php';
//}
//регистрируем обе функции автозагрузки
//spl_autoload_register('loadFromClasses');

new faqPostType();
new faqUsers();
new cf7extender();
new notifications();

if( is_admin() ){
	new settingsPage();
}

function call_MetaBoxes() {
	new MetaBoxes();
}

if ( is_admin() ) {
	add_action( 'load-post.php', 'call_MetaBoxes' );
	add_action( 'load-post-new.php', 'call_MetaBoxes' );
}


//include ('templates.php');
//include ('custom-fields.php');







//подключаем стили
//wp_enqueue_style('stylesheet', plugins_url( 'css/style.css', __FILE__ ) , array(), '0.1' );

//Добавляем картинку
add_image_size( 'doctor-thumb', 220, 220, true ); // Кадрирование изображения



if (!function_exists('write_log')) {

	function write_log($log) {

			if (is_array($log) || is_object($log)) {
				error_log(print_r($log, true));
			} else {
				error_log($log);
			}

	}

}


function add_cf7_form( $content ) {
	global $post;

	if ( is_single() && TICKETS_POST_TYPE == get_post_type() ) {
		$expert_answer = get_post_meta($post->ID, 'expert_answer');
		return $content.$expert_answer[0];
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'add_cf7_form' );



//echo '<pre>';
//print_r($GLOBALS['wp_post_types']);
//echo '</pre>';
/*
public function expert_box($expert, $answer){
	//global $userpro;
	//$expert_id = $expert["ID"];

//	$expert_profile_url = $userpro->permalink($expert["ID"]);
	//$avatar_img = get_avatar( get_the_author_meta( 'user_email' , $expert_id ), 100 );
	//echo $expert_profile_url;
//	var_dump($expert_id) ;
	$avatar = true; $social = true; $name = false;
	if( $avatar ) :
		?>
        <div includes="author-bio" >
        <div includes="author-avatar">
			<?php
			echo '<a href="'.$expert_profile_url.'">'.$avatar_img.'</a>';

			if ($name = get_the_author_meta('first_name', $expert_id)){
				$l_name = get_the_author_meta('last_name', $expert_id);
				echo $profile_name_link = '<span includes="prof_link"><a href = "'.$expert_profile_url.'">'.$name.' '.$l_name.'</a></span>';
			}
			else
				echo $profile_name_link = '<span includes="prof_link"><a href = "'.$expert_profile_url.'">'.get_the_author_meta('nickname', $expert_id).'</a></span>';
			?>
        </div><!-- #author-avatar -->
	<?php endif; ?>
    <div includes="author-description" style="float:right;width:82%;">
		<?php if( !empty( $name ) ): ?>
            <!--	<h3><a href="<?php echo get_author_posts_url( $expert_id ); ?>"><?php echo $name ?> </a></h3> -->
		<?php endif; ?>
		<?php echo $answer; ?>
    </div><!-- #author-description -->

    <div includes="clear"></div>
    </div>
	<?php
}
*/



function psp_add_project_management_role() {
	remove_role('psp_project_manager');
	add_role('psp_project_manager',
		'Project Manager',
		array(
			'read' => false,
			'edit_posts' => false,
			'delete_posts' => false,
			'publish_posts' => false,
			'upload_files' => false,
		)
	);
}
add_action('init',          'psp_add_project_management_role');



add_action( 'init', 'psp_register_cpt_projects');
function psp_register_cpt_projects() {
	$args = array(
		'label'               => __( 'psp_projects', 'psp_projects' ),
		'description'         => __( 'Projects', 'psp_projects' ),
	//	'labels'              => $labels,
		'supports'            => array( 'title', 'comments', 'revisions', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
	//	'rewrite'             => true,
		'capability_type'     => array('psp_project','psp_projects'),
		'map_meta_cap'        => true,
	);
	register_post_type( 'psp_projects', $args );
}



add_action('admin_init','psp_add_role_caps',999);
function psp_add_role_caps() {

	// Add the roles you'd like to administer the custom post types
	$roles = array( 'psp_project_manager', 'editor', 'administrator' );

	// Loop through each role and assign capabilities
	foreach ( $roles as $the_role ) {

		$role = get_role( $the_role );

//		$role->add_cap( 'read' );
		$role->add_cap( 'read' );
		$role->add_cap( 'read_psp_project' );
		$role->add_cap( 'read_private_psp_projects' );
		$role->add_cap( 'edit_psp_project' );
		$role->add_cap( 'edit_psp_projects' );
		$role->add_cap( 'edit_others_psp_projects' );
		$role->add_cap( 'edit_published_psp_projects' );
		$role->add_cap( 'publish_psp_projects' );
		$role->add_cap( 'delete_others_psp_projects' );
		$role->add_cap( 'delete_private_psp_projects' );
		$role->add_cap( 'delete_published_psp_projects' );

//		$role->add_cap( 'manage_genre' );
//		$role->add_cap( 'edit_genre' );
//		$role->add_cap( 'delete_genre' );
		$role->add_cap( 'assign_genre' );

	}


}


// хук, через который подключается функция
// регистрирующая новые таксономии (create_book_taxonomies)
add_action( 'init', 'create_book_taxonomies' );

// функция, создающая 2 новые таксономии "genres" и "writers" для постов типа "book"
function create_book_taxonomies(){

	// Добавляем древовидную таксономию 'genre' (как категории)
	register_taxonomy('genre', array('psp_projects'), array(
		'hierarchical'  => true,
		'labels'        => array(
			'name'              => _x( 'Genres', 'taxonomy general name' ),
			'singular_name'     => _x( 'Genre', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Genres' ),
			'all_items'         => __( 'All Genres' ),
			'parent_item'       => __( 'Parent Genre' ),
			'parent_item_colon' => __( 'Parent Genre:' ),
			'edit_item'         => __( 'Edit Genre' ),
			'update_item'       => __( 'Update Genre' ),
			'add_new_item'      => __( 'Add New Genre' ),
			'new_item_name'     => __( 'New Genre Name' ),
			'menu_name'         => __( 'Genre' ),
		),
		'capabilities' => array(
			'manage_terms' => 'manage_genre',
			'edit_terms' => 'edit_genre',
			'delete_terms' => 'delete_genre',
			'assign_terms' => 'assign_genre',
		),
		'show_ui'       => true,
		'query_var'     => true,
		//'rewrite'       => array( 'slug' => 'the_genre' ), // свой слаг в URL
	));


	register_taxonomy('writer', 'psp_projects',array(
		'hierarchical'  => false,
		'labels'        => array(
			'name'                        => _x( 'Writers', 'taxonomy general name' ),
			'singular_name'               => _x( 'Writer', 'taxonomy singular name' ),
			'search_items'                =>  __( 'Search Writers' ),
			'popular_items'               => __( 'Popular Writers' ),
			'all_items'                   => __( 'All Writers' ),
			'parent_item'                 => null,
			'parent_item_colon'           => null,
			'edit_item'                   => __( 'Edit Writer' ),
			'update_item'                 => __( 'Update Writer' ),
			'add_new_item'                => __( 'Add New Writer' ),
			'new_item_name'               => __( 'New Writer Name' ),
			'separate_items_with_commas'  => __( 'Separate writers with commas' ),
			'add_or_remove_items'         => __( 'Add or remove writers' ),
			'choose_from_most_used'       => __( 'Choose from the most used writers' ),
			'menu_name'                   => __( 'Writers' ),
		),
		'show_ui'       => true,
		'query_var'     => true,
		//'rewrite'       => array( 'slug' => 'the_writer' ), // свой слаг в URL
	));
}