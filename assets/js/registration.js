const accountTabs = document.querySelectorAll("[data-account]");
const employeeDetails = document.querySelector("#employee-details");
const departmentSelect = employeeDetails?.querySelector("select");
const registrationForm = document.querySelector("#registration-form");
const accountRole = document.querySelector("#account-role");
const passwordInput = document.querySelector("#password");
const passwordToggle = document.querySelector(".password-toggle");

function activateAccountTab(activeTab, focusTab = false) {
  const isEmployee = activeTab.dataset.account === "employee";

  accountTabs.forEach((tab) => {
    const isActive = tab === activeTab;
    tab.classList.toggle("is-active", isActive);
    tab.setAttribute("aria-selected", String(isActive));
    tab.tabIndex = isActive ? 0 : -1;
  });

  employeeDetails.hidden = !isEmployee;
  accountRole.value = isEmployee ? "employee" : "customer";
  departmentSelect.disabled = !isEmployee;
  departmentSelect.required = isEmployee;
  registrationForm.setAttribute("aria-labelledby", activeTab.id);

  if (focusTab) {
    activeTab.focus();
  }
}

if (accountTabs.length && employeeDetails && departmentSelect && registrationForm) {
  accountTabs.forEach((tab) => {
    tab.addEventListener("click", () => activateAccountTab(tab));

    tab.addEventListener("keydown", (event) => {
      const currentIndex = Array.from(accountTabs).indexOf(tab);
      let nextIndex;

      switch (event.key) {
        case "ArrowRight":
          nextIndex = (currentIndex + 1) % accountTabs.length;
          break;
        case "ArrowLeft":
          nextIndex = (currentIndex - 1 + accountTabs.length) % accountTabs.length;
          break;
        case "Home":
          nextIndex = 0;
          break;
        case "End":
          nextIndex = accountTabs.length - 1;
          break;
        default:
          return;
      }

      event.preventDefault();
      activateAccountTab(accountTabs[nextIndex], true);
    });
  });

  activateAccountTab(accountTabs[0]);
}

if (passwordInput && passwordToggle) {
  passwordToggle.addEventListener("click", () => {
    const shouldShow = passwordInput.type === "password";
    passwordInput.type = shouldShow ? "text" : "password";
    passwordToggle.setAttribute("aria-pressed", String(shouldShow));
    passwordToggle.setAttribute("aria-label", shouldShow ? "Hide password" : "Show password");
  });
}
