const staticCacheName = "pwa-v" + new Date().getTime();
const filesToCache = [
    '/resources/css/app.css',
    '/resources/css/modalSearch.css',
    '/resources/js/owCarouselConfig.js',
    '/resources/css/fivestars.css',
    '/resources/js/ajaxSearch.js',
    '/resources/js/popper.min.js',
    '/resources/js/pwa-script.js',
    '/pwa-sw.js', 
    '/resources/owlcarousel/assets/owl.carousel.min.css',
    '/resources/owlcarousel/assets/owl.theme.default.min.css',
    '/resources/js/jquery-3.6.3.min.js',
    '/resources/bootstrap-5.2.3-dist/css/bootstrap.min.css',
    '/resources/bootstrap-5.2.3-dist/js/bootstrap.min.js',
    'offline.html',
    'offline.png'
];

// Cache on install
self.addEventListener("install", event => {
    //console.log(staticCacheName);
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});