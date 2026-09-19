# PAKET 19 — FAZ 2/3: KAYIT FORMU ZENGİNLEŞTİRME SNIPPET + ÇEVİRİ KALAN STRATEJİSİ (@coder)

## Görev
Sahibin iki iş talebi: (1) My Account Register formunu klasik zengin forma dönüştür (isim/soyisim/telefon/email/şifre — email-only değil; anayasa §3.2 minimal), (2) tema/block şablonlarındaki İngilizce görünen metinlerin stratejisi — sahibin sıfırdan tema tasarımı Faz 2/3 ilerisinde; şimdi yalnız kayıt form snippet. Sen sunucuya dokunmuyorsun; snippet + kurulum talimatı üretirsin.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile P17/P18 raporları + ADR-003). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/dispatch/out/out-17-woo-install.md`
2. `/opt/data/workspace/proje/ADR/ADR-003-magaza-sema.md`
3. `/opt/data/workspace/proje/docs/operations/checkout-login-enhancement.md`
4. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` §3.2, §5

## Kapsam / görev
1. **mU-plugins snippet üret:** `docs/snippets/wc-rich-register.php` içerik yaz:
   - `woocommerce_register_form` hook ile: **ad, soyisim, telefon alanları** (mandatory: ad+soyisim; telefon opsiyonel — placeholder yalnız)
   - `user_register` handler'da `first_name`, `last_name`, `billing_phone` meta set
   - Klavye UX: parola alanı Checkout during-checkout zaten açık gelen om admitted — sadece My Account register form Rich alanlar
   - Kod standart: sanitize (§3.1 sanitize/validate), sanitize_text_field/email/phone
   - **Dosyayı `wp-content/mu-plugins/` altına XŞARTI: bu snippet senin ürettiğin; sahibin FTP/cPanel File Manager'e elle kopyalayacağı** — talimat dosyaya (mu-plugins dizin yoksa oluştur, mod 755, file 644)
   - Değişiklik staging DB'ye DOKUNMAZ; geçici; enable/disable küçük bir dosya silme ile rollback — anayasa §0.6 tekrarlenebilir.
2. **Çeviri stratejisi belge** `docs/operations/translation-strategy.md`:
   - **Durum hex:** WP core + WooCommerce + tema çevirileri Türkçe aktif (Updates'de "Çevirileriniz güncel"); Width "Twenty Twenty-Five + Block templates İngilizce string'le — bu temalar/şablonlar içinde hard-coded." 
   - **Strateji:** görünen tüm sayfa içeriği türkçe hale getirme YÖNTEMLERİ: (a) wp-content/theme içindeki .php/block şablonlar elle Türkçe, (b) WooCommerce block-based checkout/login page'lerinde **page editor'de eklenen block'ların içerikleri** Türkçe'ye elle yazılır Salih tarafından — bot sunucu edit'si yapmaz.
   - Karar işi: Sahibin İsteği Faz 2/3'te daha sonra Tema/visual identity FULL REDESIGN (bot paket) — o zamana kadar "aslında ön yüz metinlerinin İngilizce kalması" treat olarak kanıtlan̠ı; GEÇİCİ not; P17'dekiLoginForm Login/Register strings changeable in page editor — sahibin elle Türkçe'e çevirebilir.
3. **GİLEN admin TODO listesi (özet)** `docs/FAZ-DURUM.md` Fella'dagina "Faz 2/3 Silk yapılacaklar" bölümü ( Sahibin elle çalışacagı talimat satırları):
   - mu-plugins snippet'i cPanel File Manager'da root'a ekleme talimatları
   - Settings→"Membership" ON + new user default role **Customer** confrmation (sahibin zaten!)
   - Register form kontrol (website on front, register with name/surname/phone)
   - saga VOERT validation notifications_
4. **Sayfayı düz kod hazırla:** Sahibin wp-admin'de我所補助 My Accountwoocommerce-extension: register form after checkout with rich fields —
   - kısa test steps (Sahibin elle bucket test)

## Bağlılık sınırları (out-of-scope)
- PayTR YOK; tema redesign YOK (ayrı Paket faz 2/4)
- Gerçek ürün/kategori data YOK; secret YOK
- Server erişim YOK

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-19-register-enhancements.md`
§16 format; 150-300 kelime.
