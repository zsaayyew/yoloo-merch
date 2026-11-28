// CAT-FR-003 / CAT-FR-005 / CAT-FR-006 / CAT-FR-011 — Interaksi katalog.

import { displayEstimate, requestCatalogCheck } from "./price-calculator.js";

for (const form of document.querySelectorAll("[data-catalog-configurator]")) {
  const fields = [...form.querySelectorAll("[data-catalog-input]")];
  const quantity = form.elements.namedItem("quantity");
  const cart = form.querySelector("[data-catalog-cart]");
  const quotation = form.querySelector("[data-catalog-quotation]");
  const panel = form.querySelector("[data-catalog-estimate]");
  const status = form.querySelector("[data-catalog-status]");
  const selection = form.querySelector("[data-catalog-selection]");
  const retry = form.querySelector("[data-catalog-retry]");
  const serverErrors = form.querySelector("[data-catalog-server-errors]");
  const calculator = form.dataset.priceModel === "calculator";
  let activeRequest;
  let timer;

  function updateField(input, message = "") {
    input.setAttribute("aria-invalid", String(Boolean(message)));
    form.querySelector(
      `[data-catalog-field-error="${input.dataset.catalogInput}"]`,
    ).textContent = message;
  }

  function updateButtons(cartEnabled, quotationEnabled) {
    if (cart) {
      cart.disabled = !cartEnabled;
    }

    if (quotation) {
      quotation.disabled = !quotationEnabled;
    }
  }

  async function check(controller) {
    const { signal } = controller;
    status.textContent = calculator
      ? "Menghitung estimasi…"
      : "Memeriksa konfigurasi…";

    try {
      const intent = calculator || cart ? "estimate" : "check";
      const { ok, statusCode, data } = await requestCatalogCheck(
        form,
        intent,
        signal,
      );

      if (signal.aborted) {
        return;
      }

      if (ok) {
        if (calculator) {
          displayEstimate(panel, data);
        }

        updateButtons(true, true);
        status.textContent = calculator ? "" : "Konfigurasi siap dilanjutkan.";
        return;
      }

      retry.hidden = statusCode < 500;
      const errors = data.errors ?? {};

      for (const field of fields) {
        updateField(field, errors[field.dataset.catalogInput]?.[0]);
      }

      const canRequestQuotation =
        Object.keys(errors).length === 1 && Boolean(errors.pricing);
      updateButtons(false, canRequestQuotation);
      status.textContent =
        errors.pricing?.[0] ??
        errors.option_value_ids?.[0] ??
        data.message ??
        "Periksa kembali opsi dan jumlah pesanan.";
    } catch {
      if (signal.aborted) {
        return;
      }

      status.textContent = "Data gagal dimuat.";
      retry.hidden = false;
    } finally {
      if (!signal.aborted) {
        form.removeAttribute("aria-busy");
      }
    }
  }

  function scheduleCheck(immediate = false) {
    serverErrors?.remove();
    activeRequest?.abort();
    clearTimeout(timer);
    form.removeAttribute("aria-busy");
    displayEstimate(panel, null);
    updateButtons(false, false);
    retry.hidden = true;

    const choices = fields
      .filter((field) => field instanceof HTMLSelectElement && field.value)
      .map((field) => field.selectedOptions[0].textContent.trim());

    if (quantity.validity.valid) {
      choices.push(`${quantity.value} pcs`);
    }

    selection.textContent = choices.join(" · ");
    selection.hidden = choices.length === 0;

    for (const field of fields) {
      let message = "";

      if (!field.validity.valid) {
        if (field === quantity) {
          message = quantity.validity.rangeUnderflow
            ? `Jumlah minimal ${quantity.min} pcs.`
            : "Isi jumlah dengan bilangan bulat positif.";
        } else {
          message = "Pilih opsi ini.";
        }
      }

      updateField(field, message);
    }

    if (fields.some((field) => !field.validity.valid)) {
      status.textContent = "Lengkapi opsi dan jumlah pesanan.";
      return;
    }

    form.setAttribute("aria-busy", "true");
    activeRequest = new AbortController();
    const controller = activeRequest;

    if (immediate) {
      void check(controller);
    } else {
      timer = setTimeout(() => check(controller), 300);
    }
  }

  retry.addEventListener("click", () => {
    scheduleCheck(true);
  });

  form.addEventListener("input", (event) => {
    if (fields.includes(event.target)) {
      scheduleCheck();
    }
  });

  form.addEventListener("submit", (event) => {
    if (
      event.submitter?.matches("[data-catalog-cart], [data-catalog-quotation]")
    ) {
      return;
    }

    event.preventDefault();
    scheduleCheck(true);
  });

  if (serverErrors) {
    updateButtons(false, serverErrors.dataset.pricingOnly === "true");
  } else {
    scheduleCheck();
  }
}

for (const form of document.querySelectorAll("[data-catalog-search]")) {
  const status = form.querySelector("[data-catalog-search-status]");

  form.addEventListener("submit", () => {
    status.hidden = false;
  });

  window.addEventListener("pageshow", () => {
    status.hidden = true;
  });
}

for (const fallback of document.querySelectorAll("[data-catalog-no-photo]")) {
  const photo = fallback.previousElementSibling;

  const showFallback = () => {
    photo.hidden = true;
    fallback.hidden = false;
    fallback.closest("[data-catalog-photo]")?.removeAttribute("href");
  };

  if (photo.complete && photo.naturalWidth === 0) {
    showFallback();
  } else {
    photo.addEventListener("error", showFallback, { once: true });
  }
}

for (const gallery of document.querySelectorAll("[data-catalog-gallery]")) {
  const dialog = gallery.querySelector("[data-catalog-lightbox]");
  const enlarged = gallery.querySelector("[data-catalog-lightbox-image]");

  gallery.addEventListener("click", (event) => {
    const link = event.target.closest("[data-catalog-photo][href]");

    if (
      !link ||
      typeof dialog.showModal !== "function" ||
      event.ctrlKey ||
      event.metaKey ||
      event.shiftKey ||
      event.altKey
    ) {
      return;
    }

    event.preventDefault();
    enlarged.src = link.href;
    enlarged.alt = link.querySelector("img").alt;
    dialog.showModal();
  });
}
