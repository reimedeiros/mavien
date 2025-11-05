<?php
/**
 * Template for displaying a single blog post
 */

get_header(); ?>

<main class="single-post container">

  <?php if (have_posts()): while (have_posts()): the_post(); ?>

    <article class="post-content">
      <h1 class="post-title"><?php the_title(); ?></h1>

      <div class="post-meta">
        <span>Publicado em <?php echo get_the_date(); ?></span>
        <span>por <?php the_author(); ?></span>
      </div>

      <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
          <?php the_post_thumbnail('large'); ?>
        </div>
      <?php endif; ?>

      <div class="post-body">
        <?php the_content(); ?>
      </div>

      <div class="post-tags">
        <?php the_tags('<strong>Tags:</strong> ', ', '); ?>
      </div>

      <div class="post-navigation">
        <div class="prev-post"><?php previous_post_link('%link', '← Post anterior'); ?></div>
        <div class="next-post"><?php next_post_link('%link', 'Próximo post →'); ?></div>
      </div>

    </article>

  <?php endwhile; else: ?>
    <p>Nenhum post encontrado.</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
