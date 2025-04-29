import API from '@api/index.js';
import * as bootstrap from 'bootstrap';
import swal from 'sweetalert';
import registerServiceWorker from './sw-register';


// Opcional: Inicializa componentes que necesiten JS
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Tu código JavaScript
//import './bootstrap';  // Si usas el archivo bootstrap.js de Laravel

//console.log('Bootstrap cargado correctamente');

// Selecciona el elemento que contiene el texto
let headers = document.querySelectorAll('.section-header');
/* console.log(headers); */

headers.forEach(header => {
    // Divide el texto en palabras
    let words = header.textContent.split(' ');

    // Vuelve a unir con la primera palabra envuelta en un span
    header.innerHTML = `<span class="first-word">${words[0]}</span> ${words.slice(1).join(' ')}`;
});

const token = localStorage.getItem('api_token');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

/* function onDomReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
    } else {
        callback();
    }
} */

/* PWA */
// Ejecutamos la función de registro
//registerServiceWorker();
/* PWA */

function hideModal(modalId) {
    try {
        const modalElement = document.getElementById(modalId);

        if (!modalElement) {
            throw new Error(`Modal con ID ${modalId} no encontrado`);
        }

        const modalInstance = bootstrap.Modal.getInstance(modalElement);

        if (modalInstance) {
            modalInstance.hide();
        } else {
            // Si no existe instancia, crear una temporal
            new bootstrap.Modal(modalElement).hide();
        }

        return true;
    } catch (error) {
        console.error('Error ocultando modal:', error);
        return false;
    }
}


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

export { API, token, csrfToken, hideModal, swal };

//console.log('API loaded successfully');
