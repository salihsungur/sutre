# WooCommerce 11.1.0 Staging Kurulum Talimatı — staging.sutre.store (Paket 17, Faz 2/1)

> Belge türü: İŞLETİM TALİMATI (sahibin cPanel Terminal'inde + wp-admin'de sırayla uygulanır).
> Sahip: Salih. Hazırlayan: @coder. Tarih: 2026-09-15.
> Bağlılık: version-lock.md (WC 11.1.0 kilidi), ADR-002 (Softaculous YASAK, public_html'e yazma YASAK — §0.6),
> P15 staging kurulum talimat formatı (kanıt dosyası + her adım çıktısı).
> Kural: HER adımın terminal çıktısını / ekranını kanıt dosyasına yapıştır (`woo-kurulum-kanit.txt`).
> Secret YASAĞI: hiçbir parola/anahtar bu talimata, kanıt dosyasına veya herhangi bir loga YAZILMAZ (§4.2).

## 0. Kurulum öncesi sürüm kilidi doğrulama (2026-09-15 anlık tarama — @coder, wp.org resmî API)

Anlık wp.org eklenti API taraması (`api.wordpress.org/plugins/info/1.2/`, 2026-09-15):

| Alan | Değer |
|---|---|
| version | 11.1.0 |
| requires (WP) | 7.0 |
| tested (WP) | 7.1 |
| requires_php | 7.4 (minimum) |

→ staging'de kurulu WP 7.1 + PHP 8.5.9 (web tier) minimumların ÜSTÜNDE, uyumlu. Zip arşivinin içinden
(`woocommerce/woocommerce.php` header) aynı beyanlar doğrulandı: `* Version: 11.1.0`, `* Requires at least: 7.0`,
`* Requires PHP: 7.4`. **WooCommerce 11.1.0 kilit bu taramayla geçerli — sürüm kilidi kapanmıştır.**

## 1. Zip indir + checksum doğrula (cPanel Terminal)

```bash
curl -sSL https://downloads.wordpress.org/plugin/woocommerce.11.1.0.zip -o /tmp/wc.zip
md5sum /tmp/wc.zip
sha256sum /tmp/wc.zip
```

KANIT BEKLENEN ÇIKTI (@coder'ın 2026-09-15 wp.org CDN taramasından, BİREBİR AYNI OLMALI):

```
c31b1d9ebbfa57fc5a371157f8ef1ff7  /tmp/wc.zip
6bae9bf74d722b6deb15f049687c311cfafc26e3a5d8fa55ac6ea4b9a3a8df19  /tmp/wc.zip
```

HASH EŞLEŞMEDİYSE İLERLEME — arşiv bozuk/değiştirilmiş olabilir; kanıt dosyasına çıktıyı yapıştır ve @coder'a bildir.

## 2. Docroot'a aç (WP 7.1 staging içine plugin olarak)

```bash
cd ~/staging.sutre.store
unzip /tmp/wc.zip -d wp-content/plugins/
ls wp-content/plugins/woocommerce/woocommerce.php
```

KANIT BEKLENEN ÇIKTI: `wp-content/plugins/woocommerce/woocommerce.php` yolu listelenmeli.
(`/tmp` yazılabilir değilse zip'i `~/` altına kaydet, yolu ona göre değiştir. `unzip` yoksa
`python3 -m zipfile -e /tmp/wc.zip wp-content/plugins/` fallback'i çalışır.)

## 3. wp-admin → Plugins → WooCommerce → Activate

Tarayıcı: `https://staging.sutre.store/wp-admin/plugins.php`
→ WooCommerce satırında **Activate** bas.

KANIT BEKLENEN ÇIKTI: "Plugin activated." bandı + plugins listesinde WooCommerce "Active" görünümü.

## 4. Setup Wizard (wp-admin yönlendirmesi)

WooCommerce Activation sonrası otomatik açılan **Setup Wizard** adımlarında:

- **Store details**: ülke/bölge **Türkiye**, para birimi **TRY (₺)**, timezone **Europe/Istanbul**.
- Adres alanları: **Adres 1 / Adres 2 / Şehir / Posta Kodu** alanlarını içer; sahibin gerçek mağaza
  adresi + telefon bilgisini girer (bu girişler sahibin işi — Bot bu verileri yazmaz, bilmez).
- **Industry / Product types**: "Physical products" gibi minimal seçim yeter; ek satış kanalı/abonelik seçimi YOK.
- **Taxes step**: mali veri GİRİLMEZ. Anayasa §5.1 gereği KDV oranı **LEGAL_REVIEW_REQUIRED** —
  bu talimat "single rate 20%" gibi herhangi bir varsayılan KDV modeli YAZMAZ / önermez / kodla girmez.
  Bu adımı sahibin boş bırakması veya atlaması beklenir; vergi kararları ayrı hukuk/mali paketinde.

KANIT BEKLENEN ÇIKTI: wizard bitiş ekranı (store setup tamam) + `WooCommerce → Settings → General`
sayfasında Türkiye/TRY/Europe/Istanbul görünümlü ekran görüntüsü.

## 5. HPOS kontrol (P6 şartı) — zorunlu kanıt

`wp-admin → WooCommerce → Status` sayfasını aç.

KANIT BEKLENEN ÇIKTI: **"High-Performance Order Storage" → "Enabled"** satırı (ekran görüntüsü).
HPOS "Enabled" GÖRÜNMÜYORSA İLERLEME — WC 11.1.0'da HPOS varsayılan sipariş deposudur; "Compatibility mode"
görünüyorsa bunu kanıt dosyasına yaz ve @coder'a bildir (karar: compatibility mode kabul edilmez, inceleme gerekir).

## 6. Ürün/Stock şeması — dummy test ürünü (setup wizard sonrası)

Sahibin elle ekleyeceği tek dummy test ürünü, Faz 2 mağaza şema kurallarına göre:

- **SKU formatı**: `SUTRE-SKU-B0001` (bu formatı birebir kullan — başka format YASAK).
- **Kategori**: Faz 2 sonunda sahibin belirlediği kategori adıyla (paket kapsamında hazır liste YOK —
  kategori adını sahibin belirlemesi NEEDS_OWNER_INPUT kapısıdır; dummy ürün bu kategoriye eklenir).
- Ürün adı: `ürün-1` (sadece dummy/test amaçlı).
- **Fiyat/stok verisi**: gerçek rakam girme zorunluluğu YOK; dummy ürün test amaçlı, canlıya taşınmaz.

KANIT BEKLENEN ÇIKTI: `wp-admin → Products` listesinde `ürün-1` + `SUTRE-SKU-B0001` SKU görünümü.

## 7. Kargo bölgesi (setup wizard içi veya sonra: WooCommerce → Settings → Shipping)

- **Shipping zone**: ad **Türkiye**; Zone regions **Türkiye** (tüm ülke).
- **Shipping method**: **Flat rate** tek yöntem; **Cost: TRY 0,00** başlangıç değeri.
- Canlı fiyatlandırma kararı ayrı açık kapı — bu talimat yalnız sıfır maliyetli tek standart ayarı yazar.

KANIT BEKLENEN ÇIKTI: Shipping settings sayfasında "Türkiye" zone + Flat rate 0,00 satırı ekran görüntüsü.

## 8. Misafir Checkout (guest checkout)

WooCommerce 11.1.0 varsayılanında misafir checkout **açık** gelir (kayıt zorunlu değil).
**Bu varsayılanın kabulü OWNER_APPROVAL_REQUIRED** — sahibin açık kararı olmadan bu ayar
değiştirilmez, saklanmaz veya genişletilmez. Bu paket yalnız VARSAYILAN DURUMU kanıtlar:

KANIT BEKLENEN ÇIKTI: `WooCommerce → Settings → Accounts & Privacy` sayfasında
"Allow customers to place orders without an account" (guest checkout) durumunu gösteren ekran görüntüsü.
(Değer değiştirilmez; sadece görüntülenir ve kanıt dosyasına eklenir.)

## 9. Test — sahibin elle doğrulaması

1. `wp-admin → WooCommerce → Status` → HPOS "Enabled" (Adım 5 kanıtının aynısı, tekrar çekilebilir).
2. `wp-admin → Products` → `ürün-1` listede canlı.
3. Shop ön yüzü (`https://staging.sutre.store/shop/`) → ürün kartı görünüyor.
4. İsteğe bağlı dummy checkout denemesi (PayTR YOK — ödeme adımı bu pakette BEKLENİR,
   checkout tamamlanmaz; yalnız sepete ekleme/checkout sayfası açılıyor kanıtı yeterli).

KANIT BEKLENEN ÇIKTI: 4 maddenin her biri için ekran görüntüsü / tarayıcı çıktısı.

## 10. Kanıt paketleme ve gönderim

`woo-kurulum-kanit.txt` dosyasına (P15 `staging-kurulum-kanit.txt` formatına benzer) sırayla:

- Adım 1: md5sum + sha256sum çıktısı (beklenen iki hash ile eşleşme)
- Adım 2: `ls wp-content/plugins/woocommerce/woocommerce.php` çıktısı
- Adım 3: "Plugin activated" ekran görüntüsü
- Adım 4: Setup wizard bitiş + General settings ekran görüntüsü (adres/telefon verisi KIRMIZILANMAMAK ŞARTIYLA da eklenmesin — sadece ülke/para birimi görünen kısım yeter)
- Adım 5: HPOS "Enabled" ekran görüntüsü
- Adım 6: Products listesi (ürün-1 + SUTRE-SKU-B0001)
- Adım 7: Shipping zone Türkiye + Flat rate 0,00 ekran görüntüsü
- Adım 8: Accounts & Privacy (guest checkout) ekran görüntüsü
- Adım 9: 4 test maddesinin çıktıları

Kanıt dosyası hazır olunca @coder'a "Paket 17 kurulum kanıt dosyası hazır" bilgisi ver.
@coder kanıt dosyasını inceleyip HPOS/durum doğrulamasını bağımsız teyit edecek; Faz 2/2 mağaza
şema/veri modeli paketi bu kanıt kapısından sonra açılır.

---

## Yasaklar özeti (bu talimatta)

- PayTR entegrasyonu / sandbox kurulumu BU PAKETTE YOK (Faz 3).
- KDV oranı, vergi sınıfı, garanti/cayma hakkı metni yazmak YASAK (anayasa §5.1 — LEGAL_REVIEW_REQUIRED).
- Misafir checkout varsayılanını değiştirmek YASAK (OWNER_APPROVAL_REQUIRED — sahibin açık kararı yok).
- PayTR/merchant secret, DB parola, admin parola herhangi bir dokümana/kanıt dosyasına/log'a YAZILMAZ (§4.2).
- Softaculous üzerinden WooCommerce kurulumu YASAK (sürüm kilidi doğrulaması — ADR-002 §2).
- `~/public_html` içine yazmak YASAK (anayasa §0.6 — spokenlab.com.tr ezilmez).
- Production domain (sutre.store) docroot işlemleri BU PAKETTE YOK.
- cPanel API çağrısı BU PAKETTE YOK (yalnız Terminal + wp-admin).
