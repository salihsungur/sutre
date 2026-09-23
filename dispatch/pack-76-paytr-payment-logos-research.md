# PACK-76 — PayTR Ödeme Yöntemi Logoları: ARAŞTIRMA (read-only)

## 0. ÖN KOŞUL / İLK ADIMLAR (zorunlu)
- Repo: `/Users/salihsungur/dev/sutre` — beklenen HEAD = origin/main = `ceef9e67dd2228b500df8ebb32aee184800169c8`.
- İlk tool çağrıların: (1) `read_file` ile `/Users/salihsungur/dev/sutre/AGENTS.md` TAMAMINI oku, (2) `read_file` ile `/Users/salihsungur/dev/sutre/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMINI oku.
- **İlk yanıtın ZORUNLU olarak bir tool çağrısı olsun. Plan metni yazıp bitirme; plan yazma yasak.**

## 1. GÖREV
Sahip (Salih) şunu istedi: *"paytr onaylaması için websitede mastercard visa gibi şeylerin logosu olması gerekiyormuş. footerda 'istanbul' yazısının soluna mastercard, visa, paytr logosu ekler misin — kısaca paytr'nin desteklediği ödeme yöntemlerinin logosunu ekle."*

Sen **yalnız araştırma** yapıyorsun (kod yazma, deploy yok). Çıktın, sonraki coder paketinin girdisi olacak.

### 1.1 Cevaplanacak sorular (her biri KAYNAK URL'si ile)
1. **PayTR hangi ödeme yöntemlerini destekliyor?** (Visa, Mastercard, Troy, Amex, BKM Express, banka kartı, taksit vb.) — resmi PayTR kaynaklarından (paytr.com dokümantasyon/yardım/merchant sözleşmesi) doğrula.
2. **PayTR başvuru/onay sürecinde "ödeme yöntemi logolarının sitede gösterilmesi" gerçekten bir şart mı?** Varsa: şartın **tam metni** ve hangi sayfada (footer/checkout/gizlilik) istendiği. Yoksa: bunu açıkça yaz ("resmi şart bulunamadı") ve buna karşılık **sektör pratiğini** kaynakla belirt.
3. **Hangi logo seti eklenmeli?** Öneri: yalnız PayTR'nin desteklediği yöntemler + PayTR'nin kendi logosu (ödemeler PayTR altyapısından geçecekse). Türkiye'de beklenen asgari set nedir — kaynakla.
4. **Marka-uyumlu logo kaynakları:** Visa / Mastercard / Troy / PayTR marka merkezleri (official brand centers) veya PayTR'nin merchant'lara sağladığı hazır logo seti var mı? **Lisans/uyum notu:** logoların rengi/ölçeği/boşluğu ile ilgili "doğru kullanım" kuralları (ör. Mastercard'ın kırmızı-turuncu dairelerinin oranı, Visa mavi tonu, tek renk kullanım izni).
5. **Teknik format önerisi:** SVG mi PNG mi (tema `assets/img/` altında); yükseklik (footer'a uygun ~16–24px?), yatay dizilim, alt (alt="") metinleri, erişilebilirlik (`aria-label`), lazy-load gereksizliği, retina için @2x.

### 1.2 Saha kanıtı (zorunlu, dosya:satır ile)
Aşağıdakileri oku ve **satır numarası kanıtıyla** raporla:
- `/Users/salihsungur/dev/sutre/theme/sutre-child-v2/footer.php` → footer'da **"istanbul"** (veya "İstanbul") metninin geçtiği satır(lar); o metnin bulunduğu HTML bloğunun yapısı; **hemen soluna** logo bloğu eklenebilecek yer (satır no + mevcut markup örneği).
- `/Users/salihsungur/dev/sutre/theme/sutre-child-v2/style.css` → footer alt bölgesiyle ilgili mevcut selector'ler (footer copyright/alt satır), logo bloğu için **scoped** selector önerisi (global selector yazılmasın kuralı).
- `/Users/salihsungur/dev/sutre/theme/sutre-child-v2/assets/img/` → mevcut görsel düzeni (logo/ ve site/ alt klasörleri); yeni logo dosyalarının konacağı yol.
- `functions.php` içinde `SUTRE_VERSION` sabiti var mı ve değeri ne (CSS cache-buster kuralı) — satır no ile.

### 1.3 Çıktı: önerilen uygulama planı (kısa)
- Eklenecek logo listesi (öncelik sırasıyla, soldan sağa).
- Format + dosya adları (ör. `assets/img/payment/visa.svg`) + boyutlar.
- Footer markup önerisi (mevcut markup diliyle uyumlu, kısa kod örneği).
- CSS selector önerisi.
- Açık riskler (marka lisansı, PayTR şartı belirsizliği).

## 2. ROL SINIRI (read-only)
- Sen **hiçbir dosyayı değiştirmezsin**, commit/push YAPMAZSIN, FTP/deploy YOK.
- Yalnızca şu dosyayı YAZABİLİRSİN: `/Users/salihsungur/dev/sutre/dispatch/out/out-76-paytr-logos-research.md`
- Web erişimi serbest (paytr.com, brand center'lar). **Ücretli API çağrısı yok.**
- Provider/model override YASAK. Hata alırsan aynı konfigle tekrar dene; görevi küçült.

## 3. KAPSAM DIŞI
- Kod yazmak/düzenlemek, footer.php veya style.css değiştirmek, görsel dosyası oluşturmak.
- Deploy, FTP, cPanel.
- PayTR merchant başvurusu yapmak.
- Logo dosyalarının kendisini indirip depoya koymak (bu coder paketine ait).

## 4. RAPOR (zorunlu)
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-76-paytr-logos-research.md`
- Bölümler: (1) Özet (≤10 satır, "ne yapıldı" dilinde — "güzel görünüyor/başarılı" gibi ifade KULLANMA), (2) PayTR desteklenen yöntemler + kaynak URL'ler, (3) Onay şartı var mı — tam metin/alıntı + URL, (4) Logo listesi önerisi, (5) Marka-uyum kuralları + asset kaynakları (URL), (6) footer.php/style.css/assets img/SUTRE_VERSION saha kanıtları (dosya:satır), (7) Uygulama planı (markup+CSS önerisi), (8) Açık sorular/riskler.
- Rapor ≤ 1200 kelime. Belirsiz olan yeri "DOĞRULANAMADI" olarak işaretle; tahmin yazma.
- Rapor sonunda: `KANIT: <her iddianın kaynağı — URL veya dosya:satır>` listesi.

## 5. TEMİZLİK
- Geçici dosya bırakma; `git status -sb` çıktısını raporun sonuna ekle (repoda senin değişikliğin olmamalı).