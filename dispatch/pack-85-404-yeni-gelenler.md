# PACK-85 — 404 sayfası ürün bloğu düzeltmesi: "Yeni gelenler" (dürüst + deterministik)

## 0. KARAR (orkestratör, 23-09-2026)
Sahip: *"kendin ne yapmak istiyosan onu yap hangisini uygun görürsen."* → Karar: **blok kalır ama dürüst ve deterministik olur.**
- Şu anki hal: "Öne Çıkanlar" başlığı + `featured` sorgusu; featured işaretli ürün yoksa **en yeni ürünlere düşüyor** → etiket yanlış beyan (sahip geri bildirimi, pack-83 raporu).
- Yeni hal: **"Yeni gelenler"** başlığı + `orderby="date"` (deterministik, her zaman doğru) + **3 ürün** + yanında "Tüm koleksiyonu gör" bağlantısı (`/shop/`).
- `featured` mantığı ve yedek/fallback zinciri **tamamen kaldırılır** (yanlış etiket üretme ihtimali sıfırlanır).
- **Ürün yoksa blok hiç basılmaz** (boş başlık/kırık grid olmaz).

## 1. ÖN KOŞUL / İLK ADIMLAR
- Repo: `/Users/salihsungur/dev/sutre` — beklenen HEAD = origin/main = `400cb8ed5e494ca82e90756221ae0120033b432b` (başlarken teyit et).
- `git show e3185dc -- theme/sutre-child-v2/404.php` → mevcut şablonu gör.
- İlk tool çağrıların: (1) `AGENTS.md` TAMAMI, (2) `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMI, (3) `dispatch/out/out-83-404-template.md`.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**
- **Kabuk kuralı:** iç içe `$(...)` + tırnaklı operand içeren tek satır komut YAZMA (güvenlik katmanı bunları koşulsuz bloklar). Doğrulamalar için `bash ~/.hermes/tools/check-live.sh <url>` kullan (HTTP + cache başlığı + footer markörleri + robots meta tek komutta, iç içe ikame yok).

## 2. GÖREV
1. `theme/sutre-child-v2/404.php` içindeki ürün bloğunu yukarıdaki karara göre değiştir (başlık metni, sorgu, ürün sayısı, fallback kaldırma, boş-durumda hiç basmama).
2. Metin dili: jargon yok, doğru beyan. Başlık **"Yeni gelenler"**. Bağlantı metni **"Tüm koleksiyonu gör"**.
3. Scoped CSS korunur (`.sv-404*`); konum/yerleşim değişmez (yalnız etiket + sorgu + boş durum davranışı). CSS değişirse `SUTRE_VERSION` **3.6.7 → 3.6.8**.
4. Commit: `fix(theme): 404 'Yeni gelenler' blogu (deterministik sorgu, featured fallback kaldirildi)`.

## 3. KANIT / GATE SIRASI
1. `php -l theme/sutre-child-v2/404.php` → 0 hata.
2. Yerel render → `dispatch/out/evidence/out-85-404-local.png` (blok başlığını gör).
3. Commit + push; `git rev-parse HEAD origin/main` eşitliği.
4. **Staging:** FTP mutlak yolla deploy (`/staging.sutre.store/wp-content/themes/sutre-child/`) + SHA-256 doğrula.
5. **Staging canlı:** `bash ~/.hermes/tools/check-live.sh https://staging.sutre.store/404-test-xyz/` çıktısını rapora yapıştır + «Yeni gelenler» başlığını `curl` ile doğrula (`grep -c` kullanacaksan nested substitution YAPMA) + ekran görüntüsü.
6. **PRODUCTION (sahip bu sayfa için doğrudan prod iznini daha önce verdi):** yedek (`dispatch/out/backups/p85-prod-pre-deploy/` + `SHA256SUMS`) → deploy → SHA-256 3/3 → canlı `check-live.sh` + başlık doğrulaması + ekran görüntüleri `out-85-404-production-{desktop,mobile}.png`.
7. **Regresyon:** `/`, `/shop/`, `/cart/`, `/my-account/`, bir ürün sayfası → HTTP 200 + Fatal 0 (check-live.sh ile).
8. **AGENTS.md §7:** P81 maddesini güncelle (… "blok 'Yeni gelenler' olarak düzeltildi (23-09-2026)" + kanıt) + commit + push.

## 4. ROL SINIRI
- Yalnız `theme/sutre-child-v2/{404.php,style.css,functions.php}` + `AGENTS.md` + rapor/kanıt/yedek.
- DB, plugin, başka tema dosyası YASAK. Provider/model override YASAK.

## 5. RAPOR
- `/Users/salihsungur/dev/sutre/dispatch/out/out-85-404-yeni-gelenler.md` (≤600 kelime): yapılanlar · php -l · commit/push + HEAD==origin · staging (SHA+canlı+görsel) · production (yedek+SHA 3/3+canlı+görseller) · regresyon çıktıları · AGENTS güncellemesi · geri alma · açık noktalar.
- Yargı cümlesi YASAK; ölçüm + ham çıktı yaz. Yapılamayanı `YAPILAMADI: <neden>`.
- `git status -sb` + `git diff --check` çıktısı rapora.