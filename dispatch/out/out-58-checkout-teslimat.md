# P58 — Checkout "Teslimat Bilgileri" tek-form UX + Fatura opsiyonu + Adres şeması Woo-native · Çıktı Raporu

**Kanal:** coder · **Tarih:** 21-09-2026 · **Format:** Anayasa §16 AI ÇIKTI SÖZLEŞMESİ + Woo kaynak yürüyüşü

## Sonuç

Sahibin kararı birebir: checkout'ta TEK adres formu ("Teslimat Bilgileri"), altında varsayılan işaretli "Faturayı da aynı adrese gönderilsin" checkbox'ı; işaret kalkarsa "Farklı fatura adresi" formu; TC ve Mahalle tüm formlardan kaldırıldı; adres şeması Woo-native key/sıraya alındı. Ver 3.4.17.

### Görev 2 — Adres şeması Woo-native (TC/Mahalle kaldırma)

| Değişiklik | Dosya | Kanıt |
| :--- | :--- | :--- |
| Yeni alan şeması: Ad → Soyad → Firma(ops) → Adres Satırı 1 → Adres Satırı 2(ops) → İlçe/Semt → Posta Kodu → Ülke(TR kilit) → Şehir → Telefon → etiket; key'ler Woo kanonik | `functions.php` `sv56_address_fields()` | Defter öğeleri artık shipping_*/billing_ key'leriyle birebir; eski `name/district/neighborhood/tc` key'leri OKUNMAZ (migrasyon yok, dispatch şartı) |
| İl normalizasyonu korunup 'state' key'ine taşındı: sanitize KOD→İL ADI (hesaplayıcı select'i KOD post'lar), customer uygulaması İL ADI→KOD | `sv56_sanitize_address_data`, `sv56_apply_address_to_customer` | P57 zinciri tek değişen nokta: key adı (eski 'city'=İl → yeni 'state'=İl; yeni 'city'=İlçe serbest metin) |
| Adreslerim kartları: Ad Soyad satırı, TC satırı YOK; özet İlçe→Şehir sırası | `sv56_address_book_content`, `sv56_address_summary` | |
| Hesaplayıcı işleyicisi yeni POST key'leri (sv_addr_first_name/last_name/company/…) + İlçe=calc_shipping_city, İl=calc_shipping_state | `sv56_handle_cart_save_address` | TC/Mahalle POST okuma satırları kaldırıldı; `error_address_tc` notice + `$only_tc` dalları silindi |
| Hesaplayıcı şablonu: girişli dal şema sırası (Ad→Soyad→Firma→Adres1→Adres2→İlçe→Posta→Ülke→İl→Telefon→etiket), **misafir dalı çekirdek sırasıyla birebir** (country→state→city→postcode) | `woocommerce/cart/shipping-calculator.php` | P56 dersi: alan/sıra değişimi yalnız girişli dalda |
| Checkout: `shipping_neighborhood` P57 alanı + mahalle prefill filtresi KALDIRILDI; şema sırası/etiketleri her iki fieldset'e; Firma gönderimde TR locale gizli geldiğinden opsiyonel geri eklendi; billing_phone/billing_company çıkarıldı (şemada yok; telefon aynalamayla gelir) | `functions.php` `sv58_checkout_fields()` | Canlı misafir HTML: data-priority 10/20/30/40/50/60/70/80/90/100 sırası + labels |

### Görev 1 — Checkout tek-form UX

| Değişiklik | Kanıt (Woo 11.1.0 kaynak, zip sha256 6bae9bf74d722b6d…) |
| :--- | :--- |
| `woocommerce/checkout/form-shipping.php` override — girişli: `<h3>Teslimat Bilgileri</h3>` + gönderim alanları (daima görünür) + "Faturayı da aynı adrese gönderilsin" checkbox (`#sv_invoice_same`, default CHECKED, oturum anahtarı `sv_invoice_same` ile kalıcı) + marker hidden `sv_invoice_same_present` + gizli `ship_to_different_address=1` | Çekirdek `maybe_skip_fieldset` (:773): ship_to_different_address POST'lanmazsa gönderim fieldset ATLANIR ve :849-853'te fatura→gönderim kopyalanır → gizli input gönderim setinin daima toplanmasını garantiler |
| `woocommerce/checkout/form-billing.php` override — girişli: "Farklı fatura adresi" başlığı + fatura alanları (Çekirdek "Fatura Bilgileri" h3'ü kaldırıldı); checkbox işaretliyken bölüm CSS `:has()` ile gizli (gizli inputlar POST'a devam eder — POST muhafazası) | Misafir dalı: çekirdek iki-form çıktısı aynen (h3 + ship-to-different checkbox + account fields) — P56 dersi + dispatch "misafir çıktısı çekirdekle tutarlı" kuralı |
| **Sunucu aynalaması:** `woocommerce_checkout_posted_data` filtresi — işaretliyken posted billing alanları shipping'den yazılır (first_name, last_name, address_1/2, city, postcode, state, phone + country + boş billing_email'e user_email yedeği) | Filtre çıktısı (:858) update_session → validate_checkout → create_order zincirine geçer (:1381-1411); create_order adresleri $data argümanından yazar (:435-445); validate aynı veriyle çalışır → gizli fatura alanları hata üretmez. E-posta yedeği gerekli: `get_value`'da billing_email fallback'i YOK (class-wc-customer.php:676-678) |
| Aynalama kuralları: yalnız girişli; işaret KALKTIYSA (marker var, checkbox yok) aynalama YAPILMAZ; formumuz dışından gelen POST'ta güvenlik ağı; gönderim adı boşsa fatura ezilmez | HTML checkbox işaretsiz = POST'ta yok → "işaret kalktı / hiç POST edilmedi" ayrımı marker input ile |
| Ülke alanları tek seçenekli TR select (type=select, options=[TR=>Türkiye]) — şema "TR kilit, readonly"; mağaza ülke ayarına dokunulmadı | Canlı: `<option value="TR" selected='selected'>Türkiye</option>` tek seçenek (öncesi 68/180 ülke) |

### Görev 3 — Adreslerim

Kartlar + form yeni şemayla; varsayılan/ekle/düzenle/sil akışları dokunulmadan korundu (nonce'lu POST işleyicileri aynı). Kaydedilen/varsayılan adres checkout teslimat formuna prefill mekanizması değişmedi (`sv56_apply_address_to_customer` artık Ad/Soyad/Firma'yı da customer gönderim alanlarına yazıyor → checkout Ad/Soyad/Firma alanları da dolu gelir).

### Görev 4 — CSS + metinler

- Yalnız `style.css` P57 checkout bloğu güncellendi: `#customer_details` flex column + `.col-2 { order:-1 }` (gönderim DOM'da sonra geldiği hâlde birincil görünür), `:has(#sv_invoice_same:checked) .col-1 { display:none }` (gizli billing), `.sv-invoice-same-row` checkbox satırı dili. Brace dengesi {442}/{442}.
- Metinler: "Teslimat Bilgileri", "Faturayı da aynı adrese gönderilsin", "Farklı fatura adresi" (theme string, insan-dili); gettext haritasına hesaplayıcı çekirdek etiketleri eklendi (City:→İlçe / Semt, State / County→Şehir, Postcode / ZIP:→Posta Kodu, Country / region→Ülke — bu stringler yalnız hesaplayıcı şablonunda geçer).

## Doğrulama

- **Sözdizimi:** npm php-parser (PHP 8 grammar) — functions.php + shipping-calculator.php + form-billing.php + form-shipping.php **4/4 PASS** (`node check.js`). İlk turda 2 gerçek parse hatası yakalandı ve düzeltildi: override yorumlarındaki `shipping_*/billing_*` yazımı `*/` ile docblock'u erken kapatıyordu.
- **Sözleşme kontrolü:** **33/33 PASS** — şema key'leri (tc/neighborhood YOK), sanitize/apply/summary/matches key taşımaları, kart render, notices temizliği, hesaplayıcı iki dal sırası, checkout_fields (unset'ler + Firma + TR kilit), aynalama (marker/unchecked dalı/e-posta yedeği/boş-gönderim koruması/filtre kaydı), şablon marker'ları, CSS kuralları, ver 3.4.17.
- **FTP deploy (kanal B):** 5 dosya, **5/5 SHA-256 PASS** (functions.php, style.css, shipping-calculator.php, checkout/form-billing.php [YENİ — dizin açıldı], checkout/form-shipping.php [YENİ]).
- **Canlı misafir (staging):**
  - Guest Fatal 0: `/` `/shop/` `/cart/` `/checkout/` → 0 0 0 0.
  - `style.css?ver=3.4.17` yayında; `:has(#sv_invoice_same:checked)` kuralı yayındaki CSS'te.
  - Misafir checkout (sepet dolu): şema sırası data-priority ile kanıtlı (billing: Ad/Soyad/Adres1-2/İlçe/Posta/Ülke/Şehir/E-posta — phone/company YOK; shipping: +Firma(30)+Telefon(100) zorunlu); `shipping_neighborhood`=0, `sv_addr_tc`=0, TC=0; Ülke select'leri tek seçenek TR; misafir dalı çekirdek çıktısı (Fatura Bilgileri h3 + ship-to-different checkbox) aynen; `sv_invoice_same` misafirde YOK (aynalama misafire dokunmaz). HTML'deki 6 "Mahalle" geçişi çekirdek address-i18n JS locale config'indendir (GG/JP ülkeleri) — formumuz değil.
- **FAIL/atlanan (dürüst kayıt):** Giriş-yapmış E2E ve **aynalamanın SİPARİŞ üzerindeki canlı kanıtı** bot girişi olmadığından yapılamadı; sipariş oluşumu ayrıca ödeme gateway'siz (PayTR başvurusu bekliyor) staging'de uçtan uca çalıştırılamaz — bu yüzden aynalama kanıtı kaynak-satır yürüyüşü (:858/:1381-1411/:435-445) + birim davranış sözleşmesi (33/33) + canlı şema/şablon doğrulamasıyla kuruldu. Sipariş kanıtı PayTR kurulum sonrası ilk test siparişinde alınmalı (KULLANICI ADIMLARI 3d).

## Risk ve güvenlik

- Secret/kişisel veri: Secret YOK. TC toplama tamamen durduruldu (veri minimizasyonu kazanımı); eski TC meta'ları silinmedi (yok sayılır — migrasyon yasak). Mahalle order meta'sı artık yeni siparişlerde oluşmaz.
- Ödeme/fiyat/stok etkisi: YOK. Sipariş durum makinesi, callback katmanı, PayTR dokunulmadı. Mağaza ülke/satış ayarı değiştirilmedi (KDV/ülke LEGAL_REVIEW_REQUIRED kapısı açık kaldı) — Ülke kiliti şablon/fields katmanında.
- Riskler: (1) `:has()` CSS'i 2023 öncesi tarayıcılarda çalışmaz → fatura bölümü görünür kalır (aynalama yine çalışır; yanlış veri riski yok, yalnız çift form görünümü). (2) Fatura checkbox durumu oturumda tutulur — kullanıcı işareti kaldırıp ayrı fatura girip yarıda bırakırsa sonraki ziyarette işaretsiz gelir (çekirdek ship_to_different deseniyle aynı). (3) Aynalama `billing_country`'i gönderim ülkesinden yazar; Ülke kilitli TR olduğundan pratikte daima TR. (4) Woo güncellemesi form-billing/form-shipping'i değiştirirse override'lar revize edilmeli (@version 3.6.0 — çekirdek şablonlar 3.6.0'dan beri stabil).
- Geri dönüş: `git revert b61e881 65d42be <docs SHA>` VEYA tema klasörünü `.bak` yedeğine döndür; FTP ile önceki dosyalar geri atılır. Rollback sonrası ek adım gerekmez (yeni endpoint yok, DB şeması yok, rewrite flush gerekmez).

## Açık kapılar

- NEEDS_OWNER_INPUT: YOK.
- OWNER_APPROVAL_REQUIRED: Checkout tek-form görünümü (Teslimat Bilgileri + checkbox + gizli fatura), yeni alan sırası (adreslerim + hesaplayıcı + checkout), TC/Mahalle kaldırma — Salih görsel onayı (AGENTS §0.6; staging'de).
- LEGAL_REVIEW_REQUIRED: YOK (yeni hukuki metin yok; KVKK PLACEHOLDER kapısı P41'de zaten açık; Ülke kiliti vergi ayarına dokunmadığı için KDV kapısı değişmedi).
- Sonraki en küçük güvenli adım: KULLANICI ADIMLARI (purge + giriş-yapmış E2E); PayTR sonrası ilk gerçek siparişte `billing_*` meta'larının `shipping_*` ile eşitliğinin admin'den teyidi.

## KULLANICI ADIMLARI (deploy + E2E — bu tur rewrite flush GEREKMEZ)

1. **LiteSpeed Cache → Purge All** (Hermes FTP deploy + SHA doğrulamasını yaptı; cPanel `git pull` şart değil, ama repo zaten push'lu).
2. **Giriş yapmış tarayıcı E2E:**
   - Sepet → "Adresi değiştirin" → yeni şema sırası (Ad→Soyad→Firma→Adres1→Adres2→İlçe/Semt→Posta→Ülke→Şehir→Telefon→etiket); TC ve Mahalle YOK; Güncelle → "Adres kaydedildi." → Adreslerim'de yeni şemalı kart.
   - Hesabım → Adreslerim → Düzenle/Varsayılan Yap/Sil akışları bozulmadığını teyit.
   - Sepet → "Bu Adrese Gönder" → Checkout: **tek form "Teslimat Bilgileri"** (sepetten gelen adres dolu) + altında **"Faturayı da aynı adrese gönderilsin" İŞARETLİ** ve fatura formu görünmez.
   - İşareti kaldır → "Farklı fatura adresi" formu açılır (Ad/Soyad/Adres/İlçe/Posta/Ülke/Şehir + E-posta); tekrar işaretle → gizlenir.
   - Sipariş Onayla (ödemeye kadar) — hata yoksa form doğrulama sorunu yok demektir; **PayTR sonrası ilk siparişte admin → sipariş → Fatura adresi = Teslimat adresi teyidi (aynalama kanıtı).**
3. **Misafir doğrulama (bot/tarayıcı) — yapılması gerektiği kadarı bot tarafından zaten yapıldı:**
   ```bash
   for p in "" "shop/" "cart/" "checkout/"; do curl -skL "https://staging.sutre.store/$p?v=$(date +%s)" | grep -c 'Fatal error'; done   # 0 0 0 0
   curl -skL "https://staging.sutre.store/checkout/" | grep -c 'shipping_neighborhood'   # 0 (formda yok)
   ```

---

### Woo 11.1.0 kaynak yürüyüşü — aynalama zinciri (satır kanıtları)

1. `WC_Checkout::get_posted_data()` — POST alanlarını toplar; `ship_to_different_address` POST'a bakar (:798); **çıktı `apply_filters('woocommerce_checkout_posted_data', $data)` (:858)**.
2. `process_checkout` — `$posted_data = get_posted_data()` (:1381) → `update_session($posted_data)` (:1385) → `validate_checkout($posted_data, $errors)` (:1396) → `create_order($posted_data)` (:1411). Aynalama üçünü de besler.
3. `create_order($data)` — her `$data` anahtarı için `set_{key}` çağırır (:435-437); billing_/shipping_ önekli kalanlar order meta'ya yazılır (:439-444) → aynalanmış billing siparişe tam yansır.
4. `update_session` — `set_customer_address_fields` billing değerini customer'a da yazar → müşteri oturum tutarlılığı.
5. `maybe_skip_fieldset` (:773) — ship_to_different_address yoksa gönderim fieldset atlanır (:849-853 fatura→gönderim kopyası) → tek-formda gizli `ship_to_different_address=1` zorunlu (form-shipping override'ında mevcut).
6. `get_value` (:1480-1522) — e-posta için hesap fallback'i YOK → aynalamada `user_email` yedeği eklendi.

**Commit'ler:** `b61e881` (Görev 2 — şema), `65d42be` (Görev 1 — checkout tek-form), `<docs>` (bu rapor + AGENTS kaydı). Hepsi `main`e push'lu.
