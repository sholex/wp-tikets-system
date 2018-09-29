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
//define('TICKETS_PATH', plugin_dir_path(__FILE__)); //use for include files to other files

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


include ('templates.php');
include ('custom-fields.php');







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




