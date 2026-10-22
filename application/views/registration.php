<?php defined('BASEPATH') OR exit('No direct script access allowed');
$account_role = isset($account_role) ? $account_role : 'customer';
$is_employee = $account_role === 'employee';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f5f8ff">
  <title><?php echo $is_employee ? 'Employee registration' : 'Customer registration'; ?> | Sun Son Solar</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/registration.css'); ?>">
  <script src="<?php echo base_url('assets/js/registration.js'); ?>" defer></script>
</head>
<body data-account-role="<?php echo html_escape($account_role); ?>">
  <main class="registration">
    <section class="welcome" aria-label="Welcome to Sun Son Solar">
      <a class="brand" href="<?php echo base_url(); ?>" aria-label="Sun Son Solar home">
        <svg class="brand__icon" viewBox="0 0 52 52" aria-hidden="true">
          <circle cx="26" cy="23" r="8" />
          <g class="rays">
            <path d="M26 2v9M26 35v15M5 23h9M38 23h9M11 8l7 7M34 31l7 7M41 8l-7 7M18 31l-7 7" />
          </g>
          <path class="panel" d="M8 37h36l-3 8H11z" />
          <path class="panel-line" d="M20 37l-2 8m12-8 2 8M10 41h32" />
        </svg>
        <span>
          <strong>Sun Son Solar</strong>
          <small>CLEAN ENERGY CO.</small>
        </span>
      </a>
      <div class="welcome__copy">
        <h1><?php echo $is_employee ? 'Welcome to<br>the Team!' : 'Welcome to<br>Sun Son Solar!'; ?></h1>
        <p><?php echo $is_employee ? 'Join our team and help us power a brighter, cleaner tomorrow.' : 'Let’s start your journey to a brighter, cleaner tomorrow.'; ?></p>
      </div>
      <svg class="solar-art" viewBox="0 0 380 260" role="img" aria-label="Illustration of solar panels beneath a shining sun">
        <g class="art-sun">
          <circle cx="190" cy="64" r="16" />
          <path d="M190 25v13m0 52v13m-39-39h13m52 0h13m-66-27 9 9m36 36 9 9m0-54-9 9m-36 36-9 9" />
        </g>
        <g class="panel-group">
          <g>
            <rect x="55" y="137" width="84" height="62" rx="3" />
            <path d="M83 138v60m28-60v60M56 158h82m-82 20h82" />
          </g>
          <g>
            <rect x="148" y="137" width="84" height="62" rx="3" />
            <path d="M176 138v60m28-60v60m-55-40h82m-82 20h82" />
          </g>
          <g>
            <rect x="241" y="137" width="84" height="62" rx="3" />
            <path d="M269 138v60m28-60v60m-55-40h82m-82 20h82" />
          </g>
        </g>
        <path class="stand" d="M72 200v42m50-42v42m40-42v42m56-42v42m40-42v42m50-42v42M64 242h252" />
        <path class="ground" d="M38 250h304" />
        <rect class="battery" x="168" y="215" width="44" height="25" rx="3" />
        <circle class="battery-dot" cx="180" cy="227" r="3" />
        <path class="battery-line" d="M188 224h17m-17 6h17" />
      </svg>
      <div class="welcome__footer">
        <nav class="social" aria-label="Social media">
          <a href="#" aria-label="Facebook">f</a>
          <a href="#" aria-label="LinkedIn">in</a>
          <a href="#" aria-label="Instagram">◎</a>
        </nav>
        <small>© Sun Son Solar. All rights reserved.</small>
      </div>
    </section>

    <section class="form-panel" aria-labelledby="form-title">
      <div class="form-wrap">
        <header class="form-header">
          <h2 id="form-title"><?php echo $is_employee ? 'Employee Registration' : 'Customer Registration'; ?></h2>
          <p><?php echo $is_employee ? 'Create your Sun Son team account' : 'Join us and start your solar journey'; ?></p>
        </header>
        <?php if ($this->session->flashdata('registration_error')): ?>
          <p role="alert"><?php echo html_escape($this->session->flashdata('registration_error')); ?></p>
        <?php elseif ($this->session->flashdata('registration_success')): ?>
          <p role="status"><?php echo html_escape($this->session->flashdata('registration_success')); ?></p>
        <?php endif; ?>
        <?php echo form_open('auth/process_registration', array('class' => 'form', 'id' => 'registration-form')); ?>
          <input type="hidden" id="account-role" name="role" value="<?php echo html_escape($account_role); ?>">
          <div class="form-divider"><span>Personal Information</span></div>
          <div class="fields fields--personal">
            <label class="field">
              <span class="sr-only">First name</span>
              <span class="field__icon">♙</span>
              <input type="text" name="first_name" autocomplete="given-name" placeholder="First Name" required>
            </label>
            <label class="field">
              <span class="sr-only">Last name</span>
              <span class="field__icon">♙</span>
              <input type="text" name="last_name" autocomplete="family-name" placeholder="Last Name" required>
            </label>
            <label class="field field--wide">
              <span class="sr-only">Middle name</span>
              <span class="field__icon">♙</span>
              <input type="text" name="middle_name" autocomplete="additional-name" placeholder="Middle Name (Optional)">
            </label>
            <label class="field">
              <span class="field__icon">▦</span>
              <span class="sr-only">Date of birth</span>
              <input type="date" name="birth_date" aria-label="Date of birth" required>
            </label>
            <label class="field">
              <span class="field__icon">⚥</span>
              <span class="sr-only">Gender</span>
              <select name="gender" aria-label="Gender" required>
                <option value="" selected disabled>Gender</option>
                <option>Female</option>
                <option>Male</option>
                <option>Non-binary</option>
                <option>Prefer not to say</option>
              </select>
            </label>
          </div>
          <div class="form-divider"><span>Contact &amp; Credentials</span></div>
          <div class="fields fields--credentials">
            <div class="phone-row">
              <label class="field field--country">
                <span class="sr-only">Country code</span>
                <select name="country_code" aria-label="Country code">
                  <option value="+63">🇵🇭 +63</option>
                  <option value="+1">🇺🇸 +1</option>
                  <option value="+44">🇬🇧 +44</option>
                </select>
              </label>
              <label class="field">
                <span class="field__icon">♧</span>
                <span class="sr-only">Phone number</span>
                <input type="tel" name="phone_number" autocomplete="tel" placeholder="Phone Number" required>
              </label>
            </div>
            <label class="field field--wide">
              <span class="field__icon">✉</span>
              <span class="sr-only">Email address</span>
              <input type="email" name="email" autocomplete="email" placeholder="Email Address" required>
            </label>
            <label class="field">
              <span class="field__icon">♙</span>
              <span class="sr-only">Username</span>
              <input type="text" name="username" autocomplete="username" placeholder="Username" required>
            </label>
            <label class="field">
              <span class="field__icon">♙</span>
              <span class="sr-only">Password</span>
              <input id="password" type="password" name="password" autocomplete="new-password" placeholder="Password" required minlength="8">
              <button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false">◉</button>
            </label>
            <label class="field field--wide">
              <span class="field__icon">♙</span>
              <span class="sr-only">Confirm password</span>
              <input type="password" name="confirm_password" autocomplete="new-password" placeholder="Confirm Password" required minlength="8">
            </label>
          </div>
          <section class="employee-details" id="employee-details" aria-labelledby="employee-title" <?php if (!$is_employee): ?>hidden<?php endif; ?>>
            <div class="employee-details__heading">
              <span class="employee-details__icon">⌂</span>
              <strong id="employee-title">Employee Details</strong>
              <span class="staff-badge">Staff Only</span>
            </div>
            <label class="field">
              <span class="field__icon">⌂</span>
              <span class="sr-only">Department</span>
              <select name="department" aria-label="Department" <?php if (!$is_employee): ?>disabled<?php else: ?>required<?php endif; ?>>
                <option value="" selected>Select Department</option>
                <option>IT</option>
                <option>Dispatch</option>
                <option>Accounting</option>
                <option>Installation</option>
                <option>Engineering</option>
                <option>Sales</option>
                <option>Customer Support</option>
                <option>Operations</option>
              </select>
            </label>
          </section>
          <label class="terms"><input type="checkbox" name="agree_terms" value="1" required><span>I agree to the <a href="#terms">Terms of Service</a> and <a href="#privacy">Privacy Policy</a></span></label>
          <button class="submit-button" type="submit">
            Register <span aria-hidden="true">→</span>
          </button>
        </form>
        <p class="login-prompt">Already have an account? <a href="<?php echo site_url('auth/login/' . $account_role); ?>">Log in</a></p>
        <div class="form-divider form-divider--social"><span>or continue with</span></div>
        <div class="social-login">
          <button type="button"><b class="google">G</b> Google</button>
          <button type="button"><b>f</b> Facebook</button>
          <button type="button"><b>in</b> LinkedIn</button>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
