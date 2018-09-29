<?php
/*
Template Name: Задать вопрос custom
*/
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

get_header(); // подключаем header.php 

?>


<?php if ( ! have_posts() ) : ?>
<?php echo 'Нет тегов!'; ?>
<?php endif; ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php $get_meta = get_post_custom($post->ID);  ?>

		


<div class="title">
	<h1><?php the_title(); ?></h1>
</div>

<div class="page-wrap">
	<div class="page-content">
		
		<?php the_content(); ?>
		
		
<!-- New Post Form -->
<?php 
if (is_user_logged_in() == false){
			//если задана страница вопроса - делаем редирект на нее
			if ($ask_question_page = get_queried_object()) $redirect_url = 'register_redirect="'.get_permalink($ask_question_page->ID).'"';
			else $redirect_url = '';

			
			echo do_shortcode('[userpro 
			template="register"
			
			login_redirect="zadat-vopros"
			register_redirect="zadat-vopros"
			]'); 
		}else{
?>

<div class="wpcf7">
<form id="new_faq" name="new_post">
		
		
		<p><label for="title">Заголовок вопроса</label><br />
		<input type="text" id="title" value="" tabindex="1" size="20" name="title" required/></p>
		
		<p><label for="description">Опишите ваш вопрос</label><br />
		<textarea id="description" tabindex="3" name="description" cols="50" rows="6" required></textarea>
		</p>
		
		<p><?php wp_dropdown_categories( 'show_option_none=Выберите категорию вопроса&tab_index=4&taxonomy=faqcat&hide_empty=0&required=true' ); ?></p>
		
		<!--	
		<p><label for="post_tags">Tags</label>
		<input type="text" value="" tabindex="5" size="16" name="post_tags" id="post_tags" /></p>
		-->
		
		<p><input type="submit" value="Задать вопрос" tabindex="6" id="submit" name="submit" /></p>
		
		<input type="hidden" name="post_type" id="post_type" value="faq" />
		<input type="hidden" name="action" value="post" />
		<?php wp_nonce_field( 'new-post' ); ?>
	
	</form>
	<span class="answerspan"></span>
</div>




 <script type="text/javascript">
    jQuery(document).ready(function ($) {
      var is_sending = false,
          failure_message = 'Whoops, looks like there was a problem. Please try again later.';
 
      $('#new_faq').submit(function (e) {
        if (is_sending || !validateInputs()) {
          return false; // Don't let someone submit the form while it is in-progress...
        }
        e.preventDefault(); // Prevent the default form submit
        $this = $(this); // Cache this
        $.ajax({
          url: '<?php bloginfo('template_directory');?>/insert-faq.php', // Let WordPress figure this url out...
          type: 'post',
          dataType: 'JSON', // Set this so we don't need to decode the response...
          data: $this.serialize(), // One-liner form data prep...
          beforeSend: function () {
            is_sending = true;
            // You could do an animation here...
          },
          error: handleFormError,
          success: function (data) {
            if (data.status === 'success') {
				console.log(data.message);
				 $('span.answerspan').prepend(data.message);
              // Here, you could trigger a success message
            } else {
				
              handleFormError(); // If we don't get the expected response, it's an error...
            }
          }
        });
      });
 
      function handleFormError () {
		  console.log(data);
        is_sending = false; // Reset the is_sending var so they can try again...
        console.log(failure_message);
      }
 
      function validateInputs () {
        var $name = $('#new_faq > input[name="name"]').val(),
            $email = $('#new_faq > input[name="email"]').val(),
            $message = $('#new_faq > description').val();
    /*    if (!$message) {
          alert('Before sending, please make sure to provide your name, email, and message.');
          return false;
        }*/
        return true;
      }
    });
  </script>


		<?php } ?>
<!--// New Post Form -->
		
		
		
		<div class="clear"></div>
	
</div>
	<?php endwhile; ?>
	
	<?php get_sidebar(); ?>

</div><!--page-wrap-->

<?php get_footer(); ?>