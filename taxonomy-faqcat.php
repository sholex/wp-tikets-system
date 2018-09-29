<?php
/**
 * Шаблон рубрики вопросов
*/
get_header(); ?>



<div class="title">
	<h1><?php echo single_term_title(); ?></h1>
</div>



<div class="page-wrap">
<div class="page-content">

	<?php if( !is_paged() ) echo term_description(); ?>
	
<div class="questions">
			


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="question">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<span class="person"><?php echo get_the_author();?></span>
		<span class="date"><?php echo get_the_date( 'd M Y H:m' ); ?></span>
	</div>


<?php 
	endwhile; // конец цикла
	else: echo '<h2>Нет записей.</h2>'; 
	endif; // если записей нет, напишим "простите" 
?>

</div><!--questions-->

		<div class="pagination">
			<?php //pagination(); // пагинация, функция нах-ся в function.php ?>

		</div>
		
		<div class="info-block">
			
		</div>
</div><!--page-content-->


<?php get_sidebar(); // подключаем sidebar.php ?>
</div><!--page-wrap-->



<?php get_footer(); // подключаем footer.php ?>