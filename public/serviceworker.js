importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

if (workbox) {
  workbox.setConfig({ debug: false });
  workbox.core.skipWaiting();
  workbox.core.clientsClaim();

  // Precaching static assets
  workbox.precaching.precacheAndRoute([
    { url: '/offline', revision: null },
    { url: '/images/icons/icon-72x72.png', revision: null },
    { url: '/images/icons/icon-96x96.png', revision: null },
    { url: '/images/icons/icon-128x128.png', revision: null },
    { url: '/images/icons/icon-144x144.png', revision: null },
    { url: '/images/icons/icon-152x152.png', revision: null },
    { url: '/images/icons/icon-192x192.png', revision: null },
    { url: '/images/icons/icon-384x384.png', revision: null },
    { url: '/images/icons/icon-512x512.png', revision: null },
  ]);

  // Background Sync plugin untuk upload offline - DISABLED karena menggunakan manual sync
  // const bgSyncPlugin = new workbox.backgroundSync.BackgroundSyncPlugin('uploadQueue', {
  //   maxRetentionTime: 24 * 60 // Retry selama 1 hari
  // });

  // Manual handling untuk POST ke '/bukti-pendukung' - tidak menggunakan background sync
  // karena menggunakan localStorage approach di frontend
  workbox.routing.registerRoute(
    ({ url, request }) =>
      request.method === 'POST' && url.pathname.includes('/bukti-pendukung'),
    new workbox.strategies.NetworkOnly(),
    'POST'
  );

  // Cache-first untuk aset statis
  workbox.routing.registerRoute(
    ({ request }) => ['style', 'script', 'image', 'font'].includes(request.destination),
    new workbox.strategies.CacheFirst({
      cacheName: 'static-resources',
      plugins: [
        new workbox.expiration.ExpirationPlugin({
          maxEntries: 50,
          maxAgeSeconds: 30 * 24 * 60 * 60,
        }),
      ],
    })
  );

  // Offline caching khusus untuk halaman upload (bukti-pendukung)
  workbox.routing.registerRoute(
    ({ request, url }) => request.mode === 'navigate' && url.pathname.startsWith('/bukti-pendukung'),
    new workbox.strategies.NetworkFirst({
      cacheName: 'upload-pages',
      networkTimeoutSeconds: 3,
      plugins: [
        new workbox.expiration.ExpirationPlugin({
          maxEntries: 10,
          maxAgeSeconds: 24 * 60 * 60, // cache satu hari
        }),
        new workbox.cacheableResponse.CacheableResponsePlugin({ statuses: [0, 200] }),
        // Saat offline, kembalikan halaman upload yang sudah di-cache, atau halaman offline
        {
          handlerDidError: async ({ event }) => {
            const cache = await caches.open('upload-pages');
            const cachedResponse = await cache.match(event.request);
            return cachedResponse || caches.match('/offline');
          }
        }
      ],
    })
  );

  // Fallback ke halaman offline untuk navigasi lain
  workbox.routing.registerRoute(
    ({ request, url }) => request.mode === 'navigate' && !url.pathname.startsWith('/bukti-pendukung'),
    new workbox.strategies.NetworkFirst({
      cacheName: 'pages',
      networkTimeoutSeconds: 3,
      plugins: [
        new workbox.expiration.ExpirationPlugin({ maxEntries: 50, maxAgeSeconds: 7 * 24 * 60 * 60 }),
        new workbox.cacheableResponse.CacheableResponsePlugin({ statuses: [0, 200] }),
        { handlerDidError: async () => caches.match('/offline') }
      ],
    })
  );

} else {
  console.log('Workbox gagal dimuat');
}