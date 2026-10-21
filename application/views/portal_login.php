<?php defined('BASEPATH') OR exit('No direct script access allowed');
$is_employee = $account_role === 'employee';
$is_admin = $account_role === 'admin';
$portal_name = $is_admin ? 'Administrator Login' : ($is_employee ? 'Employee Login' : 'Customer Login');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#4165df">
  <title><?php echo html_escape($portal_name); ?> | Sun Son Solar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/registration.css'); ?>">
  <script src="<?php echo base_url('assets/js/registration.js'); ?>" defer></script>
  <style>
    .login-form-wrap{width:min(100%,480px)}
    .login-form{gap:1rem}
    .login-form .field{height:48px}
    .login-form .field--password{grid-column:1/-1}
    .login-note{display:flex;gap:.7rem;align-items:flex-start;padding:.9rem 1rem;border:1px solid #d7e0f0;border-radius:10px;background:#fff;color:#6983ac;font-size:.78rem;line-height:1.55}
    .login-note strong{display:block;color:#315ce0;font-size:.8rem}
    .login-note-icon{color:#4165df;font-size:1.15rem;line-height:1}
    .login-error{margin:0 0 .8rem;padding:.7rem .85rem;border-left:3px solid #ffd23f;background:#fff9e4;color:#805a0b;font-size:.8rem}
    .login-info{margin:0 0 .8rem;padding:.7rem .85rem;border-left:3px solid #4165df;background:#edf2ff;color:#3157bf;font-size:.8rem}
    .login-success{margin:0 0 .8rem;padding:.7rem .85rem;border-left:3px solid #4165df;background:#edf2ff;color:#3157bf;font-size:.8rem}
    .login-form-wrap .login-prompt{margin-top:1rem}
    @media(max-height:680px) and (min-width:769px){.login-form{gap:.65rem}.login-form .field{height:42px}.login-note{padding:.65rem .8rem}}
  </style>
</head>
<body>
  <main class="registration login-page">
    <section class="welcome" aria-label="Welcome to Sun Son Solar">
      <a class="brand" href="<?php echo base_url(); ?>" aria-label="Sun Son Solar home">
        <svg class="brand__icon" viewBox="0 0 52 52" aria-hidden="true"><circle cx="26" cy="23" r="8"/><g class="rays"><path d="M26 2v9M26 35v15M5 23h9M38 23h9M11 8l7 7M34 31l7 7M41 8l-7 7M18 31l-7 7"/></g><path class="panel" d="M8 37h36l-3 8H11z"/><path class="panel-line" d="M20 37l-2 8m12-8 2 8M10 41h32"/></svg>
        <span><strong>Sun Son Solar</strong><small>CLEAN ENERGY CO.</small></span>
      </a>
      <div class="welcome__copy">
        <h1><?php echo $is_admin ? 'Welcome back<br>to the Office!' : ($is_employee ? 'Welcome back<br>to the Team!' : 'Welcome back,<br>Sun Son customer!'); ?></h1>
        <p><?php echo ($is_employee || $is_admin) ? 'Sign in to continue your work powering a brighter, cleaner tomorrow.' : 'Sign in to stay connected with your solar journey.'; ?></p>
      </div>
      <svg class="solar-art" viewBox="0 0 380 260" role="img" aria-label="Illustration of solar panels beneath a shining sun">
        <g class="art-sun"><circle cx="190" cy="64" r="16"/><path d="M190 25v13m0 52v13m-39-39h13m52 0h13m-66-27 9 9m36 36 9 9m0-54-9 9m-36 36-9 9"/></g>
        <g class="panel-group"><g><rect x="55" y="137" width="84" height="62" rx="3"/><path d="M83 138v60m28-60v60M56 158h82m-82 20h82"/></g><g><rect x="148" y="137" width="84" height="62" rx="3"/><path d="M176 138v60m28-60v60m-55-40h82m-82 20h82"/></g><g><rect x="241" y="137" width="84" height="62" rx="3"/><path d="M269 138v60m28-60v60m-55-40h82m-82 20h82"/></g></g>
        <path class="stand" d="M72 200v42m50-42v42m40-42v42m56-42v42m40-42v42m50-42v42M64 242h252"/><path class="ground" d="M38 250h304"/><rect class="battery" x="168" y="215" width="44" height="25" rx="3"/><circle class="battery-dot" cx="180" cy="227" r="3"/><path class="battery-line" d="M188 224h17m-17 6h17"/>
      </svg>
      <div class="welcome__footer"><nav class="social" aria-label="Social media"><a href="#" aria-label="Facebook">f</a><a href="#" aria-label="LinkedIn">in</a><a href="#" aria-label="Instagram">◎</a></nav><small>© Sun Son Solar. All rights reserved.</small></div>
    </section>

    <section class="form-panel" aria-labelledby="form-title">
      <div class="form-wrap login-form-wrap">
        <header class="form-header">
          <h2 id="form-title"><?php echo html_escape($portal_name); ?></h2>
          <p><?php echo $is_admin ? 'Access the Sun Son administration workspace' : ($is_employee ? 'Access your employee workspace' : 'Sign in to your customer account'); ?></p>
        </header>
        <?php if (!empty($login_error)): ?><p class="login-error" role="alert"><?php echo html_escape($login_error); ?></p><?php endif; ?>
        <?php if (!empty($employee_notice)): ?><p class="login-info" role="status"><?php echo html_escape($employee_notice); ?></p><?php endif; ?>
        <?php if ($this->session->flashdata('login_success')): ?><p class="login-success" role="status"><?php echo html_escape($this->session->flashdata('login_success')); ?></p><?php endif; ?>
        <?php echo form_open('auth/process_login/' . $account_role, array('class' => 'form login-form')); ?>
          <div class="form-divider"><span>ACCOUNT DETAILS</span></div>
          <label class="field field--wide"><span class="field__icon" aria-hidden="true">♙</span><span class="sr-only">Username or email</span><input name="identifier" type="text" autocomplete="username" placeholder="Username or Email" required maxlength="255" autofocus></label>
          <label class="field field--wide field--password"><span class="field__icon" aria-hidden="true">♙</span><span class="sr-only">Password</span><input id="password" name="password" type="password" autocomplete="current-password" placeholder="Password" required><button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false">◉</button></label>
          <div class="login-note"><span class="login-note-icon" aria-hidden="true">✳</span><span><strong><?php echo $is_employee ? 'Employee access' : 'Your solar account'; ?></strong><?php echo $is_employee ? 'Sign in with the credentials connected to your employee account.' : 'Sign in to keep your account and solar service details close at hand.'; ?></span></div>
          <button class="submit-button" type="submit">Log in <span aria-hidden="true">→</span></button>
        </form>
        <?php if (!$is_employee && !$is_admin): ?><p class="login-prompt">New to Sun Son Solar?<a href="<?php echo site_url('register/customer'); ?>">Register</a></p><?php elseif ($is_employee): ?><p class="login-prompt">Employee accounts are provisioned by HR or an administrator.</p><?php endif; ?>
        <p class="login-prompt"><a href="<?php echo base_url(); ?>">Back to Sun Son Solar</a></p>
      </div>
    </section>
  </main>
</body>
</html>
