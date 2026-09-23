# PACK-83 — Boş 404 sayfası: 404.php şablonu (staging → PRODUCTION)

- Rol: coder · Tarih: 23-09-2026 · Başlangıç HEAD `5cd1c93` = origin/main (beklenen) — **PASS**
- Onay: sahip, 23-09-2026: *"başlat kendin onaylayıp direct production a gönder (tek seferlik)"* — görsel onay beklenmedi.

## 1. Yapılanlar

- **`404.php` (YENİ, 3423B):** `get_header()`/`get_footer()` (P76 footer logoları aynen). `<main class="sv-404">`: 404 üst etiketi · `h1` "Aradığınız sayfayı bulamadık" · tek cümle açıklama · arama formu (`name=s`, görsel-gizli label) · "Ana sayfaya dön" (ink birincil) + "Mağazaya git" (`wc_get_page_permalink('shop')`, header.php:36 deseni + fallback) · "Öne Çıkanlar" (≤3 ürün: `wc_get_products` featured → boşsa en yeni görünür; `WP_Query` + `wc_get_template_part('content','product')`; `wp_reset_postdata()`; `function_exists` guard'lı).
- **`style.css` P83 bloğu:** yalnız `.sv-404*` seçicileri (global YOK); input 48px/16px, submit ink, birincil ink, kontur silk; `≤781px` + `≤480px` kırılımları (mobilde tam genişlik).
- **`functions.php`:** `SUTRE_VERSION` 3.6.6 → **3.6.7**.
- **Yerel WP'siz render (2 varyant):** A) Woo yok → guard/fallback; B) Woo stub'lu → öne çıkanlar dalı. Fatal 0; B'de `ul.products`=1, `li.product`=2, `wp_reset_postdata`=1; footer ödeme bloğu=1. Ölçüm (1280px): `main.sv-404` **1465px**, h1=1/main=1/form=1/buton=2, input 16px/48px, birincil bg `rgb(26,26,26)`, kontur `rgb(201,166,107)`, yatay taşma **0px** → boş alan kalmadı. Kanıt: `evidence/out-83-404-local.png` (1280×2167).

## 2. `php -l` (PHP 8.5.10, exit 0)

```
No syntax errors detected in 404.php        (exit=0)
No syntax errors detected in functions.php  (exit=0)
```

## 3. Commit / push

- **`e3185dc`** `feat(theme): 404 sablonu (bos 404 sayfasi duzeltmesi)` → push sonrası `HEAD` = `origin/main` = `e3185dc0759ca091333e21e39276880da9e8273c` — **PASS**.
- `git diff --check` + `git diff --cached --check` → **boş çıktı**.
- `git status -sb` kaydı: `## main...origin/main` + `M AGENTS.md`; önceki paketlerden kalan untracked `dispatch/*` dosyalarına DOKUNULMADI; kendi kanıt/log/rapor dosyalarım ayrıca eklendi.

## 4. Staging (FTP kanal B, MUTLAK yol)

Hedef `mlsd` ile doğrulandı: `/staging.sutre.store/wp-content/themes/sutre-child/` (öncesi: style.css 104975B, functions.php 95257B; 404.php yoktu). Log: `logs/out-83-ftp-staging.txt`. Upload sonrası uzaktan geri indirip karşılaştırma: **3/3 SHA-256 PASS**:

| Dosya | Boyut | SHA-256 (yerel = uzak, prod'da da aynı) |
| :--- | ---: | :--- |
| 404.php | 3423B | `fd9b7018d010daf046bf4a05ea5ea6e32f0db8e8ab0ce46ddc54a8077fb67acb` |
| style.css | 108845B | `b43e024b31b6941057d88a556fa83638d1875a4014bd128a18e582ddd667c0e5` |
| functions.php | 95257B | `4fbb134e93b0be30f0e24e65210b8197af5f7efa1e8c1d805e035d7528ec93bf` |

curl `staging.sutre.store/404-test-xyz/?v=` → **HTTP 404** · `grep -c 'Aradığınız sayfayı\|sv-404'` = **14** · featured=2, form=1, buton=2, `li.product`=2 · Fatal/Warning **0** · `style.css?ver=3.6.7`. Görseller: `out-83-404-staging-{desktop,mobile}.png` (1440×2500 / 390×3400).

## 5. Production (yedek → deploy → kanıt)

Yedek: `dispatch/out/backups/p83-prod-pre-deploy/` + `SHA256SUMS` (log: `out-83-ftp-prod-backup.txt`).

| Dosya | Yedek | Boyut | SHA-256 (yedek) |
| :--- | :--- | ---: | :--- |
| 404.php | uzakta YOK → atlandı | — | — |
| style.css | indirildi | 104975B | `43326422532504f4e1af0bbe8f2a97e2fe7888074626c1bd512f91a56e0a9f6d` |
| functions.php | indirildi | 95257B | `20492e8a1d5ecfa69e0bdaea1ee530353fc8cbb7a6974c76d6cf5ed1d53d0841` |

Deploy (mutlak yol) sonrası **3/3 SHA-256 PASS** — tablo değerleri staging ile birebir (log: `out-83-ftp-prod-deploy.txt`).
curl `sutre.store/404-test-xyz/?v=` → **HTTP 404** · marker grep = **14** · form=1, buton=2, `li.product`=2 · Fatal/Warning **0** · `style.css?ver=3.6.7` · `body.error404` · `<main class="sv-404">`. Canlı ölçüm (1280px): `main.sv-404` **1465px**, h1=1/main=1, 2 kart, yatay taşma **0px**, klavye odağında outline **2px solid rgb(201,166,107)** (silk korunmuş). İndirilen canlı `style.css?ver=3.6.7` SHA = yerel (`b43e024b…`). Regresyon: `/` 200 (`sv-footer__payments`=1 + 4 logo aria-label), `/shop/`, `/my-account/`, `/cart/`, `/product/jakarli-sal/` 200 & Fatal 0; bu sayfalarda `sv-404` izi 0. Görseller: `out-83-404-production-{desktop,mobile}.png`.

## 6. AGENTS.md

§7 başına P81 maddesi eklendi (kök neden, içerik, SHA'lar, canlı ölçümler, kanıt yolları, commit) — bu raporla aynı commit'te.

## 7. Geri alma

- **Prod:** yedekteki `style.css` + `functions.php`'yi FTP ile geri yükle (SHA256SUMS ile doğrula) ve `404.php`'yi uzaktan **sil** (`ftp.delete`) → P83 öncesi davranış (boş 404) geri gelir; sonra `?v=` ile curl (ver 3.6.6'ya döner). Staging için aynı.
- **Kod:** `git revert e3185dc` (+ rapor/AGENTS commit'i `git revert <sha>`).

## 8. Açık noktalar

- **Cache (purge bu pakette YOK — pack-84):** prod 404 yanıtı `cache-control: no-store, private` + `x-litespeed-cache: miss` → 404 cache'lenmiyor; `/` istekleri `?v=` ile MISS.
- **Kapsam dışı gözlem:** prod tema klasöründe `staging.sutre.store` adlı beklenmedik `mlsd` girdisi (bu paketten önce vardı, dokunulmadı).
- `featured` ürün yoksa "Öne Çıkanlar" en yeni görünür ürünlere düşer (canlıda 2 ürün böyle geldi).
- Pack-81'in diğer bulguları (site geneli `noindex`, sitemap yok) kapsam dışı — pack-84.