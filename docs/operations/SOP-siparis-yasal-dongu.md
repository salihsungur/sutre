# Standart Operasyon Prosedürü (SOP): E-Ticaret Sipariş & Yasal İşlem Döngüsü

Bu doküman; WooCommerce altyapılı mağazada bir satış gerçekleştiğinde **PayTR, WooCommerce, GİB (Fatura)** ve **Shipink** sistemleri arasındaki operasyonel ve yasal sorumlulukların icra sırasını tanımlar.

---

## 1. Sistemlerin Rol Dağılımı ve Yasal Çerçeve

| Sistem | Rolü | Yasal / Mali Niteliği |
| :--- | :--- | :--- |
| **PayTR** | Ödeme Tahsilatı | Aracı kurumdur; son kullanıcıya fatura kesmez. Ay sonunda şirketinize komisyon faturası düzenler. |
| **WooCommerce** | Sipariş & Müşteri Verisi | İşlemin kayıt altına alındığı ana veritabanıdır; sipariş durumlarını yönetir. |
| **GİB e-Arşiv** | Yasal Belgelendirme | Vergi Usul Kanunu (VUK) uyarınca satışın resmi mali belgesini (e-Arşiv Fatura) üretir. |
| **Shipink** | Lojistik Yönetimi | Kargo taşıma irsaliyesi/barkodunu üretir; resmi vergi faturası yerine geçmez. |

---

## 2. Adım Adım Operasyonel Yol Haritası

### Adım 1: Tahsilat Teyidi (PayTR & WooCommerce)
1. **Webhook / Ödeme Kontrolü:** 
   * Müşteri siparişi verdiğinde PayTR bildirimi tetikler.
   * WooCommerce sipariş durumu otomatik olarak **"İşleniyor" (Processing)** durumuna geçmelidir.
   * *Kontrol:* Durum "Ödeme Bekliyor"da kaldıysa veya PayTR panelinde ödeme başarısızsa işlem yapmayın.
2. **Sipariş Bilgilerini Çekme:**
   * Müşteri Adı - Soyadı
   * Teslimat ve Fatura Adresi
   * Varsa T.C. Kimlik Numarası (TCKN)
   * Tahsil edilen toplam tutar (Ürün bedeli + varsa Kargo bedeli)

---

### Adım 2: Yasal Faturanın Düzenlenmesi (GİB e-Arşiv Portalı)
*Yasal Dayanak: Fatura mal tesliminden önce veya teslimden itibaren en geç 7 gün içinde düzenlenmelidir (VUK Madde 231/5). Yol denetimlerinde ceza almamak için kargoya vermeden önce kesilmesi esastır.*

1. **Giriş:** `earsivportal.efatura.gov.tr` adresine şirket kullanıcı kodu ve şifresiyle giriş yapın; **e-Arşiv Portal** modülünü açın.
2. **Fatura Oluştur Ekranı:**
   * **Fatura Tarihi ve Saati:** Güncel tarih ve saat bırakılır (saat bilgisi sevk için zorunludur).
   * **Para Birimi:** `TRY`
   * **Fatura Tipi:** `SATIŞ`
3. **Alıcı Bilgileri:**
   * **VKN/TCKN:** Müşteri TC'sini girmediyse 11 haneli **`11111111111`** yazın.
   * **Vergi Dairesi:** TC girilmediyse boş bırakılabilir.
   * **Ad - Soyad - Adres:** WooCommerce siparişindeki fatura bilgileri eksiksiz yapıştırılır.
4. **Kalemlerin Hesaplanması (Matrah / KDV Ayrımı):**
   * Sitedeki fiyat KDV dahil fiyattır. Birim fiyat alanına **KDV Hariç Tutar** yazılmalıdır:
     $$\text{KDV Hariç Tutar} = \frac{\text{Satış Fiyatı}}{1 + (\text{KDV Oranı} / 100)}$$
     *(Örnek: %20 KDV dahil 1.200 TL için -> 1.200 / 1.20 = 1.000 TL birim fiyat girilir).*
   * **Kargo Tahsil Edildiyse:** İkinci satır eklenerek `Kargo Taşıma Bedeli` kalemi KDV hariç olarak yazılır (KDV %20).
5. **Not Alanı (Kritik Yasal Zorunluluk):**
   * Not kutusuna mutlaka şu ifade yazılır:
     > **"İrsaliye yerine geçer."**  
     > **"WooCommerce Sipariş No: #[SIPARIS_NO]"**
   * *Not:* "İrsaliye yerine geçer" ibaresi ve faturanın düzenlenme saati sayesinde faturanın çıktısı sevk irsaliyesi yerine geçer; ayrı sevk irsaliyesi düzenleme zorunluluğu kalkar.
6. **İmzalama ve İndirme:**
   * Fatura kaydedilir (`Oluştur`).
   * **Taslaklar** ekranına gidilir, fatura seçilip **"GİB İmza"** butonuna basılır.
   * Telefona gelen SMS doğrulama kodu girilerek fatura resmiyete kavuşturulur.
   * Faturanın **PDF formatı** cihaza indirilir.

---

### Adım 3: Lojistik ve Sevk Hazırlığı (Shipink)
1. **Sipariş Eşitlemesi:**
   * WooCommerce ile entegre Shipink paneline girilir; yeni sipariş görüntülenir.
2. **Kargo Seçimi ve Etiket Üretimi:**
   * Anlaşmalı kargo firması (Yurtiçi, MNG vb.) seçilir.
   * Kargo takip barkodu üretilir ve termal etikete/A4 kağıda yazdırılır.
3. **Paketleme Standartları:**
   * **Koli İçine:** GİB'den alınan e-Arşiv faturanın A4 çıktısı katlanıp ürünün yanına konur.
   * **Koli Dışına:** Shipink kargo barkodu şeffaf cebe konur veya paketin üzerine düzgünce yapıştırılır.
   * Paket kargo şubesine teslim edilir / kuryeye verilir.

---

### Adım 4: Müşteri Bildirimi ve Siparişin Kapatılması (WooCommerce)
1. **Yasal Tebliğ:** İndirilen e-Arşiv fatura PDF'i müşterinin sipariş e-posta adresine gönderilir (Faturanın elektronik ortamda iletilmesi yasal tebliğ için zorunludur).
2. **Durum Güncellemesi:**
   * Shipink tarafından üretilen Kargo Takip Kodu WooCommerce sipariş notuna eklenir.
   * WooCommerce sipariş durumu **"Tamamlandı" (Completed)** olarak işaretlenir.

---

### Adım 5: Dönem Sonu Muhasebe Takibi (Gider & Arşivleme)
* **Gider Kayıtları:**
  1. PayTR'ın her ay kestiği komisyon faturasını panelden indirin ve muhasebecinize iletin (Şirket gideridir).
  2. Shipink'in kargo taşıma faturalarını gider olarak işleyin.
* **Arşivleme:** Düzenlenen e-Arşiv fatura PDF'lerini Google Drive veya yerel diskte klasörleyerek Vergi Usul Kanunu gereği **5 yıl** boyunca saklayın.