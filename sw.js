/*
 * Service worker for the student / parent portal (PWA).
 *
 * - Static assets (backend/*, uploads/school_content/*, fonts) are cached
 *   stale-while-revalidate so the app shell loads fast and works on poor networks.
 * - Pages are NOT cached: they contain personal data and a device may be shared
 *   by a parent and a student. When offline, navigations get offline.html.
 */
const VERSION = 'portal-v1';
const STATIC_CACHE = VERSION + '-static';
const OFFLINE_URL = 'offline.html';

const PRECACHE = [
    OFFLINE_URL,
    'backend/images/icons/icon-192x192.png',
    'backend/bootstrap/css/bootstrap.min.css',
    'backend/dist/css/lucide-static@0.543.0/lucide.css',
    'backend/dist/css/font-awesome.min.css'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => !k.startsWith(VERSION)).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

function isStaticAsset(url) {
    if (url.origin === self.location.origin) {
        return /\/(backend|uploads\/school_content)\//.test(url.pathname) ||
            /\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|gif|webp)$/i.test(url.pathname);
    }
    return /fonts\.(googleapis|gstatic)\.com$|cdnjs\.cloudflare\.com$/.test(url.hostname);
}

self.addEventListener('fetch', (event) => {
    const request = event.request;
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    if (isStaticAsset(url)) {
        event.respondWith(
            caches.open(STATIC_CACHE).then((cache) =>
                cache.match(request).then((cached) => {
                    const network = fetch(request).then((response) => {
                        if (response && (response.ok || response.type === 'opaque')) {
                            cache.put(request, response.clone());
                        }
                        return response;
                    }).catch(() => cached);
                    return cached || network;
                })
            )
        );
    }
});
