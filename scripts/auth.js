const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");
const btnLogin = document.getElementById("btnLogin");
const btnRegister = document.getElementById("btnRegister");
const statusBox = document.getElementById("statusBox");
const loginSubmit = document.getElementById("loginSubmit");
const registerSubmit = document.getElementById("registerSubmit");

function switchTab(tab) {
  loginForm.classList.remove("active");
  registerForm.classList.remove("active");
  btnLogin.classList.remove("active");
  btnRegister.classList.remove("active");
  clearStatus();

  if (tab === "login") {
    loginForm.classList.add("active");
    btnLogin.classList.add("active");
  } else {
    registerForm.classList.add("active");
    btnRegister.classList.add("active");
  }
}

function showStatus(message, type) {
  statusBox.textContent = message;
  statusBox.className = "status-box " + type;
}

function clearStatus() {
  statusBox.textContent = "";
  statusBox.className = "status-box";
}

function setLoading(button, isLoading, idleText, loadingText) {
  button.disabled = isLoading;
  button.textContent = isLoading ? loadingText : idleText;
}

btnLogin.addEventListener("click", () => switchTab("login"));
btnRegister.addEventListener("click", () => switchTab("register"));

loginForm.addEventListener("submit", (e) => {
  e.preventDefault();
  clearStatus();
  setLoading(loginSubmit, true, "Iniciar sesion", "Validando...");

  fetch("auth.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      action: "login",
      email: document.getElementById("loginEmail").value.trim(),
      password: document.getElementById("loginPassword").value
    })
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showStatus("Acceso correcto. Redirigiendo...", "success");
        window.location.href = "index.php";
      } else {
        showStatus(data.msg || "Credenciales incorrectas", "error");
      }
    })
    .catch(() => {
      showStatus("No se pudo conectar con el servidor.", "error");
    })
    .finally(() => {
      setLoading(loginSubmit, false, "Iniciar sesion", "Validando...");
    });
});

registerForm.addEventListener("submit", (e) => {
  e.preventDefault();
  clearStatus();
  setLoading(registerSubmit, true, "Crear cuenta", "Creando cuenta...");

  fetch("auth.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      action: "register",
      nombres: document.getElementById("regNombres").value.trim(),
      apellidos: document.getElementById("regApellidos").value.trim(),
      email: document.getElementById("regEmail").value.trim(),
      numero: document.getElementById("regNumero").value.trim(),
      password: document.getElementById("regPassword").value
    })
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        showStatus("Cuenta creada correctamente. Ya puedes iniciar sesion.", "success");
        registerForm.reset();
        switchTab("login");
      } else {
        showStatus(data.msg || "Error al registrar", "error");
      }
    })
    .catch(() => {
      showStatus("No se pudo completar el registro.", "error");
    })
    .finally(() => {
      setLoading(registerSubmit, false, "Crear cuenta", "Creando cuenta...");
    });
});
