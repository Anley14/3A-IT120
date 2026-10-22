<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f7f8f4">
  <meta name="description" content="Make the switch to clean, reliable solar energy with Sun Son Solar.">
  <title>Sun Son Solar | Power Your Future</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
  <script src="<?php echo base_url('assets/js/home.js'); ?>" defer></script>
</head>
<body>
  <header class="site-header">
    <nav class="nav wrap" aria-label="Main navigation">
      <a class="brand" href="<?php echo base_url(); ?>" aria-label="Sun Son Solar home">
        <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 40 40"><circle cx="20" cy="17" r="6"/><path d="M20 2v5m0 20v6M5 17h6m18 0h6M9.4 6.4 13 10m14 14 3.6 3.6M30.6 6.4 27 10m-14 14-3.6 3.6M7 33h26l-3-7H10z"/></svg></span>
        <span><strong>sun son</strong><small>SOLAR CO.</small></span>
      </a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu"><span></span><span></span><span class="sr-only">Toggle navigation</span></button>
      <div class="nav-menu" id="primary-menu">
        <div class="nav-links"><a href="#services">Services</a><a href="#about">About us</a><a href="#contact">Contact</a><a href="#faq">FAQ</a><a href="#journal">Journal</a><a href="<?php echo site_url('shop'); ?>">Shop</a></div>
        <div class="login-menu">
          <button class="login-trigger" type="button" aria-expanded="false">Login <span aria-hidden="true">⌄</span></button>
          <div class="login-dropdown">
            <span class="dropdown-label">SIGN IN</span>
            <a href="<?php echo site_url('auth/login/customer'); ?>">Customer login <span>→</span></a>
            <a href="<?php echo site_url('auth/login/employee'); ?>">Employee login <span>→</span></a>
            <a href="<?php echo site_url('auth/login/admin'); ?>">Administrator login <span>→</span></a>
            <span class="dropdown-label dropdown-label--register">REGISTER</span>
            <a href="<?php echo site_url('register/customer'); ?>">Customer account <span>→</span></a>
            <span class="dropdown-label">Employee accounts are provisioned by HR or an administrator.</span>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <section class="hero" id="home">
      <div class="hero-photo" aria-hidden="true"></div>
      <div class="hero-shade" aria-hidden="true"></div>
      <div class="hero-content wrap">
        <p class="eyebrow eyebrow-light"><span></span> A brighter way to power home</p>
        <h1>Power Your<br>Future with Solar</h1>
        <p class="hero-lede">Thoughtful solar solutions that put clean energy, lower bills, and more control within reach.</p>
        <div class="hero-actions"><a class="button button-lime" href="#contact">Get a free quote <span>↗</span></a><a class="text-link light-link" href="#services">Explore our services <span>↓</span></a></div>
        <div class="hero-note"><span class="hero-sun">✳</span><span>Made for your home.<br><strong>Ready for tomorrow.</strong></span></div>
      </div>
      <div class="hero-caption"><span>01 / 03</span><span>Clean power, a little closer to home</span></div>
    </section>

    <section class="intro section-pad" id="about">
      <div class="wrap intro-grid">
        <div><p class="eyebrow"><span></span> The sun is on your side</p><h2>A better kind of<br><em>everyday energy.</em></h2></div>
        <div class="intro-copy"><p>We make going solar feel clear from the very first conversation. Sun Son Solar brings local expertise, dependable equipment, and a people-first team to every step of your energy journey.</p><a class="text-link" href="#services">Meet your solar partner <span>↗</span></a></div>
      </div>
      <div class="wrap benefits">
        <article class="benefit"><span class="benefit-icon">↘</span><div><h3>Spend less on energy</h3><p>Make your monthly power bill more predictable and keep more money for what matters.</p></div><b>01</b></article>
        <article class="benefit"><span class="benefit-icon">✳</span><div><h3>Choose a lighter footprint</h3><p>Generate clean electricity right where you use it and help cut household emissions.</p></div><b>02</b></article>
        <article class="benefit"><span class="benefit-icon">⌁</span><div><h3>Feel more energy independent</h3><p>Build resilience and take a more active role in how your home gets its power.</p></div><b>03</b></article>
      </div>
    </section>

    <section class="services section-pad" id="services">
      <div class="wrap"><div class="section-heading"><div><p class="eyebrow"><span></span> What we do</p><h2>Good energy,<br><em>start to finish.</em></h2></div><p>From the first roof check to the years of care that follow, our team makes every part of solar feel refreshingly simple.</p></div>
        <div class="service-grid">
          <article class="service-card service-card-featured"><div class="service-top"><span>01 — PLAN</span><span class="service-symbol">⌕</span></div><div><h3>Consultation</h3><p>Clear answers and a solar plan shaped around your home, your goals, and your budget.</p><a href="#contact" aria-label="Learn more about consultation">↗</a></div><div class="service-art art-consult"><span>HOME</span><span>☼</span><span>ENERGY PLAN</span></div></article>
          <article class="service-card"><div class="service-top"><span>02 — BUILD</span><span class="service-symbol">⌂</span></div><div><h3>Installation</h3><p>Careful, professional installation with quality panels and a crew that respects your space.</p><a href="#contact" aria-label="Learn more about installation">↗</a></div><div class="service-art art-install"><div class="roof"><i></i><i></i><i></i><i></i></div><span>POWERING HOME</span></div></article>
          <article class="service-card"><div class="service-top"><span>03 — SUPPORT</span><span class="service-symbol">↻</span></div><div><h3>Maintenance</h3><p>Ongoing monitoring and responsive support to help your system perform at its best.</p><a href="#contact" aria-label="Learn more about maintenance">↗</a></div><div class="service-art art-maintain"><span class="pulse">✳</span><span>ALL SYSTEMS<br><b>LOOKING GOOD</b></span><span class="pulse-line"></span></div></article>
        </div>
      </div>
    </section>

    <section class="story section-pad">
      <div class="wrap story-grid"><div class="story-image"><div class="story-stamp">SUN SON<br><b>HOME STORIES</b><br>✳</div><span class="image-caption">A little more sunshine, every day.</span></div><div class="story-copy"><p class="eyebrow"><span></span> From our customers</p><div class="stars" aria-label="5 out of 5 stars">★★★★★</div><blockquote>“The whole process felt easy. The team explained every step, and it feels good knowing our home is making its own clean energy.”</blockquote><div class="quote-by"><span class="avatar">JM</span><span><strong>Jamie M.</strong><small>Sun Son customer · 2025</small></span><span class="quote-count">01 / 03</span></div><a class="text-link" href="#contact">Your story could be next <span>↗</span></a></div></div>
    </section>

    <section class="cta-section" id="contact"><div class="wrap cta-inner"><div><p class="eyebrow eyebrow-light"><span></span> Your next step starts here</p><h2>Let’s make room<br>for a brighter <em>tomorrow.</em></h2></div><div class="cta-actions"><p>Tell us a little about your home. We’ll help you figure out what solar can do for you.</p>
      <?php if ($this->session->flashdata('quote_success')): ?><p class="quote-feedback quote-feedback--success" role="status"><?php echo html_escape($this->session->flashdata('quote_success')); ?></p><?php endif; ?>
      <?php if ($this->session->flashdata('quote_error')): ?><p class="quote-feedback quote-feedback--error" role="alert"><?php echo html_escape($this->session->flashdata('quote_error')); ?></p><?php endif; ?>
      <?php echo form_open('auth/process_quote', array('class' => 'quote-form')); ?>
        <label for="quote-name">Your name</label><input id="quote-name" name="name" type="text" autocomplete="name" placeholder="Full name" required maxlength="160">
        <label for="quote-email">Email address</label><input id="quote-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required maxlength="255">
        <label for="quote-phone">Phone number</label><input id="quote-phone" name="phone" type="tel" autocomplete="tel" placeholder="Best number to reach you" required maxlength="30">
        <label for="quote-service">I’m interested in</label><select id="quote-service" name="service" required><option value="" selected disabled>Select a service</option><option>Consultation</option><option>Installation</option><option>Maintenance</option></select>
        <label for="quote-message">A little about your project <span>(optional)</span></label><textarea id="quote-message" name="message" rows="3" maxlength="2000" placeholder="Tell us what you have in mind"></textarea>
        <button class="button button-lime" type="submit">Request a free quote <span aria-hidden="true">↗</span></button>
      </form><a class="cta-phone" href="tel:+18005550142">Prefer to talk? Call <strong>(800) 555-0142</strong></a></div><span class="cta-sun" aria-hidden="true">✳</span></div></section>

    <section class="faq-section section-pad" id="faq"><div class="wrap faq-grid"><div class="faq-intro"><p class="eyebrow"><span></span> Good questions</p><h2>Curious about<br><em>solar?</em></h2><p>We’re glad you asked. Here are a few things homeowners often want to know.</p><a class="text-link" href="#contact">Ask us anything <span>↗</span></a></div><div class="faq-list">
      <details open><summary>Will solar work for my home?<span></span></summary><p>Most homes with a suitable roof and good sun exposure are candidates. We’ll review your roof, energy use, and local conditions before recommending a system.</p></details>
      <details><summary>What happens on cloudy days?<span></span></summary><p>Panels still produce electricity in daylight, even when skies are overcast. Your home can draw from the grid when your system produces less.</p></details>
      <details><summary>How long does installation take?<span></span></summary><p>Once permits and approvals are in place, most residential installations take one to a few days. We’ll share a clear timeline for your project.</p></details>
      <details><summary>Do solar panels need a lot of maintenance?<span></span></summary><p>Solar systems are designed for low maintenance. We can monitor performance and help with inspections or support whenever you need it.</p></details>
    </div></div></section>

    <section class="journal section-pad" id="journal"><div class="wrap"><div class="journal-heading"><div><p class="eyebrow"><span></span> Notes from the sunny side</p><h2>The latest in <em>good energy.</em></h2></div><a class="text-link" href="#journal">Visit the journal <span>↗</span></a></div><div class="journal-grid">
      <article class="journal-card"><a class="journal-image journal-image-one" href="#contact" aria-label="Read about home battery basics"><span>FIELD NOTES · 6 MIN READ</span></a><p class="journal-meta">SOLAR 101 <span>·</span> MAY 18, 2025</p><h3>Home batteries, made simple</h3><a class="journal-arrow" href="#contact" aria-label="Read Home batteries, made simple">↗</a></article>
      <article class="journal-card"><a class="journal-image journal-image-two" href="#contact" aria-label="Read about what happens during installation"><span>BEHIND THE BUILD</span></a><p class="journal-meta">OUR WORK <span>·</span> APR 02, 2025</p><h3>A closer look at installation day</h3><a class="journal-arrow" href="#contact" aria-label="Read A closer look at installation day">↗</a></article>
      <article class="journal-card"><a class="journal-image journal-image-three" href="#contact" aria-label="Read how solar panels work"><span>THE BRIGHT IDEA</span></a><p class="journal-meta">SOLAR 101 <span>·</span> MAR 12, 2025</p><h3>How sunlight becomes home power</h3><a class="journal-arrow" href="#contact" aria-label="Read How sunlight becomes home power">↗</a></article>
    </div></div></section>
  </main>

  <footer class="footer"><div class="wrap"><div class="footer-main"><div class="footer-brand-col"><a class="brand brand-footer" href="#home"><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 40 40"><circle cx="20" cy="17" r="6"/><path d="M20 2v5m0 20v6M5 17h6m18 0h6M9.4 6.4 13 10m14 14 3.6 3.6M30.6 6.4 27 10m-14 14-3.6 3.6M7 33h26l-3-7H10z"/></svg></span><span><strong>sun son</strong><small>SOLAR CO.</small></span></a><p>Good energy for the way<br>you live today.</p><div class="socials"><a href="#contact" aria-label="Instagram">ig</a><a href="#contact" aria-label="Facebook">f</a><a href="#contact" aria-label="LinkedIn">in</a></div></div><div class="footer-col"><h3>Explore</h3><a href="#services">Our services</a><a href="#about">About us</a><a href="#journal">Journal</a><a href="#faq">FAQs</a></div><div class="footer-col"><h3>Get in touch</h3><a href="tel:+18005550142">(800) 555-0142</a><a href="mailto:hello@sunsonsolar.com">hello@sunsonsolar.com</a><span>Mon – Fri, 8am – 6pm</span></div><div class="footer-news"><h3>Let’s talk solar</h3><p>Our team can help you find the right next step for your home.</p><a class="text-link" href="#contact">Request a free quote <span>↗</span></a></div></div><div class="footer-bottom"><span>© 2025 Sun Son Solar Co. All rights reserved.</span><div><a href="#privacy">Privacy</a><a href="#terms">Terms</a><a href="#accessibility">Accessibility</a></div><span>Thoughtfully powered by the sun ✳</span></div></div></footer>
</body>
</html>
