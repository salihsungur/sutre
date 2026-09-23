# PACK-82 — LiteSpeed cache purge (YALNIZ sutre.store girdileri) + canlı doğrulama

## 0. SORUN (kanıtlı)
P76 footer logoları production'a alındı ve **dosyalar doğru** (FTP SHA-256 3/3 eşleşti), ancak **ana sayfa cache'ten eski haliyle servis ediliyor**:

```
https://sutre.store/            → HTTP 200, sv-footer__payments=0, x-litespeed-cache: hit    ← ESKİ
https://sutre.store/?v=<ts>     → HTTP 200, sv-footer__payments=1, x-litespeed-cache: miss   ← YENİ
https://sutre.store/shop/       → HTTP 200, sv-footer__payments=1, x-litespeed-cache: miss   ← YENİ
```

Yani kod canlıda; yalnız **sayfa cache'i** bayat. Görev: sutre.store'a ait cache girdilerini temizlemek — **hesabın tümünü değil**.

## 1. ÖN KOŞUL / İLK ADIMLAR
- Hosting bilgileri: `~/Desktop/ftpinfo.txt` (değerleri rapora, loga, komut geçmişine YAZMA).
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**
- Hesap kökünde başka siteler de var (`geleceginbilimi.com`, `detoneakademi_*` vb.). **Onların cache'ine dokunma.**
- Bilinen dizinler: `lscache/` (LiteSpeed sunucu sayfa cache'i, hash'li alt dizinler) · `sutre.store/wp-content/litespeed/` (WP eklentisinin verisi; içinde `.htaccess`, `cssjs` vb.).

## 2. GÖREV — hedefli purge
1. **Envanter (önce ölç, sonra sil):** `lscache/` altını özyinelemeli listele (FTP `LIST`/`NLST` ile). Girdi sayısını ve toplam boyutu raporla. **Girdi sayısı > 20.000 ise DUR** — silme yapma, raporla (sahip kararı gerekir).
2. **Ait olanı ayır:** her cache dosyasını indir (gzip'liyse aç) ve içinde **`sutre` / `sutre.store` / `sv-footer`** dizgesi geçenleri "sutre girdisi" olarak işaretle. Bu, başka sitelerin girdilerini korur. Eşleşme sayısını ve örnek yolları (dosya adı + boyut) rapora yaz.
3. **Sil:** yalnız işaretlediğin dosyaları sil (FTP `DELE`). **Hiçbir dizini silme, `.cm.log` gibi yönetim dosyalarına dokunma.** Silinen dosya sayısını raporla.
4. **Eklenti cache'i (site-scoped, güvenli):** `sutre.store/wp-content/litespeed/cssjs/` içeriği varsa temizle (yalnız o klasörün içi; `.htaccess` ve `robots.txt` KALSIN).
5. **Doğrulama (zorunlu, cache-buster'sız!):**
   - `https://sutre.store/` → `sv-footer__payments` **= 1**, `x-litespeed-cache` başlığı **miss** (veya hit ama içerik YENİ)
   - `https://sutre.store/shop/`, `/cart/`, `/my-account/`, 2 hukuki sayfa → aynı kontrol
   - `aria-label="Visa|Mastercard|TROY|PayTR"` dört marka görünüyor mu, `style.css?ver=3.6.6` mü, Fatal/Warning 0 mı
   - Ham çıktıları rapora yapıştır (başlık + gövde kontrolü).
6. **Regresyon:** temizlikten sonra `geleceginbilimi.com` ana sayfası HTTP 200 dönüyor mu (dokunmadığımızın kanıtı) — tek `curl` yeter, içerik analizi yapma.

## 3. ROL SINIRI
- Yalnız **cache dosyaları** silinir. Tema/plugin/DB/kullanıcı dosyalarına, `public_html` içeriğine, başka sitelerin dizinlerine DOKUNMA.
- Repoda dosya değiştirme; commit YOK (rapor dosyası hariç).
- Provider/model override YASAK. FTP oturumu koparsa kaldığın yerden devam et; önce ne kadar sildiğini yaz.

## 4. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-82-litespeed-purge.md` (≤600 kelime).
- Bölümler: (1) envanter (girdi sayısı/boyut), (2) sutre girdisi sayısı + örnek yollar, (3) silinen dosya sayısı, (4) eklenti cache temizliği, (5) **cache-buster'sız canlı doğrulama ham çıktıları** (önce/sonra karşılaştırmalı), (6) diğer site regresyon kontrolü, (7) `YAPILAMADI`/sınırlar, (8) not: cache kendini yeniden doldurur (geri alma gerekmez).
- "İyi görünüyor" gibi yargı cümlesi YASAK; yalnız ölçüm ve ham çıktı.