# P31 — Block Template Kapatma + Woo Klasik Şablonlar + Tema Bug Fix

Bot: @coder · Tarih: 2026-09-20 · Paket: dispatch/pack-31-block-template-fix.md · Şablon hedef sürüm: WooCommerce 11.1.0

## Sonuç (değişiklik listesi)

- **G1** `functions.php`: `remove_theme_support( 'block-templates' )` — `after_setup_theme`, priority 20. Eski `add_filter( 'woocommerce_has_block_template', '__return_false', 999 )` satırı SİLİNDİ. Kanıt (Woo 11.1.0 kaynağı): `includes/class-wc-template-loader.php` L132-148 — filtre yalnız `WP_Block_Templates_Registry::is_registered()` sonucunu filtreliyor; WP çekirdeğinin `locate_block_template()` kapısı bu filtreyi bilmiyor. Ayrıca `get_template_loader_default_file()` L160-189: blok şablon kayıtlıysa `default_file = ''` → template_loader blok .html'e dokunmuyor. Doğru kapı theme support kaldırmaktır.
- **G2** `theme/sutre-child-v2/woocommerce/` içine 3 klasik şablon VERBATIM kopyalandı: `archive-product.php`, `single-product.php`, `content-single-product.php`. Kaynak `.woo-templates-src/` — md5'ler resmî Woo 11.1.0 zip'i (downloads.wordpress.org) ile birebir doğrulandı: 830a7d0a… / c8a282ff… / bd85d5c7…. Şablon dosyalarında DEĞİŞİKLİK YAPILMADI (§3.1); `get_header( 'shop' )` / `get_footer( 'shop' )` zinciri her Woo sayfasında header.php/footer.php'yi bağlar.
- **G3** Sidebar: mevcut `remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 )` korundu (hook katmanı; şablon verbatim — paketin "veya" seçeneği).
- **G4** Shop banner `woocommerce_before_main_content` priority 20→5 (Woo wrapper'ından ÖNCE → full-bleed). `woocommerce_show_page_title` filtresi zaten mevcuttu.
- **Ek (gerekçeli)**: `add_theme_support( 'woocommerce' )` + gallery bayrakları restore edildi (v1'de vardı, v2'ye kurulurken düşmüş; G1 sonrası klasik yolun garantisi). Woo default wrapper (`<main id="main">`) hook katmanında kaldırılıp `.woocommerce` container verildi — default wrapper'da `.woocommerce` class'ı olmadığı için `#sutre-content .woocommerce` padding kuralı ölüydü (G5 ön koşulu).
- **G5** style.css: `.woocommerce-breadcrumb` stili EKLENDİ (12px, whisper; link hover ink); grid container padding mobil `48px 20px 64px`→`64px 20px`, desktop `48px 40px 96px`→`96px 40px` (`.sv-section` ölçeğiyle tutarlı).
- **Test** `tests/test_p31_classic_templates.py` (zero-dep) eklendi.

## Doğrulama

- TDD sırası: önce test çalıştırıldı — RED 14 FAIL (beklenen nedenlerle: özellikler eksik) → implementasyon → GREEN 21/21 PASS, exit 0. Guard testleri (G3, G4b) ilk çalışmada PASS — mevcut davranışın regresyon kilidi.
- md5: kopyalar = resmî Woo 11.1.0 şablonları (iki yönlü karşılaştırma çıktısı yukarıdaki sha'lar).
- Sapma notu: pakette banner yüksekliği 240px desktop / 160px mobil yazıyor; P30'da commit'lenmiş 280px stili KORUNDU (G5 "EKLE (yoksa)" koşulu gerçekleşmedi). Karar sahibin.

## Risk ve güvenlik

- Çekirdek dosya yok, secret yok; home.php/header.php/footer.php dokunulmadan korundu.
- Banner priority değişikliği ve wrapper override render çıktısını değiştirir → deploy sonrası görsel doğrulama şart; onay sahibin.
- Bu konteynerde PHP runtime yok → doğrulama statik (yapı, md5, hook desenleri); runtime doğrulama staging'e kalır.

## Açık kapılar

- **DEPLOY YOK** — Hermes FTP ile yükleyecek. Rollback: `git revert <feat-sha>`.
- **NEEDS_OWNER_INPUT**: banner görseli `assets/img/site/shop-banner-sutre.png` repoda YOK (git geçmişinde hiç bulunmadı; P27 görselleri muhtemelen FTP ile doğrudan canlıya gitti). Canlıda yoksa banner marine zemin + metinle render olur. Aynı nedenle header/footer logo PNG'leri de repoda yok — ayrı bulgu, ayrı karar.
- **Sonraki en küçük güvenli adım**: deploy sonrası `/shop/`, bir ürün sayfası ve bir kategori sayfasında header/footer/breadcrumb/banner/grid görsel kontrolü (sahip).
