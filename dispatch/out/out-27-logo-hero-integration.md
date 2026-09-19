# PAKET 27 — LOGO + SITE GÖRSELLERİNİN TEMAYA ENTEGRASYONU (out-27)

**Tarih:** 2026-09-19 · **Kapsam:** theme/sutre-child (6 dosya) · **feat SHA:** `34b7b30` · **push:** `6d76679..34b7b30 main -> main`

## Sonuç
Kabul kriterlerinin tümü uygulandı:

- **G1 header:** `block-template-parts/header.html` text logo → `sutre-logo-header.png` img (`wp:html` blok; blok markup'ta PHP çalışmadığından tema sabit statik yol — anayasa §3.1 ihlali yok, asset tema-owned). override.css: height 40px, mobil 32px.
- **G2 footer:** `sutre-logo-footer.png` (koyu zemin) © 2026 satırının ÜSTÜNE; © satırı KORUNDU.
- **G3 hero (home.php + templates/page-sutre-anasayfa.html senkron):** h1 "Sutre" + tagline "Deniz ve dokumanın zarafeti" KALDIRILDI (sahibin kararı); `.sutre-hero` artık hero-banner-sutre.png background (cover, right center) + `sutre-hero-lockup.png` img (max-width 420px, solda) + "Koleksiyonu Keşfet" CTA (korunur). Mobil: hero 380px, lockup %80. P26f 980px hero kuralı P27'de `max-width:none !important` ile ezilir — strip etkilenmez.
- **G4 kategori kartı:** `sutre-collection__card--single` → kategori-giyim-kart.png bg + ink gradient overlay `rgba(26,26,26,.55→.75)`; metin (01/Giyim/Keşfet) bone/silk renklerle okunur; min 280px mobil / 360px desktop.
- **G5 shop banner:** `functions.php` → `add_action('woocommerce_before_shop_loop','sutre_shop_banner',5)`; yalnız `is_shop()` (kategori arşiv/ürün sayfası hariç, `function_exists` guard). shop-banner-sutre.png bg, 220px/160px mobil, beyaz serif "Koleksiyon" + gölge. Core override dosyası YOK (hook = public API).
- **G6 override.css:** P27 bloğu eklendi; D8 tagline renk kuralı işlevsiz olduğundan kaldırıldı ve yorumla belgelendi; diğer eski hero/breakpoint stillerine dokunulmadı.

## Doğrulama
- PHP için `php -l` YOK (ortamda php ikiliği yok, P26 ile aynı): statik token kontrol — home.php `<?php`/`?>` 15/15, kod küme parantezi 4/4; functions.php 14/14; override.css brace 121/121 + comment 66/66 dengeli. **HOST'TA DEPLOY ÖNCESİ `php -l` ÖNERİLİR.**
- Grep kanıtı: `sutre-hero__tagline`/`Deniz ve dokuman` → home.php + blok şablon 0 eşleşme; header/footer img yolları mevcut; `2026 Sutre` © satırı duruyor.
- Önceki fonksiyonlar korundu: `[products]` shortcode, placeholder img filtresi, header/footer do_blocks tek kaynak, checkout/register dokunulmadı (diff kendi kapsamıyla sınırlı: 6 tema dosyası).

## Risk ve güvenlik
- Görseller FTP ile sunucuya yüklendi (statik yol `/wp-content/themes/sutre-child/assets/img/...` bağlı); repo'da binary YOK — yol değişirse kırılır (bilinçli, dispatch talimatı).
- DEPLOY YOK — Hermes FTP ile yapacak; deploy sonrası staging http 200 + gerçek görsel render smoke testi gerekir.
- Secret/ödeme/çekirdek dokunuşu: none (ödemeye dokunulmadı).

## Açık kapılar
- NEEDS_OWNER_INPUT: deploy sonrası staging ana sayfa + /shop/ ekran kontrolü (banner görselleri + 375px mobil taşma).
- OWNER_APPROVAL_REQUIRED: yok. LEGAL_REVIEW_REQUIRED: yok.
- Rollback: `git revert 34b7b30`.
- Sonraki en küçük güvenli adım: FTP deploy + `php -l` host'ta + staging smoke test.
