const settingsModal = document.getElementById("settingsModal");
const confirmModal = document.getElementById("confirmModal");
const confirmText = document.getElementById("confirmText");
const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
const searchForm = document.getElementById("searchForm");
const servicioForm = document.getElementById("servicioForm");
const servicioImageInput = servicioForm?.querySelector('input[name="imagen"]');
const servicioIdInput = servicioForm?.querySelector('input[name="servicio_id"]');
const servicioTitle = document.getElementById("servicioFormTitle");
const servicioDescription = document.getElementById("servicioFormDescription");
const servicioKicker = document.getElementById("servicioFormKicker");
const servicioSubmitBtn = document.getElementById("servicioSubmitBtn");
const servicioImageHint = document.getElementById("servicioImageHint");
const cuentaForm = document.getElementById("cuentaForm");
const cuentaIdInput = cuentaForm?.querySelector('input[name="cuenta_id"]');
const cuentaTitle = document.getElementById("cuentaFormTitle");
const cuentaDescription = document.getElementById("cuentaFormDescription");
const cuentaKicker = document.getElementById("cuentaFormKicker");
const cuentaSubmitBtn = document.getElementById("cuentaSubmitBtn");
const bankCountrySelect = document.getElementById("bankCountrySelect");
const bankEntitySelect = document.getElementById("bankEntitySelect");
let currentDelete = null;

document.querySelectorAll(".js-settings-open").forEach((trigger) => {
  trigger.addEventListener("click", () => {
    settingsModal.style.display = "flex";
  });
});

document.querySelector(".js-settings-close")?.addEventListener("click", () => {
  settingsModal.style.display = "none";
});

document.querySelectorAll(".js-settings-dismiss").forEach((trigger) => {
  trigger.addEventListener("click", () => {
    settingsModal.style.display = "none";
  });
});

document.querySelectorAll(".js-settings-open-modal").forEach((trigger) => {
  trigger.addEventListener("click", () => {
    prepareModalForCreate(trigger.dataset.modalTarget);
    settingsModal.style.display = "none";
    openModalById(trigger.dataset.modalTarget);
  });
});

document.querySelectorAll(".js-copy-url").forEach((trigger) => {
  trigger.addEventListener("click", async () => {
    const rawUrl = trigger.dataset.copyUrl || window.location.href;
    const copyUrl = new URL(rawUrl, window.location.href).href;
    await navigator.clipboard.writeText(copyUrl);
    alert("Enlace copiado: " + copyUrl);
  });
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

function syncBankEntityOptions(preselectedBankId = "") {
  if (!bankCountrySelect || !bankEntitySelect) {
    return;
  }

  const selectedCountryId = bankCountrySelect.value;
  const placeholder = bankEntitySelect.dataset.placeholder || "Selecciona un banco";
  const emptyOption = bankEntitySelect.querySelector("option[value=\"\"]");

  if (emptyOption) {
    emptyOption.textContent = selectedCountryId ? placeholder : "Selecciona un pais primero";
  }

  bankEntitySelect.disabled = !selectedCountryId;

  bankEntitySelect.querySelectorAll("option[data-country-id]").forEach((option) => {
    const matchesCountry = option.dataset.countryId === selectedCountryId;
    option.hidden = !matchesCountry;
    option.disabled = !matchesCountry;
  });

  if (!selectedCountryId) {
    bankEntitySelect.value = "";
    return;
  }

  const targetValue = String(preselectedBankId || "");
  const targetOption = targetValue ? bankEntitySelect.querySelector(`option[value="${CSS.escape(targetValue)}"]`) : null;
  bankEntitySelect.value = targetOption && !targetOption.disabled ? targetValue : "";
}

function resetServicioForm() {
  if (!servicioForm) {
    return;
  }

  servicioForm.reset();
  servicioIdInput.value = "";
  servicioImageInput.required = true;
  servicioKicker.textContent = "Servicio destacado";
  servicioTitle.textContent = "Presenta mejor lo que ofreces";
  servicioDescription.textContent = "Agrega una imagen clara, una descripcion breve y un enlace directo para que puedan conocerte y contactarte rapido.";
  servicioImageHint.textContent = "Sube una imagen representativa para mostrar mejor tu servicio.";
  servicioSubmitBtn.textContent = "Guardar";
}

function setServicioFormForEdit(service) {
  if (!servicioForm) {
    return;
  }

  servicioForm.reset();
  servicioIdInput.value = service.id || "";
  servicioForm.querySelector('input[name="nombre_servicio"]').value = service.name || "";
  servicioForm.querySelector('textarea[name="resena"]').value = service.resena || "";
  servicioForm.querySelector('input[name="enlace"]').value = service.link || "";
  servicioImageInput.required = false;
  servicioKicker.textContent = "Editar servicio";
  servicioTitle.textContent = "Actualiza este acceso rapido";
  servicioDescription.textContent = "Puedes cambiar el nombre, la descripcion, el enlace y, si quieres, reemplazar la imagen actual.";
  servicioImageHint.textContent = "Deja este campo vacio si quieres conservar la imagen actual.";
  servicioSubmitBtn.textContent = "Guardar cambios";
}

function resetCuentaForm() {
  if (!cuentaForm) {
    return;
  }

  cuentaForm.reset();
  cuentaIdInput.value = "";
  cuentaKicker.textContent = "Cuenta bancaria";
  cuentaTitle.textContent = "Selecciona primero el pais";
  cuentaDescription.textContent = "Para evitar una lista demasiado larga, elige el pais y luego veras solo las entidades financieras disponibles en ese mercado.";
  cuentaSubmitBtn.textContent = "Guardar";
  syncBankEntityOptions();
}

function setCuentaFormForEdit(account) {
  if (!cuentaForm) {
    return;
  }

  cuentaForm.reset();
  cuentaIdInput.value = account.id || "";
  cuentaKicker.textContent = "Editar cuenta bancaria";
  cuentaTitle.textContent = "Actualiza los datos de esta cuenta";
  cuentaDescription.textContent = "Puedes cambiar el pais, el banco, el tipo de cuenta o el numero sin tener que eliminarla y crearla de nuevo.";
  cuentaSubmitBtn.textContent = "Guardar cambios";
  bankCountrySelect.value = account.countryId || "";
  syncBankEntityOptions(account.bankId || "");
  cuentaForm.querySelector('select[name="tipo"]').value = account.type || "Ahorro";
  cuentaForm.querySelector('input[name="numero"]').value = account.number || "";
}

function prepareModalForCreate(targetModal) {
  if (targetModal === "servicio") {
    resetServicioForm();
  }

  if (targetModal === "cuenta") {
    resetCuentaForm();
  }
}

document.querySelectorAll('.js-open-modal[data-modal-target="servicio"], .js-open-modal[data-modal-target="cuenta"]').forEach((trigger) => {
  trigger.addEventListener("click", () => {
    prepareModalForCreate(trigger.dataset.modalTarget);
  });
});

document.getElementById("btnEditarServicio")?.addEventListener("click", () => {
  const modal = document.getElementById("modal");
  setServicioFormForEdit({
    id: modal.dataset.serviceId || "",
    name: modal.dataset.serviceTitle || "",
    resena: modal.dataset.serviceText || "",
    link: modal.dataset.serviceLink || ""
  });
  closeModalById("modal");
  openModalById("servicio");
});

document.querySelectorAll(".js-edit-account").forEach((button) => {
  button.addEventListener("click", () => {
    setCuentaFormForEdit({
      id: button.dataset.accountId || "",
      countryId: button.dataset.accountCountryId || "",
      bankId: button.dataset.accountBankId || "",
      type: button.dataset.accountType || "",
      number: button.dataset.accountNumber || ""
    });
    openModalById("cuenta");
  });
});

bankCountrySelect?.addEventListener("change", () => syncBankEntityOptions());
resetServicioForm();
resetCuentaForm();

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
