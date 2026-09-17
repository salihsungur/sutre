# PAKET 22b ÇIKTI RAPORU — TT5 Footer Pattern Override, Kök Tabanlı Düzeltme

Tarih: 2026-09-17 · Bot: @coder · Anayasa §16 format.

## SONUÇ

P22'de child `parts/footer.html` yüklü olmadı. Kök-neden pakette tahmin edilenden
DAHA DERİN; ikinci ancak gerçek kök disk doğrulamalarla bulundu:

**Kök-neden (kod kanıtları, WP çekirdek kaynak 6.8/trunk):**
1. Parent `parts/footer.html` yalnız `<!-- wp:pattern {"slug":"twentytwentyfive/footer"} /-->`
   çağırır; "Designed with WordPress" parent `patterns/footer.php` içindedir (raw.github kanıtı).
2. `WP_Theme::get_block_template_folders()` (class-wp-theme.php ~L1810): klasör tespiti
   TEMA BAŞINA yapılır; child theme klasöründe `block-templates/` var oldugu anda
   child'ın klasörleri `block-templates` + `block-template-parts` olur — parent ise
   `templates` + `parts`.
3. `_get_block_templates_files()` (block-template-utils.php L401-413) her temayı
   KENDİ klasör plakasıyla tarar → child `parts/` altındaki header/footer .html
   dosyaları HİÇ taranmıyor; slug `footer` child'da boş → parent part yüklenir →
   pattern → "Designed with WordPress" render. P22 hipotezinin YANLIŞ olduğu kanıt:
   `WP_Theme::get_block_template_folders()`, `_get_block_templates_files()` kaynak innenidildi
   (/tmp/p22b/wp-class-wp-theme.php, wp-block-template-utils.php, 2026-09-17).

**Kök tabanlı düzeltme (P22b):**
- `theme/sutre-child/block-template-parts/header.html` + `footer.html` (YENİ KLASÖR):
  P22'nin part içeriği AYNEN, DOĞRU part klasörüne taşındı. Artık WP bu partları
  tarar (WP_Theme::get_block_template_folders doğrulaması).
- `theme/sutre-child/block-templates/index.html` (YENİ): Child'a yazilen index,
  parent `templates/index.html`'i override eder. Footer **DOĞRUDAN** blok markup
  olarak gömülü (any child `wp:pattern` footer çağrısı yok); header `template-part slug=header`
  referansı bırakıldı (child'daki `block-template-parts/header.html` bu slug'a düşer).
- `theme/sutre-child/patterns/footer.php` (YENİ): WP pattern header'lı
  `Slug: sutre-child/sutre-footer` pattern (insert/registry içindir). `Block Types`
  YOK — pattern anayasa'a ayırı; P22'nin Block-Hooks katan sorununu tekrar yaşamamak için.
- `parts/override.css` YERİNDE KALDI (enqueue dokunulmamış); `functions.php` DOKUNULMADI.

## DOĞRULAMA (disk + git kanıtı)

- Dosya byte/line; write_file verified:true (hash kanıtı):
  - `theme/sutre-child/patterns/footer.php`          — **2094 byte / 56 satır**
  - `theme/sutre-child/block-templates/index.html`   — **2406 byte / 57 satır**
  - `block-template-parts/header.html`               — git status `??`, taşınmış (P22 içeriği AYNEN)
  - `block-template-parts/footer.html`               — `??` (P22 içeriği AYNEN)
  - `parts/override.css`                             — değişmeden duruyor
- `functions.php` diff YOK (`git status --porcelain` temizlik kanıtı).
- "gururla / Powered / Proudly" içerik taraması: 0 canlı-blok eşleşme;
  2 yasa-hatırlatma yorumu (PHP doc-comment + HTML comment; render edilmez).
- Ana sayfa HTML'ında "gururla" durumu SAHİBİN cPanel deploy sonrası curl ile
  doğrulamalı (bot sunucuda PHP/cUrl yok — P22 ile aynı sınırlama; kodda
  `wp:pattern` footer çağrısı kaldığından pattern render zinciri ortadan kalktı).
- 5 hukuki taslak DOSYA İÇERİĞİ DEĞİŞMEDİ (md5+sha256 `docs/legal-placeholders/*.md`
  tarih öncesi = sonrası; listesi aşağıda).
- `git status -sb`: main...origin/main; yalnız `theme/sutre-child/` değişti
  (AGENTS.md/PRIVACY-DATA-MAP.md docs/architecture değişiklikleri başka bot'lara aittir,
  P22b commit'inde HİÇBİRİ eklenmedi).

## RİSK VE GÜVENLİK

- Çekirdek/DB/secret dokunma YOK; yalnız child-theme dosya ekleme/taşıma.
- Checkout akışı, PayTR, e-mail, KDV, legal metin — hiçbiri değişmedi.
- Checkout.html (block-templates/) dokunulmadı — P17 Login block kuralı korunur.
- ROLLBACK: `git revert <p22b-sha>` + cPanel "Update from Remote" tek elle adım yeterli.

## AÇIK KAPILAR

- **NEEDS_OWNER_INPUT:** Deploy sonrası LINK'te kök-set: cPanel → Git → Update from
  Remote → LiteSpeed Cache → Purge All → ana sayfa curl test.
  Beklenen: `(grep -c 'gururla' <html>) == 0` ve footer'da `sutre-footer` sınıfı + 3 hukuki link.
- **SAHİBİN RIZASI:** Footer remains resmi "© 2026 Sutre — Tüm hakları saklıdır";
  hukuki sayfalar yayınlanınca AYNI linklerin ID'li WP sayfa URL'lerine güncellenmesi
  sahibin elle adımıdır (P22'de not edilmişti; burada değişiklik yok).
- **Sonraki en küçük güvenli adım:** Sahibin staging deploy + curl kanıtını e-posta
  veya panoda paylaşması → P22b kapatılır; yalnız o zaman "gururla" sıfırlanmış
  kabul edilir (anayasa §16'da kanıt sahibinden istenir).
