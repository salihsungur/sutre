# SUTRE — PREMIUM GELİŞTİRME MASTER PLANI (Faz 3 ERTELENDİ; web/Instagram önce)

> Sahibin 2026-09-17 kararı: PayTR/staging→canlı taşıma ERTESİNDE. Şimdi odak:
> siteyi olabildiğince güzel, premium, dikkat çekici hale getirmek. Website + Instagram
> kalıcı en yüksek efor alanları. Sadece web bitene kadar Instagram'a girmiyoruz;
> web hazır → Instagram fotoğraf üretimi → yayına/reklama hazır.

## SIRALI YOL HARİTASI (uygulama sırası — her blok tek tek onayla gider)

### BLOK A — HUKUKİ SAYFALARIN DOLDURULMASI (yayın öncesi zorunlu kapı)
A1. 5 taslağın sahaya uyarlanması (docs/legal-placeholders/):
    gizlilik, mesafeli satış, iade-cayma, ön-bilgilendirme, çerez
A2. Şirket/satıcı bilgilerinin girilmesi → NEEDS_OWNER_INPUT (unvan, TCKN, adres)
A3. WP'de sayfa olarak yayınla + footer linkleri bağla (çalışan linkler: /gizlilik-politikasi/ vb.)
A4. LEGAL_REVIEW_REQUIRED kapısı: avukat onayı notu — yayın "taslak" ibaresiyle
    (anayasa §6.1; onay gelince ibare kalkar)
Kabul: footer 3 link canlı sayfalara gider; sayfalar premium tipografide, %100 Türkçe.

### BLOK B — KULLANICI HESAP YÖNETİMİ GELİŞTİRME
B1. Hesabım sayfası bilgileri: sipariş geçmişi, adres defteri, hesap detayları (Woo hazır)
B2. Sipariş takibi akışı: "Siparişim nerede" self-servis görünümü
B3. Parola sıfırlama akışı SMTP üzerinden çalışıyor mu testi (Brevo) — kanıt
B4. Kayıt formu iyileştirmeleri: mevcut Ad/Soyad/Telefon + mesafeli satış onay checkbox'ı
    (pazarlama izni SEPARATE ve opsiyonel — §6.2)
B5. Hoş geldin e-postası (Woo template, Türkçe)
Kabul: kayıt→giriş→sipariş→adres akışı hatasız; onay kayıtları ispatlanabilir.

### BLOK C — ÜRÜN SAYFALARI PREMIUM İYİLEŞTİRME
C1. Ürün galerisi düzeni (büyük görsel + thumb); zoom
C2. Ürün açıklama şablonu: kumaş, ölçü, bakım, teslimat — sabit premium yapı
C3. Varyasyon seçici görselleştirme (renk swatch'ları)
C4. İlgili ürünler / "Bunlarla birlikte alınır"
C5. Ürün mikro-copy: stok adedi az ise "Son X adet" (gerçek stok verisinden, uydurma yasak)
Kabul: ürün sayfası dönüşüm odaklı, mobil akıcı.

### BLOK D — GÖRSEL ÜRETİM PLANI (Instagram dahil) — SALİH'LE BİRLİKTE
D1. Fotoğraf ihtiyaç envanteri:
    - Ürün çekimleri (her SKU, beyaz/düz zemin, 1500×2000px 3:4, e-ticaret standardı)
    - Yaşam tarzı çekimleri (model üstünde, sahilde/yacht hissi, 1080×1350 IG + 1200×1500 web)
    - Detay makro (dokuma dokusu, ipek parlaklığı)
    - Paketleme (premium kutu, kurdele — teslimat hissi)
    - Instagram: 3 içerik kolonu — ürün, estetik/atmosfer, marka hikayesi
D2. Üretim yöntemi: Nous görsel üretimi (referans fotoğraf sorusu aşağıda cevaplandı)
D3. Yerleşim ölçüleri tablosu: web hero 1920×900; ürün 3:4; IG post 1080×1350; IG story 1080×1920
D4. Marka rehberi uygulaması: palet Bone/Ink/Silk/Marine; Cormorant başlık overlay'leri
Kabul: her SKU için min 3 görsel + IG için 9 post'a hazır set.

### BLOK E — SITE TASARIM İYİLEŞTİRMELERİ (premium dokunuşlar)
E1. Ana sayfa: son gelişmeler + ince animasyon (scroll-reveal, 200ms, reduced-motion uyumlu)
E2. Shop sayfası: filtre/renk swatch, sıralama; boşluk/kart hizası final
E3. Sepet + checkout premium görünümü (PayTR öncesi son form)
E4. 404 + arama sayfası (marka dili)
E5. Performans: LSCache ayarları + görsel lazy-load + Core Web Vitals kontrolü
Kabul: CWV yeşil; animasyonlar rahatsız etmiyor.

### BLOK F — MARKA KİMLİĞİ SITE ENTEGRASYONU
F1. Favicon + og-image (social share önizleme)
F2. E-posta şablonları marka renkleriyle (Brevo/Woo e-postaları)
F3. "Sutre" mikro-dokunuşlar: loading state, buton hover'ları, scroll-üstü header davranışı
F4. Instagram profili site ile uyum: bio link, highlight kapakları (D blok görselleriyle)
Kabul: marka hissi uçtan uca tutarlı.

### BLOK G — FAZ 3 ERTESİ (ertelenmiş, sıra geldiğinde)
- staging→sutre.store taşıma; ETBİS kontrol; PayTR başvuru+entegrasyon; canlı kapıları (anayasa §13/§14)

## ÇALIŞMA KURALI
Her blok: tek tek mini paketlerle; kanıtlı deploy (FTP auto) + screenshot kabul.
Sahip her bloğun sonunda "onay" verir; sıradaki bloğa geçilir.
