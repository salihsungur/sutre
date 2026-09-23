# PACK-82 — LiteSpeed cache purge (yalnız sutre.store girdileri) + canlı doğrulama

Tarih: 23-09-2026 · Kanal: FTP (ftplib) + curl · FTP hedefi: hesap kökü `/` (mutlak yol)

## 1. Envanter (`/lscache`, FTP özyinelemeli, salt-okunur)
- **127 dizin, 57 dosya, toplam 2.364.510 B (2,25 MB)** = 56 önbellek girdisi (2.364.467 B) + 1 yönetim dosyası `/lscache/.cm.log` (43 B).
- Girdi sayısı 20.000 eşiğinin altında → durma koşulu yok.
- `/lscache` kökü ve ilk-seviye dizinlerin son değişimi: **2026-08-24 … 2026-08-26**.
- Girdi formatı: `LSCH` ikili başlığı; başlıkta düz metin URL yolu taşınıyor (ör. `/direncli-kentler/`, `/kurumsal-kimlik/`, `/kategori/blog/`).

## 2. Ait olanı ayırma
- 56/56 girdi indirildi, gzip akışları açıldı, içerik tarandı: `sutre`/`sutre.store`/`sv-footer` içeren **0**; `geleceginbilimi` içeren **56**.
- **FTP ile görünen mağazadaki sutre girdisi = 0.**
- Örnek yollar (yol · dosya · boyut):
  - `/` · `/lscache/4/d/6/4d6ec3b9980adf29` · 374.042 B (295× geleceginbilimi)
  - `/wp-json/` · `/lscache/3/a/2/3a2eb56ab6b12d54` · 374.037 B
  - `/feed/` · `/lscache/f/7/b/f7bbd2de94bffa95` · 270.965 B
  - `/kategori/blog/feed/` · `/lscache/a/5/8/a5895a1f94c17f67` · 246.741 B
  - Tam liste: `~/.hermes/profiles/devops/cache/scratch/p82/paths.json` (geçici dizin).

## 3. Silme
- **Silinen sutre girdisi: 0.** Hiçbir dosya silinmedi; dizin silinmedi; `.cm.log`'a dokunulmadı; geleceginbilimi.com girdileri kapsam dışı olduğu için korundu.

## 4. Eklenti cache'i (site-scoped)
- `/sutre.store/wp-content/litespeed/`: `qc.*`, `.htaccess`, `robots.txt`, `debug/` var. **`cssjs/` ve `cache/` dizinleri YOK** (CWD testi: `550 … No such file or directory`) → temizlenecek içerik yok.
- `/sutre.store/.litespeed_conf.dat` FTP ile okunamadı (`error_perm`).

## 5. Cache-buster'sız canlı doğrulama (ham)

ÖNCE (pack başı):
```
/                http=200 x-litespeed-cache: hit   sv-footer__payments=0
/shop/           http=200 x-litespeed-cache: hit   sv-footer__payments=1
/cart/           http=200 x-litespeed-cache: miss  sv-footer__payments=1
/my-account/     http=200 x-litespeed-cache: miss  sv-footer__payments=1
/mesafeli-satis-sozlesmesi/ http=200 miss 1   /iade-ve-cayma/ http=200 miss 1
```
SONRA (turlar sonrası, ham):
```
=== https://sutre.store/ ===
HTTP/2 200 | content-length: 52443 | etag: W/"6ab3ce27-304f"
x-gws-cache: hit | x-cache: HIT | x-litespeed-cache: hit
sv-footer__payments=0 | aria Visa/Mastercard/TROY/PayTR = 0/0/0/0
themes/sutre-child/style.css?ver=3.6.5 | Fatal=0 Warning=0
=== https://sutre.store/shop/ ===
HTTP/2 200 | x-litespeed-cache: hit | payments=1 | aria 1/1/1/1 | ver=3.6.6 | Fatal=0
=== https://sutre.store/cart/ ===
HTTP/2 200 | x-litespeed-cache: miss | payments=1 | aria 1/1/1/1 | ver=3.6.6 | Fatal=0
=== https://sutre.store/my-account/ ===
HTTP/2 200 | x-litespeed-cache: miss | payments=1 | aria 1/1/1/1 | ver=3.6.6 | Fatal=0
=== https://sutre.store/mesafeli-satis-sozlesmesi/ ===
HTTP/2 200 | x-litespeed-cache: hit | payments=1 | aria 1/1/1/1 | ver=3.6.6 | Fatal=0
=== https://sutre.store/iade-ve-cayma/ ===
HTTP/2 200 | x-litespeed-cache: hit | payments=1 | aria 1/1/1/1 | ver=3.6.6 | Fatal=0
```
Ölçüm: bayatlık **yalnız `/` girdisinde** (ver 3.6.5, payments=0); diğer 5 URL yeni (ver 3.6.6, payments=1, dört marka görünür, Fatal/Warning 0).

Mağaza-konumu kanıtı: 3 taze sutre URL'si (`?p82probe=b1..b3`) 1. istek `miss` → 2. istek `hit`; buna karşın `/lscache` dosya sayısı **57 → 57, yeni dosya 0**. sutre.store girdileri FTP-görünür `/lscache` ağacında değil.

Dışarıdan purge denemeleri (sonuç başlıkları): `X-LiteSpeed-Purge: public,<tag>` → yanıtta `x-litespeed-purge` YOK · `PURGE` metodu → **HTTP 405** · `?LSCWP_CTRL=purge_all` → 6 sn sonra `/` değişmedi · `wp-cron.php` → 200, `/` değişmedi. Kaynak kanıtı: `plugins/litespeed-cache/src/router.cls.php:557-577, 631-636` (purge için nonce veya yönetici-IP şartı).

## 6. Regresyon
- `geleceginbilimi.com` → HTTP 200 · `staging.sutre.store` → HTTP 200.

## 7. YAPILAMADI / sınırlar
- (a) **`/` ana sayfa cache girdisi temizlenemedi**: girdi FTP-görünür mağazada değil (bkz. §5 kanıtı), dışarıdan purge reddediliyor.
- (b) **Ana sayfa cache-buster'sız doğrulaması BAŞARISIZ**: `sv-footer__payments=0`, `style.css?ver=3.6.5`.
- (c) Silme yapılmadı çünkü sutre girdisi 0 bulundu; başka sitelerin girdileri silinmedi.
- (d) Sınır: cPanel/SSH ve WP admin erişimi bu oturumda yok; WP eklenti purge rotası nonce/admin-IP korumalı (kod kanıtlı).
- Sahip için tek mini adım: WP Admin → LiteSpeed Cache → Toolbox → **Purge All** (sutre.store kapsamlı) veya cPanel → LiteSpeed Cache Manager → sutre.store purge; ardından `https://sutre.store/` (parametresiz) kontrolü.

## 8. Not
- Cache kendini yeniden doldurur; bu iş için geri alma adımı gerekmez.