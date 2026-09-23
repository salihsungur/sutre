# PACK-80 — P76 footer logoları: PRODUCTION DEPLOY + AGENTS kapanışı

## 0. DURUM / ÖN KOŞUL
- Sahip onayı ALINDI (23-09-2026): *"Evet, production'a al (footer logoları canlıya)."*
- Repo: `/Users/salihsungur/dev/sutre` — beklenen HEAD = origin/main = **`be6dc4320c7554b5d80d4026ecb4ddacc08e50e9`**.
- Staging doğrulandı: `dispatch/out/out-78-footer-payment-logos.md` (coder) + `dispatch/out/out-79-footer-verification.md` (tester, kapsam içi her madde PASS; FTP SHA 3/3 eşleşti).
- İlk tool çağrıların: (1) `AGENTS.md` TAMAMI, (2) `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMI, (3) `dispatch/out/out-79-footer-verification.md`, (4) `git log --oneline -3` + `git rev-parse HEAD origin/main`.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**

## 1. GÖREV (sırayla, her adımdan sonra kanıtı diske yaz)
1. **Prod hedefini tespit et (FTP):** hosting bilgileri `~/Desktop/ftpinfo.txt` (değerleri rapora/log'a YAZMA). Kök listesinden üretim tema klasörünü bul: tipik `public_html/wp-content/themes/sutre-child` (teyit: `https://sutre.store` HTML'inde `themes/sutre-child` izi). **Yolu tahmin etme — listeleyerek doğrula.**
2. **YEDEK (zorunlu, üzerine yazmadan ÖNCE):** mevcut prod `footer.php`, `style.css`, `functions.php` dosyalarını indir → `dispatch/out/backups/p76-prod-pre-deploy/` altına koy + her birinin **SHA-256 ve boyutunu** kaydet (`SHA256SUMS` dosyası). Bu yedek, geri alma planının temelidir.
3. **Upload:** yerel HEAD kopyalarını **MUTLAK yol** ile prod tema klasörüne yükle (üç dosya). Göreli yol YASAK (home köküne yazar).
4. **Yerel↔uzak SHA-256 doğrulaması:** üç dosyayı tekrar indir, SHA-256 hesapla, repo HEAD kopyalarıyla karşılaştır → **3/3 eşleşme zorunlu**. Eşleşme yoksa dur ve raporla (`YAPILAMADI`), devam etme.
5. **Canlı kanıt (production, cache-buster):**
   - `curl -skL "https://sutre.store/?v=$(date +%s)" -o /tmp/prod.html -w 'HTTP %{http_code}\n'`
   - `grep -c 'sv-footer__payments' /tmp/prod.html` → **1**
   - `grep -o 'aria-label="[^"]*"' /tmp/prod.html | sort -u` → Visa · Mastercard · TROY · PayTR · "Kabul edilen ödeme yöntemleri"
   - `grep -o 'ver=3\.[0-9]\.[0-9]' /tmp/prod.html | sort -u` → **ver=3.6.6**
   - `grep -ci 'fatal error\|warning:' /tmp/prod.html` → **0**
   - Ham çıktıları rapora yapıştır.
6. **Ekran görüntüleri (Playwriter/gerçek Brave):** prod footer → `dispatch/out/evidence/out-80-footer-production-desktop.png` (1440) + `...-mobile.png` (375). Taşma ölçümü: `scrollWidth-clientWidth` (1440/768'de 0 bekleniyor; 375'te `.sv-cat-card` kaynaklı 2px **bilinen P76 dışı** durumdur, footer kaynaklı değilse "P76 DIŞI" yaz).
7. **AGENTS.md §7 güncellemesi:**
   - P76 satırını güncelle: `STAGING DOĞRULANDI — production ONAY BEKLİYOR` → **`ZATEN YAPILDI (production canlı, 23-09-2026)`**; prod curl sonuçları, SHA-256 eşleşmesi, ekran görüntüsü yolları, commit SHA'ları eklensin.
   - Yeni düşük öncelikli kayıt EKLE (sahip kararı: "şimdilik gündemde değil"):
     `- [ ] **P76c (düşük öncelik, ertelendi) — mobil 2px yatay taşma:** kaynak `.sv-cat-card` (genişlik 379px, ana sayfa kategori kartı); footer ile ilgisi YOK (footer gizlenince de 2px). Sahip kararı 23-09-2026: şimdilik gündemde değil. Kanıt: `dispatch/out/out-79-footer-verification.md` madde 4.`
   - `/magaza/` 404 konusu ayrı pakette (pack-81) inceleniyor; P76 satırına kısa not düş: `Ek: /magaza/→404 (gerçek rota /shop/) incelemesi pack-81.`
8. **Commit + push:** AGENTS.md + rapor + ekran görüntüleri + yedek `SHA256SUMS` (yedek dosyalarının kendisini repoya KOYMA — yalnız SHA listesi). Mesaj: `feat(p76): footer odeme logolari PRODUCTION canlida + AGENTS kapanis`. Sonra `git rev-parse HEAD origin/main` eşitliğini doğrula.

## 2. ROL SINIRI
- Yalnız: prod tema klasöründeki üç dosya (deploy), `AGENTS.md`, `dispatch/out/evidence/*`, `dispatch/out/backups/p76-prod-pre-deploy/*`, rapor.
- **Başka hiçbir prod dosyasına dokunma** (checkout, plugin, DB, .htaccess vb. YASAK).
- Provider/model override YASAK. Ağ hatası alırsan küçük adımlarla tekrar dene; her adımın kanıtını hemen yaz.

## 3. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-80-p76-production.md` (≤700 kelime).
- Bölümler: (1) yapılanlar, (2) prod hedef yolu (nasıl doğrulandı), (3) **yedek tablosu** (dosya/boyut/SHA-256), (4) **yerel↔uzak SHA-256 tablosu (3/3)**, (5) canlı curl ham çıktıları, (6) ekran görüntüleri + ölçümler, (7) AGENTS güncellemesi, (8) **GERİ ALMA**: `git revert` gerekmez — yedekten geri yükleme komutu/prosedürü (FTP upload) + prod dosya SHA'ları, (9) açık noktalar.
- "Güzel görünüyor/başarılı" gibi yargı cümlesi YASAK; yalnız ölçülebilir olgular.

## 4. TEMİZLİK
- `git status -sb` + `git diff --check` çıktılarını rapora ekle; geçici dosya bırakma.