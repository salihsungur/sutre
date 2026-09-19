# PAKET 27 — LOGO + SITE GÖRSELLERİNİN TEMAYA ENTEGRASYONU (@coder — max effort)

## DURUM
Görseller ÜRETİLDİ ve FTP ile sunucuya yüklendi:
`/home/spokenla/staging.sutre.store/wp-content/themes/sutre-child/assets/img/`
  - logo/sutre-logo-header.png (bone zemin, ink yazı — Didot serif, S swash)
  - logo/sutre-logo-footer.png (ink zemin, bone yazı)
  - logo/sutre-logo-square.png
  - logo/sutre-hero-lockup.png (SUTRE + silk-gold çizgi — SLOGANSIZ)
  - site/hero-banner-sutre.png (21:9 — şal sağda, solda boşluk)
  - site/kategori-giyim-kart.png (16:9 — emerald koltuk drape)
  - site/shop-banner-sutre.png (21:9 — makro doku geniş)

## SAHİBİN KURALLARI
- Logo: düz text DEĞİL, ürettiğimiz görsel kullanılacak
- Slogan/tagline YOK (hero'da 'Deniz ve dokumanın...' yazısı KALDIRILACAK)
- Site tasarımı minimalist ama görseller dolu/canlı
- anayasa §3.1: sadece tema dosyaları, çekirdek yok; public API

## İLK YANIT KURALI
İlk yanıt zorunlu tool çağrısı: read_file(block-template-parts/header.html) + git status -sb.

## OKUMA
1. theme/sutre-child/block-template-parts/header.html
2. theme/sutre-child/block-template-parts/footer.html
3. theme/sutre-child/templates/page-sutre-anasayfa.html — YOKSA: klasörü kontrol et ('Şal/İman Nour' düzenlemesi sırasında silinmiş olabilir; yoksa page-templates/home.php'de iş var)
4. theme/sutre-child/page-templates/home.php
5. theme/sutre-child/parts/override.css
6. shop sayfası: Woo block template mi klasik mi kontrol et (archive-product override var mı)

## GÖREVLER

### G1 — HEADER: text logo → img
`block-template-parts/header.html` içindeki
`<p class="sutre-header__logo"><a href="/">Sutre</a></p>` satırını değiştir:
`<a href="/"><img src=".../assets/img/logo/sutre-logo-header.png" alt="Sutre" class="sutre-header__logo-img"></a>`
- URL sabit yazma: `esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/sutre-logo-header.png' )`
- Ama .html blok dosyasında PHP ÇALIŞMAZ! → img'i CSS background-image İLE ver:
  override.css: `.sutre-header__logo-img { height: 40px; width: auto; }` ve header.html'e
  `<img>` yazarken görsel yolunu statik `/wp-content/themes/sutre-child/assets/img/logo/...` yaz
  (blok markup'ta statik yol kabul edilebilir — tema sabit yolu; anayasa §3.1 ihlali yok)
- CSS: header yüksekliği buna göre dengelenir (logo ~40px yükseklik, nav sağda)
- Mobil: logo height 32px

### G2 — FOOTER: text logo → img (koyu zemin versiyonu)
`block-template-parts/footer.html` içindeki copyright satırının ÜSTÜNE:
`<img src="/wp-content/themes/sutre-child/assets/img/logo/sutre-logo-footer.png" alt="Sutre" class="sutre-footer__logo-img">`
- CSS: height ~28px, center
- Eski © 2026 satırı KALSIN (altında)

### G3 — ANA SAYFA HERO: görsel lockup + arka plan
`page-templates/home.php`:
- Mevcut text hero (h1 Sutre + tagline p + CTA) → ŞU ŞEKİLDE değiştir:
  - `<section class="sutre-hero">` içinde ARTIK sadece:
    hero-banner görselini CSS background olarak veren bir div +
    üstünde `sutre-hero-lockup.png` görseli (img) +
    CTA butonu 'Koleksiyonu Keşfet' (korunur)
  - Tagline text satırı SİLİNECEK (lockup görselinde yok, sahibin kararı)
- CSS: `.sutre-hero { background-image: url('/wp-content/themes/sutre-child/assets/img/site/hero-banner-sutre.png'); background-size: cover; background-position: right center; min-height: 520px; }` mobil 380px
- lockup img: max-width 420px, ortalanmış-solda (hero banner'ın boş sol alanına denk gelir)
- Mobil: lockup max-width %80, min-height 380px

### G4 — KATEGORİ KARTI: arka plan görseli
home.php'deki `sutre-collection__card--single` kartına:
- CSS background-image: kategori-giyim-kart.png (cover)
- Üzerindeki metin (01/Giyim/Keşfet) okunabilirlik: metin altına koyu gradient overlay
  `linear-gradient(rgba(26,26,26,.55), rgba(26,26,26,.75))` veya metin koyu zemin pill
- Kart yüksekliği: min 280px mobil / 360px desktop

### G5 — SHOP BANNER: /shop/ üstü
WooCommerce shop sayfası BLOCK template (archive-product.html fallback) — biz child'ta
woocommerce/archive-product.php YOK (silinmişti). En güvenli yol:
- functions.php'ye hook: `woocommerce_before_main_content` → sadece `is_shop()` sayfasında
  bir banner div yaz (img background css + padding)
  `add_action('woocommerce_before_shop_loop', 'sutre_shop_banner', 5);`
- Banner: shop-banner-sutre.png background cover, height 220px desktop/160px mobil,
  üzerinde 'Koleksiyon' başlığı (h1 yerine, beyaz text + gölge)
- anayasa §3.1: hook = public API ✓

### G6 — override.css: yeni stiller (G1-G5'e ait) + eski sutre-hero text stillerinin
kullanılmayanlarını SİLME (breakpoint'ler korunur); sadece tagline satır stili silinebilir.

## KABUL KRİTERLERİ
1. Header'da SUTRE görsel logosu görünüyor (text değil)
2. Footer'da koyu-zemin logo + © satırı
3. Ana sayfa hero: banner arka plan + lockup görseli + CTA; tagline text YOK
4. Kategori kartında görsel arka plan + okunabilir 'Giyim' yazısı
5. /shop/ üstünde banner görünüyor
6. Mobil 375px: taşma yok, logo 32px, hero 380px
7. PHP token dengesi + CSS brace dengesi OK (php CLI yok — tarayıcı smoke testi veya statik kontrol)
8. Önceki tüm fonksiyonlar korunur (checkout, register, ürün grid)

## DELIVERABLE
- commit + push (feat + docs)
- Rapor: dispatch/out/out-27-logo-hero-integration.md (§16, 150-300 kelime)
- DEPLOY YOK — Hermes FTP ile yapacak
- Rollback: git revert

## OUT-OF-SCOPE
- Ürün fotoğraflarının Woo'ya yüklenmesi YOK (sonraki aşama)
- İman Nour görselleri YOK
- PayTR/diğer YOK
