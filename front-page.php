<?php
/**
 * Template Name: Front Page
 * Description: Página inicial personalizada da Mavien.
 */

get_header(); ?>

<main class="home">

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-background">
      <?php echo file_get_contents(get_template_directory() . '/assets/svg/mavien-hero.svg'); ?>
    </div>

    <div class="hero-content">
      <h1>Transforme ideias em soluções tecnológicas</h1>
      <p>Implantamos equipes especializadas e desenvolvemos soluções sob medida.</p>
      <a href="contato" class="btn-primary">Contrate a Mavien</a>
    </div>
  </section>

  <!-- Serviços -->
  <section id="servicos" class="our-services">
    <div class="container">
      <h2>Nossos Serviços</h2>
      <div class="services-grid">
        <div class="service-card">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/mavien-icon-team.svg" alt="Alocação de Equipes" />
          <h3>Alocação de Equipes</h3>
          <p>Montamos equipes sob medida para atender às necessidades tecnológicas do seu negócio.</p>
        </div>

        <div class="service-card">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/mavien-icon-gear.svg" alt="Soluções Personalizadas" />
          <h3>Soluções Personalizadas</h3>
          <p>Desenvolvemos sistemas e plataformas que conectam inovação e resultados reais.</p>
        </div>

        <div class="service-card">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/mavien-icon-consult.svg" alt="Consultoria" />
          <h3>Consultoria</h3>
          <p>Guiamos sua empresa rumo à transformação digital com estratégias tecnológicas assertivas.</p>
        </div>
      </div>
    </div>
  </section>


  <!-- Sobre a Mavien -->
  <section id="sobre-mavien" class="about-mavien">
    <div class="container about-content">
      <div class="about-text">
        <h2>Sobre a Mavien</h2>
        <p>A Mavien é uma consultoria de tecnologia que acredita que o sucesso nasce de equipes integradas, inovação e propósito. Atuamos alocando times de desenvolvimento e criando soluções digitais de alto impacto.</p>
      </div>
      <div class="about-image">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/svg/gear-pattern-mavien.svg" alt="Engrenagens Mavien" />
      </div>
    </div>
  </section>

  <!-- Blog -->
  <section id="blog" class="from-our-blog">
    <div class="container">
      <h2>Nosso Blog</h2>
      <div class="blog-grid">
        <?php
        $recent_posts = new WP_Query(array('posts_per_page' => 3));
        if ($recent_posts->have_posts()):
          while ($recent_posts->have_posts()): $recent_posts->the_post(); ?>
            <div class="blog-card">
              <?php if (has_post_thumbnail()) : ?>
                <div class="blog-thumb"><?php the_post_thumbnail('medium'); ?></div>
              <?php endif; ?>
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
            </div>
          <?php endwhile;
          wp_reset_postdata();
        else: ?>
          <p>Sem posts no momento.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Vagas -->
  <section id="vagas" class="careers">
    <div class="container">
      <h2>Vagas Disponíveis</h2>
      <div class="careers-grid">
        <?php
        $vagas = new WP_Query(array('post_type' => 'vagas', 'posts_per_page' => 3));
        if ($vagas->have_posts()):
          while ($vagas->have_posts()): $vagas->the_post(); ?>
            <div class="career-card">
              <h3><?php the_title(); ?></h3>
              <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
              <a href="<?php the_permalink(); ?>" class="btn-outline">Ver vaga</a>
            </div>
          <?php endwhile;
          wp_reset_postdata();
        else: ?>
          <p>Não há vagas no momento.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
