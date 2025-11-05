<?php
function mavien_enqueue_assets() {
  wp_enqueue_style('mavien-header', get_template_directory_uri() . '/assets/css/components/header.css', [], '1.0.0');
  wp_enqueue_style('mavien-footer', get_template_directory_uri() . '/assets/css/components/footer.css', [], '1.0.0');
  wp_enqueue_style('mavien-home', get_template_directory_uri() . '/assets/css/pages/home.css', [], '1.0.0');
  wp_enqueue_style('mavien-single', get_template_directory_uri() . '/assets/css/pages/single.css', [], '1.0.4');
  wp_enqueue_style('mavie-contact', get_template_directory_uri() . '/assets/css/pages/contact.css', [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'mavien_enqueue_assets');

function mavien_enqueue_scripts() {
  wp_enqueue_script('main-js', get_template_directory_uri() . '/assets/js/main.js', [], '1.0.3', true);
}
add_action('wp_enqueue_scripts', 'mavien_enqueue_scripts');

function mavien_theme_setup() {
  add_theme_support('custom-logo');
  add_theme_support('post-thumbnails');
  register_nav_menus(array(
    'main_menu' => 'Menu Principal',
    'footer_menu' => 'Menu do Rodapé'
  ));
}
add_action('after_setup_theme', 'mavien_theme_setup');

function mavien_register_cpt_vagas() {
  $args = array(
    'labels' => array('name' => 'Vagas', 'singular_name' => 'Vaga'),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'vagas'),
    'menu_icon' => 'dashicons-businessman',
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail')
  );
  register_post_type('vagas', $args);
}
add_action('init', 'mavien_register_cpt_vagas');

function mavien_register_cpt_servicos() {
  $args = array(
    'labels' => array('name' => 'Serviços', 'singular_name' => 'Serviço'),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'servicos'),
    'menu_icon' => 'dashicons-hammer',
    'supports' => array('title', 'editor', 'excerpt', 'thumbnail')
  );
  register_post_type('mavien-servico', $args);
}
add_action('init', 'mavien_register_cpt_servicos');
