(function () {
  if (!("serviceWorker" in navigator) || !window.isSecureContext) {
    return;
  }

  let isRefreshing = false;

  navigator.serviceWorker.addEventListener("controllerchange", () => {
    if (isRefreshing) {
      return;
    }

    isRefreshing = true;
    window.location.reload();
  });

  window.addEventListener("load", async () => {
    try {
      const registration = await navigator.serviceWorker.register("service-worker.js", {
        updateViaCache: "none"
      });

      const activateWaitingWorker = () => {
        if (registration.waiting) {
          registration.waiting.postMessage({ type: "SKIP_WAITING" });
        }
      };

      registration.addEventListener("updatefound", () => {
        const installingWorker = registration.installing;
        if (!installingWorker) {
          return;
        }

        installingWorker.addEventListener("statechange", () => {
          if (installingWorker.state === "installed" && navigator.serviceWorker.controller) {
            activateWaitingWorker();
          }
        });
      });

      activateWaitingWorker();
      await registration.update();
    } catch (error) {
      console.warn("No se pudo registrar la PWA.", error);
    }
  });
})();
