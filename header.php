<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header class="site-header">
  <div class="container header-content">
    <div class="logo">
      <a href="<?php echo home_url(); ?>">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-mavien.png" alt="Mavien" />
      </a>
    </div>

    <nav class="main-nav" id="main-nav">
      <?php
        wp_nav_menu(array(
          'theme_location' => 'main_menu',
          'container' => false,
          'menu_class' => 'menu-items'
        ));
      ?>
    </nav>

    <a href="contato" class="btn-contrate">Contrate a Mavien</a>

    <!-- Botão hamburguer (mobile) -->
    <button class="nav-toggle" id="nav-toggle" aria-label="Abrir menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
