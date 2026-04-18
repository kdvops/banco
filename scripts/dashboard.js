const settingsModal = document.getElementById("settingsModal");
const confirmModal = document.getElementById("confirmModal");
const confirmText = document.getElementById("confirmText");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const searchForm = document.getElementById("searchForm");
let currentDelete = null;

document.querySelector(".js-settings-open")?.addEventListener("click", () => {
  settingsModal.style.display = "flex";
});

document.querySelector(".js-settings-close")?.addEventListener("click", () => {
  settingsModal.style.display = "none";
});

window.addEventListener("click", (event) => {
  if (event.target === settingsModal) {
    settingsModal.style.display = "none";
  }
});

document.querySelectorAll(".js-delete-trigger").forEach((button) => {
  button.addEventListener("click", () => {
    currentDelete = {
      id: button.dataset.deleteId,
      type: button.dataset.deleteType
    };
    confirmText.innerText = "Estas seguro de que deseas eliminar este elemento?";
    openModalById("confirmModal");
  });
});

document.getElementById("btnEliminarServicio")?.addEventListener("click", () => {
  const modal = document.getElementById("modal");
  currentDelete = {
    id: modal.dataset.serviceId,
    type: "servicio"
  };
  closeModalById("modal");
  confirmText.innerText = "Estas seguro de que deseas eliminar este elemento?";
  openModalById("confirmModal");
});

confirmDeleteBtn?.addEventListener("click", () => {
  if (!currentDelete?.id) {
    closeModalById("confirmModal");
    return;
  }

  const payload = new URLSearchParams({
    action: "eliminar",
    id: currentDelete.id,
    tipo: currentDelete.type
  });

  fetch("procesar.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: payload.toString()
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "ok") {
        location.reload();
      } else {
        alert(data.msg || "No se pudo eliminar");
      }
    });
});

function bindAjaxForm(formId, useFormData = true) {
  const form = document.getElementById(formId);
  if (!form) {
    return;
  }

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const options = {
      method: "POST"
    };

    if (useFormData) {
      options.body = new FormData(form);
    } else {
      options.headers = {
        "Content-Type": "application/x-www-form-urlencoded"
      };
      options.body = new URLSearchParams(new FormData(form)).toString();
    }

    fetch("procesar.php", options)
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "ok") {
          location.reload();
        } else {
          alert(data.msg || "No se pudo guardar");
        }
      });
  });
}

bindAjaxForm("perfilForm", true);
bindAjaxForm("servicioForm", true);
bindAjaxForm("cuentaForm", false);
bindAjaxForm("cryptoForm", false);
bindAjaxForm("plataformaForm", false);

searchForm?.addEventListener("submit", (event) => {
  event.preventDefault();
  const phone = document.getElementById("telefonoBusqueda").value;

  fetch("buscar_telefono.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: new URLSearchParams({ telefono: phone }).toString()
  })
    .then((res) => res.text())
    .then((result) => {
      const alertBox = document.getElementById("searchAlert");

      if (result.trim() === "no_encontrado") {
        alertBox.className = "alert-error";
        alertBox.textContent = "Numero no encontrado";
        alertBox.style.display = "block";
      } else {
        window.location.href = "perfildecuentas.php?numero=" + encodeURIComponent(phone);
      }
    });
});
