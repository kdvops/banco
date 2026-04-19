const CORE_CACHE = "organizador-core-v1";
const ASSET_CACHE = "organizador-assets-v1";
const IMAGE_CACHE = "organizador-images-v1";
const OFFLINE_URL = "./offline.html";
const CORE_ASSETS = [
  OFFLINE_URL,
  "./manifest.json",
  "./favicon.svg",
  "./pwa/icon-192.png",
  "./pwa/icon-512.png",
  "./pwa/apple-touch-icon.png"
];
const MAX_IMAGE_ENTRIES = 80;

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CORE_CACHE).then((cache) =>
      Promise.all(
        CORE_ASSETS.map((asset) => cache.add(new Request(asset, { cache: "reload" })))
      )
    ).then(() => self.skipWaiting())
  );
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => ![CORE_CACHE, ASSET_CACHE, IMAGE_CACHE].includes(key))
          .map((key) => caches.delete(key))
      )
    ).then(() => self.clients.claim())
  );
});

self.addEventListener("message", (event) => {
  if (event.data?.type === "SKIP_WAITING") {
    self.skipWaiting();
  }
});

self.addEventListener("fetch", (event) => {
  const { request } = event;

  if (request.method !== "GET") {
    return;
  }

  const url = new URL(request.url);

  if (request.mode === "navigate") {
    event.respondWith(handleNavigationRequest(request));
    return;
  }

  if (request.destination === "image") {
    event.respondWith(handleImageRequest(request));
    return;
  }

  if (url.origin === self.location.origin && ["style", "script", "font"].includes(request.destination)) {
    event.respondWith(handleAssetRequest(request));
  }
});

async function handleNavigationRequest(request) {
  try {
    return await fetch(request);
  } catch (error) {
    const cachedOfflinePage = await caches.match(OFFLINE_URL);
    return cachedOfflinePage || Response.error();
  }
}

async function handleAssetRequest(request) {
  const cache = await caches.open(ASSET_CACHE);
  const cachedResponse = await cache.match(request);
  const networkResponsePromise = fetch(request)
    .then((response) => {
      if (response.ok) {
        cache.put(request, response.clone());
      }

      return response;
    })
    .catch(() => null);

  if (cachedResponse) {
    return cachedResponse;
  }

  const networkResponse = await networkResponsePromise;
  return networkResponse || Response.error();
}

async function handleImageRequest(request) {
  const cache = await caches.open(IMAGE_CACHE);
  const cachedResponse = await cache.match(request);

  if (cachedResponse) {
    return cachedResponse;
  }

  try {
    const networkResponse = await fetch(request);

    if (networkResponse.ok || networkResponse.type === "opaque") {
      await cache.put(request, networkResponse.clone());
      await trimImageCache(cache);
    }

    return networkResponse;
  } catch (error) {
    return cachedResponse || Response.error();
  }
}

async function trimImageCache(cache) {
  const requests = await cache.keys();

  if (requests.length <= MAX_IMAGE_ENTRIES) {
    return;
  }

  const overflow = requests.length - MAX_IMAGE_ENTRIES;
  await Promise.all(requests.slice(0, overflow).map((request) => cache.delete(request)));
}
