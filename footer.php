<footer class="site-footer">
  <div class="container footer-content">
    <div class="footer-top">
      <div class="footer-logo">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-mavien.png" alt="Mavien" />
      </div>
      <nav class="footer-nav">
        <?php
          wp_nav_menu(array(
            'theme_location' => 'footer_menu',
            'container' => false
          ));
        ?>
      </nav>
    </div>

    <div class="footer-bottom">
      <p class="footer-copy">© <?php echo date('Y'); ?> Mavien. Todos os direitos reservados.</p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
