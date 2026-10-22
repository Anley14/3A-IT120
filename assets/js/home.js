// Keep the mobile menu and login menu usable with mouse, touch, and keyboard.
document.addEventListener('DOMContentLoaded', function () {
  var menuButton = document.querySelector('.menu-toggle');
  var navMenu = document.querySelector('.nav-menu');
  var loginMenu = document.querySelector('.login-menu');
  var loginButton = document.querySelector('.login-trigger');

  if (menuButton && navMenu) {
    menuButton.addEventListener('click', function () {
      var isOpen = navMenu.classList.toggle('is-open');
      menuButton.setAttribute('aria-expanded', String(isOpen));
    });
    navMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        navMenu.classList.remove('is-open');
        menuButton.setAttribute('aria-expanded', 'false');
      });
    });
  }

  if (loginButton && loginMenu) {
    loginButton.addEventListener('click', function () {
      var isOpen = loginMenu.classList.toggle('is-open');
      loginButton.setAttribute('aria-expanded', String(isOpen));
    });
    document.addEventListener('click', function (event) {
      if (!loginMenu.contains(event.target)) {
        loginMenu.classList.remove('is-open');
        loginButton.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        loginMenu.classList.remove('is-open');
        loginButton.setAttribute('aria-expanded', 'false');
        loginButton.focus();
      }
    });
  }
});
