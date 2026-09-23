# PACK-78 — P76 footer logoları: DEVAM (staging deploy + kanıt + rapor)

## 0. DURUM: ÖNCEKİ KOŞU AĞ KESİNTİSİNDE ÖLDÜ — İŞİ BAŞTAN YAPMA
Önceki `coder` koşusu (session `20260923_144659_54839d`) ağ kesintisinde ölü socket'te takıldı ve sonlandırıldı. **Aşağıdaki işler ZATEN YAPILDI ve push edildi:**

- Repo: `/Users/salihsungur/dev/sutre` — HEAD = origin/main = **`619c2e36d88ecc84f93c13e497b60040730d4eb1`**
  - `theme/sutre-child-v2/footer.php` +31 satır: `.sv-footer__payments` bloğu **satır 52**'de (`role="group"`, `aria-label` çevrilebilir), "İstanbul"un solunda.
  - `theme/sutre-child-v2/style.css` +26 satır: `.sv-footer__payments` (satır **974**), mobil düzeltmeleri satır **1000** (@781) ve **2085** (@480).
  - `theme/sutre-child-v2/functions.php`: `SUTRE_VERSION` → **'3.6.6'**.
  - `dispatch/out/evidence/out-77-footer-local-desktop.png` (yerel render ekran görüntüsü) commit'te.
- Kurtarılabilir çalışma alanı: `~/.hermes/profiles/coder/cache/scratch/out77/` → `visa-2021.svg`, `mc-*.svg`, `paytr-logo.svg`, `troy-logo/TROY Logolar/{SVG,PNG}` (resmî medya kiti), `block.php.txt`, `build_markup.py`, `ftp_deploy_staging.py`, `render/chips*.png`.

**YAPILMAYANLAR (bu paketin işi):** staging FTP deploy · canlı curl kanıtı · staging masaüstü+mobil ekran görüntüsü · `dispatch/out/out-78-footer-payment-logos.md` raporu · AGENTS.md §7 P76 güncellemesi.
**Commit'i/yeniden kodlamayı TEKRARLAMA.** Önce `git show 619c2e3` ile mevcut durumu doğrula; iş kodda bitti, kanıt ve deploy kaldı.

## 1. ÖN KOŞUL / İLK ADIMLAR
- İlk tool çağrıların: (1) `/Users/salihsungur/dev/sutre/AGENTS.md` TAMAMI, (2) `/Users/salihsungur/dev/sutre/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMI, (3) `dispatch/out/out-76-paytr-logos-research.md`, (4) `git show --stat 619c2e3` ve `git rev-parse HEAD origin/main`.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**
- Ağ: kesinti olduğu biliniyor. Adımları KÜÇÜK tut, her adımdan sonra kanıtı diske yaz (uzun tek blok iş yapma); bir komut hata verirse tekrar dene, görevi küçült.

## 2. GÖREV SIRASI (kanıt zorunlu)
1. `php -l theme/sutre-child-v2/footer.php`, `php -l theme/sutre-child-v2/functions.php` → 0 hata; çıktıyı rapora yapıştır.
2. `git show 619c2e3 -- theme/sutre-child-v2/footer.php | head -60` → eklenen bloğun dört markayı (Visa · Mastercard · TROY · PayTR) inline SVG olarak içerdiğini doğrula; **dördü de yoksa eksik olanı tamamla** (yeni commit at, mesaj: `fix(footer): <eksik marka> logosu eklendi`).
3. **Staging deploy (FTP, kanal B):** bilgiler `~/Desktop/ftpinfo.txt` (değerleri rapora/log'a YAZMA). Hedef klasör: **`/staging.sutre.store/wp-content/themes/sutre-child/`** — MUTLAK yol kullan (göreli yol home köküne yazar). Yüklenecek: `footer.php`, `style.css`, `functions.php`. Upload sonrası **SHA-256 karşılaştırma zorunlu** (yerel vs uzak; üçü için tek tek).
4. **Canlı kanıt (staging):** cache-buster ile
   - `curl -skL "https://staging.sutre.store/?v=$(date +%s)" | grep -c 'sv-footer__payments'` → **≥1**
   - `curl -skL "https://staging.sutre.store/?v=$(date +%s)" | grep -o 'aria-label="[^"]*"' | sort -u | head` → Visa/Mastercard/TROY/PayTR etiketleri görünmeli
   - `curl -skL "https://staging.sutre.store/?v=$(date +%s)" | grep -o 'sutre-style[^"]*ver=3\.6\.6' | head -2` → CSS sürümü 3.6.6 (cache-buster doğrulaması)
   Gerçek çıktıları rapora yapıştır.
5. **Staging ekran görüntüleri (Playwriter/Brave izinli):** footer'ı kırp → `dispatch/out/evidence/out-78-footer-staging-desktop.png` ve 375px genişlikte `dispatch/out/evidence/out-78-footer-staging-mobile.png`. Taşma/düzen bozulması varsa **rapora yaz** (düzeltme gerekiyorsa küçük bir `fix` commit'i at).
6. **Görsel doğrulama notu (§0.6):** Raporunda "güzel görünüyor/başarılı" gibi yargı cümlesi KURMA; yalnız ölçülebilir olgular (logo sayısı, yükseklikler, hizalama, taşma yok/var, ekran görüntüsü yolu).
7. **AGENTS.md §7 P76 maddesini güncelle:** `SÜRÜYOR` → `STAGING DOĞRULANDI — production ONAY BEKLİYOR`; commit SHA'ları (`619c2e3` + varsa yeni), SHA-256 eşleşmesi, canlı curl sonuçları, ekran görüntüsü yolları. Commit mesajı: `docs(agents): P76 staging dogrulandi - production onay bekliyor`.
8. **Push:** `git push origin main` → `git rev-parse HEAD origin/main` eşitliğini doğrula.

## 3. ROL SINIRI
- Yalnız: `theme/sutre-child-v2/{footer.php,style.css,functions.php}`, `AGENTS.md`, rapor + `dispatch/out/evidence/` görselleri.
- **PRODUCTION DEPLOY YOK** (sahibin onayı ayrı paket). checkout/PayTR/başka tema dosyaları YASAK.
- Provider/model override YASAK.

## 4. KAPSAM DIŞI
Production FTP; logo dosyalarını repoya eklemek; PayTR merchant ayarları; başka plan `.md` dosyası oluşturmak.

## 5. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-78-footer-payment-logos.md` (≤800 kelime).
- Bölümler: (1) yapılanlar madde madde, (2) php -l çıktıları, (3) commit/push SHA'ları + HEAD==origin, (4) FTP upload + **SHA-256 karşılaştırma tablosu** (yerel/uzak), (5) canlı curl çıktıları (ham), (6) ekran görüntüsü yolları + ölçülebilir olgular, (7) AGENTS.md güncellemesi, (8) **geri alma komutu** (git revert + FTP geri yükleme), (9) açık noktalar/riskler. Yapılamayanı `YAPILAMADI: <neden>` yaz.

## 6. TEMİZLİK
- `git status -sb` çıktısını raporun sonuna ekle; `git diff --check` temiz; geçici dosya bırakma.