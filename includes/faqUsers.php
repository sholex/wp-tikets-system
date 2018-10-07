<?php

//namespace faqUsers;


class faqUsers
{


    public function __construct()
    {

        add_action('init',          [$this, 'add_doctor']);
	    add_action('init',          [$this, 'doctor_role']);
        add_action('init',          [$this, 'enable_admin_bar_for_doctors'], 9);
//        add_action( 'admin_init',   [$this, 'add_theme_caps']);

//		$this->debug_caps();
    }


    /**
     * Даем возможность администратору редактировать созданные записи
     */
    public function add_theme_caps() {
        // gets the administrator role
        $admins = get_role( 'administrator' );

        $admins->add_cap( 'create_faqs' );
        $admins->add_cap( 'edit_faq' );
        $admins->add_cap( 'edit_faqs' );
        $admins->add_cap( 'edit_other_faqs' );
        $admins->add_cap( 'publish_faqs' );
        $admins->add_cap( 'read_faq' );
        $admins->add_cap( 'read_private_faqs' );
        $admins->add_cap( 'delete_faq' );
        $admins->add_cap( 'edit_published_faqs' );


        $subscriber = get_role( 'subscriber' );
        $subscriber->add_cap( 'create_faq' );
//    $admins->add_cap( 'edit_faq' );
//    $admins->add_cap( 'edit_faqs' );
//    $admins->add_cap( 'edit_other_faqs' );
        $subscriber->add_cap( 'publish_faqs' );
        $subscriber->add_cap( 'read_faq' );
//    $admins->add_cap( 'read_private_faqs' );
//    $admins->add_cap( 'delete_faq' );

//        $doctor = get_role( 'doctor' );
//	    $doctor->add_cap( 'edit_faq' );
//	    $doctor->add_cap( 'edit_faqs' );
//	    $doctor->add_cap( 'edit_other_faqs' );

    }

    public function add_doctor()
    {
	    remove_role('doctor');

	    add_role('doctor', 'Doctor',
		    array(
			    'read' => false,
			    'edit_posts' => false,
			    'delete_posts' => false,
			    'publish_posts' => false,
			    'upload_files' => false,
		    )
	    );
    }

/*

*/

    /**
     * показываем админ панель для докторов
     */
    public function enable_admin_bar_for_doctors(){
        if ( is_user_logged_in() ):
            global $current_user;
            if( !empty( $current_user->caps['doctor'] ) ):
                add_filter('show_admin_bar', '__return_true');
            endif;
        endif;
    }




    /**
     * Проверяет роль определенного пользователя.
     * Возвращает true при совпадении.
     *
     * @param строка $role Название роли.
     * @param логический $user_id (не обязательный) ID пользователя, роль которого нужно проверить.
     * @return bool
     */
    public function is_user_role( $role, $user_id = null ) {
        $user = is_numeric( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();

        if( ! $user )
            return false;

        return in_array( $role, (array) $user->roles );
    }


    /**
     * Добавляем роль "Доктор"
     */
    public function doctor_role() {

	    global $wp_roles;
	    // Add the roles you'd like to administer the custom post types
	    $roles = array( 'doctor', 'editor', 'administrator' );

	    // Loop through each role and assign capabilities
	    foreach ( $roles as $the_role ) {

		    $role = get_role( $the_role );
		    $role->add_cap( 'read' );
		    $role->add_cap( 'read_' . TICKETS_POST_TYPE );
		    $role->add_cap( 'read_private_' . TICKETS_POST_TYPE . 's' );
		    $role->add_cap( 'edit_' . TICKETS_POST_TYPE );
		    $role->add_cap( 'edit_' . TICKETS_POST_TYPE . 's' );
		    $role->add_cap( 'edit_others_' . TICKETS_POST_TYPE . 's' );
		    $role->add_cap( 'edit_published_' . TICKETS_POST_TYPE . 's' );
		    $role->add_cap( 'publish_' . TICKETS_POST_TYPE . 's' );
		    $role->add_cap( 'delete_others_' . TICKETS_POST_TYPE . 's' );
		    $role->add_cap( 'delete_private_' . TICKETS_POST_TYPE . 's' );
		    $role->add_cap( 'delete_published_' . TICKETS_POST_TYPE . 's' );

//		    $role->add_cap( 'manage_' . TICKETS_TAXONOMY );
//		    $role->add_cap( 'edit_' . TICKETS_TAXONOMY );
//		    $role->add_cap( 'delete_' . TICKETS_TAXONOMY );
		    $role->add_cap( 'assign_' . TICKETS_TAXONOMY );
	    }
    }


	public function debug_caps()
	{
		if (is_admin()){
			require_once(ABSPATH . 'wp-includes/pluggable.php');
			$data = get_userdata( get_current_user_id() );

			if ( is_object( $data) ) {
				$current_user_caps = $data->allcaps;

				// print it to the screen
				echo '<pre>' . print_r( $current_user_caps, true ) . '</pre>';
			}
		}
	}

}