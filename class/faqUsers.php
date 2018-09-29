<?php

//namespace faqUsers;


class faqUsers
{


    public function __construct()
    {

        add_action('init',          [$this, 'doctor_role']);
        add_action('init',          [$this, 'enable_admin_bar_for_doctors'], 9);
        add_action( 'admin_init',   [$this, 'add_theme_caps']);



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

        $doctor = get_role( 'doctor' );


    }


/*
remove_role('doctor');
//**Создаем спец роль для врача
add_role('doctor', 'Doctor',
array(
    // Dashboard
'read' => true, // Access to Dashboard and Users -> Your Profile.
'update_core' => false, // Can NOT update core. I added a plugin for this.
'edit_posts' => false, //Access to Posts, Add New, Comments and moderating comments.

    // Posts
'edit_posts' => false, //Access to Posts, Add New, Comments and moderating comments.
'create_posts' => false, // Allows user to create new posts
'delete_posts' => false, // Can delete posts.
'publish_posts' => false, // Can publish posts. Otherwise they stay in draft mode.
'delete_published_posts' => false, // Can delete published posts.
'edit_published_posts' => false, // Can edit posts.
'edit_others_posts' => false, // Can edit other users posts.
'delete_others_posts' => false, // Can delete other users posts.

    //FAQ
'edit_faq' => true,
'create_faqs' =>true,
'edit_faqs' => true,
'edit_other_faqs' => true,
'publish_faqs' => true,
'read_faq' => true,
'read_private_faqs' => true,
'edit_published_faqs' => true, // Can edit posts.

    // Categories, comments and users
'manage_categories' => false, // Access to managing categories.
'moderate_comments' => false, // Access to moderating comments. Edit posts also needs to be set to true.
'edit_comments' => false, // Comments are blocked out for this user.
'edit_users' => false, // Can not view other users.
    // Pages
'edit_pages' => false, // Access to Pages and Add New (page).
'publish_pages' => false, // Can publish pages.
'edit_other_pages' => false, // Can edit other users pages.
'edit_published_ pages' => false, // Can edit published pages.
'delete_pages' => false, // Can delete pages.
'delete_others_pages' => false, // Can delete other users pages.
'delete_published_pages' => false, // Can delete published pages.
    // Media Library
'upload_files' => false, // Access to Media Library.
    // Appearance
'edit_themes_options' => false, // Access to Appearance panel options.
'switch_themes' => false, // Can not switch themes.
'delete_themes' => false, // Can NOT delete themes.
'install_themes' => false, // Can not install a new theme.
'update_themes' => false, // Can NOT update themes.
'edit_themes' => false, // Can not edit themes - through the appearance editor.
    // Plugins
'activate_plugins' => false, // Access to plugins screen.
'edit_plugins' => false, // Can not edit plugins - through the appearance editor.
'install_plugins' => false, // Access to installing a new plugin.
'update_plugins' => false, // Can update plugins.
'delete_plugins' => false, // Can NOT delete plugins.
    // Settings
'manage_options' => false, // Can not access Settings section.
    // Tools
'import' => false, // Can not access Tools section.

)
);
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
    public function doctor_role()
    {

        global $wp_roles;
        if (!isset($wp_roles))
        $wp_roles = new WP_Roles();

        $adm = $wp_roles->get_role('subscriber');
        //pre($adm->capabilities);

        // Добавляем доктору права подписчика
        $wp_roles->add_role('doctor', 'Doctor', $adm->capabilities);

        $doctor = get_role( 'doctor' );
        $doctor->add_cap( 'create_faqs' );
        $doctor->add_cap( 'edit_faq' );
        $doctor->add_cap( 'edit_faq' );
        $doctor->add_cap( 'edit_faqs' );
        $doctor->add_cap( 'edit_other_faqs' );
        $doctor->add_cap( 'publish_faqs' );
        $doctor->add_cap( 'read_faq' );
        $doctor->add_cap( 'read_private_faqs' );
        $doctor->add_cap( 'delete_faq' );

    }

}