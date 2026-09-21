# P56 — Adres Defteri sistemi · Çıktı Raporu

**Kanal:** coder · **Tarih:** 21-09-2026 · **Format:** Anayasa §16 AI ÇIKTI SÖZLEŞMESİ + KULLANICI ADIMLARI

## Sonuç

- Tamamlanan hedef (adres ömrü döngüsü baştan kuruldu):
  - **Veri modeli (DB şeması YOK):** `sv_address_book` (user meta, dizi) — her öğe sahibin alan şeması SIRASIYLA: `label` (zorunlu), `name` (zorunlu), `phone` (zorunlu), `address_1` (zorunlu), `address_2` (opsiyonel), `country` (SABİT 'TR'), `city` (zorunlu), `district` (zorunlu), `neighborhood` (zorunlu), `postcode` (zorunlu), `tc` (opsiyonel; verildiyse `^\d{11}$`) + `id` (`sva_<uniqid>_<rand>`) + `created` (time). `sv_default_address` (user meta, string id) — saklanan değer defterde yoksa **en eski kayıt otomatik varsayılan** olur ve meta kalıcılaşır; defter boşsa meta silinir.
  - **Hesabım entegrasyonu:** `/my-account/adreslerim/` endpoint'i (`add_rewrite_endpoint('adreslerim', EP_ROOT|EP_PAGES)` + `woocommerce_account_adreslerim_endpoint` content callback). Nav'da `edit-address` çıkarıldı, yerine `adreslerim` → **"Adreslerim"** (P41-P50 filtresi aynı closure'da güncellendi; key §5a-b — `wc_get_account_menu_items` Woo 11.1.0 kaynak kodundan doğrulandı). Sayfa: adres kartları (etiket serif başlık, isim · telefon, adres özeti, "Varsayılan" silk rozeti) + aksiyonlar **Düzenle** (link `?duzenle=id`), **Varsayılan Yap** (nonce'lu POST + customer shipping'e anında uygulanır), **Sil** (iki adımlı, JS'siz onay: `?sil=id` → onay kartı → "Evet, sil" nonce'lu POST). Yeni/Düzenle formu aynı endpoint'te `?duzenle=yeni|id` (alanlar şema sırasıyla; Ülke alanı readonly "Türkiye" + hidden TR; TC inputmode numeric maxlength 11). Tüm POST'lar PRG + `sv41_notices` beyaz listesi (yeni anahtarlar: address_saved/updated/deleted/default, address_applied, error_address, error_address_tc, error_address_label, error_address_notfound). `edit-address` endpoint'i 302 → adreslerim (`template_redirect:30`; Woo `save_address` öncelik 10'da POST'u ÖNCE işler — kayıt kırılmaz, P50 edit-account deseni). Dashboard'daki "adreslerinizi" linki adreslerim'e güncellendi.
  - **Sepet entegrasyonu:** (1) **Kayıtlı adres seçici** — `woocommerce_before_shipping_calculator` (Woo 9.7.0 şablonunda bu hook `<form>` DIŞINA basar → hesaplayıcı üstünde GÖRÜNÜR konum; kaynak kodla doğrulandı): radio listesi (etiket + kısa adres; ön-seçim = seansın seçili gönderim adresiyle birebir eşleşen kayıt, yoksa varsayılan) + "Bu Adrese Gönder" POST (nonce `sv_address_apply`) → seçilen adres `WC()->customer` shipping alanlarına yazılır (**İl→state, İlçe→city** — Woo TR locale, kaynak kodla doğrulandı; postcode, adres satır 1-2, telefon) + `save()` → PRG (cart URL) + Woo oturum bildirimi. (2) **Hesaplayıcıya kaydet** — yeni şablon override'ı `woocommerce/cart/shipping-calculator.php` (Woo 11.1.0 `templates/cart/shipping-calculator.php` @9.7.0 temelli; **misafirde çekirdek çıktısıyla birebir**): girişli kullanıcıda formun İÇİNİNE isim/telefon/adres satırı 1-2/mahalle alanları + "Bu adresi hesabıma kaydet" (etiket input'u, etiket zorunlu) + "Varsayılan adres yap" checkbox'ları; POST işleyicisi `sv56_handle_cart_save_address` (`wp_loaded:30`) adresi deftere ekler, istenirse varsayılan yapar + customer shipping'e uygular. Woo'nun hesaplayıcı nonce'u 9.7+ ile değiştiği için **ikili nonce kabulü** (`woocommerce-shipping-calculator` öncelikli, `woocommerce-cart` fallback — Woo çekirdeğinin kendi fallback'iyle aynı) korunur. İşleyici redirect/exit YAPMAZ — Woo'nun calc işleyicisi render anında (`WC_Shortcode_Cart::output`) çalışmaya devam eder, bildirimler sepet üstünde Woo oturum bildirimiyle basılır. (3) **Checkout ön-doldurma:** seçili/varsayılan adres checkout'ta shipping olarak dolu gelir (Woo customer üzerinden — ek kod gerekmedi). (4) **Misafirlerde** mevcut Woo hesaplayıcı aynen kalır (iki akış da giriş şartlı).
  - **P55 kalıntısı temizliği:** P55'in doğrudan `shipping_*` user-meta persist işleyicisi ve `woocommerce_after_shipping_calculator` checkbox hook'u KALDIRILDI — adres defteri tek kaynak. Ölü edit-address CSS blokları (`.woocommerce-Addresses .col2-set` kart grid'i + `.woocommerce-address-fields` form dili) kaldırıldı, yerine P56 stilleri geldi (seçici kartı, kaydet satırları, Adreslerim kart grid'i + rozet + aksiyonlar, 2-sütun adres formu, silme onayı kartı; ≤781px tek sütun).
- Değiştirilen dosyalar:
  - `theme/sutre-child-v2/functions.php` — P56 bloğu: endpoint, `sv56_address_fields/addresses/find_address/default_address_id/set_default_address/sanitize_address_data/save_address/delete_address/apply_address_to_customer/address_matches_customer/address_summary`, `sv56_handle_account_address_forms` (template_redirect:10), `sv56_address_book_content`, edit-address redirect (:30), `sv56_cart_address_selector` + `sv56_handle_cart_apply` (template_redirect) + `sv56_handle_cart_save_address` (wp_loaded:30) + `sv56_cart_notice_redirect`; P55 hook/işleyici kaldırıldı; nav filtresi güncellendi; SUTRE_VERSION 3.4.14.
  - `theme/sutre-child-v2/woocommerce/cart/shipping-calculator.php` — YENİ (Woo @9.7.0 temelli override).
  - `theme/sutre-child-v2/woocommerce/myaccount/dashboard.php` — 1 satır: edit-address linki → adreslerim.
  - `theme/sutre-child-v2/style.css` — yalnız P55→P56 bloğu güncellendi.
  - `AGENTS.md` — §7 P56 kaydı + §8 P56 dersleri.
- Veritabanı/ayar etkisi: Yeni user meta'lar yalnız kullanıcının kendi form gönderimiyle oluşur (`sv_address_book`, `sv_default_address`; uygulanınca Woo'nun kendi `shipping_*` customer metaları). Rewrite kuralı `init`'te kaydedilir; **flush kullanıcı adımı gerekli** (aşağıda). Eklenti/çekirdek değişikliği YOK; DB şeması değişmedi. header/footer dokunulmadı.

## Doğrulama

- Çalıştırılan testler:
  - **Sözdizimi:** npm `php-parser` (gerçek PHP 8 grammar) — 3 PHP dosyası (`functions.php`, `woocommerce/cart/shipping-calculator.php`, `woocommerce/myaccount/dashboard.php`) **3/3 PASS** (`node check.js` çıktısı; php binary ortamda yok — P41'den beri bu yöntem). CSS brace dengesi: `{409}/{409}`.
  - **Entegrasyon çapraz kontrol:** 42/42 PASS — nonce action çiftleri form↔handler (sv_address_save/default/delete/apply + woocommerce-shipping-calculator), sv_form değerleri, tüm `sv_addr_*` alan adları form+template+handler tutarlılığı, `sv_save_address`/`sv_make_default`/`sv_address_label`/`sv_apply_address`, meta anahtarları (sv_address_book/sv_default_address), endpoint tutarlılığı (add_rewrite_endpoint ↔ is_wc_endpoint_url ↔ content action ↔ nav ↔ redirect), sv56 fonksiyon tanımları (17 tanım, tekrar yok, çağrılar tanımlı), guard'lar (is_user_logged_in + current_user_can('read') + nonce), wc_clean tabanlı sanitize + TC regex.
  - **Woo 11.1.0 kaynak doğrulaması:** WordPress.org zip (woocommerce.11.1.0.zip) indirilip okundu — `templates/cart/shipping-calculator.php` (@9.7.0: hook'lar form dışında, nonce alan adı, yalnız 4 alan), `includes/shortcodes/class-wc-shortcode-cart.php` (calc POST render anında işlenir; ikili nonce kabulü), `class-wc-form-handler.php` (save_address template_redirect:10, nonce woocommerce-edit_address), `wc-account-functions.php` (nav key'leri: edit-address gerçek key), `class-wc-countries.php` (TR locale: state='Province', TR state listesi YOK → text input; `{postcode} {city} {state}` formatı), `class-wc-customer.php` + data store (`set_shipping_phone` Woo 5.6+, `shipping_phone` user meta persist), `wc-template-hooks.php` (bildirimler `woocommerce_account_content` ve `woocommerce_before_cart` üzerinde basılır), `class-wc-form-handler.php` update_cart_action (kupon/sepet nonce'u).
- PASS sonuçları: 3/3 syntax PASS; 42/42 sözleşme PASS; commit'ler: `08ce684` (P56a veri modeli+endpoint), `54366af` (P56b sepet entegrasyonu), `7e3809a` (P56c CSS+ver 3.4.14) — main'e pushlandı (aşağıda SHA'lar).
- FAIL / atlanan testler ve nedeni: Girişli E2E (kaydet/uygula/sil form gönderimleri) staging'e deploy + rewrite flush SONRASI tarayıcıyla yapılacak — bu tur "push'a kadar" (P41/P55 geleneği). Canlı doğrulama (deploy sonrası) aşağıdaki komutlarla yapılmalı; bu turda mevcut canlının guest yüzeyleri kontrol edildi (aşağıda).

## Risk ve güvenlik

- Secret veya kişisel veri etkisi: Secret YOK. Kişisel veri: kullanıcı kendi teslimat adresini defterinde saklar (label/name/phone/adres/opsiyonel TC) — tek user meta'da, yalnız sahibi okur/yazar (`get_user_meta( kendi_id )`; her POST'ta sahipliği kontrolü: id defterde yoksa işlem reddedilir). TC yalnızca kullanıcı verirse saklanır (11 rakam, regex doğrulamalı), log/rapora yazılmaz. Hesaplayıcı akışında TC hiç toplanmaz (veri minimizasyonu). Notice'lar beyaz-listeli anahtar taşır; adres verisi URL'de taşınmaz. Kart/CVV/ödeme verisi akışa hiç girmez.
- Ödeme/fiyat/stok/fatura etkisi: YOK. Ödeme katmanına dokunulmadı; yalnız teslimat ADRESİ seçimi/kaydı. Sipariş durum makinesi etkilenmez (checkout'a adres customer object üzerinden Woo'nun kendi yoluyla gider). Riskler: (1) Hesaplayıcı nonce'u çift kabul (yeni + eski) — Woo çekirdeğiyle birebir aynı davranış, downgrade/upgrade penceresinde uyumlu; (2) sepet "kaydet" işleyicisi redirect yapmadığı için Woo calc'ı da işler — aynı POST'tan çift kayıt oluşmaz (defter eki tek sefer; id benzersiz); (3) `:has()` CSS desteği eski tarayıcılarda yoksa seçici vurgusu kaybolur — fonksiyonel etki yok (radio çalışır).
- Geri dönüş adımı: Tek adım: `git revert 7e3809a 54366af 08ce684` (sırayla) VEYA tema klasörünü önceki `cp -a` yedeğine döndür. Revert sonrası da bir kez Kalıcı Bağlantılar→Kaydet önerilir (endpoint kuralı düşer). `shipping-calculator.php` override silindiğinde Woo çekirdek şablonu otomatik devreye girer. Yeni user meta'lar rollback gerektirmez (izole).

## Açık kapılar

- NEEDS_OWNER_INPUT: YOK (bu pakette işletme verisi istenmedi).
- OWNER_APPROVAL_REQUIRED: Adres Defteri yüzeylerinin canlıya alınması + görsel onay (AGENTS §0.6 — Salih'e gösterilecek; her şey staging'de). 
- LEGAL_REVIEW_REQUIRED: YOK — bu paket hukuki metin içermiyor; TC alanı opsiyoneldir ve KVKK aydınlatma bağlantısı P41'deki PLACEHOLDER metne bağlı kalır (o kapı zaten açık).
- Sonraki en küçük güvenli adım: Aşağıdaki KULLANICI ADIMLARI (rewrite flush) → deploy → LiteSpeed purge → canlı doğrulama (`/my-account/adreslerim/` giriş curl/tarayıcı + guest Fatal 0) → E2E: adres ekle → varsayılan yap → sepette seç → checkout dolu gelmesi → hesaplayıcıdan kaydet + varsayılan yap → sil.

## KULLANICI ADIMLARI (rewrite flush — TEK mini adım)

Yeni `/my-account/adreslerim/` sayfası, WordPress'in kalıcı bağlantı kuralları yenilenmeden 404 verir. (P41'deki `iletisim-tercihleri` gibi; aynı tek tık bu turu da kapsar.)

1. **WP Admin'e gir** (staging.sutre.store/wp-admin).
2. **Ayarlar → Kalıcı Bağlantılar** sayfasını aç.
3. Hiçbir şeyi DEĞİŞTİRMEDEN sayfanın en altındaki **Değişiklikleri Kaydet** butonuna bas.

Sonra: cPanel Terminal'de `git pull` + `cp -a` (deploy kanal A; `.bak` yedeğiyle) VEYA Hermes FTP deploy (kanal B, SHA karşılaştırmalı) → LiteSpeed Cache → **Purge All**. Doğrulama:

```bash
# guest: Fatal 0 OLMALI (3 sayfa)
for p in "" "shop/" "cart/"; do curl -skL "https://staging.sutre.store/$p?v=$(date +%s)" | grep -c 'Fatal error'; done

# girişli tarayıcıda: /my-account/ nav'ında "Adreslerim" + /my-account/adreslerim/ kart grid'i
# sepet (girişli, ürün sepette): "Kayıtlı Adresinize Gönder" bloğu + hesaplayıcı içinde "Bu adresi hesabıma kaydet"
```

---

### Paket-Anayasa teknik notları (anayasa §0.10 şeffaflık kaydı)

1. **P55 neden baştan kuruldu (kanıt):** Woo 11.1.0 kaynak zip'inden okundu — (a) hesaplayıcı şablonu @9.7.0'da nonce `wp_nonce_field('woocommerce-shipping-calculator','woocommerce-shipping-calculator-nonce')`; P55 handler'ının doğruladığı `$_POST['_wpnonce']` + `'woocommerce-cart'` formda YOK → handler sessizce erken dönerdi; (b) P55 checkbox'ı `woocommerce_after_shipping_calculator`'da basılır ve o hook `</form>`'un SONRASINDADIR → checkbox submit'e hiç gitmezdi; (c) hesaplayıcıda `calc_shipping_address_1/2` alanları artık yok (P55'in okuduğu anahtarlar boş gelirdi). Bu yüzden hook yerine şablon override'ı seçildi (alanlar form İÇİNDE olmak zorunda) — anayasa §3.1'e uygun tek yol.
2. **Hook konumu:** Paket "woocommerce_before_shipping_calculator (veya hesaplayıcı üstü)" diyor; kaynak kod doğrulaması bu hook'un `<form>` DIŞINA (görünür alana) bastığını gösterdi → seçici için AYNEN kullanıldı. Kaydet UI'i için hook yetersizdi (form dışı) → şablon override.
3. **calc işleyicisi sıralaması:** Paket "wp_loaded:30" kalıbını sürdürdü; Woo'nun calc işleyicisi render anında (WC_Shortcode_Cart::output) çalıştığı için wp_loaded işleyicisi redirect/exit yapmaz — aksi halde Woo calc'ı devre dışı kalırdı. Bildirimler Woo oturum bildirimiyle basılır (cart.php üstü).
4. **Alan eşlemesi:** Woo TR locale'de `state` = İl (Province), `city` = İlçe (Town/City TR çevirisi) → defter `city`→`shipping_state`, defter `district`→`shipping_city` (paket: "city/district→state" ile uyumlu). Hesaplayıcı POST'unda `calc_shipping_state`(İl)→defter `city`, `calc_shipping_city`(İlçe)→defter `district`.
5. **İlk adres = varsayılan:** Paket varsayılan atanmamış durum için kural veriyor ("silinen varsayılanın yerine en eski otomatik varsayılan olur"); aynı kural boş `sv_default_address` için de uygulandı → kaydedilen İLK adres otomatik varsayılan (kullanıcı kart aksiyonuyla değiştirebilir). Sepet seçici ön-seçimi eşleşen kayıt > varsayılan sırasıyla.
6. **TC alanı:** Hesap formunda opsiyonel toplanır (11 rakam doğrulamalı); sepet hesaplayıcı akışında toplanmaz (minim alandan doğan ek KVKK yükü olmasın).
7. **TDD notu:** Ortamda PHP runtime yok; proje geleneği (P41'den beri) RED-GREEN yerine statik kanıt zinciri kullanır: php-parser (PHP 8 grammar) + 42 maddelik sözleşme kontrolü + Woo kaynak doğrulaması + deploy sonrası canlı grep. Unit test altyapısı (wp-cli/phpunit) staging paylaşımlı hostta yok.
