<?php get_header(); ?>

<section class="container single-service">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
    <div class="content"><?php the_content(); ?></div>
  <?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>
