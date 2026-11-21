# YOLOO Merch — Brand & UI Reference Pack

This folder contains reference materials supplied by the project owner. Use these to guide the visual treatment of PRD-01 Company Profile (CP). They are not a replacement for the Master PRD or AI_CONTEXT.

## Official brand assets
- `public/images/brand/yoloo-logo-full.png` — full horizontal logo. Use in header/footer where it fits.
- `public/images/brand/yoloo-symbol.png` — standalone brand symbol. Use only where a mark/icon is appropriate.
- `public/images/brand/yoloo-mascot.png` — mascot illustration. Use selectively (hero or a few supporting moments); do not repeat in every section/card.

All three logo/mascot PNGs have an alpha channel. Preserve proportions; do not recolor, stretch, crop important parts, or place them on backgrounds that reduce visibility.

## Reference UI screenshots
- `homepage-reference.png` — layout/visual inspiration for homepage only.
- `gallery-reference.png` — card/grid/gallery visual inspiration.
- `gallery-detail-reference.png` — image and content-card visual inspiration only. Do not create a new gallery-detail route unless the Master PRD explicitly requires it; CP gallery uses grid + lightbox.
- `catalog-estimate-legacy-reference.png` — legacy catalog price/time estimator visual reference only. This is CAT-owned functionality, not CP. Do not implement estimator, pricing logic, quantity calculation, product option selection, or WhatsApp estimate form in CP.

## Product catalogue
- `product-catalog-reference.pdf` — source for the types/categories of merchandise and the general visual identity. It is not a source for inventing live inventory, product records, prices, ratings, customer claims, or published gallery entries. CAT owns product descriptions, configurations, prices, and calculators.

## Approximate visual palette sampled from supplied brand artwork/screenshots
- Brand turquoise: `#58C0C8`
- Brand orange: `#E87830`
- Brand yellow: `#E8C050`
- Ink/near-black: `#181818`
- Off-white canvas seen in references: `#F8F8F8`
- White surface: `#FFFFFF`

These are sampled approximations for starting tokens, not a formal brand guideline. Ensure accessible text contrast. Bright turquoise/orange/yellow surfaces should generally use dark ink text; do not assume white text is readable on bright brand colors. Use a darker teal only when white text is required and verify contrast.

## Design direction
Create a clean, contemporary, friendly merchandise-studio identity: ample breathing room, strong headings, clear hierarchy, image-led product/gallery areas, restrained rounded cards, subtle shadows, and small playful accents. Use turquoise as the main action/accent, orange for warmth and selected highlights, and yellow sparingly. Keep the whole page from becoming a saturated orange gradient. Use the mascot sparingly so it supports rather than competes with content.

## Authority and scope rules
1. The Master PRD (`docs/PRD WEBSITE YOLOO MERCH - FINAL.md`) controls requirements and business behavior.
2. `docs/AI_CONTEXT_YOLOO_MERCH.md` controls technical ownership, allowed files, and collaboration boundaries.
3. This pack and old prototypes are visual/contextual references only. If they conflict with either authority document, follow the documents.
4. CP must not implement CAT pricing/configuration/catalog pages, ORD cart/checkout/order tracking/custom-order submission, ADM content management, or FIN functions.
5. Do not publish demo/sample data from prototypes as real business facts. Only show data from approved published records or verified official configuration.
6. Do not invent vision, mission, values, testimonials, collaboration partners, contact details, location, business hours, or production promises.

## Note on the full prototype PDF
The full prototype board supplied separately is very large (~98 MB) and includes screens for catalog, price estimation, cart/checkout, order status and other domains. It is intentionally not bundled here to avoid repository bloat. The individual CP-oriented screenshots and catalogue are included. The full board may be consulted locally if needed, but its old flows must not override the current Master PRD or ownership boundaries.
