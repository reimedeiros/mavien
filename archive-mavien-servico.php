<?php get_header(); ?>

<section class="container">
  <h1>Serviços Mavien</h1>
  <div class="services-grid">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <div class="service-card">
        <?php if (has_post_thumbnail()) the_post_thumbnail('medium'); ?>
        <h2><?php the_title(); ?></h2>
        <p><?php the_excerpt(); ?></p>
        <a href="<?php the_permalink(); ?>" class="btn-outline">Saiba mais</a>
      </div>
    <?php endwhile; endif; ?>
  </div>
</section>

<?php get_footer(); ?>
