# PAKET 26 — Ana Sayfa Görsel Bug Düzeltmeleri (out-25b)

**Tarih:** 2026-09-17 · **Kapsam:** yalnız `home.php` + `override.css` · **feat SHA:** `fda4b05`

## Sonuç
Sahibin screenshot-analizi defektleri D1–D9 giderildi (D10 nav admin-bar'a DOKUNULMADI — public yok):

- **D1:** `.sutre-products ul.products` → `display:grid`; mobil 1 kolon, ≥782px 2 kolon, `gap:24px`, `align-items:stretch`.
- **D2:** Ürün butonu `box-sizing:border-box`, `width:calc(100% - 32px)`, kart içinde, ellipsis overflow.
- **D3:** `.onsale` badge `position:absolute; top:12px; left:12px`, kart `overflow:hidden` radius kilitli, `--sutre-marine` zemin + `--sutre-white` yazı.
- **D4:** `woocommerce_placeholder_img` filtresi eklendi — placeholder src WooCommerce çekirdek public API'den gelir, kodda sabit URL YOK.
- **D5/D6:** section padding 48px mobil / 64px desktop; başlık + "TÜMÜNÜ GÖR →" aynı flex row (`sutre-products__header`, space-between).
- **D7:** divider `width:64px`, tek silk çizgi; boşta altın çizgi kaldırıldı.
- **D8:** hero tagline `--sutre-whisper` → `--sutre-ink` (kontrast ~2:1 → ~14.6:1, WCAG AA).
- **D9:** footer © `rgba(255,255,255,.7)` → `--sutre-white`; fazladan `margin-top` kaldırıldı.
- **KATEGORİLER:** yalnız tek "Giyim" kartı (01 numaralı, tam genişlik hero-benzeri panel) → `/shop/`; İpek/Pamuk/Bambu yalnız mini alt not satırında.

## Doğrulama (Hermes browser render smoke testi)
Statik DOM + gerçek override.css render'ında ölçüldü:
- kart: `overflow:hidden / relative / border-box` ✓; badge `absolute top=12px inside=true` ✓
- buton `border-box, widthOK=true, inside=true` ✓; grid 2 kolon `[572.5px 572.5px] gap=24px stretch` ✓
- mobil 375px: `cols=1`, yatay taşma `0px` ✓
- tagline `rgb(26,26,26)`, © `rgb(255,255,255)` ✓; KATEGORİLER kart sayısı = 1 ✓; header flex ✓; "TÜMÜNÜ GÖR" başlık sağında ✓; divider 64px×2px silk ✓
- CSS brace/comment dengesi + PHP blok dengesi ✓; hardcoded hex yok (hex→custom property standardı)

## Risk ve güvenlik
- PHP lint (php -l) yapılAMADI (ortamda php yok; Docker daemon kapalı) — sözdizimi yalnız token denge + JS analiziyle doğrulandı; deploy öncesi host'ta `php -l` önerilir.
- `woocommerce_placeholder_img` filtresi çekirdek imzasını değiştirmez; sadece boş placeholder src durumunda boş string döner.
- WP admin bar çakışması (D10) bilinçli dokunulmadı.

## Açık kapılar
- NEEDS_OWNER_INPUT: staging URL'sinde Hermes FTP deploy sonrası gerçek [products] render'ında görselleri kontrol et.
- OWNER_APPROVAL_REQUIRED: yok.
- Rollback: `git revert fda4b05`.
- Sonraki en küçük güvenli adım: deploy + staging ana sayfa curl/screenshot kabulü.
