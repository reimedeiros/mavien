<?php
/* Template Name: Contato */
get_header();

// Lógica de envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mavien_nonce']) && wp_verify_nonce($_POST['mavien_nonce'], 'mavien_form')) {

  $nome       = sanitize_text_field($_POST['nome']);
  $email      = sanitize_email($_POST['email']);
  $cpf_cnpj   = sanitize_text_field($_POST['cpf_cnpj']);
  $whatsapp   = sanitize_text_field($_POST['whatsapp']);
  $empresa    = sanitize_text_field($_POST['empresa']);
  $mensagem   = sanitize_textarea_field($_POST['mensagem']);
  $honeypot   = $_POST['endereco']; // campo oculto

  if (empty($honeypot)) { // evita bots
    $to = get_option('admin_email');
    $cc = 'contato@mavien.com.br';
    $subject = "📩 Novo contato - Mavien";

    $headers = [
    'Content-Type: text/html; charset=UTF-8',
    "From: Mavien Site <no-reply@" . $_SERVER['SERVER_NAME'] . ">",
    "Reply-To: {$nome} <{$email}>",
    "Cc: {$cc}"
    ];

    $body = "
      <strong>Nome:</strong> {$nome}<br>
      <strong>Email:</strong> {$email}<br>
      <strong>CPF/CNPJ:</strong> {$cpf_cnpj}<br>
      <strong>WhatsApp:</strong> {$whatsapp}<br>
      <strong>Empresa:</strong> {$empresa}<br><br>
      <strong>Mensagem:</strong><br>
      {$mensagem}
    ";

    if (wp_mail($to, $subject, $body, $headers)) {
      $success = "✅ Mensagem enviada com sucesso! Entraremos em contato em breve.";
    } else {
      $error = "❌ Ocorreu um erro ao enviar sua mensagem. Tente novamente mais tarde.";
    }
  } else {
    $error = "Falha na verificação anti-spam.";
  }
}
?>

<main class="contact-page">
  <section class="container contact-section">
    <h1>Entre em Contato com a Mavien</h1>
    <p>Preencha o formulário abaixo e nossa equipe retornará o mais breve possível.</p>

    <?php if (!empty($success)): ?>
      <div class="alert success"><?php echo esc_html($success); ?></div>
    <?php elseif (!empty($error)): ?>
      <div class="alert error"><?php echo esc_html($error); ?></div>
    <?php endif; ?>

    <form id="contactForm" class="contact-form" method="POST">
        <input type="text" id="nome" name="nome" placeholder="Nome completo" required>
        <input type="email" id="email" name="email" placeholder="E-mail" required>
        <input type="text" id="cpfCnpj" name="cpf_cnpj" placeholder="CPF ou CNPJ" required>
        <input type="text" id="whatsapp" name="whatsapp" placeholder="WhatsApp (com DDD)" required>
        <input type="text" id="empresa" name="empresa" placeholder="Nome da empresa" required>
        <textarea id="mensagem" name="mensagem" rows="5" placeholder="Mensagem..." required></textarea>
        
        <label>
            <input type="checkbox" id="humano" required> Sou humano 🤖
        </label>

        <button type="submit" class="btn-primary">Enviar</button>
    </form>
  </section>
</main>

<?php get_footer(); ?>