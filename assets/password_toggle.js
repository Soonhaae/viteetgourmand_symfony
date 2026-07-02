function initPasswordToggles() {
  document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    if (button.dataset.initialized === 'true') {
      return;
    }

    const input = document.getElementById(button.dataset.passwordToggle);

    if (!input) {
      return;
    }

    button.dataset.initialized = 'true';
    button.setAttribute('aria-pressed', 'false');
    button.setAttribute('aria-controls', input.id);
    button.setAttribute('aria-label', 'Afficher le mot de passe');

    button.addEventListener('click', () => {
      const isHidden = input.type === 'password';

      input.type = isHidden ? 'text' : 'password';
      button.textContent = isHidden ? 'masquer le mot de passe' : 'afficher le mot de passe';
      button.setAttribute('aria-pressed', String(isHidden));
      button.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
  });
}

document.addEventListener('DOMContentLoaded', initPasswordToggles);
document.addEventListener('turbo:load', initPasswordToggles);
