document.querySelectorAll(".js-nav-toggle").forEach((button) => {
  button.addEventListener("click", () => {
    const nav = button.closest(".site-nav");
    const isOpen = nav.classList.toggle("is-open");
    button.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });
});

document.addEventListener("click", (event) => {
  document.querySelectorAll(".site-nav.is-open").forEach((nav) => {
    if (!nav.contains(event.target)) {
      nav.classList.remove("is-open");
      const toggle = nav.querySelector(".js-nav-toggle");
      if (toggle) {
        toggle.setAttribute("aria-expanded", "false");
      }
    }
  });
});
