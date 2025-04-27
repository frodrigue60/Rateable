const staticCacheName = "pwa-cache-v1";
const dynamicCacheName = "pwa-dynamic-cache-v1";
const filesToCache = [
    '/',
    // Añade otros assets estáticos que quieras cachear
];

if (import.meta.env.VITE_HMR) {
    self.addEventListener('install', () => self.skipWaiting());
    self.addEventListener('activate', () => self.registration.unregister());
}

// Cache on install
self.addEventListener("install", event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    );
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => cacheName.startsWith("pwa-"))
                    .filter(cacheName => (cacheName !== staticCacheName && cacheName !== dynamicCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache with network fallback
self.addEventListener("fetch", event => {
    // Ignora solicitudes de chrome-extension
    if (event.request.url.indexOf('chrome-extension') > -1) return;

    event.respondWith(
        caches.match(event.request)
            .then(cachedResponse => {
                // Devuelve la respuesta en caché si existe
                if (cachedResponse) {
                    return cachedResponse;
                }

                // Para recursos de Vite (assets en /assets/)
                if (event.request.url.includes('/assets/')) {
                    return caches.match(event.request.url)
                        .then(cachedAsset => {
                            return cachedAsset || fetchAndCache(event.request, dynamicCacheName);
                        });
                }

                // Intenta obtener de la red
                return fetchAndCache(event.request, dynamicCacheName);
            })
            .catch(() => {
                // Fallback para cuando no hay conexión
                return caches.match('/offline.html');
            })
    );
});

// Helper function to fetch and cache
function fetchAndCache(request, cacheName) {
    return fetch(request).then(response => {
        // Clonamos la respuesta porque solo se puede consumir una vez
        const responseToCache = response.clone();

        caches.open(cacheName).then(cache => {
            cache.put(request, responseToCache);
        });

        return response;
    });
}

// Mensaje para actualizar el service worker
self.addEventListener('message', (event) => {
    if (event.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});

self.addEventListener('fetch', (event) => {
    // Excluir las rutas de HMR de Vite
    if (event.request.url.includes('@vite') ||
        event.request.url.includes('@react-refresh') ||
        event.request.url.includes('?t=')) {
        return fetch(event.request);
    }

    // Tu lógica normal de cache aquí
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
    );
});
