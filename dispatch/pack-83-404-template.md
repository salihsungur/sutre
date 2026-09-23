# PACK-83 — Boş 404 sayfası: 404.php şablonu (staging → PRODUCTION, tek seferlik onay)

## 0. DURUM / ONAY
- Sahip onayı (23-09-2026, birebir): *"başlat kendin onaylayıp direct production a gönder (tek seferlik)"* → **staging'e koy, kendi doğrulamanı yap, sonra doğrudan production'a deploy et.** Sahip görsel onayı beklenmeyecek (bu paket için verilmiş özel izin).
- Repo: `/Users/salihsungur/dev/sutre` — beklenen HEAD = origin/main = `5cd1c935c36d06dc970e867f9d735f2ddf074dcd` (başlarken `git rev-parse` ile teyit et; farklıysa raporla, devam et).
- Kök neden (tester pack-81 kanıtı): temada `404.php` YOK; `index.php` `have_posts()` false iken hiçbir şey basmıyor → `<div id="sutre-content"></div>` **tamamen boş**. Canlı doğrulama: `https://sutre.store/404-test-sayfasi-xyz/` → HTTP 404 + boş içerik.
- İlk tool çağrıların: (1) `AGENTS.md` TAMAMI, (2) `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMI, (3) `dispatch/out/out-81-magaza-404-audit.md`, (4) `theme/sutre-child-v2/{header.php,footer.php,index.php,style.css}` — mevcut tasarım dili.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**

## 1. GÖREV
`theme/sutre-child-v2/404.php` şablonunu yaz × mevcut tema diliyle uyumlu biçimde:

- `get_header()` / `get_footer()` kullan (footer'da P76 ödeme logoları dahil her şey aynen gelir).
- İçerik (Türkçe, jargon YOK — "istasyon" gibi ifadeler kullanma):
  - Kısa başlık: ör. **"Aradığınız sayfayı bulamadık"**
  - Tek cümle açıklama (ör. "Bağlantı taşınmış veya adres yanlış yazılmış olabilir.")
  - **Arama kutusu** (WordPress arama formu; mevcut header'daki arama bileşeninin stil diliyle aynı)
  - İki eylem: **"Ana sayfaya dön"** (birincil buton) + **"Mağazaya git"** (`wc_get_page_permalink('shop')` — header.php:36/60'taki desenle aynı)
  - Opsiyonel: en çok satan/öne çıkan 3 ürün ya da "Tüm koleksiyon" bağlantısı — yalnızca mevcut WooCommerce sorgularıyla ve performansı bozmadan (sorgu ekliyorsan `wp_reset_postdata()` şart).
- **Scoped CSS:** yalnız `.sv-404*` sınıfları (`style.css` içine, mevcut blokların yanına; global selector YASAK). Mobil (≤480) ve ≤781 davranışını da tanımla.
- **UI kuralı:** header/footer ve genel yerleşim DEĞİŞMEZ; yalnız boş içerik alanı doldurulur (§0.6 konum sabit kuralı).
- Erişilebilirlik: `main`/`h1` hiyerarşisi doğru, klavye odak halkaları korunur, buton/link metinleri anlamlı.
- `SUTRE_VERSION` → '3.6.6' → **'3.6.7'** (CSS değişti).

## 2. ZORUNLU KANIT / GATE SIRASI (sırayla, her adımı rapora yaz)
1. `php -l theme/sutre-child-v2/404.php` + `php -l functions.php` → 0 hata.
2. Yerel doğrulama: şablonu statik olarak render et (veya bir WP'siz parça testi) → boş alan kalmadığının kanıtı + `dispatch/out/evidence/out-83-404-local.png`.
3. Commit + push: `feat(theme): 404 sablonu (bos 404 sayfasi duzeltmesi)`. Yalnız `404.php`, `style.css`, `functions.php` (+ rapor/kanıt).
4. **Staging deploy (FTP, MUTLAK yol):** `~/Desktop/ftpinfo.txt` (değerleri rapora YAZMA) → `/staging.sutre.store/wp-content/themes/sutre-child/` üç dosya. SHA-256 yerel↔uzak **3/3** doğrula.
5. **Staging canlı kanıt:** `curl -skL "https://staging.sutre.store/404-test-xyz/?v=$(date +%s)" -w '%{http_code}'` → **404** olmalı ve gövdede başlık + arama + iki buton bulunmalı (`grep -c 'Aradığınız sayfayı\|sv-404'`). Ekran görüntüsü `out-83-404-staging-desktop.png` + mobil.
6. **PRODUCTION deploy (mutlak yol, önce YEDEK):** prod tema klasörüne (`/sutre.store/wp-content/themes/sutre-child/`) yüklemeden önce mevcut `404.php`(yoksa atla) + `style.css` + `functions.php` dosyalarını `dispatch/out/backups/p83-prod-pre-deploy/` altına indir + `SHA256SUMS` yaz. Sonra yükle + SHA-256 **3/3** doğrula.
7. **Production canlı kanıt:** `curl -skL "https://sutre.store/404-test-xyz/?v=$(date +%s)"` → HTTP **404** + gövdede başlık/arama/butonlar; `?ver=3.6.7` CSS; Fatal/Warning **0**; ana sayfa `/?v=` hâlâ footer logolarını gösteriyor (regresyon yok). Ekran görüntüleri: `out-83-404-production-desktop.png` + `...-mobile.png`.
8. **AGENTS.md §7:** yeni madde ekle: `- [x] **P81 — Boş 404 sayfası düzeltmesi — ZATEN YAPILDI (23-09-2026):** tema'da 404.php yoktu → ziyaretçi boş alan görüyordu; 404.php eklendi (başlık + arama + "Ana sayfaya dön"/"Mağazaya git"), staging+production doğrulandı, kanıt: out-83*.md` + commit/push.
9. **Cache notu:** production'da `/404` sayfası cache'lenmez (404'ler LiteSpeed'de genelde cache dışıdır) ama `/` bayat olabilir — bu paket cache purge YAPMAZ (pack-84 onu yapıyor). Raporda durumu yaz.

## 3. ROL SINIRI
- Yalnız `theme/sutre-child-v2/{404.php,style.css,functions.php}` + `AGENTS.md` + rapor/kanıt/yedek.
- DB'ye yazma YOK, eklenti kurma YOK, başka prod dosyası YOK.
- Provider/model override YASAK.

## 4. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-83-404-template.md` (≤700 kelime). Bölümler: yapılanlar · php -l · commit/push SHA + HEAD==origin · staging (SHA + curl + görsel) · production (yedek tablosu + SHA 3/3 + curl + görsel) · AGENTS güncellemesi · **geri alma** (yedekten geri yükleme + `git revert <sha>`) · açık noktalar.
- Yargı cümlesi ("güzel görünüyor") YASAK; ölçüm ve ham çıktı yaz. Yapılamayanı `YAPILAMADI: <neden>` yaz.
- `git status -sb` + `git diff --check` çıktısı rapora.