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

headers.forEach(header => {
    let words = header.textContent.split(' ');
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

export { API, token, csrfToken, hideModal, swal };

//console.log('API loaded successfully');
