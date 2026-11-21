// CAT-FR-008 / CAT-FR-009 — Estimasi harga dari server.

const rupiah = new Intl.NumberFormat("id-ID", {
  style: "currency",
  currency: "IDR",
  maximumFractionDigits: 0,
});

export async function requestCatalogCheck(form, intent, signal) {
  const body = new FormData(form);
  body.set("intent", intent);

  const response = await fetch(form.action, {
    method: "POST",
    headers: { Accept: "application/json" },
    body,
    signal,
  });

  if (!response.headers.get("content-type")?.includes("application/json")) {
    throw new Error("Respons server tidak tersedia.");
  }

  return {
    ok: response.ok,
    statusCode: response.status,
    data: await response.json(),
  };
}

export function displayEstimate(panel, estimate) {
  if (!panel) {
    return;
  }

  panel.replaceChildren();
  panel.hidden = !estimate;

  if (!estimate) {
    return;
  }

  const totals = document.createElement("dl");
  totals.className = "grid gap-2";

  for (const [label, amount, classes] of [
    ["Estimasi per pcs", estimate.unit_price, "font-semibold"],
    ["Total estimasi", estimate.total, "text-xl font-bold"],
  ]) {
    const group = document.createElement("div");
    const term = document.createElement("dt");
    const detail = document.createElement("dd");
    term.textContent = label;
    detail.textContent = rupiah.format(amount);
    detail.className = classes;
    group.append(term, detail);
    totals.append(group);
  }

  panel.append(totals);

  const breakdown = document.createElement("ul");
  breakdown.className = "mt-2 space-y-1 text-sm text-zinc-700";

  const tier = estimate.breakdown.quantity_tier;
  const lines = tier
    ? [`Tier: ${tier.min}${tier.max === null ? "+" : `–${tier.max}`} pcs`]
    : [];

  for (const [key, label] of [
    ["option_surcharge", "Tambahan opsi per pcs"],
    ["discount_per_unit", "Diskon per pcs"],
    ["extra_fee", "Biaya tambahan"],
    ["minimum_total_adjustment", "Penyesuaian minimum total"],
  ]) {
    const amount = estimate.breakdown[key];

    if (amount > 0) {
      lines.push(`${label} ${rupiah.format(amount)}`);
    }
  }

  for (const line of lines) {
    const item = document.createElement("li");
    item.textContent = line;
    breakdown.append(item);
  }

  if (breakdown.childElementCount) {
    panel.append(breakdown);
  }
}
