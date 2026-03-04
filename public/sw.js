const CACHE_NAME = 'fhts-v1';

// Assets to cache on install
const STATIC_ASSETS = [
    '/',
    '/projects',
    '/offline',
];

// ── Install: cache static shell ──────────────────────────────────────────────
self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS).catch(() => { });
        })
    );
});

// ── Activate: clean old caches ───────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

// ── Fetch: Network-first, fallback to cache ───────────────────────────────────
self.addEventListener('fetch', (event) => {
    // Skip non-GET and non-HTTP requests
    if (event.request.method !== 'GET') return;
    if (!event.request.url.startsWith('http')) return;

    // Skip Livewire / API calls – always fresh
    if (
        event.request.url.includes('/livewire') ||
        event.request.url.includes('/api/') ||
        event.request.url.includes('?livewire') ||
        event.request.url.includes('logout')
    ) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Cache successful HTML/JS/CSS responses
                if (response && response.status === 200) {
                    const clone = response.clone();
                    const url = event.request.url;
                    if (
                        url.includes('/build/') ||
                        url.includes('/storage/')
                    ) {
                        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                    }
                }
                return response;
            })
            .catch(() => {
                // Offline fallback: try cache, else nothing
                return caches.match(event.request).then((cached) => cached || new Response('Offline', { status: 503 }));
            })
    );
});
