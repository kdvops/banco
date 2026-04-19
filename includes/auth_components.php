<?php

function app_render_auth_shell(): void
{
    echo <<<HTML
<div class="ambient" aria-hidden="true"></div>

<main class="auth-shell">
  <section class="auth-panel">
    <div class="auth-card">
      <div class="auth-head">
        <span class="eyebrow">Perfil compartible de cobro</span>
        <h1>Accede a tu panel</h1>
        <p>Inicia sesion o crea tu cuenta para gestionar tus metodos de cobro.</p>
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

  <aside class="hero-panel">
    <div class="hero-badge">
      <i class="fa-solid fa-shield-heart"></i>
      <span>Informacion rapida</span>
    </div>

    <div class="hero-icon">
      <i class="fa-solid fa-wallet"></i>
    </div>

    <h2>Todo en un solo perfil compartible.</h2>
    <p>Organiza cuentas, billeteras y enlaces para recibir dinero sin enviar tus datos por partes.</p>

    <ul class="feature-list">
      <li class="feature-item">
        <i class="fa-solid fa-layer-group"></i>
        <div>
          <strong>Banco, cripto y online</strong>
          <span>Ten todo reunido en un mismo lugar.</span>
        </div>
      </li>
      <li class="feature-item">
        <i class="fa-solid fa-share-nodes"></i>
        <div>
          <strong>Mas facil de compartir</strong>
          <span>Usa un solo perfil publico actualizado.</span>
        </div>
      </li>
    </ul>
  </aside>
</main>
HTML;
}
