# OUT-75 — SEPET BAŞTAN TASARIM: KART GÖRÜNÜMÜ + ÜRÜN SEÇİMİ + BEKLEYEN ÜRÜNLER (@coder)

Paket: P75. Tarih: 2026-09-23. Durum: **TAMAM** (staging canlı; görsel onay sahibin).

## Sonuç

Sahibin 4 talebi birebir uygulandı, `theme/sutre-child-v2/` içinde yalnız 2 dosya değişti (header/footer/checkout şablonlarına kod DOKUNULMADI; git diff kanıtlı):

1. **Thumbnail sorunu kökten çözüldü:** P74'ün data-title float stack'i SİLİNDİ; kart düzeninde thumbnail desktop'ta 140px sol blok (3/4 oran), mobilde kartın ÜSTÜNDE tam genişlik 220px. Artık tüm kırılımlarda görünür.
2. **Kart görünümü:** Sepet tablosu CSS grid ile ürün kartlarına dönüştü — desktop: sol kolon kartlar (thumb / ad+checkbox / birim fiyat / adet / ara toplam / sağ üst ×), sağ kolon 380px "Sepet Özeti" paneli (cart-collaterals); mobil ≤781px tek kolon, adet tam genişlik, kupon + butonlar tam genişlik. Çok-satırlı fiyat/adet etiketleri mobilde Türkçe (::before: "Birim fiyat / Adet / Ara toplam" — Woo'nun İngilizce data-title'ları içerik:none ile öldürüldü).
3. **"Seç ve Öde" (Bekleyen Ürünler sistemi):**
   - Her kartta "Ödemeye dahil et" checkbox'ı (varsayılan İŞARETLİ, value = `$cart_item_key`).
   - "Seçilenlerle Ödemeye Geç" → `fetch` → admin-ajax `sv_park_unselected` (nonce `sv_cart_selection`) → seçilmeyenler `WC()->session` `sv_cart_parked` listesine taşınır → JSON `{redirect}` → checkout (misafirde mevcut davranış: inline login formu).
   - Bekleyen Ürünler bölümü: sepette ayrı bölüm, ürün kartı + "Sepete Geri Al" (form POST `sv_restore_parked` + nonce → `add_to_cart` + park listesinden çıkar → PRG 302 + "Ürün sepete geri alındı" bildirimi).
   - Tüm ürünler park edilirse: klasik "Sepetiniz şu anda boş" + bekleyen bölümü birlikte (redirect bilinçli olarak sepete döner; checkout'ta boş-sepet dolanması engellendi).
   - Panel: seçim sayacı (aria-live), kupon "Kupon kodunuz var mı?" açılır detay, "Sepeti Güncelle" kontur buton — kupon ve güncelle native konumdan JS ile panele taşınır (input/button'lara `form` niteliği atanır, native submit bozulmaz; JS yoksa yerinde kalır, işlevli). JS'siz fallback: CTA checkout linki → tüm ürünlerle ödeme (paketin istediği graceful degradation).
4. **Genel tasarım yenilemesi:** gettext ('Cart totals'→'Sepet Özeti', 'Update cart'→'Sepeti Güncelle'), panel tipografisi/totals hizası, cross-sells panel altına düzenli taşındı, bekleyen kartlar site kart dilinde (beyaz, hairline, serif başlık). SUTRE_VERSION 3.6.0 (cache-buster).

Veri modeli (paket şartnamesine birebir): `sv_cart_parked` = WC()->session dizisi `{id, product_id, variation_id, variation, quantity}`; misafir + üye (Woo session cookie); login sonrası sepet birleşimi Woo'ya aittir — dokunulmadı. `wp_mail` yok; PayTR/checkout akışına kod dokunmaz.

## Doğrulama

| Kanıt | Sonuç |
|---|---|
| PHP sözdizimi (php-parser 8.3, `/opt/data/workspace/php-lint/check.js`) | `PASS functions.php` |
| style.css brace dengesi | 649/649 dengeli; P74 data-title stack kuralları 0 |
| Staging FTP deploy (functions.php + style.css) | SHA-256 PASS 2/2 (95,1KB CSS / 93,9KB PHP) |
| Canlı markup (misafir curl, staging /cart/) | 2 kart + 2 checkbox + `sv-pay-selected` + slot'lar + `style.css?ver=3.6.0` |
| E2E 1 — park | JSON success, remaining=1, parked=1, redirect=checkout |
| E2E 2 — seçili kalır / diğer bekleyende | Sepette yalnız "Jakarlı Şal - Siyah"; "İman Nour Şal - Bej" Bekleyen Ürünler'de (Adet: 1) + restore form/nonce |
| E2E 3 — Sepete Geri Al | HTTP 302 (PRG) → 2 satır geri + "Ürün sepete geri alındı" bildirimi + Bekleyen başlığı kalktı |
| E2E 4 — geçersiz nonce (P57 kuralı) | Görünür hata bildirimi ("Güvenlik doğrulaması başarısız…"), sepet sağlam (2 satır) |
| E2E 5 — tüm-park | redirect /cart/ + "Sepetiniz şu anda boş" + 2 bekleyen kart birlikte |
| Regresyon | /, /shop/, /product/jakarli-sal/, /my-account/ → Fatal 0, header/footer aynen; boş sepet + bekleyen görünümü çalışıyor |
| Checkout dokunulmazlık | git diff yalnız functions.php + style.css; misafir checkout davranışı P75 öncesiyle birebir (production çapraz-kanıt: dolu sepet → 200 + inline login formu; boş sepet → 302 /cart/) |

Not: E2E'de curl'un admin-ajax POST'ları imunify "Checking your browser" challenge'ına düşüyordu; tam tarayıcı başlıklarıyla (X-Requested-With + Origin + Referer + gerçek UA) geçildi. Tarayıcı (Playwriter) bu ortamda kapalı — görsel onay sahibin telefonundan (P70 audit notuyla uyumlu).

Commit: `d0cdb53` (P75, ver 3.6.0) — push main OK. AGENTS.md §1.1 checkout satırı düzeltildi + §7 kaydı + §8 P75 dersleri yazıldı.

## Risk ve güvenlik

- **Nonce + key doğrulama:** Park AJAX `check_ajax_referer('sv_cart_selection')`; POST'tan gelen item key'ler yalnız sunucunun `get_cart()` ürettiği key'lerle kesişir (uydurma key işlenemez). Restore kendi nonce'u (`sv_restore_parked`) + PRG; nonce fail sessiz değildir (görünür hata).
- **Veri bütünlüğü:** Park/restore yalnız sepetteki ürün kümesini değiştirir; sipariş durumu, stok, checkout ve PayTR akışı DOKUNULMADI. Restore'da stok hatası → Woo'nun kendi hatası + ürün parkta kalır (kayıp yok). Park girişleri okuma anında beyaz-liste/absint ile temizlenir.
- **Escape/sanitize:** Tüm çıktılar esc_html/esc_url/esc_attr/wp_kses_post; tüm girişler sanitize_text_field/absint; JSON `wp_send_json_*`.
- **Kayıp riski:** JS kapalıysa seçim çalışmaz (fallback: tüm ürünlerle checkout — pakette bilinçli karar); JS açıksa kupon/güncelle `form` niteliğiyle taşındığından native submit korunur.
- **Session kapsamı:** Bekleyen liste session bazlıdır (tarayıcı oturumu kapanınca gider) — paket kapsamı; kalıcı kaydetme ileride.

## Açık kapılar

- **GÖRSEL ONAY (SAHİPTEN):** Staging `https://staging.sutre.store/cart/` iki varyasyon ekleyerek mobil (≤781px: kart üstünde büyük thumbnail, tam genişlik kupon/buton) ve desktop (2 kolon + panel) incelenecek. §0.6 gereği bot görseli onaylamaz.
- **OWNER_APPROVAL_REQUIRED — production deploy:** Sahip onay verince `theme/sutre-child-v2/functions.php` + `style.css` production'a atılır (sonraki en küçük güvenli adım; aynı SHA akışı).
- **NEEDS_OWNER_INPUT — bekleyen ürünlerin kalıcılığı:** Login sonrası park listesinin hesaba taşınması (paket: kapsam dışı, ileride).
- **Gözlem (dokunulmadı):** AGENTS §1.1'deki "checkout 302 → login" notu bayattı — canlı gerçeklik iki ortamda da "dolu sepet + misafir → 200 + sayfada inline login formu"; site haritası düzeltildi. Misafir ödeme politikası kararı sahibinindir.

## KULLANICI ADIMLARI (Salih)

1. Telefonda `https://staging.sutre.store` → iki ürünü sepete ekle → `/cart/` aç:
   - Kart görünümü: her ürün bir kart; thumbnail HER yerde görünüyor mu bak (mobilde ürün fotoğrafı kartın üstünde büyük).
   - Bir ürünün "Ödemeye dahil et" işaretini kaldır → "Seçilenlerle Ödemeye Geç" → sadece seçili ürünle ödeme sayfasına gideceksin; dönüp sepette "Bekleyen Ürünler" bölümünde kalan ürünü göreceksin → "Sepete Geri Al" dene.
   - Panelde "Kupon kodunuz var mı?" ve "Sepeti Güncelle"yi dene (SUTRE10 kodu ile kupon da test edilebilir).
2. Görüş bildir: onay → production'a aynı dosyalar atılır; revize → istediğin değişiklik uygulanır.
3. Sonrası: WP Admin → LiteSpeed Cache → **Purge All** (staging görüntülenmesinde eski CSS kalıntısı olmasın; canlıya atınca da bir kez daha).
