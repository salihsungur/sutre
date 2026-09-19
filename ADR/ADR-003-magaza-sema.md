# ADR-003 — Mağaza Veri Şeması (Kategori / SKU / Kargo) — TASLAK

- Durum: TASLAK — SAHİP ONAYI BEKLENİYOR (OWNER_APPROVAL_REQUIRED)
- Tarih: 2026-09-15 (P18, Faz 2/2)
- Sahip: Salih. Yazar: @coder (bot).
- İlişki: §5 (Ürün/Fiyat/Vergi/Stok) ve §13 Faz 2 gerekliliklerinin staging veri şemasına yansıması. P17 kanıtı: WC 11.1.0 + HPOS + Türkiye/TRY + `Giyim` kategorisi + kargo bölgesi Türkiye (Flat rate ₺0,00).
- Bu ADR ÜRÜN YÜKLEMEZ, FİYAT UYDURMAZ: yalnız şema ve iskelet kararlarını kaydeder. Tüm somut değerler sahibin PROJECT_INPUTS.md girdisine tabidir.

## 1. Kategori ağacı taslağı (ÖNERİ — sahibin onayı şart)

P17'de `Giyim` root kategorisi doğrulandı (dummy ürün `ürün-1`, SKU `SUTRE-SKU-B0001`). Önerilen iskelet:

```
Giyim (root)
├── Kadın
│   ├── Tişört
│   ├── Kapşonlu (hoodie)
│   └── Aksesuar
├── Erkek
│   ├── Tişört
│   ├── Kapşonlu (hoodie)
│   └── Aksesuar
└── (AYNI STELOW — sahibin ekleyeceği)
```

- Hiyerarşi en fazla 3 seviye (root → cinsiyet → ürün tipi); daha derin yapı WooCommerce arama/SEO'sunu karmaşıklaştırır.
- Alt kategori slug'ları ASCII olmalı (`kadin-tisort` gibi); Türkçe karakterli slug permalink sorunlarına yol açabilir.
- Ağaçta bilinçli boş satır bırakıldı: gerçek kategori sayısı, sezon/beden niteliği (attribute) kararı **NEEDS_OWNER_INPUT** (sahibin gerçek ürün listesine bağlı).

## 2. SKU şeması iskeleti

Format: `SUTRE-SKU-<KATEGORI-KODU>-<NUMARA>` (P17 dummy `B0001` kalıbıyla uyumlu).

- Kategori kodu: 2–4 harf kodu; öneri `B` = Baz/root ürün dummy, `KL-TSR` = Kadın Tişört, `ER-HOD` = Erkek Kapşonlu v.b. Kod tablosu sahibin onayıyla ADR-003'e işlenir.
- Numara: 4 hane, kategori içi sıralı (`KL-TSR-0001`).
- Varyant (beden/renk): WooCommerce varyasyon SKU'su `${parent}-S`, `${parent}-M`... veya `#`-sonek — karar NEEDS_OWNER_INPUT (sahibin gerçek ürün listesi belirleyecek: varyantlı mı tek tip mi).
- Kural: SKU bir kez atanır, sonra ASLA değiştirilmez (tarihçe/bağlantı sağlıklığı §5).

## 3. Kargo şeması (LEGAL_REVIEW_REQUIRED — sahibin/finans kararı)

- P17'de tek bölge (Türkiye) + tek yöntem (Flat rate ₺0,00 "Free") doğrulandı.
- Şema modeli (hiçbir rakam AI tarafından girilmez — anayasa §5.1 ve BENZER fake numbers yasa): kargo maliyeti yapısı sahibin işidir; LEGAL_REVIEW_REQUIRED.

| Ölçüt | Alan | Kim doldurur |
|---|---|---|
| Ağırlık dilimi | WooCommerce Shipping class + ağırlık tabanlı maliyet (WC çekirdek; kod gerekmez) | Sahip |
| Bölge | WC Shipping Zone (Türkiye; illere alt bölünme ihtiyacı dahil) | Sahip |
| Taşıyıcı | Ücretsiz taşıyıcı adı/süre/fiyat — sahibin belirler; bot rakam UYDURAMAZ | Sahip |
| İade kargo masrafı | Satış kargo maliyetinden farklı olabilir; mesafeli satış anayasa §6.3 | LEGAL_REVIEW_REQUIRED |

- Yasa: Kargo fiyatı/rakamları AI tarafından UYDURULAMAZ; her alan `TBD` kalır, sahibin elle WooCommerce → Settings → Shipping'e girer. Bot yalnız talimat/docs üretir.

## 4. SKU veri şeması — her ürün için zorunlu alan tablosu (anayasa §5)

Bu tablo şablonlarıdır (template); değerler sahibin PROJECT_INPUTS.md + WooCommerce ürün editörüne elle girilir:

| Alan | WC karşılığı | Kim doldurur / kapı |
|---|---|---|
| benzersiz SKU | Ürün → Inventory | Sahip |
| ad/açıklama/kategori/marka | Ürün genel + marka alanı | Sahip |
| ürün maliyeti | Ürün meta (sahibin elle girişi; fazladan plugin eklenmez) | Sahip |
| KDV oranı + doğrulama kaynağı | Settings → Tax + ürün override | **LEGAL_REVIEW_REQUIRED — Settings→Tax P17'de BOŞ bırakıldı** |
| vergi dahil/harici fiyat | tax display setting | Sahip + mali |
| ağırlık/kargo boyutları | Ürün → Shipping | Sahip |
| stok / ayrılan / satılabilir | WC Inventory (HPOS ile sipariş-stok izleme) | Sahip |
| garanti/servis sınıfı | ürün meta / özel alan | LEGAL_REVIEW_REQUIRED |
| iade/cayma istisnası | anayasa §5 alanı | LEGAL_REVIEW_REQUIRED |
| kanal bazlı fiyat/komisyon | Faz 5/7 (şimdilik TBD) | TBD |
| görsel alt metni / canonical / schema | Ürün görsel alanları + SEO katmanı | Sahip + SEO Faz 5 |
| barcode | özel meta `sutre_product_barcode` (öneri: project-core plugin custom meta, çekirdekte alan yok) | Sahip |

- Yasa: KDV `%20` gibi ürün sınıflandırması varsayılmaz (anayasa §5.1); Settings → Tax mali müşavir onayına kadar boş kalır (Faz 4 kapısı).
- Not: barcode/garanti/cayma alanları WC çekirdeğinde yerleşik DEĞİL; project-core plugin custom meta olarak tasarlanır — çekirdek kod değişikliği YOK (anayasa §3.1).

## 5. Checkout login/register normalization

P17 sabiti: checkout üstünde Login formu görünür; guest checkout checkbox kaldırıldı; "During checkout account creation" ON (sahibin kararı).

İki gerçek durum kontrol altında:
1. **WP kayıt kapalı mı?** WP-admin → Settings → General → "Membership: Anyone can register" checkbox durumu kontrol edilir. ON ise checkout akışı kayıt formunu gösterebilir; OFF ise "Hesap oluştur" görünmez — bu ayarın sahibin bilinçli seçimi olduğundan emin olunur (P17'de guest checkout OFF ile birlikte tutarlı bir tercihtir, ancak beyan edilmemişse NEEDS_OWNER_INPUT).
2. **Checkout Login block içinde Register görünmeyebilir** (P17 gözlemi): Login block yalnız login formu render edebilir. Çözüm kod/plugin DEĞİL — aynı page'e sahibin editor'ünde ikinci bir blok (Register/Hesap oluştur) eklenmesi yöntemi; ayrıntılı adım adım talimat `docs/operations/checkout-login-enhancement.md` içinde (sahibin WP-admin elle uygulaması için).

## 6. Alternatifler (neden reddedildi)

- Ürün import CSV ile toplu yükleme: Faz 2'de erken; sahibin ürün listesi yok (NEEDS_OWNER_INPUT). Elle 1-3 ürünle şema doğrulaması düşük risklidir.
- Kargo taşıyıcı/fiyat rakamlarını botun doldurması: anayasa §5.1 temsili veri yasağı — REDDEDİLDİ.
- Checkout register linki için özel plugin/kod yazılması: küçük özellik için gereksiz; page editor blok çözümü yeterli (anayasa §3.2 eklenti politikası).
- KDV tahmini/varsayılan oranı girilmesi: anayasa §15 mutlak yasağı — REDDEDİLDİ.

## 7. Açık kapılar

- NEEDS_OWNER_INPUT: gerçek ürün listesi/SKU kod tablosu; ürün sayısı ve varyant kararı; WP "Anyone can register" durumunun sahibin bilinçli tercihi olduğunun teyidi.
- OWNER_APPROVAL_REQUIRED: ADR-003'ün kendisi; kategori ağacı; SKU formatı; kargo şema modeli.
- LEGAL_REVIEW_REQUIRED: KDV oranları (Settings→Tax boş), kargo ücret yapısı ve taşıyıcı masrafı, iade kargo masrafı, garanti/cayma istisnaları.

