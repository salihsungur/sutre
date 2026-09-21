# P57 — Adres defteri POST akış bug fix'leri + Mahalle sırası + TC + checkout klasikleştirme · Çıktı Raporu

**Kanal:** coder · **Tarih:** 21-09-2026 · **Format:** Anayasa §16 AI ÇIKTI SÖZLEŞMESİ + POST yaşam döngüsü yürüyüşü

## Sonuç

Yöntem notu: bot girişi olmadığı için (dispatch uyarısı) fix'ler KÖR denemeyle değil, **kaynak-kod yürüyüşü + canlı misafir deneyi + 32 maddelik sözleşme kontrolü** ile kuruldu. Kanıt zinciri aşağıda adım adım.

### Bug 1 — Adreslerim "Kaydet" (KÖK NEDEN KAYNAK KODLA KESİN)

| Yürüyüş adımı | Kanıt | Sonuç |
| :--- | :--- | :--- |
| (1) Form action URL | `sv56_address_book_content` → `sv41_myaccount_url('adreslerim')` → `wc_get_endpoint_url` (functions.php:945 civarı) | DOĞRU: POST `/my-account/adreslerim/`'e gider; sayfa render olduğu için rewrite flush yapılmış |
| (2) Submit `name` ↔ tetikleyici | Buton isimsiz `type=submit`; tetikleyici HIDDEN `sv_form=address_save` (formda var) + işleyici `sv41_param('sv_form','post')` | DOĞRU (tetikleyici hidden input; buton name gerekmez) |
| (3) Nonce alan/ad | `wp_nonce_field('sv_address_save','sv_nonce')` ↔ `wp_verify_nonce(sv41_param('sv_nonce','post'), 'sv_address_save')` | DOĞRU çift |
| (4) Hook yaşam döngüsü | `add_action('template_redirect', 'sv56_handle_account_address_forms')` (öncelik 10, WP'nin `wp_loaded`→`wp`→`template_redirect` zincirinde; Woo'nun kendi `WC_Form_Handler::save_address` da 10'da ama `action=edit_address` olmadığından erken döner — çakışma yok) | DOĞRU |
| **(5) İşleyici dalına giriş** | **HATA BURADA:** guard `is_wc_endpoint_url('adreslerim')` — Woo 11.1.0 kaynak `wc-conditional-functions.php:166-175`: endpoint adı ÖNCE `WC()->query->get_query_vars()`'ta aranır, **orada yoksa DAİMA false**; `add_rewrite_endpoint` ile kaydedilen özel endpoint o listede YOK → işleyici HİÇ çalışmıyordu: sessiz fall-through → kayıt YOK, redirect YOK, notice YOK. Sahibin raporuyla birebir ("Adreslerim'e dönüyor, kaydolmuyor": POST sonrası `?duzenle` parametresi POST URL'sinde olmadığından grid görünümü tekrar basılıyordu) | **KÖK NEDEN** |
| (6) `sv56_addresses` okuma ↔ save yazma meta key | İkisi de `sv_address_book` (functions.php:653 / 759) | DOĞRU (meta key uyumlu; bug bu değildi) |
| Neden sayfa yine de görünüyor? | `woocommerce_account_content()` (wc-template-functions.php:3795-3808) içerik basımını `$wp->query_vars` + `has_action('woocommerce_account_..._endpoint')` ile yapar — özel endpoint'ler de basılır. Görüntü kapısı ≠ POST guard kapısı | Kandırmaca açıklandı |

**FIX:** guard → `array_key_exists('adreslerim', $wp->query_vars)` (çekirdeğin kendi dispatch mekanizmasıyla birebir). **BONUS BULGU — AYNI sessiz bug P41'de de vardı:** `sv41_handle_account_forms` case 'prefs' aynı `is_wc_endpoint_url('iletisim-tercihleri')` guard'ını kullanıyordu → İletişim Tercihleri formu da hiç kaydetmiyordu (raporlanmamıştı). O da aynı fix ile kapatıldı.

### Bug 2 — Sepet "Güncelle" (pipeline canlı deneyiyle sağlam; sessiz kapılar kapatıldı)

Canlı misafir deneyi (staging, gerçek POST — curl çerez oturumuyla):
1. `GET /product/jakarli-sal/` → varyasyon id 30 (Siyah) doğrulandı.
2. Klasik form POST `add-to-cart=29&attribute_renk=Siyah&variation_id=30` → HTTP 200, sepet doldu.
3. `GET /cart/` → P47 klasik sepet + P56 şablon override canlı (form `action=https://staging.sutre.store/cart/`, nonce alanı `woocommerce-shipping-calculator-nonce` = 26d30420f9, buton `name="calc_shipping" value="1"`).
4. **calc POST** (`calc_shipping_country=TR&calc_shipping_city=Bornova&calc_shipping_postcode=35030&calc_shipping=1&nonce...`) → HTTP 200; ardından `GET /cart/`: `calculated_shipping` class VAR ve `calc_shipping_city` değeri = **Bornova** → **Woo'nun calc POST akışı LiteSpeed arkasında çalışıyor ve customer oturumu kalıcı yazıyor.**
5. HTML yapı: 2 form (cart-form 98429→102062 kapanır, calculator 103022→117526) → **iç içe form YOK** (browser'ın iç form'u düşürmesi elendi); LiteSpeed `x-litespeed-cache: miss` (cart cache'lenmiyor; bayat nonce'tan kaynaklı sistemik sessizlik olasılığı düşük ama tek sessiz kapı nonce-fail).

Kalan sessiz kapılar (rapor senaryosunu en makul açıklayanlar) FIX'le GÖRÜNÜR hale getirildi:
- **Nonce fail → artık görünür error notice** (önce `return;` — bayat sekme/cache'li sayfada "Güncelle hiçbir şey yapmıyor, notice yok" birebir bu senaryo; Woo'nun kendi calc kapısı da aynı POST'ta sessizce atlıyor).
- **İki checkbox işaretsiz → artık görünür `address_applied` onayı** ("Teslimat adresi güncellendi.") — buton asla ölü hissettirmesin.
- Başarılı kayıtta `address_saved` success notice mevcut (dispatch şartı).

### P56 kanısının canlı HTML ile düzeltilmesi — İl KOD normalizasyonu

Canlı `/cart/`: İl alanı **82 seçenekli SELECT** (TR01 Adana … TR81) ve post değeri KOD'dur. P56 raporundaki "TR state listesi YOK → text input" kanısı YANLIŞTI. Bu, iki somut sorun doğuruyordu: defterde Şehir="TR34" gibi kod yazılması ve seçici ön-seçim eşleşmesinin (kod↔ad) hiç tutmaması. FIX (tek kaynak = çalışma anı `WC()->countries->get_states('TR')`):
- `sv56_state_list()` + `sv56_state_name_to_code()` + `sv56_state_code_to_name()`
- Defter sanitize'da İl KODU → İL ADI ('TR34' → 'İstanbul'); customer uygulamasında İL ADI → Woo kanonik KODU; `sv56_address_matches_customer` iki tarafı İL ADI üzerinden karşılaştırır.

### Görev 3 — TC alanı (sepet hesaplayıcı)

`sv_addr_tc` input (inputmode numeric, maxlength 11, opsiyonel) + işleyiciye dahil + sanitize mevcut `^\d{11}$` kuralı. Checkout'a TC EKLENMEDİ (sahip kararı: "checkout'u abartma").

### Görev 4 — Alan sırası (iki form)

Hesap formu (adreslerim) `sv56_address_fields()` sırasıyla otomatik: **İsim Soyisim → Telefon → Adres 1 → Adres 2 → Ülke (TR kilit, readonly) → Şehir → İlçe → Mahalle/Köy → Posta Kodu → TC → Adres Etiketi → Kaydet**. Sepet hesaplayıcı şablonu aynı akış: sv isim/telefon/adres 1-2 → Ülke (select) → Şehir (İl select) → İlçe → **Mahalle/Köy** (İlçe'den hemen sonra) → Posta → TC → etiket/varsayılan/kaydet → Güncelle. Misafir dalı çekirdek sırasını korur (country→state→city→postcode) — P56'nın "misafirde birebir" sözü bozulmaz. Mahalle prefill: defterde seansla eşleşen kayıt > varsayılan kayıt.

### Görev 5 — Checkout yenileme

- **Klasik zorlama:** `the_content:20` filtresi — `is_checkout() && ! has_shortcode(...)` → `do_shortcode('[woocommerce_checkout]')` (P47 cart deseni). POST güvenliği kanıtlı: `WC_Form_Handler::checkout_action` wp_loaded'ta `WC()->checkout()->process_checkout()` çağırır (class-wc-form-handler.php:476) — render'dan bağımsız; order-pay/order-received uçları `WC_Shortcode_Checkout::output` (kaynak:36-60) içinde aynı shortcode'tan işlenir.
- **P57 CSS** (yalnız style.css): `form.checkout` grid-areas ile 2 sütun — sol: Fatura + Gönderim kartları (col-1/col-2 float reset), sağ: "Sipariş Özeti" başlığı + `#order_review` kartı; ≤781px tek sütun. Giriş dili HESABIM bloğundan (serif h3, uppercase etiket, bordered input/select + select2 dili, checkbox accent). `#place_order` ink dolu.
- **gettext:** 'Billing details' → Fatura Bilgileri, 'Your order' → Sipariş Özeti, 'Ship to a different address?' → 'Farklı bir adrese gönderilsin mi?', 'Place order' → Siparişi Onayla, 'Order notes' → Sipariş Notu, 'Have a coupon?', 'Create an account?', 'Update totals', 'You must be logged in to checkout.', 'Billing &amp; Shipping'. **'Additional information' bağlam-duyarlı** yapıldı: checkout'ta 'Sipariş Notu', ürün sekmesinde 'Ürün Bilgileri' (P52 kazancı korunur) — klasikleştirme bu çakışmayı doğuracaktı, önceden yakalandı.
- **Mahalle alanı (checkout):** `woocommerce_checkout_fields` filtresiyle `shipping_neighborhood` (opsiyonel, priority 75 → İlçe city:70'den sonra, Posta postcode:80'den önce; label 'Mahalle / Köy'; autocomplete address-level3). **Order meta kanıtı (Woo 11.1.0):** `WC_Checkout::create_order()` shipping_/billing_ önekli tüm checkout alanlarını `_{key}` order meta'sına yazar (class-wc-checkout.php:438-444; hariç tutma listesi 29-33'te neighborhood yok) → siparişte `_shipping_neighborhood`. **Prefill:** `woocommerce_checkout_get_value` filtresi (get_value:1487 kısa-devre kapısı) — defterde seansın gönderim adresiyle birebir eşleşen kayıt > varsayılan kaydın mahallesi; POST dönüşünde gönderilen değer korunur (get_value:1482).
- Adres defteri ön-dolumu (ad/telefon/adres/İl/İlçe/Posta) customer üzerinden zaten gelir (P56); ilave kod gerekmedi.

### Değiştirilen dosyalar

- `theme/sutre-child-v2/functions.php` — guard fix'leri (adreslerim + prefs), `sv56_state_list/name_to_code/code_to_name`, alan şeması sırası, sanitize/apply/matches İl normalizasyonu, sepet işleyici görünür-dal fix'leri + TC, gettext P57, checkout klasik zorlama, `shipping_neighborhood` + prefill; SUTRE_VERSION 3.4.15.
- `theme/sutre-child-v2/woocommerce/cart/shipping-calculator.php` — P57 alan sırası + TC + Mahalle prefill (misafir dalı çekirdekle birebir).
- `theme/sutre-child-v2/style.css` — yalnız P57 CHECKOUT bloğu (hesaplayıcı/sepet/adreslerim bloklarına dokunulmadı).
- `AGENTS.md` — §7 P57 kaydı + §8 P57 dersleri.
- Veritabanı/ayar etkisi: YOK (yeni veri yalnız `_shipping_neighborhood` order meta'sı — siparişle oluşur). Çekirdek/eklenti değişikliği YOK; header/footer dokunulmadı; yeni rewrite endpoint YOK (flush gerekmez).

## Doğrulama

- **Sözdizimi:** npm php-parser (PHP 8 grammar) — functions.php + shipping-calculator.php + dashboard.php **3/3 PASS** (`node check.js`); CSS brace dengesi **{437}/{437}**.
- **Sözleşme kontrolü:** **32/32 PASS** — eski guard kalıntısı yok; query_vars guard'ları var; şema sırası (11 alan); nonce çiftleri (sv_address_save/default/delete/apply + woocommerce-shipping-calculator); nonce-fail/unchecked dalları görünür notice'lı; hesaplayıcı alan sırası (14 konum) + TC; İl normalizasyonu 3 noktada + `get_states('TR')` kaynağı; checkout klasik zorlama + shipping_neighborhood (priority 75) + prefill + checkout'ta TC YOK; gettext bağlam kuralı; ver 3.4.15; hesaplayıcı CSS'i korunmuş.
- **Canlı misafir deneyi (öncesi):** calc POST pipeline çalışıyor (city=Bornova kalıcı); iç içe form yok; cart cache'lenmiyor; `imunify-bot-check` curl POST'u engellemiyor.
- **Woo 11.1.0 kaynak kanıtları** (zip sha256 6bae9bf74d722b6d…, downloads.wordpress.org): yukarıdaki adım tablosunda dosya:satır verildi.
- **FAIL/atlanan:** Giriş-yapmış E2E (kaydet/güncelle/varsayılan/checkout prefill) bot girişi olmadığından yapılamadı — KULLANICI ADIMLARI'da tarayıcı adımları verildi. Bu tur "push + FTP deploy + misafir doğrulama"ya kadar.

## Risk ve güvenlik

- Secret/kişisel veri: Secret YOK. TC yine opsiyonel, 11 rakam regex'li, log/rapora yazılmaz; checkout'a TC eklendi YOK (sahip kararı — veri minimizasyonu). Mahalle, siparişle `_shipping_neighborhood` order meta'sına yazılır (kullanıcının kendi gönderimi).
- Ödeme/fiyat/stok/fatura etkisi: YOK. Sipariş durum makinesine dokunulmadı; PayTR katmanına dokunulmadı.
- Riskler: (1) Checkout klasik zorlaması — Woo 11'de blok sayfalarda shortcode desteği resmi değil ama P47 cart'ta aynı desen canlı kanıtlı; POST akışı render'dan bağımsız olduğundan sipariş oluşumu etkilenmez. (2) İl normalizasyonu `get_states('TR')` boş dönerse (filtreyi sağlayan katman kalkarsa) ham değerle çalışır — kırılgan değil. (3) Gettext 'Additional information' bağlam kuralı `is_checkout()`'a bağlı; checkout dışı yeni bir yüzeyde aynı string çıkarsa 'Ürün Bilgileri' basılır (mevcut davranış).
- Geri dönüş: `git revert <P57 SHA'ları>` (aşağıda) VEYA tema klasörünü `cp -a` yedeğine döndür; override dosyası silinince çekirdek hesaplayıcı şablonu otomatik devreye girer. Rollback sonrası ek adım gerekmez (yeni endpoint yok, DB şeması yok).

## Açık kapılar

- NEEDS_OWNER_INPUT: YOK.
- OWNER_APPROVAL_REQUIRED: Checkout klasik görünümü + hesaplayıcı yeni alan sırası/TC alanı — Salih görsel onayı (AGENTS §0.6; staging'de).
- LEGAL_REVIEW_REQUIRED: YOK (yeni hukuki metin yok; KVKK PLACEHOLDER kapısı P41'de zaten açık).
- Sonraki en küçük güvenli adım: Aşağıdaki KULLANICI ADIMLARI (deploy + purge + giriş-yapmış E2E).

## KULLANICI ADIMLARI (deploy + E2E — bu tur rewrite flush GEREKMEZ)

1. **cPanel Terminal** (deploy kanal A): `cd ~/repositories/sutre && git pull origin main` → `cp -a ~/repositories/sutre/theme/sutre-child-v2 ~/staging.sutre.store/wp-content/themes/` (önce `.bak` yedeği). (Hermes kanal B ile FTP deploy + SHA doğrulamasını zaten yaptı — adım 1'e gerek kalmadıysa sadece purge.)
2. **LiteSpeed Cache → Purge All.**
3. **Giriş yapmış tarayıcı E2E:**
   - Hesabım → Adreslerim → Yeni Adres Ekle → doldur → Kaydet → **"Adres kaydedildi."** notice'ı + kart grid'de yeni kart.
   - İletişim Tercihleri → toggle → Kaydet → "İletişim tercihleriniz kaydedildi." (P41 sessiz bug'ının fix teyidi).
   - Sepet → hesaplayıcıyı aç → İl'den **İl seç** (kod select'i) → İlçe/Mahalle/Posta/TC/etiket doldur → Güncelle → **"Adres kaydedildi."** notice'ı; Adreslerim'de **İl adı insan-okur** ('İstanbul', 'TR34' değil).
   - Sepet → "Bu Adrese Gönder" → "Teslimat adresi güncellendi." + seçici ön-seçimi o kartta.
   - Checkout → klasik form + P57 tasarım; gönderim alanlarında Mahalle defterden dolu; Sipariş Özeti sağda.
4. **Misafir doğrulama (bot/tarayıcı):**
   ```bash
   for p in "" "shop/" "cart/"; do curl -skL "https://staging.sutre.store/$p?v=$(date +%s)" | grep -c 'Fatal error'; done   # 0 0 0 OLMALI
   curl -skL "https://staging.sutre.store/checkout/" | grep -c 'wp-block-woocommerce-checkout'   # 0'a düşmeli (blok → klasik)
   ```

---

### POST yaşam döngüsü yürüyüşü — paketin istediği 6 kapının kapanış özeti

1. **Form action URL doğru mu?** EVET — hesap formu `sv41_myaccount_url('adreslerim')`, hesaplayıcı `wc_get_cart_url()` (canlı HTML'de teyitli).
2. **Submit `name` ↔ tetikleyici aynı mı?** EVET — adreslerim: hidden `sv_form=address_save`; sepet: buton `name="calc_shipping"` ↔ `$_POST['calc_shipping']` (canlı HTML'de teyitli).
3. **Nonce alan/ad eşleşiyor mu?** EVET — tüm çiftler B2/C1 kontrolü; tek sorun guard arkasındaki sessiz nonce-fail'di (fix: görünür notice).
4. **Hook Woo yaşam döngüsünde tetikleniyor mu?** template_redirect (adreslerim) ve wp_loaded:30 (sepet) sıralamaları kaynakla doğrulandı; Woo çakışan handler'ları (`save_address:10`, calc render-anında) erken dönüşle eleniyor.
5. **İşleyici dalı girip save çağırıyor mu?** Bug 1'de HAYIRDI (is_wc_endpoint_url guard) → FIX sonrası EVET (dal, guard'ın hemen arkasında; dal içindeki save çağrısı functions.php case 'address_save').
6. **Okunan meta key = yazılan key mi?** EVET — `sv_address_book` (okuma functions.php:653, yazım :759) + `sv_default_address`.
