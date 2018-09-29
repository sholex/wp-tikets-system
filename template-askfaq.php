<?php
/*
Template Name: Задать вопрос
*/
 require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
 
 
 get_header(); ?>
<?php $get_meta = get_post_custom($post->ID);  ?>

		


<div class="title">
	<h1>111<?php the_title(); ?></h1>
</div>
<div class="page-wrap graphic">
	<div class="page-content">
		
		<?php the_content(); ?>
		<?php //Шорткод для вывода формы ввода вопроса
			//		echo '<pre>';
		//	print_r(get_queried_object());
		//	echo '</pre>';
		
		if (is_user_logged_in()){
			echo do_shortcode('[userpro 
				template="publish" 
				publish_heading="Задать вопрос" 
				post_type="faq"
				allowed_taxonomies="faqcat" 
				taxonomy="faqcat" 
				publish_field_order="title,content,category,post_type" 
				publish_button_primary="Спросить" 
				]'); 
		}else{
			//если задана страница вопроса - делаем редирект на нее
			if ($ask_question_page = get_queried_object()) $redirect_url = 'register_redirect="'.get_permalink($ask_question_page->ID).'"';
			else $redirect_url = '';

			
			echo do_shortcode('[userpro 
			template="register"
			
			login_redirect="zadat-vopros"
			register_redirect="zadat-vopros"
			]'); 
		}

			
		?>
		
	
		<div class="clear"></div>
	
</div>

	
	<?php get_sidebar(); ?>

</div><!--page-wrap-->

<?php get_footer(); ?>