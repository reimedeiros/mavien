<?php get_header(); ?>

<section class="container single-job">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <div class="content"><?php the_content(); ?></div>
    <a href="mailto:contato@mavien.com.br" class="btn-primary">Candidatar-se</a>
  <?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>
