# PAKET 23 — Sahibin (Salih) Elle Adımları

> Bot staging sunucusuna SSH ile yazamaz (ADR-002 pull-only deploy). Aşağıdaki
> adımlar **cPanel Terminal + WP-admin**'de sırayla elle çalıştırılır.

## Adım 1 — Kodu staging'e al (cPanel → Git Version Control)

1. cPanel → **Git™ Version Control** → `staging.sutre.store` reposunu aç.
2. **Update from Remote** (ya da cPanel Terminal'de):
   ```bash
   cd /home/spokenla/staging.sutre.store
   git pull origin main
   ```
   Beklenen: `68c4537..813a5c0  main -> main` şeklinde pull çıktısı
   (commit `813a5c0` — "P23 ana sayfa icerik dolumu").
3. Kanıt: pull çıktısını buraya /dispatch/out/evidence-23/ altına kaydet.

## Adım 2 — PHP sözdizimi + WP debug kontrol (cPanel Terminal)

```bash
which php
php -l /home/spokenla/staging.sutre.store/wp-content/themes/sutre-child/page-templates/home.php
```
Beklenen: `No syntax errors detected`.

Sonra `https://staging.sutre.store/` tarayıcıda aç; `WP_DEBUG` açıksa
`wp-content/debug.log` boş/PHP fatal yok kontrol:
```bash
tail -20 /home/spokenla/staging.sutre.store/wp-content/debug.log 2>/dev/null || echo "debug.log yok (iyi)"
```

## Adım 3 — Öne çıkan ürünleri işaretle (WP-admin)

1. WP-admin → **Ürünler** → İman Nour Şal + Jakarlı Şal'ı bul (varsa).
2. Hızlı düzenle → **Öne Çıkan (Featured)** kutusunu işaretle.
   Bu, ana sayfadaki "[products visibility=featured]" bölümünü doldurur.
   Ürün yoksa bölüm boş "Hanproducts" görünür — ürün girişi ADR-003 akışına aittir.

## Adım 4 — Cache temizle + görsel doğrulama

1. LiteSpeed Cache varsa WP-admin üst çubuğu → **LiteSpeed Cache → Purge All**.
2. `https://staging.sutre.store/` — görmen gereken sıra:
   Hero (Sutre + Deniz ve dokumanın zarafeti + Koleksiyonu Keşfet)
   → Hikâyemiz → Koleksiyonlar (01 İpek / 02 Pamuk / 03 Bambu kartları)
   → Öne Çıkan Ürünler → Neden Sutre? → footer.
3. Mobil (telefon ya da DevTools 375px): kartlar tek kolon dikey;
   yatay kaydırma (x-overflow) YOK.
4. "Koleksiyonu Keşfet" ve "Tümünü Gör" → /shop/ açılıyor mu.

## Adım 5 — Kanıt + kabul

- Ekran görüntülerini `dispatch/out/evidence-23/` altına koy.
- onay/blok durumunu salih'e ilet; P23 kabul bu kanıtla kapanır.
- Herhangi bir PHP fatal / görsel bozulma: `git revert 813a5c0` her iki docroot'ta
  geri alınabildik — tek dosya + yalnız child theme.

## Dikkt notu
- "Neden Sutre?" bölümündeki **14 Gün Cayma Hakkı** metni DRAFT —
  mesafeli satış/iade sayfaları hukuk onayına kadar geçerli yönlendirme sadece
  koşullar sayfamıza bakınız. Rakam/yayın kararı LEGAL_REVIEW_REQUIRED.
