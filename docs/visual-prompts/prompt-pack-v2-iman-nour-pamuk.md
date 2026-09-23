# SUTRE — GÖRSEL ÜRETİM PROMPT PAKETİ v2 (İman Nour PAMUK Şal — 6 renk × 3 görsel)

> Kanal: **Higgsfield API** · model `xai/grok-imagine-image-2.0` (image_edit + text2image)
> İstemci: `~/.hermes/tools/higgsfield-venv/bin/python` · SDK `higgsfield_client`
> Referans: **ZORUNLU** (`image_urls`) — gerçek pamuk fotoğrafları, staging `refs/` üzerinden public URL'e alınır.
> Kaynak şablon: `prompt-pack-v1-mavi-jakarlı.md` (v1) + `hijab-stil-rehberi.md` (stil sabiti)

## JAKARLI'DAN TEK FARK: MAT KUMAŞ
- v1 cümleleri: "smooth satin sheen", "satin sheen catches soft studio light", "jacquard weave"
- v2 karşılıkları: **"soft matte cotton fabric, dry natural texture with a subtle woven grain, NO satin or silk sheen, light absorbed softly rather than reflected"**
- Parlaklık ima eden TÜM ifadeler çıkarılır (satın alınan ürün pamuk; yanıltıcı görsel yasak).

## ORTAK ÜRÜN TANIMI (her promptta geçer — kumaş kısmı mat)
"a premium rectangular cotton scarf measuring 190 cm × 70 cm, soft matte woven cotton fabric with a fine natural grain, gently hemmed edges without fringe, light diffused softly across the surface with no satin or silk sheen"

## HİJAB STİL SABİTİ (insanlı görsellerde — v1 rehberiyle aynı)
"styled as an elegant modest headscarf: placed high on the crown, fully covering all hair with NO hair visible; KNOTLESS tying — no tight knot under the chin, the fabric wrapped once loosely around the neck creating a soft draped cowl-collar; one long end drapes asymmetrically over one shoulder falling fluidly toward the waist, the other side tucks shorter behind; soft vertical folds over the chest; matte cotton folds fall softly with matte edge shadows (not shiny)"

## RENKLER (24-09-2026 — sahibin 5 referans fotoğrafından AI ölçümüyle belirlendi)
| Klasör | Prompt rengi | Referans dosyası | Ölçülen ton |
|---|---|---|---|
| Karamel | rich caramel | `ref-karamel.jpeg` | `#815F49` |
| Gül Kurusu | dusty rose-brown (gul kurusu) | `ref-gul-kurusu.jpeg` | `#A66B4F`–`#B88370` |
| Gri Bej | soft greige (grey-beige) | `ref-gri-bej.jpeg` | `#BEBDB7` |
| Lacivert | deep navy blue | `ref-lacivert.jpeg` | `#26344B` |
| Vişne Çürüğü | deep cherry red (visne curugu) | `ref-visne-curugu.jpeg` | `#AC213C` |

> NOT (24-09-2026): Eski plan listesi (Açık Bej/Bej/Koyu Bej/Siyah/Sütlü Kahve) GERÇEK renklerle uyuşmuyordu; ölçüm sonucu bu 5 renk geçerlidir. Eski boş klasörler `_arsiv-bos-klasorler/` altına alındı (silinmedi). Eşleşme kaydı: `_renk-eslesmesi.json`. Tüm referanslar mat pamuk DEĞİL (fotoğraflar parlak jakar görünümlü) — doku referansı olarak kullanılır, kumaş karakteri prompt'taki "matte cotton, no sheen" ifadesiyle belirlenir.

## GÖRSELLER (renk başına 3 — arşiv adlandırması)
Tür adları ve dosya adları: `<renk-slug>-pamuk-urun-drape.png`, `<renk-slug>-pamuk-makro-doku.png`, `<renk-slug>-pamuk-model-portre.png`
(renk-slug: karamel, gul-kurusu, gri-bej, lacivert, visne-curugu)

### GÖRSEL 1 — ÜRÜN DRAPE (API: `resolution=2k`, `aspect_ratio=2:3`)
Professional e-commerce product photography of a premium rectangular cotton scarf measuring 190 cm × 70 cm, in {COLOR}, soft matte woven cotton fabric with a fine natural grain, gently hemmed edges without fringe. The scarf is elegantly folded and draped in a soft flowing arrangement on a clean warm-white seamless studio background. Soft diffused studio lighting from top-left, gentle natural shadow beneath the fabric. Matte surface absorbs light — no shine, no satin sheen. Premium editorial fashion catalog style, ultra sharp fabric texture detail, vertical composition. No props, no text, no watermark, no person.

### GÖRSEL 2 — MAKRO DOKU (API: `resolution=2k`, `aspect_ratio=1:1`)
Extreme macro photography of {COLOR} matte cotton scarf fabric, 190 cm × 70 cm woven cotton. Visible fine woven threads and natural cotton grain, dry matte surface with soft light absorption — absolutely no gloss, no satin sheen. Subtle tonal variation across the weave, honest textile detail. Premium textile catalog quality, square composition. No text, no person.

### GÖRSEL 3 — MODEL PORTRE (API: `resolution=2k`, `aspect_ratio=2:3`)
High-end studio fashion photography of an elegant young woman wearing a {COLOR} matte cotton scarf as a modest headscarf. The scarf, 190 cm × 70 cm woven cotton: placed high on the crown, fully covering all hair with NO hair visible; KNOTLESS tying — no tight knot under the chin, wrapped once loosely around the neck creating a soft draped cowl-collar; one long end drapes asymmetrically over one shoulder falling fluidly toward the waist, the other tucks shorter behind; soft vertical folds over the chest with matte edge shadows. Warm beige seamless studio backdrop, soft professional softbox lighting with subtle golden rim light, premium editorial luxury campaign style. Natural elegant makeup, simple cream-colored modest outfit so the scarf stands out. Shot on 85mm lens, shallow depth of field, vertical composition. No text, no watermark.

---

## ÜRETİM AKIŞI (Higgsfield API)
1. Referans pamuk fotoğrafları `~/Desktop/SUTRE/Ürün Fotoğrafları/İman Nour/_referans/`'a konur (sahip).
2. Referanslar FTP ile staging `refs/` altına atılır → public URL (`https://staging.sutre.store/refs/<dosya>`) — `image_urls` için.
3. Her renk × görsel için `prompt` + `image_urls` + `resolution=2k` + `aspect_ratio` + `quality=medium` ile `higgsfield_client.subscribe(...)` çağrılır.
4. Dönen görseller indirilir → `~/Desktop/SUTRE/Ürün Fotoğrafları/İman Nour/<Renk>/<renk-slug>-pamuk-<tür>.png`.
5. Sahibin görsel onayı (§0.6: görsel değişiklik sahibe gösterilir) → onay sonrası siteye yerleştirme ayrı tur.

## KISITLAR
- Referanssız üretim YASAK. Saç ASLA görünmez. Paketleme/açık alan fotoğrafı YOK (§4).
- Tek renk için üretilen 3 görsel birbirinden bağımsız üretilir (aynı renk tutarlılığı referans görsele bağlı).
- Kimlik bilgisi (HF_KEY) repoya/log'a/rapora YAZILMAZ.