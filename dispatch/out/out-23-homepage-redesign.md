# PAKET 23 ÇIKTI RAPORU — Ana Sayfa İçerik Dolumu + Kategori Bölümü Redesign

Tarih: 2026-09-17 · Bot: @coder+birleşik (designer @coder) · Anayasa §16 format.

## SONUÇ

Ana sayfa (`staging.sutre.store/`) yeniden yapılandırıldı; eski
`sutre-cats` tablo-breadcrumb bölümü ATILDI, yerine yeni section'lar geldi:

1. **Hero** (mevcut korundu): "Sutre" wordmark + tagline + "Koleksiyonu Keşfet" CTA → /shop/.
2. **`<section class="sutre-story">`** (yeni): "Hikâyemiz" — İstanbul 2026 kuruluş,
   ipek/pamuk/bambu, el işçiliği + deniz metaforu (draft metin, sahibin onayına açık).
3. **`<section class="sutre-collection">`** (BAŞTAN redesign):
   3 kolon koleksiyon kartları (mobil 1 kolon), her kartta büyük serial no
   (01/02/03), İpek/Pamuk/Bambu adları, kısa açıklama + "Keşfet →" mikro-CTA
   ≥44px dokunma. Beyaz kart + 1px rgba(Ink,.08) kenar; hover'da kart yükselme
   (-3px, 200ms) + silk (C9A66B var(--sutre-silk)) alt-çizgi scaleX animasyonu;
   dilvin.com.tr'ye benzemez — farklı yapı (numaralı kart grid'i).
   "Tümünü Gör" → /shop/. Sabit tanım `home.php` $collections (sahip talebi).
4. **`<section class="sutre-featured">`** (yeni): WooCommerce resmi
   `do_shortcode('[products limit="2" columns="2" visibility="featured"]')` —
   çekirdek dokunuş yok, public API (§3.1 uyumlu). İman Nour/Jakarlı ürünler sahibin
   WooCommerce → Ürünler → "Öne çıkan" işaretlemesine bağlı (elle adım).
5. **`<section class="sutre-values">`** (yeni): Doğal İpek / El Dokuması /
   14 Gün Cayma (iade koşulları mesafeli satış sayfasına yönlendirme; DRAFT,
   LEGAL_REVIEW_REQUIRED işaretli — rakam koymadan yönlendirme cümlesi).

## DOĞRULAMA (kanıt)

- Dosyalar:
  - `theme/sutre-child/page-templates/home.php`  — tam yeniden yazım (5 section + do_blocks part render aynı)
  - `theme/sutre-child/parts/override.css`       — +205 satır P23 bloğu (story/collection/featured/values/mq/reduced-motion)
- Commit: **`813a5c0`** — `feat(theme): P23 ana sayfa icerik dolumu + koleksiyon kart redesign`
- Push kanıtı: `To github.com:salihsungur/sutre.git  68c4537..813a5c0  main -> main`
- PHP parantez/süslü denge kontrol: 18 PHP bloğu — balance OK (bot makinesinde `php` CLI yok;
  `php -l` —in için sahibin cPanel'de `php -l` veya sayfa yükleyerek WP debug boş kontrolü).
- Style lint: custom property'ler (var(--sutre-*)) — hardcode hex yok (rgba() yalnız
  kenar/gölge; mevcut main.css diline birebir uyum).

## RİSK VE GÜVENLİK

- Yalnız child-theme dosya ekleme/değişiklik; çekirdek/DB/secret YOK.
- `home.php` header/footer render mantığı (do_blocks) DEĞİŞMEDİ — tek kaynak korundu.
- Pazarlama formu, abonelik, PayTR, analitik — hiçbiri eklenmedi (anayasa §6.2/§10.1 uyumlu).
- Anayasa §15 ihlali YOK: çıktı escape'li (esc_html/esc_url), iş mantığı yok,
  "iade" metni koşullara önerme ile sınırlandı (KDV/garanti/cayma tahmini yok).

## AÇIK KAPILAR

- NEEDS_OWNER_INPUT (sahibin elle): cPanel → Git Version Control "Update from Remote"
  (pull-only deploy, ADR-002); staging docroot'a `git pull origin main` sonrası cache purge.
  Öne çıkan ürünler için WooCommerce'де ürünleri "featured" işaretleme.
- OWNER_APPROVAL_REQUIRED: marka hikâyesi metni ve koleksiyon kartları adları (İpek/Pamuk/Bambu
  sabit tanım) — sahibin dil onayı.
- LEGAL_REVIEW_REQUIRED: "Neden Sutre" bölümündeki cayma hakkı metni Yapılari
  (rakamı sadece mesafeli satış paneline yönlendirme; hukuk onayı sonrası güncellenir).
- Sonraki en küçük güvenli adım: sahibin elle cPanel "Update from Remote" +
  WP debug log boş kontrol → Playwriter/elle 375px mobil kontrol → PASS ise P24.
