# PRIVACY-DATA-MAP — KVKK Veri Haritası (SKELETON)

> **Durum: ŞABLON / SKELETON — YAYINA HAZIR DEĞİL.**
> Bu dosya anayasa §7 gereği tutulur. Tüm hücreler `TBD — LEGAL_REVIEW_REQUIRED` olarak açılmıştır; hiçbir değer doğrulanmış kabul edilmez. Doldurma ve uzman onayı Faz 4 kapsamındadır (anayasa §13 Faz 4).
>
> Anayasa §15: hukuki metin onaylıymış gibi yayımlanamaz; güncel olmayan mevzuat rakamları sabit doğru olarak kodlanamaz. Bu dosyada geçen tüm süre/rakam bilgileri (İYS bildirim süreleri, saklama süreleri, cayma süreleri vb.) **güncel resmî kaynaktan doğrulanmadan** kesin değer olarak yazılmamalıdır.

## 1. Amaç ve kapsam

- Her kişisel veri kategorisi için anayasa §7'deki 9 zorunlu alan sütun olarak izlenir.
- Veri minimizasyonu ilkesi: yalnız gerekli kişisel veri toplanır (MUST).
- Canlı veri development/staging'e kopyalanamaz; zorunluysa anonimleştirilir (MUST).

## 2. Veri haritası tablosu

| # | Veri kategorisi / alan | Toplama amacı | İşleme şartı / hukuki dayanak | Kaynak | Erişen roller | Üçüncü taraf alıcı / işleyen | Yurtdışı aktarım | Saklama süresi / silme yöntemi | Veri sahibi talebinde uygulanacak işlem |
|---|---|---|---|---|---|---|---|---|---|
| 1 | Hesap / kullanıcı kaydı (ad, e-posta, parola hash'i, telefon) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED |
| 2 | Sipariş ve teslimat verisi (ad, adres, telefon, sipariş içeriği, fatura bilgisi) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (kargo, fatura/entegratör) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED — vergi/muhasebe/uyuşmazlık yasal saklama zorunluluğu kontrol edilmeden silinemez (anayasa §7) | TBD — LEGAL_REVIEW_REQUIRED |
| 3 | Ödeme callback kayıtları (sipariş no, tutar, para birimi, işlem kimliği, imza doğrulama sonucu) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (PayTR callback) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (PayTR) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED |
| 3a | **Kart numarası / CVV / hassas doğrulama verisi** | **SAKLANMAZ — MUST NOT** (anayasa §4.1, §15: kart/CVV, parola, secret veya gereksiz kişisel veri saklanmaz/loglanmaz) | **İşleme şartı YOK — kategorik olarak hariç** | Kart verisi yalnız PayTR güvenli checkout/iFrame bileşeni içinde kalır; mağaza sunucusundan geçmez | **Hiçbir rol erişemez — MUST NOT** | **PayTR iFrame dışına çıkarılamaz — MUST NOT** | **Yok — MUST NOT** | **Kategorik olarak saklanmaz** | **Uygulanmaz — veri mevcut olmamalı** |
| 4 | Çerez / analitik verisi (oturum, analytics, reklam) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED — analytics/reklam çerezleri gerekli çerezlerden ayrılır; tercihler sonradan değiştirilebilmelidir (anayasa §7) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (Google/Meta, hata izleme, CDN) | TBD — LEGAL_REVIEW_REQUIRED — yurtdışı aktarım etkisi ayrıca incelenecek | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED |
| 5 | Pazarlama izni kayıtları (e-posta/SMS/WhatsApp onay-ret, İYS) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED — onay/ret kayıtları güncel İYS gereksinimleriyle uyumlu yönetilir; İYS bildirim süreleri **güncel resmî kaynaktan doğrulanacak** (anayasa §7) | TBD — LEGAL_REVIEW_REQUIRED (checkout ayrık checkbox, önceden işaretli YASAK) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (İYS, e-posta/SMS/WhatsApp sağlayıcıları) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED |
| 6 | Sunucu / audit logları (admin işlemleri, ödeme statü değişiklikleri, güvenlik olayları) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (hosting) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED — loglarda password/secret/kart verisi/tam kimlik/gereksiz adres bulunmaz (anayasa §8.2) | TBD — LEGAL_REVIEW_REQUIRED |
| 7 | Destek iletişimi (e-posta, WhatsApp mesajları, destek talepleri) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (WhatsApp Business, e-posta sağlayıcı) | TBD — LEGAL_REVIEW_REQUIRED — WhatsApp/Meta aktarım etkisi ayrıca incelenecek | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED |
| 8 | Yedekler (veritabanı + dosya, şifreli, site sunucusundan ayrı konum) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (canlı sistemden üretilir; canlı veri staging'e kopyalanamaz) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED (yedekleme sağlayıcısı) | TBD — LEGAL_REVIEW_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED — saklama süreleri tanımlanacak, RPO/RTO OWNER_APPROVAL_REQUIRED | TBD — LEGAL_REVIEW_REQUIRED |

## 3. Kurallar (anayasa §7'den — değiştirilemez)

- Yalnız gerekli kişisel veri toplanmalıdır (MUST).
- Analytics ve reklam çerezleri gerekli çerezlerden ayrılmalı; tercihler sonradan değiştirilebilmelidir.
- Google/Meta/WhatsApp, e-posta, SMS, CDN, hata izleme, hosting ve yedekleme sağlayıcılarının veri aktarım etkisi incelenmelidir.
- Pazarlama onay/ret kayıtları güncel İYS gereksinimleriyle uyumlu yönetilmelidir.
- Veri erişim/silme/düzeltme talepleri için kimlik doğrulamalı bir operasyon akışı bulunmalıdır.
- Vergi, muhasebe, uyuşmazlık ve güvenlik kayıtları yasal saklama zorunluluğu kontrol edilmeden silinmemelidir.

## 4. Veri sahibi talep akışı (iskelet — Faz 4'te doldurulacak)

1. Talep kanalı: TBD — LEGAL_REVIEW_REQUIRED
2. Kimlik doğrulama yöntemi: TBD — LEGAL_REVIEW_REQUIRED
3. Yanıt süresi: TBD — LEGAL_REVIEW_REQUIRED — **güncel resmî kaynaktan doğrulanacak**
4. Talep türleri (erişim/düzeltme/silme/aktarım itirazı) işleme adımları: TBD — LEGAL_REVIEW_REQUIRED

## 5. Onay ve güncellik kaydı

| Kayıt | Tarih | Kaynak | Durum |
|---|---|---|---|
| Tablo doldurulması (tüm TBD'ler) | — | — | BEKLEMEDE — LEGAL_REVIEW_REQUIRED |
| Avukat/KVKK uzmanı doğrulaması | — | — | BEKLEMEDE — LEGAL_REVIEW_REQUIRED |
| Mevzuat güncellik taraması (resmî kaynak) | — | — | BEKLEMEDE — @researcher ile yapılacak |

Son güncelleme: 2026-09-12 — skeleton oluşturuldu (@legal).
