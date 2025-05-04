const themeToggle = document.getElementById('themeToggle');
/* const themeIcon = document.getElementById('themeIcon'); */
const htmlElement = document.documentElement;

// Verificar preferencia del sistema o almacenamiento local
const savedTheme = localStorage.getItem('theme') ||
    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

// Aplicar tema guardado o preferido
htmlElement.setAttribute('data-bs-theme', savedTheme);
/* updateIcon(savedTheme); */

// Alternar tema al hacer clic
themeToggle.addEventListener('click', function () {
    const currentTheme = htmlElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    // Cambiar tema
    htmlElement.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    /* updateIcon(newTheme); */
});

// Actualizar icono según el tema
/* function updateIcon(theme) {
    themeIcon.textContent = theme === 'dark' ? '🌙' : '🌞';
} */

// Opcional: Escuchar cambios en la preferencia del sistema
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem('theme')) {
        const newTheme = e.matches ? 'dark' : 'light';
        htmlElement.setAttribute('data-bs-theme', newTheme);
        /*  updateIcon(newTheme); */
    }
});
