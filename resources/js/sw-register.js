// Función para detectar entorno de desarrollo
const isDevelopment = () => {
    const host = window.location.hostname;
    return host === 'localhost' ||
        host === '127.0.0.1' ||
        host.includes('.local') ||
        window.location.port === '3000' ||
        window.location.port === '5173'; // Puerto por defecto de Vite
};

// Función para registrar el Service Worker solo en producción
const registerServiceWorker = () => {
    if (isDevelopment()) {
        console.log('Modo desarrollo detectado. Service Worker no registrado.');
        // Si existe un service worker previamente registrado en desarrollo, lo eliminamos
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(registrations => {
                for (let registration of registrations) {
                    registration.unregister();
                    console.log('Service Worker previo desregistrado en entorno de desarrollo');
                }
            });
        }
        return;
    }

    // Solo registramos el Service Worker en producción
    if ('serviceWorker' in navigator) {
        // Esperamos hasta que la página esté completamente cargada
        window.addEventListener('load', () => {
            // El service worker se encuentra en la raíz del sitio
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('Service Worker registrado con éxito:', registration.scope);
                })
                .catch(error => {
                    console.error('Error al registrar el Service Worker:', error);
                });
        });

        // Manejamos la instalación de la PWA
        let deferredPrompt;

        // Capturamos el evento beforeinstallprompt para mostrar nuestro propio botón de instalación
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevenimos el prompt automático
            e.preventDefault();
            // Guardamos el evento para usarlo después
            deferredPrompt = e;

            // Aquí podrías mostrar tu propio botón de instalación
            console.log('La PWA es instalable. Puedes mostrar un botón de instalación.');

            // Ejemplo: Si tienes un botón con id "installButton"
            // document.getElementById('installButton').style.display = 'block';
        });

        // Detectar cuando la PWA ha sido instalada
        window.addEventListener('appinstalled', () => {
            console.log('PWA instalada correctamente');
            deferredPrompt = null;

            // Aquí podrías ocultar el botón de instalación
            // document.getElementById('installButton').style.display = 'none';
        });
    }
};

export default registerServiceWorker;
