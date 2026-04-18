<?php

function app_render_auth_shell(): void
{
    echo <<<HTML
<div class="ambient" aria-hidden="true"></div>

<main class="auth-shell">
  <section class="hero-panel">
    <div class="hero-badge">
      <i class="fa-solid fa-shield-heart"></i>
      <span>Comparte tus datos de cobro de forma clara y rapida</span>
    </div>

    <div class="hero-icon">
      <i class="fa-solid fa-wallet"></i>
    </div>

    <h1>Organiza tus billeteras, cuentas y metodos para recibir dinero en un solo perfil.</h1>
    <p>Esta aplicacion no procesa pagos. Te ayuda a reunir y compartir de forma simple las cuentas, billeteras y enlaces donde otras personas pueden enviarte dinero rapidamente.</p>

    <ul class="feature-list">
      <li class="feature-item">
        <i class="fa-solid fa-layer-group"></i>
        <div>
          <strong>Todo en un solo perfil</strong>
          <span>Reune cuentas bancarias, billeteras cripto y metodos online en un solo lugar facil de compartir.</span>
        </div>
      </li>
      <li class="feature-item">
        <i class="fa-solid fa-share-nodes"></i>
        <div>
          <strong>Comparte sin complicaciones</strong>
          <span>La idea es que otra persona vea rapidamente donde puede transferirte dinero, sin pedirte los datos uno por uno.</span>
        </div>
      </li>
      <li class="feature-item">
        <i class="fa-solid fa-mobile-screen-button"></i>
        <div>
          <strong>Rapido desde cualquier dispositivo</strong>
          <span>Tu perfil se consulta facilmente desde movil o escritorio para copiar cuentas, enlaces y direcciones.</span>
        </div>
      </li>
    </ul>
  </section>

  <section class="auth-panel">
    <div class="auth-card">
      <div class="auth-head">
        <span class="eyebrow">Perfil compartible de cobro</span>
        <h2>Bienvenido</h2>
        <p>Accede a tu panel o crea una cuenta para organizar los metodos donde puedes recibir dinero y compartirlos con otras personas de manera ordenada.</p>
      </div>

      <div id="statusBox" class="status-box" role="status" aria-live="polite"></div>

      <div class="tabs">
        <button id="btnLogin" class="active" type="button">Entrar</button>
        <button id="btnRegister" type="button">Crear cuenta</button>
      </div>

      <form id="loginForm" class="form active" novalidate>
        <div class="field">
          <label for="loginEmail">Correo electronico</label>
          <input type="email" id="loginEmail" placeholder="tu@correo.com" autocomplete="email" required>
        </div>

        <div class="field">
          <label for="loginPassword">Contrasena</label>
          <input type="password" id="loginPassword" placeholder="Ingresa tu contrasena" autocomplete="current-password" required>
          <span class="helper">Usa el correo con el que registraste tu perfil compartible.</span>
        </div>

        <button id="loginSubmit" class="submit" type="submit">Iniciar sesion</button>
      </form>

      <form id="registerForm" class="form" novalidate>
        <div class="field-row">
          <div class="field">
            <label for="regNombres">Nombres</label>
            <input type="text" id="regNombres" placeholder="Ej. Maria Elena" autocomplete="given-name" required>
          </div>

          <div class="field">
            <label for="regApellidos">Apellidos</label>
            <input type="text" id="regApellidos" placeholder="Ej. Perez Gomez" autocomplete="family-name" required>
          </div>
        </div>

        <div class="field">
          <label for="regEmail">Correo electronico</label>
          <input type="email" id="regEmail" placeholder="tu@correo.com" autocomplete="email" required>
        </div>

        <div class="field">
          <label for="regNumero">Numero celular</label>
          <input type="tel" id="regNumero" placeholder="8091234567" inputmode="numeric" autocomplete="tel" required>
        </div>

        <div class="field">
          <label for="regPassword">Contrasena</label>
          <input type="password" id="regPassword" placeholder="Crea una contrasena segura" autocomplete="new-password" required>
          <span class="helper">Tu numero puede usarse para encontrar y compartir tu perfil publicamente.</span>
        </div>

        <button id="registerSubmit" class="submit" type="submit">Crear cuenta</button>
      </form>

      <p class="footnote">La plataforma organiza datos de cobro para compartirlos facilmente. No mueve dinero ni ejecuta pagos dentro de la aplicacion.</p>
    </div>
  </section>
</main>
HTML;
}
