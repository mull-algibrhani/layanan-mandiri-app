// sw.js - PWA Service Worker dengan Workbox

importScripts(
    'https://storage.googleapis.com/workbox-cdn/releases/7.3.0/workbox-sw.js'
);

// ========================
// Widget event handlers
// ========================

// When widget is installed/pinned, push initial state.
self.addEventListener('widgetinstall', (event) => {
    event.waitUntil(updateWidget(event));
});

// When widget is shown, update content to ensure it is up-to-date.
self.addEventListener('widgetresume', (event) => {
    event.waitUntil(updateWidget(event));
});

// When the user clicks an element with an associated Action.Execute,
// handle according to the 'verb' in event.action.
self.addEventListener('widgetclick', (event) => {
    if (event.action == "updateName") {
        event.waitUntil(updateName(event));
    }
});

// When the widget is uninstalled/unpinned, clean up any unnecessary
// periodic sync or widget-related state.
self.addEventListener('widgetuninstall', (event) => { });

const updateWidget = async (event) => {
    const widgetDefinition = event.widget.definition;
    const payload = {
        template: JSON.stringify(await (await fetch(widgetDefinition.msAcTemplate)).json()),
        data: JSON.stringify(await (await fetch(widgetDefinition.data)).json()),
    };
    await self.widgets.updateByInstanceId(event.instanceId, payload);
}

const updateName = async (event) => {
    const name = event.data.json().name;
    const widgetDefinition = event.widget.definition;
    const payload = {
        template: JSON.stringify(await (await fetch(widgetDefinition.msAcTemplate)).json()),
        data: JSON.stringify({ name }),
    };
    await self.widgets.updateByInstanceId(event.instanceId, payload);
}

// ========================
// Precache (Workbox)
// ========================
workbox.precaching.precacheAndRoute([
    ...(self.__WB_MANIFEST || []),
    { url: '/assets/offline.html', revision: '1' } // halaman offline
]);

// ========================
// Fallback offline untuk navigasi (HTML)
// ========================
self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match('/assets/offline.html'))
        );
    }
});
