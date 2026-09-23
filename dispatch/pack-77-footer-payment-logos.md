# PACK-77 — P76: Footer ödeme yöntemi logoları (uygulama + staging deploy)

## 0. ÖN KOŞUL / İLK ADIMLAR (zorunlu)
- Repo: `/Users/salihsungur/dev/sutre` — beklenen HEAD = origin/main = `fea2080edae475b965105f7d08a57dc062371ed7`.
- İlk tool çağrıların: (1) `/Users/salihsungur/dev/sutre/AGENTS.md` TAMAMI, (2) `/Users/salihsungur/dev/sutre/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMI, (3) araştırma raporu `/Users/salihsungur/dev/sutre/dispatch/out/out-76-paytr-logos-research.md` TAMAMI.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun. Plan metni yazma; plan yazmak yasak.**

## 1. GÖREV
Sahip talebi (birebir): *"footerda 'istanbul' yazısının soluna mastercard, visa, paytr logosu ekler misin (mastercard visa dedim ama kısaca paytr nin desteklediği ödeme yöntemlerinin logosunu ekle)."*

**Sonuç:** `.sv-footer__bottom` bloğu içinde, `.sv-footer__origin` (İstanbul) öğesinin **hemen SOLUNA** yeni bir ödeme-logoları bloğu ekle. Logolar **Visa · Mastercard · TROY · PayTR** (araştırma raporu §3).

### 1.1 KESİN SAHA BİLGİLERİ (araştırma raporundan; doğrulanmış)
- `theme/sutre-child-v2/footer.php:49-52` → `.sv-footer__bottom`; **satır 51** = `<p class="sv-footer__origin">İstanbul</p>`. Yeni blok **51. satırın hemen öncesine** girer.
- `theme/sutre-child-v2/style.css:949-958` → `.sv-footer__bottom` (flex, space-between, align-center, gap 16px); 959-964 copyright; 965-971 origin; **974-978 @781px** ve **2057-2061 @480px** mobil kurallar.
- `theme/sutre-child-v2/functions.php:8` → `define( 'SUTRE_VERSION', '3.6.5' )`; satır 18 enqueue (CSS cache-buster).
- **ÖNEMLİ:** `theme/sutre-child-v2/assets/img/` yerelde BOŞ ve `git ls-files theme/sutre-child-v2/assets` = **0 dosya** → tema görselleri repoda takip EDİLMİYOR, yalnız sunucuda. Bu nedenle **logo dosyası eklemek yerine INLINE SVG kullan** (tek deploy kanalı git olur, ek FTP adımı gerekmez).

### 1.2 Uygulama kuralları (bağlayıcı)
1. **Inline SVG** (harici istek/CDN YOK). Her logo: `<svg role="img" aria-label="Visa" ...>` + uygun `viewBox`, `height` ~20px, genişlik oranı korunmuş.
2. **Marka uyumu:** Mastercard **tam renkli** (kırmızı/turuncu, gri/tek-renk YASAK); Visa mavi; TROY kendi renkleri; PayTR kendi renkleri. **Renk/logomark geometrisini değiştirme.**
3. **"Parity" kuralı:** dört logo **aynı görsel yükseklikte** ve aralarında eşit boşluk; Mastercard için temiz alan ≥ daire çapının 1/4'ü (20px yükseklikte pratikte ≥ 5px boşluk).
4. SVG path verisi **gerçek/güvenilir kaynaktan** alınmalı (üretici marka merkezleri: Visa brand center, Mastercard brand center, TROY Medya Merkezi, PayTR resmî sitesi; erişilemezse Vikipedi/Wikimedia Commons'taki resmî marka SVG'leri). **Path verisi uydurma YOK** — her logonun kaynak URL'sini rapora yaz. Emin olmadığın bir markayı çizmeye çalışma; bulunamazsa o markayı **ekleme** ve rapora "bulunamadı" yaz.
5. Mevcut footer metinleri/konumları DEĞİŞMEZ: copyright ve "İstanbul" aynı yerde kalır; **konum kuralı sabit** — logo bloğu yalnız origin'in SOLUNA eklenir (`.sv-footer__bottom` flex düzeni bozulmaz).
6. **Scoped CSS:** yalnız `.sv-footer__payments` (ve çocukları) için kurallar; global selector YASAK. Mobil: @781 ve @480'de taşma olmayacak şekilde (satır kayabilir ama düzen bozulmaz).
7. Erişilebilirlik: her SVG'de `role="img"` + `aria-label`; logo öğeleri tıklanabilir link DEĞİL.
8. Metin sarmalama mevcut desenle uyumlu (`esc_html_e` kullanan yerlerde kural korunur; logo bloğunda çevrilebilir metin yoksa düz markup yeterli).
9. `functions.php:8` → `SUTRE_VERSION` **'3.6.5' → '3.6.6'** (CSS cache-buster kuralı).

## 2. ZORUNLU KANIT / GATE SIRASI
1. `php -l theme/sutre-child-v2/footer.php` + `php -l theme/sutre-child-v2/functions.php` → **0 hata** (çıktıyı rapora yapıştır).
2. Değişikliklerden sonra `git diff --stat` (yalnız footer.php + style.css + functions.php + AGENTS.md).
3. **Yerel render kontrolü:** footer'ı içeren statik bir HTML taslağı ile logoların görüntüsünü al (bloğu tarayıcıda aç) ve **ekran görüntüsünü** `dispatch/out/evidence/out-77-footer-local-desktop.png` olarak kaydet. (Playwriter/Brave izinli; hesap girişi gerekmez.)
4. Commit (tek mantıklı commit): `feat(footer): odeme yontemi logolari (Visa/Mastercard/TROY/PayTR) - istanbul soluna` → `git push origin main`.
5. **Staging deploy (FTP, kanal B):** bilgiler `~/Desktop/ftpinfo.txt` (değerleri rapora/log'a YAZMA). Hedef: `/staging.sutre.store/wp-content/themes/sutre-child/{footer.php,style.css,functions.php}` — **MUTLAK yol** kullan (göreli yol home köküne yazar; P43 kazası). Upload sonrası **SHA-256 karşılaştırma zorunlu** (yerel vs uzak).
6. **Canlı doğrulama (staging):** cache-buster ile
   `curl -skL "https://staging.sutre.store/?v=$(date +%s)" | grep -c 'sv-footer__payments'` → **≥1** olmalı. Ayrıca `curl -skL "https://staging.sutre.store/?v=$(date +%s)" | grep -o 'aria-label=\"[^\"]*\"' | head` çıktısında Visa/Mastercard/TROY/PayTR etiketleri görünmeli (gerçek çıktıyı rapora yapıştır).
7. **Staging ekran görüntüsü (kanıt):** `dispatch/out/evidence/out-77-footer-staging-desktop.png` + `...-mobile.png` (375px genişlik).
8. **AGENTS.md §7 P76 maddesini güncelle:** durum `SÜRÜYOR` → staging doğrulandı/production `ONAY BEKLİYOR`; commit SHA'ları + canlı kanıt + ekran görüntüsü yollarını yaz. Commit mesajı: `docs(agents): P76 staging dogrulandi - production onay bekliyor`.

## 3. ROL SINIRI
- Yalnız şu dosyalar: `theme/sutre-child-v2/footer.php`, `theme/sutre-child-v2/style.css`, `theme/sutre-child-v2/functions.php`, `AGENTS.md` + rapor/kanıt dosyaların.
- **PRODUCTION DEPLOY YOK** (sahibin onayı gerekir — sonraki paket).
- checkout/PayTR modülü/başka tema dosyaları YASAK.
- Provider/model override YASAK. Hata alırsan aynı konfigle tekrar dene; görevi küçült.

## 4. KAPSAM DIŞI
- Production'a FTP/deploy; PayTR merchant ayarları; checkout değişiklikleri; logo dosyalarını repoya eklemek; başka `.md` plan dosyası oluşturmak.

## 5. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-77-footer-payment-logos.md` (≤900 kelime).
- Bölümler: (1) ne yapıldı (madde madde, "güzel görünüyor/başarılı" gibi ifade YASAK — §0.6), (2) eklenen markup (satır aralığı), (3) kullanılan logo kaynak URL'leri (her marka için), (4) CSS bloğu (satır aralığı) + responsive davranış, (5) php -l çıktıları, (6) commit/push SHA'ları + `git rev-parse HEAD origin/main`, (7) FTP upload + SHA-256 karşılaştırma sonuçları, (8) canlı curl çıktıları, (9) ekran görüntüsü yolları, (10) AGENTS.md güncellemesi, (11) **geri alma komutu** (tek commit revert + FTP geri yükleme notu), (12) riskler/açık noktalar.
- Kanıtlanmayan hiçbir şeyi "yapıldı" yazma; yapılamayanı açıkça `YAPILAMADI: <neden>` olarak yaz.

## 6. TEMİZLİK
- Geçici dosya/servis bırakma; `git status -sb` çıktısını raporun sonuna ekle; `git diff --check` temiz olmalı.