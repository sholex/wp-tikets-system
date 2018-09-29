<?php
/*
Template Name: Задать вопрос
Template Post Type: page
*/


get_header(); // подключаем header.php ?>

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
		1111111111111111111
		<?php the_content(); ?>
		<?php if (comments_open() || get_comments_number()) comments_template('', true);  ?>
		<div class="clear"></div>

</div>
	<?php endwhile; ?>

	<?php get_sidebar(); ?>

</div><!--page-wrap-->

<?php get_footer(); ?>