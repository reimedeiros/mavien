<?php get_header(); ?>

<section class="container single-job">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <div class="content"><?php the_content(); ?></div>

    <!-- Botão para mostrar o formulário -->
    <button id="btn-candidatar" class="btn-primary">Candidatar-se</button>

    <!-- Formulário escondido inicialmente -->
    <form id="form-candidatar" class="hidden" method="post" enctype="multipart/form-data">
      <h2>Formulário de Candidatura</h2>
      
      <label for="nome">Nome completo:</label>
      <input type="text" id="nome" name="nome" required>

      <label for="cpf">CPF:</label>
      <input type="text" id="cpfCnpj" name="cpf_cnpj" maxlength="14" required>

      <label for="whatsapp">WhatsApp:</label>
      <input type="text" id="whatsapp" name="whatsapp" required>

      <label for="cv">Anexar CV (PDF ou DOCX):</label>
      <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>

      <input type="hidden" name="vaga" value="<?php the_title(); ?>">

      <label>
        <input type="checkbox" id="humano" required> Sou humano 🤖
      </label>

      <button type="submit" name="enviar_candidatura">Enviar Candidatura</button>
    </form>

    <?php
    if(isset($_POST['enviar_candidatura'])) {
      $nome = sanitize_text_field($_POST['nome']);
      $cpf = sanitize_text_field($_POST['cpf_cnpj']);
      $whatsapp = sanitize_text_field($_POST['whatsapp']);
      $vaga = sanitize_text_field($_POST['vaga']);

      if(isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $upload = wp_upload_bits($_FILES['cv']['name'], null, file_get_contents($_FILES['cv']['tmp_name']));
        if(!$upload['error']){
          $cv_url = $upload['url'];
        } else {
          echo "<p>Erro ao enviar o CV: ".$upload['error']."</p>";
        }
      }

      $to = 'rm091291@gmail.com';
      $subject = "Candidatura para $vaga";
      $message = "Nome: $nome\nCPF: $cpf\nWhatsApp: $whatsapp\nCV: $cv_url";
      $headers = ['Content-Type: text/plain; charset=UTF-8'];

      if(wp_mail($to, $subject, $message, $headers)){
        echo "<p>Obrigado! Sua candidatura foi enviada com sucesso.</p>";
      } else {
        echo "<p>Erro ao enviar candidatura. Tente novamente.</p>";
      }
    }
    ?>

  <?php endwhile; endif; ?>
</section>
<?php get_footer(); ?>