// Service Worker con soporte para cachear carpetas completas (como /build de Vite)
const CACHE_NAME = 'pwa-cache-v2';

// Configuración de recursos a cachear
const CONFIG = {
  // Recursos individuales que siempre se cachean
  staticAssets: [
    '/',
    '/manifest.json',
  ],

  // Directorios completos a cachear (con sus subcarpetas)
  assetDirectories: [
    '/build', // Carpeta build generada por Vite
    '/storage/thumbnails' // Otra carpeta común para assets
  ],

  // Extensiones de archivos a cachear cuando se encuentran en las solicitudes
  fileExtensions: [
    '.js',
    '.css',
    '.svg',
    '.png',
    '.jpg',
    '.jpeg',
    '.gif',
    '.json',
    '.woff',
    '.woff2',
    '.ttf',
    '.eot'
  ]
};

// Función para comprobar si una URL debe cachearse según su ruta o extensión
function shouldCache(url) {
  const requestUrl = new URL(url);
  const requestPath = requestUrl.pathname;

  // Si está en la lista de recursos estáticos, siempre cachear
  if (CONFIG.staticAssets.includes(requestPath)) {
    return true;
  }

  // Comprobar si la URL está dentro de algún directorio configurado para cachear
  if (CONFIG.assetDirectories.some(dir => requestPath.startsWith(dir))) {
    return true;
  }

  // Comprobar si la extensión del archivo debe cachearse
  if (CONFIG.fileExtensions.some(ext => requestPath.endsWith(ext))) {
    return true;
  }

  return false;
}

// Evento de instalación: precachear recursos esenciales
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[Service Worker] Pre-cacheando recursos estáticos');
        return cache.addAll(CONFIG.staticAssets);
      })
      .then(() => self.skipWaiting())
  );
});

// Evento de activación: limpiar caches antiguas
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames
            .filter(cacheName => cacheName !== CACHE_NAME)
            .map(cacheName => {
              console.log('[Service Worker] Eliminando cache antigua:', cacheName);
              return caches.delete(cacheName);
            })
        );
      })
      .then(() => self.clients.claim())
  );
});

// Estrategia de caché: cache first con fallback a network para recursos estáticos
// y network first para el resto
self.addEventListener('fetch', event => {
  // Solo manejar solicitudes GET
  if (event.request.method !== 'GET') return;

  // Ignorar solicitudes a otros orígenes
  const url = new URL(event.request.url);
  if (url.origin !== self.location.origin) return;

  // Ignorar solicitudes de Chrome DevTools y otras solicitudes especiales
  if (event.request.cache === 'only-if-cached' && event.request.mode !== 'same-origin') return;

  // Verificar si debemos cachear este recurso
  const shouldCacheRequest = shouldCache(event.request.url);

  if (shouldCacheRequest) {
    // Para recursos que debemos cachear: Cache First, fallback a network
    event.respondWith(
      caches.match(event.request)
        .then(cachedResponse => {
          if (cachedResponse) {
            return cachedResponse;
          }

          // Si no está en caché, intentar recuperarlo de la red
          return fetch(event.request)
            .then(networkResponse => {
              // Verificar si la respuesta es válida
              if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                return networkResponse;
              }

              // Clonar la respuesta para poder almacenarla en caché
              const responseToCache = networkResponse.clone();

              caches.open(CACHE_NAME)
                .then(cache => {
                  console.log('[Service Worker] Cacheando nuevo recurso:', event.request.url);
                  cache.put(event.request, responseToCache);
                });

              return networkResponse;
            });
        })
    );
  } else {
    // Para otros recursos: Network First, fallback a cache
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Si la respuesta es válida, guardarla en caché para futuras visitas
          if (response && response.status === 200) {
            const clonedResponse = response.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, clonedResponse);
            });
          }
          return response;
        })
        .catch(() => {
          // Si falla la red, intentar recuperar de caché
          return caches.match(event.request);
        })
    );
  }
});

// Manejo de mensajes: permite comunicación con el Service Worker
self.addEventListener('message', event => {
  // Si recibimos un mensaje para cachear una carpeta específica
  if (event.data && event.data.action === 'cache-directory') {
    const directoryPath = event.data.directory;
    if (directoryPath && typeof directoryPath === 'string') {
      // Añadir la carpeta a la lista de directorios a cachear
      if (!CONFIG.assetDirectories.includes(directoryPath)) {
        CONFIG.assetDirectories.push(directoryPath);
        console.log('[Service Worker] Añadido directorio al caché:', directoryPath);
      }
    }
  }

  // Si recibimos un mensaje para forzar la actualización
  if (event.data && event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
});
