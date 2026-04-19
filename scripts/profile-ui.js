function openModalById(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.style.display = "flex";
  }
}

function closeModalById(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.style.display = "none";
  }
}

document.querySelectorAll(".js-open-modal").forEach((trigger) => {
  trigger.addEventListener("click", () => openModalById(trigger.dataset.modalTarget));
});

document.querySelectorAll(".js-close-modal").forEach((trigger) => {
  trigger.addEventListener("click", () => closeModalById(trigger.dataset.modalTarget));
});

document.querySelector(".js-open-profile-modal")?.addEventListener("click", () => {
  openModalById("profileModal");
});

document.querySelectorAll(".js-tab-trigger").forEach((button) => {
  button.addEventListener("click", () => {
    const target = button.dataset.tabTarget;
    document.querySelectorAll(".tabcontent").forEach((tab) => {
      tab.classList.remove("is-active");
    });
    document.querySelectorAll(".tablink").forEach((tabButton) => {
      tabButton.classList.remove("active");
    });
    document.getElementById(target)?.classList.add("is-active");
    button.classList.add("active");
  });
});

document.querySelectorAll(".js-toggle-gate").forEach((button) => {
  button.addEventListener("click", () => {
    const gate = button.closest(".card").querySelector(".gate");
    const icon = button.querySelector("i");
    gate.style.maxHeight = gate.style.maxHeight ? null : gate.scrollHeight + "px";
    icon.className = gate.style.maxHeight ? "fa-regular fa-eye-slash" : "fa-regular fa-eye";
  });
});

document.querySelectorAll(".js-copy-btn").forEach((button) => {
  button.addEventListener("click", () => {
    const text = button.parentElement.querySelector(".copy-text").innerText;
    navigator.clipboard.writeText(text);
    button.querySelector("i").className = "fa-solid fa-check";
    setTimeout(() => {
      button.querySelector("i").className = "fa-regular fa-copy";
    }, 1500);
  });
});

document.querySelectorAll(".js-card-menu").forEach((button) => {
  button.addEventListener("click", (event) => {
    event.stopPropagation();
    document.querySelectorAll(".menu-dropdown").forEach((menu) => {
      if (menu !== button.nextElementSibling) {
        menu.style.display = "none";
      }
    });
    button.nextElementSibling.style.display = button.nextElementSibling.style.display === "flex" ? "none" : "flex";
  });
});

document.addEventListener("click", () => {
  document.querySelectorAll(".menu-dropdown").forEach((menu) => {
    menu.style.display = "none";
  });
});

document.querySelectorAll(".js-open-link").forEach((button) => {
  button.addEventListener("click", () => {
    const url = button.dataset.externalLink;
    if (url) {
      window.open(url, "_blank");
    }
  });
});

document.querySelectorAll(".js-share-page").forEach((button) => {
  button.addEventListener("click", async () => {
    const title = button.dataset.shareTitle || document.title;
    if (navigator.share) {
      await navigator.share({ title, url: location.href });
    } else {
      await navigator.clipboard.writeText(location.href);
      alert("Enlace copiado: " + location.href);
    }
  });
});

document.querySelectorAll(".js-mini-profile").forEach((button) => {
  button.addEventListener("click", () => {
    const modal = document.getElementById("modal");
    document.getElementById("modalImg").src = button.dataset.serviceImg || "";
    document.getElementById("modalTitle").innerText = button.dataset.serviceTitle || "";
    document.getElementById("modalText").innerText = button.dataset.serviceText || "";

    const modalLink = document.getElementById("modalLink");
    const modalLinkContainer = document.getElementById("modalLinkContainer");
    const serviceLink = button.dataset.serviceLink || "";

    modalLink.href = serviceLink;
    modalLinkContainer.style.display = serviceLink ? "block" : "none";
    modal.dataset.serviceId = button.dataset.serviceId || "";

    openModalById("modal");
  });
});
