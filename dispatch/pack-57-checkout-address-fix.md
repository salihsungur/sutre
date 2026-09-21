# P57 — Adres defteri POST akış bug fix'leri + Mahalle sırası + TC + Checkout yenileme

**Kanal:** coder · **Tarih:** 21-09-2026 · **Repo:** `/opt/data/workspace/proje` (main)
**Önce oku:** `AGENTS.md` (§5 tuzaklar), `dispatch/out/out-56-address-book.md`, `theme/sutre-child-v2/functions.php` (P56 bloğu), `woocommerce/cart/shipping-calculator.php`, `woocommerce/myaccount/dashboard.php`.

## Sahibin birebir raporu (kanıt — gerçek kullanıcı, giriş yapmış)
1. **Hesabım → Adreslerim → Yeni adres formu:** "Kaydet" → Adreslerim'e dönüyor, **adres kaydolmuyor**.
2. **Sepette hesaplayıcı:** tüm alanlar görünüyor, **"Güncelle" hiçbir şey yapmıyor** (notice da yok).
3. **TC Kimlik** alanı sepet hesaplayıcısında yok — eklensin (opsiyonel, 11 hane rakam).
4. **Mahalle/Köy alanı İlçe/Semt'ten hemen sonra gelsin** (hem sepet hesaplayıcı hem adreslerim formu).
5. **Checkout sayfası** (`/checkout/`) Woo Checkout BLOĞU (JS-i18n İngilizce, tasarım dışı) — klasik `[woocommerce_checkout]`'a zorlanacak (P47 cart deseni: `the_content` filtresi, `is_checkout()` + has_shortcode kontrolü) + tam CSS + adres defteri entegrasyonu.

## Kritik uyarı: bot/giden curl giriş yapamaz — gerçek POST akışı reproduktif değil.
Bu yüzden fix'i KÖR denemeyle değil, **POST yaşam döngüsü yürüyüşü** ile yap:
Her kapıyı sırayla kanıtla (dosya:satır): (1) formun action URL'i doğru mu, (2) submit butonunun `name` attribute'ü işleyicinin tetikleyici parametresiyle AYNI mı, (3) nonce alan adı/değeri işleyicinin doğruladığıyla eşleşiyor mu, (4) hook (wp_loaded/template_redirect öncelik sırası) Woo yaşam döngüsünde tetikleniyor mu (Woo 11.1 kaynak zip'inden kanıtla), (5) işleyici dalı girip `sv56_save_address` çağırıyor mu, (6) `sv56_addresses` okuduğu meta key ile save'in yazdığı key AYNI mı.
Bilinen giriş noktaları (kendi incelememin bulguları — doğrula, körüne güvenme):
- Adreslerim formu: `sv_form=address_save`, nonce `sv_address_save`/`sv_nonce`, action `sv41_myaccount_url('adreslerim')`; işleyici `sv56_handle_account_address_forms` (template_redirect, `is_wc_endpoint_url('adreslerim')` guard'lı), case 'address_save' ~L853; **submit butonunun name attribute'ünü kontrol et — işleyici hangi POST anahtarıyla tetikleniyor?**
- Sepet: submit `name="calc_shipping"`; işleyici `sv56_handle_cart_save_address` (wp_loaded:30) — **hook'un gerçekten add_action ile bağlandığını ve Woo'nun kendi calc işleyicisiyle (WC_Shortcode_Cart::output — shortcode render anında, wp_loaded'DAN SONRA) çakışmadığını kanıtla; Woo reset_shipping/POST yeniden işleme save'i ezmiyor mu?**
- Şüpheli genel kök: PRG redirect save'den ÖNCE çalışıyor olabilir; ya da form submit `name` eksik.

## Görevler
1. **Bug fix 1 — Adreslerim kaydetme** (yukarıdaki yürüyüş + fix + kanıt).
2. **Bug fix 2 — Sepet Güncelle** (aynı yöntem; başarılı olunca kullanıcı "adres kaydedildi" notice'ı GÖRMELİ).
3. **TC alanı** sepet hesaplayıcısına (opsiyonel, 11 hane; işleyiciye dahil).
4. **Alan sırası** (her iki formda): İsim Soyisim → Telefon → Adres 1 → Adres 2 → Ülke(TR kilit) → Şehir → İlçe → **Mahalle/Köy** → Posta Kodu → TC → etiket/varsayılan/kaydet. (Woo TR locale eşlemesi: calc_shipping_state=İl/Şehir, calc_shipping_city=İlçe — mevcut.)
5. **Checkout yenileme:** klasik shortcode'a zorla + `P57` CSS bölümü: 2 sütun (fatura/gönderim + sipariş özeti), our input dili, button ink, gettext ile kalan İngilizce stringler. Adres defteri ön-dolumu customer üzerinden zaten gelir — checkout'ta shipping fields'a **Mahalle** (opsiyonel custom field, order meta'ya kaydet) ve TC ekleme DEĞİL (checkout'u abartma; yalnız tasarım + prefill + Mahalle alanı).
6. AGENTS.md §7 P57 kaydı + `dispatch/out/out-57-checkout-address-fix.md` + atomik commit'ler + push.

## Kabul ölçütleri
- Kod yürüyüşü kanıtıyla her iki POST akışının çalıştığı raporda adım adım belgeli.
- Mahalle sırası iki formda da İlçe'den sonra; TC sepet formunda.
- Checkout klasik + tasarım dilinde; adres defteri prefill'i checkout'ta görünür; Fatal 0 (guest, 3 sayfa).
- Header/footer dokunulmaz; CSS yalnız style.css P55/P57 blokları.

## Kapsam dışı
- Ödeme gateway'leri, order-email akışları, misafir checkout adres defteri.
