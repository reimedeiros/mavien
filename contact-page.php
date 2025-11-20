<?php
/* Template Name: Contato */
defined('ABSPATH') || exit;
get_header();

// --- Processamento do form (POST) ---
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Verifica nonce
  if ( ! isset($_POST['mavien_nonce']) || ! wp_verify_nonce($_POST['mavien_nonce'], 'mavien_form') ) {
    $error = 'Falha na validação de segurança.';
  } else {
    // Honeypot (campo oculto)
    $honeypot = isset($_POST['endereco']) ? trim($_POST['endereco']) : '';
    if (!empty($honeypot)) {
      $error = 'Falha na verificação anti-spam.';
    }
  }

  // Se ainda sem erro, continue
  if (empty($error)) {
    // Rate limit simples por IP (max 5 por hora)
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $limit_key = 'mavien_contact_limit_' . md5($ip);
    $count = (int) get_transient($limit_key);
    if ($count >= 5) {
      $error = 'Você enviou muitas mensagens. Tente novamente mais tarde.';
    }
  }

  if (empty($error)) {
    // sanitize & validate
    $nome     = isset($_POST['nome']) ? sanitize_text_field($_POST['nome']) : '';
    $email    = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $cpf_cnpj = isset($_POST['cpf_cnpj']) ? preg_replace('/\D/', '', $_POST['cpf_cnpj']) : '';
    $whatsapp = isset($_POST['whatsapp']) ? preg_replace('/\D/', '', $_POST['whatsapp']) : '';
    $empresa  = isset($_POST['empresa']) ? sanitize_text_field($_POST['empresa']) : '';
    $mensagem = isset($_POST['mensagem']) ? sanitize_textarea_field($_POST['mensagem']) : '';
    $recaptcha_token = isset($_POST['g_recaptcha_token']) ? sanitize_text_field($_POST['g_recaptcha_token']) : '';

    // checagens básicas
    if (empty($nome) || empty($email) || empty($mensagem)) {
      $error = 'Por favor preencha os campos obrigatórios.';
    } elseif (!is_email($email)) {
      $error = 'Email inválido.';
    }
  }

  // Verificar reCAPTCHA v3 (server-side)
  if (empty($error) && defined('MAVIEN_RECAPTCHA_SECRET') && ! empty($recaptcha_token)) {
    $remote_ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
      'body' => [
        'secret' => MAVIEN_RECAPTCHA_SECRET,
        'response' => $recaptcha_token,
        'remoteip' => $remote_ip,
      ],
      'timeout' => 5,
    ]);

    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body, true);
    // requisitos: success true, score >= 0.5 e action = contact
    if ( empty($json['success']) || (isset($json['score']) && $json['score'] < 0.45) || (isset($json['action']) && $json['action'] !== 'contact') ) {
      $error = 'Falha na verificação anti-spam (reCAPTCHA).';
    }
  } elseif (empty($error) && defined('MAVIEN_RECAPTCHA_SECRET') && empty($recaptcha_token)) {
    $error = 'Token reCAPTCHA ausente.';
  }

  // Envio do email
  if (empty($error)) {
    $to = get_option('admin_email');
    $cc = 'contato@mavien.com.br';
    $subject = '📩 Novo contato - Mavien';

    // Monta corpo em HTML com escapas
    $body  = '<h2>Novo contato via site</h2>';
    $body .= '<p><strong>Nome:</strong> ' . esc_html($nome) . '</p>';
    $body .= '<p><strong>Email:</strong> ' . esc_html($email) . '</p>';
    $body .= '<p><strong>CPF/CNPJ:</strong> ' . esc_html($cpf_cnpj) . '</p>';
    $body .= '<p><strong>WhatsApp:</strong> ' . esc_html($whatsapp) . '</p>';
    $body .= '<p><strong>Empresa:</strong> ' . esc_html($empresa) . '</p>';
    $body .= '<p><strong>Mensagem:</strong><br>' . nl2br(esc_html($mensagem)) . '</p>';
    $body .= '<hr>';
    $body .= '<p><small>IP: ' . esc_html($ip) . ' • User-Agent: ' . esc_html($_SERVER['HTTP_USER_AGENT'] ?? '') . '</small></p>';

    // Headers seguros
    $headers = [];
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    // From deve ser um email do seu domínio para evitar SPF/DKIM problemas
    $from_email = defined('MAVIEN_SMTP_FROM') ? MAVIEN_SMTP_FROM : 'no-reply@' . $_SERVER['SERVER_NAME'];
    $from_name = defined('MAVIEN_SMTP_FROM_NAME') ? MAVIEN_SMTP_FROM_NAME : 'Mavien Site';
    $headers[] = 'From: ' . wp_strip_all_tags($from_name) . ' <' . sanitize_email($from_email) . '>';
    if (!empty($cc)) {
      $headers[] = 'Cc: ' . sanitize_email($cc);
    }
    $reply_to = is_email($email) ? $email : $to;
    $headers[] = 'Reply-To: ' . esc_html($nome) . ' <' . sanitize_email($reply_to) . '>';

    // Tenta enviar
    $sent = wp_mail($to, $subject, $body, $headers);

    // Log do envio (sucesso ou falha)
    if (function_exists('mavien_log_contact')) {
      mavien_log_contact([
        'nome' => $nome,
        'email' => $email,
        'cpf_cnpj' => $cpf_cnpj,
        'whatsapp' => $whatsapp,
        'empresa' => $empresa,
        'mensagem' => $mensagem,
        'ip' => $ip,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'recaptcha' => $json ?? null,
        'mail_sent' => $sent,
        'time' => current_time('mysql'),
      ]);
    }

    if ($sent) {
      // incrementa contador do rate limit
      set_transient($limit_key, $count + 1, HOUR_IN_SECONDS);
      // PRG pattern para evitar reenvio ao atualizar
      wp_safe_redirect(add_query_arg('mavien_sent', '1', get_permalink()));
      exit;
    } else {
      $error = 'Ocorreu um erro ao enviar sua mensagem. Tente novamente mais tarde.';
    }
  }
}

// Se veio redirecionado após sucesso
if (isset($_GET['mavien_sent']) && $_GET['mavien_sent'] === '1') {
  $success = '✅ Mensagem enviada com sucesso! Entraremos em contato em breve.';
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

    <form id="contactForm" class="contact-form" method="POST" novalidate>
        <?php wp_nonce_field('mavien_form','mavien_nonce'); ?>

        <input type="text" id="nome" name="nome" placeholder="Nome completo" required value="<?php echo isset($_POST['nome']) ? esc_attr($_POST['nome']) : ''; ?>">
        <input type="email" id="email" name="email" placeholder="E-mail" required value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>">
        <input type="text" id="cpfCnpj" name="cpf_cnpj" placeholder="CPF ou CNPJ" required value="<?php echo isset($_POST['cpf_cnpj']) ? esc_attr($_POST['cpf_cnpj']) : ''; ?>">
        <input type="text" id="whatsapp" name="whatsapp" placeholder="WhatsApp (com DDD)" required value="<?php echo isset($_POST['whatsapp']) ? esc_attr($_POST['whatsapp']) : ''; ?>">
        <input type="text" id="empresa" name="empresa" placeholder="Nome da empresa" required value="<?php echo isset($_POST['empresa']) ? esc_attr($_POST['empresa']) : ''; ?>">
        <textarea id="mensagem" name="mensagem" rows="5" placeholder="Mensagem..." required><?php echo isset($_POST['mensagem']) ? esc_textarea($_POST['mensagem']) : ''; ?></textarea>

        <!-- Honeypot: esconda via CSS no tema -->
        <div style="display:none;">
          <label>Endereço (não preencher): <input type="text" name="endereco" value=""></label>
        </div>

        <!-- reCAPTCHA v3 token (preenchido via JS) -->
        <input type="hidden" name="g_recaptcha_token" id="g_recaptcha_token" value="">

        <label>
            <input type="checkbox" id="humano" required> Sou humano 🤖
        </label>

        <button type="submit" class="btn-primary">Enviar</button>
    </form>
  </section>
</main>

<?php get_footer(); ?>
