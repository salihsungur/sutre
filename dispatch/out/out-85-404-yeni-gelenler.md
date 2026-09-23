# PACK-85 — 404 ürün bloğu: "Yeni gelenler" (dürüst + deterministik)

- Rol: coder · Tarih: 23-09-2026 · Başlangıç HEAD `900d9de` = origin/main.
- Paket beklenen HEAD `400cb8e`; fark = docs-only pack-84 rapor commit'i. `git diff 400cb8e..900d9de -- theme/` **BOŞ**; P83 SHA'ları birebir (404.php 3423B `fd9b7018…` · style.css 108845B `b43e024b…` · functions.php 95257B `4fbb134e…`) → kod sapması 0.

## 1. Yapılanlar
- `404.php`: featured sorgusu + yedek zinciri kaldırıldı → tek deterministik sorgu `wc_get_products(status=publish, limit=3, visibility=catalog, return=ids, orderby=date, order=DESC)` + `WP_Query(post__in, orderby=post__in)`. Başlık **"Yeni gelenler"**, yanında **"Tüm koleksiyonu gör"** (`/shop/`). Ürün yoksa blok hiç basılmaz. Mağaza URL'i tek kaynak `$sv404_shop_url` (array→string guard, AGENTS §5.5).
- `style.css`: `.sv-404__featured*` → `.sv-404__latest*` + yeni `.sv-404__latest-head` (flex, ortalanmış) + `.sv-404__latest-link`; ≤480'de dikey. Scoped `.sv-404*` korundu; global selector eklenmedi.
- `functions.php`: `SUTRE_VERSION` 3.6.7 → **3.6.8**.

## 2. php -l / php-parser
```
No syntax errors detected in 404.php        (PHP 8.5.10, exit=0)
No syntax errors detected in functions.php  (exit=0)
PASS php-parser(3.7.0): 404.php · functions.php
```

## 3. Yerel render (WP'siz, 3 varyant; fatal 0)
- A (Woo yok): "Yeni gelenler"=0 · "Öne Çıkanlar"=0 · kart=0 → blok basılmaz.
- B (2 ürün): "Yeni gelenler"=1 · "Öne Çıkanlar"=0 · kart=2 → basılır; `wc_get_products` çağrı sayısı=1, args `orderby=date order=DESC limit=3 visibility=catalog featured=YOK`.
- C (0 ürün): hepsi 0 → blok basılmaz.
Kanıt `evidence/out-85-404-local.png` (1280px): başlık x473 w151 · link x642 w165 → grup ortalanmış (merkez 640), `overflowX=0`.

## 4. Commit / push
`a9563c7` `fix(theme): 404 'Yeni gelenler' blogu (deterministik sorgu, featured fallback kaldirildi)`; push sonrası `git rev-parse HEAD origin/main` = **a9563c75e4e36c9ca50e891815424307b47820dc** (eşit).

## 5. Staging
Yedek `backups/p85-staging-pre-deploy/` (P83 SHA'ları birebir). Deploy **3/3 SHA-256 PASS**: 404.php 4055B `edb099cf…` · style.css 109400B `8baaa364…` · functions.php 95257B `660ef6bf…` (loglar `logs/p85/ftp-staging-*.txt`).
```
https://staging.sutre.store/404-test-xyz/?v=85a
  HTTP=404 cache=miss payments=1 css=ver=3.6.8 fatal=0
```
Gövde: "Yeni gelenler"=1 · "Tüm koleksiyonu gör"=1 · "Öne Çıkanlar"=0 · `sv-404`=16 satır · `li.product`=2. Görseller `evidence/out-85-404-staging-{desktop,mobile}.png`; ölçüm 1440: başlık+link tek satır (link sağda), taşma 0px, kart 2, h1 1; 390: alt alta, ortada (merkez 195), taşma 0px.

## 6. Production (yedek → deploy → canlı)
Yedek `backups/p85-prod-pre-deploy/` + `SHA256SUMS` (P83 SHA'ları birebir; drift 0) → deploy **3/3 SHA-256 PASS** (değerler staging ile aynı).
```
https://sutre.store/404-test-xyz/?v=85a
  HTTP=404 cache=miss payments=1 css=ver=3.6.8 fatal=0
```
Gövde: "Yeni gelenler"=1 · "Tüm koleksiyonu gör"=1 · "Öne Çıkanlar"=0 · `li.product`=2. Görseller `evidence/out-85-404-production-{desktop,mobile}.png`; ölçüm 1440: `main.sv-404` 1466px, başlık+link tek satır ortalanmış, taşma 0px, kart 2, fatal yok; 390: alt alta ortalanmış, taşma 0px. Görsel doğrulama: "Yeni gelenler" + "Tüm koleksiyonu gör" görünür; "Öne Çıkanlar" yok; çerez bandı yok.

## 7. Regresyon (check-live.sh)
```
/ · /shop/ · /cart/ · /my-account/ · /product/jakarli-sal/ → 5/5 HTTP 200 · fatal=0
```
Not: cache-hit gövdelerde hâlâ `ver=3.6.7`; `?v=85a` taze yanıtlarda iki ortam `ver=3.6.8` (prod `noindex=0`, staging kendi noindex ayarını koruyor).

## 8. AGENTS.md §7
P81 maddesi güncellendi: inline blok tanımı yeni davranışa çevrildi + "P85 düzeltmesi (23-09-2026, pack-85, commit a9563c7)" kaydı (SHA'lar, canlı ölçümler, kanıt yolları). Bu raporla aynı commit'te.

## 9. Geri alma
`backups/p85-{staging,prod}-pre-deploy/` içindeki 3 dosyayı FTP ile geri yükle (SHA256SUMS doğrula) → ver 3.6.7 + eski blok döner. Kod: `git revert a9563c7`.

## 10. Açık noktalar
- Cache: cache-hit gövdeler hâlâ `ver=3.6.7` → sahip LiteSpeed Purge All yapmalı (kapsam dışı).
- pack-84'ün AGENTS §7 kaydı yok (pack-84 raporu §8); bu pakette eklenmedi (kapsam: P81).
- Prod tema klasöründe `staging.sutre.store` dizini (pack-83'ten beri) duruyor; dokunulmadı.
- 404 yanıtı `x-litespeed-cache: miss` (P83 ile aynı; 404 cache'lenmiyor).

## Ham çıktılar (git)
```
$ git status -sb            # commit öncesi
## main...origin/main
M  AGENTS.md
A  dispatch/out/evidence/out-85-404-{production-desktop,production-mobile,staging-desktop,staging-mobile}.png
A  dispatch/out/logs/p85/ftp-{prod,staging}-{backup,deploy}.txt + prod-{check-live,desktop-measure,mobile-measure,regression}.txt + staging-{check-live,desktop-measure,mobile-measure}.txt
?? (önceki paketlerden kalan untracked dosyalar; dokunulmadı)

$ git diff --check; git diff --cached --check
(boş çıktı, exit=0)
```