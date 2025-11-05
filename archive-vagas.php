<?php get_header(); ?>

<section class="container">
  <h1>Vagas Disponíveis</h1>
  <div class="jobs-grid">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <div class="job-card">
        <h2><?php the_title(); ?></h2>
        <p><?php the_excerpt(); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn-outline">Ver detalhes</a>
      </div>
    <?php endwhile; endif; ?>
  </div>
</section>

<?php get_footer(); ?>
