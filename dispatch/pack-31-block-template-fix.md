# PAKET 31 — BLOCK TEMPLATE KAPATMA + WOO KLASİK ŞABLONLAR + TEMA BUG FIX (@coder — max effort)

## KRİTİK BUG (sahibin raporu — 2026-09-19)
"Ana sayfa hariç header ve footer gözükmüyor, diğer sayfaların CSS'i doğru düzgün gözükmüyor/yok bile."

## KÖK NEDEN (Hermes teşhisi)
/shop/ ve diğer Woo sayfaları WooCommerce BLOCK TEMPLATE ile render oluyor —
klasik PHP header.php/footer.php hiç çağrılmıyor, TT5 parent temasının blok yapısı devrede.
v2 functions.php'deki filtre ('woocommerce_has_block_template') çalışmıyor (yanlış hook adı
veya yetersiz). style.css enqueue ediliyor AMA block template markup'ında sv-* class'ları
yok → CSS "yok gibi" görünüyor.

## İLK YANIT KURALI
İlk yanıt zorunlu tool çağrıları: read_file('theme/sutre-child-v2/functions.php') + git status -sb

## GÖREVLER

### G1 — Block template'leri GERÇEK şekilde kapat
functions.php (theme/sutre-child-v2/) içinde after_setup_theme hook'u (priority 20+) ile:
```php
add_action('after_setup_theme', function () {
    remove_theme_support('block-templates');
}, 20);
```
Ayrıca eski yanlış filtreyi SİL: `add_filter('woocommerce_has_block_template', ...)` satırı
varsa kaldır (işlevsiz).

### G2 — Woo klasik şablonları child tema içine koy
Klasör: theme/sutre-child-v2/woocommerce/
Dosyalar (WooCommerce plugin templates'inden kopyala, 11.1.0):
- archive-product.php — get_header()/get_footer() kullanan klasik şablon
- single-product.php
- content-single-product.php
Kaynak: /opt/data/workspace/proje/.woo-templates-src/woocommerce/templates/ (daha önce indirilmiş — varsa oradan, yoksa WordPress.org'dan Woo 11.1.0 zip indir, zip'ten çıkar)
DİKKAT: Bu şablonlar get_header()/get_footer() çağırır → header.php/footer.php kullanır
→ her sayfada AYNI header/footer otomatik. §3.1: şablonlar Woo'nun kendi dosyaları,
değişiklik YAPMA (sadece kopyala — override mekanizması bu şekilde çalışır).

### G3 — Sidebar temizleme
archive-product.php içinde `do_action('woocommerce_sidebar')` satırı varsa SİL
veya functions.php'de: `remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);`
(Sahibin önceki kararı: sidebar yok)

### G4 — SHOP BANNER (önceki pakette hook ile eklenmişti — v2 functions.php'de YOK)
functions.php'ye ekle:
```php
add_action('woocommerce_before_main_content', function () {
    if (is_shop()) {
        echo '<div class="sv-shop-banner" role="img" aria-label="Sutre koleksiyon"></div>';
    }
}, 5);
```
CSS: .sv-shop-banner { background-image url('/wp-content/themes/sutre-child/assets/img/site/shop-banner-sutre.png'); cover; height 240px desktop/160px mobil; position relative; }
+ üzerinde 'Koleksiyon' başlığı (Woo kendi shop başlığını gizlemek için:
  add_filter('woocommerce_show_page_title', '__return_false') — banner'da başlık zaten var)

### G5 — CSS düzeltmeleri (style.css'te)
- .sv-shop-banner stili EKLE (yoksa)
- Archive sayfalar için .woocommerce-breadcrumb stili (küçük, whisper renk)
- Ürün grid sayfalarının padding'i section ile tutarlı

## YASAKLAR
- home.php/header.php/footer.php markup'ını bozma (çalışıyor)
- Çekirdek dosya yok
- secret yok

## ÇIKTI
- commit + push (feat + docs)
- Rapor: dispatch/out/out-31-block-template-fix.md (§16, 150-300 kelime)
- RAPORDA 'başarılı/güzel' DEME — sadece değişiklik listesi; görsel onay sahibin
- DEPLOY YOK — Hermes FTP ile yapacak
- Rollback: git revert
