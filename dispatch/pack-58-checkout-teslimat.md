# P58 — Checkout "Teslimat Bilgileri" tek-form UX + Fatura opsiyonu + Adres şeması Woo-native

**Kanal:** coder · **Tarih:** 21-09-2026 · **Repo:** `/opt/data/workspace/proje` (main)
**Önce oku:** `AGENTS.md` (§5 tuzaklar 5a-b + P57 kaydı — `is_wc_endpoint_url` özel endpoint tuzakları, TR İl kod normalizasyonu), `dispatch/out/out-57-checkout-address-fix.md`, mevcut P56/P57 blokları.

## Sahibin kararı (birebir) + Hermes onayı
Checkout'ta **TEK adres formu** görünsün: **"Teslimat Bilgileri"** (gönderim alanları, otomatik dolu). Altında **"Faturayı da aynı adrese gönderilsin"** checkbox'ı **varsayılan işaretli**; işaret KALKARSA ayrı **Fatura Bilgileri** formu açılır. Fatura başlığı/çift form görünümü KALDIRILIR. Backend veri bütünlüğü korunacak (aşağıda).

## Görevler

### 1. Tek-form UX (checkout)
- Klasik checkout kalır (P57). Şablon override: `woocommerce/checkout/form-shipping.php` + `form-billing.php` (Woo 11.1.0 kaynak temelli; misafir çıktısı çekirdekle tutarlı kuralı P56 dersinden).
- **Teslimat Bilgileri** = shipping fields bölümü, sayfada birincil ve her zaman görünür; başlık "Teslimat Bilgileri" (serif).
- **Fatura checkbox'ı:** "Faturayı da aynı adrese gönderilsin" — default CHECKED. İşaretliyken billing fieldset GİZLİ (CSS + POST'ta muhafaza). İşaret kaldırılırsa billing formu açılır (alanlar: Ad, Soyad, Adres 1-2, İlçe, Posta, Ülke, Şehir — shipping ile aynı şema).
- **Sunucu aynalama (veri bütünlüğü):** `woocommerce_checkout_posted_data` filtresi — checkbox işaretliyse (veya hiç POST edilmemişse) posted billing alanları shipping alanlarından aynalanır (name/address_1/2/city/postcode/state/phone). Böylece order billing adresi daima dolu (e-arşiv/iade), kullanıcıya ek soru sorulmaz.

### 2. Adres şeması Woo-native (P58 kararı — TC ve Mahalle KALDIRILIR)
- Alan seti (adreslerim formu + sepet hesaplayıcı + checkout): **Ad, Soyad, Firma (ops), Adres Satırı 1, Adres Satırı 2 (ops), İlçe/Semt, Posta Kodu, Ülke (TR kilit, readonly), Şehir, Telefon** — sıra bu.
- **TC Kimlik alanı KALDIRILIR** (hem hesaplayıcı hem adreslerim; `sv56_address_fields` şemasından `tc` çıkar, sanitize güncelle).
- **Mahalle/Köy KALDIRILIR** — İlçe yeterli (sahibin Woo ekranı hizalaması).
- Meta eşlemesi: alanlar doğrudan Woo `shipping_*/billing_*` kanonik key'leriyle yazılsın (address_1, address_2, city=İlçe? DİKKAT: mevcut TR eşlemesi city=İlçe, state=İl/Şehir — P57'deki İL KOD normalizasyonu korunur: defter insan-okur Şehir adı saklar, customer'a TR kod yazılır). `sv_address_book` şeması güncellenir: `neighborhood` ve `tc` alanları çıkarılır; eski kayıtlarda varsa sessizce yok sayılır (migrasyon yok).
- Checkout'taki P57 `shipping_neighborhood` custom field da KALDIRILIR (tutarlılık).

### 3. Adreslerim sayfası
- Kartlar + form yeni şemayla; varsayılan/ekle/düzenle/sil akışları aynı kalır. Yeni kaydedilen adres checkout teslimat formuna prefill edilir (mevcut mekanizma).

### 4. CSS + metinler
- Yalnız `style.css` (P57 checkout bloğu güncellenir): tek-form düzeni, checkbox satırı, gizli billing durumu.
- Etiketler Türkçe insan-dili: "Teslimat Bilgileri", "Faturayı da aynı adrese gönderilsin", "Farklı fatura adresi"; gettext haritasına gerekirse ekle.

## Kabul ölçütleri
- Checkout'ta tek adres formu ("Teslimat Bilgileri", otomatik dolu) + varsayılan işaretli fatura checkbox'ı; işaret kalkınca billing formu; işaretliyken siparişin billing adresi = teslimat adresi (posted_data aynalaması kanıtlı).
- TC/Mahalle hiçbir formda görünmüyor; alan sırası yeni şema.
- Adreslerim + sepet hesaplayıcı yeni şemayla çalışıyor; varsayılan/ekle/düzenle/sil bozulmuyor.
- php-parser PASS; guest Fatal 0 (/, /shop/, /cart/, /checkout/); commit'ler push'lu; AGENTS.md §7 P58 kaydı (SEN yaz); `dispatch/out/out-58-checkout-teslimat.md`.

## Kapsam dışı
- e-Fatura/İYS entegrasyonu, TC toplama (ileride LEGAL_REVIEW ile), ödeme gateway'leri.
