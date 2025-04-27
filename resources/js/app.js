import API from '@api/index.js';
// Importa Bootstrap JS
import * as bootstrap from 'bootstrap';

// Opcional: Inicializa componentes que necesiten JS
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Tu código JavaScript
import './bootstrap';  // Si usas el archivo bootstrap.js de Laravel

console.log('Bootstrap cargado correctamente');

// resources/js/app.js (o tu archivo de entrada principal)
/* if ('serviceWorker' in navigator && import.meta.env.PROD) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/pwa-sw.js', {
            scope: '/'
        }).then(registration => {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);

            // Verifica si hay una nueva versión cada vez que se carga la página
            registration.update();

            // Escucha actualizaciones
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed') {
                        if (navigator.serviceWorker.controller) {
                            // Hay una nueva versión disponible
                            console.log('New content is available; please refresh.');
                            // Puedes mostrar un botón para actualizar aquí
                        }
                    }
                });
            });
        }).catch(err => {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
} */

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

export { API, token, csrfToken };

console.log('API loaded successfully');
