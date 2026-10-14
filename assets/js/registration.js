// Registration role is selected by the portal URL; keep password reveal local.
const passwordInput = document.querySelector("#password");
const passwordToggle = document.querySelector(".password-toggle");

if (passwordInput && passwordToggle) {
  passwordToggle.addEventListener("click", () => {
    const shouldShow = passwordInput.type === "password";
    passwordInput.type = shouldShow ? "text" : "password";
    passwordToggle.setAttribute("aria-pressed", String(shouldShow));
    passwordToggle.setAttribute("aria-label", shouldShow ? "Hide password" : "Show password");
  });
}
