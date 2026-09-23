# out-76 — PayTR Ödeme Yöntemi Logoları: ARAŞTIRMA (read-only)

Tarih: 2026-09-24 · Paket: PACK-76 · İnceleme anında repo HEAD = `ceef9e67…` (origin/main); tur sırasında paralel bir ajan `fea2080` (P76 AGENTS kaydı) commit'ledi — bu raporun değişikliği DEĞİLDİR. Bu turda kod/dosya/commit/deploy YOK.

## 1) Özet (ne yapıldı)
- PayTR resmî destek merkezi + Sanal POS + Pay by Link sayfaları incelendi; desteklenen kart/yöntemler resmî FAQ metinleriyle toplandı.
- "Logoların sitede gösterilmesi" şartının PayTR'nin yayımlanmış **başvuru koşullarında bulunmadığı** görüldü.
- Visa/Mastercard/Troy marka merkezleri, asset ve kullanım kuralları URL'leriyle belirlendi.
- PayTR'nin resmî marka/basın kiti **bulunamadı** (DOĞRULANAMADI).
- footer.php / style.css / functions.php / assets-img saha kanıtları satır numarasıyla çıkarıldı; uygulama planı + riskler yazıldı.

## 2) PayTR desteklenen ödeme yöntemleri (resmî kaynak URL'leri)
- **PayTR Sanal POS FAQ (resmî):** kabul edilen: *kredi kartı, banka kartı, taksitli ödeme, dijital cüzdan*; kart markası olarak *"VISA, Mastercard ve TROY logolu kartlar dahil tüm yurt içi banka kartları"*; yurt dışı kart desteği var. — https://www.paytr.com/paytr-sanal-pos (**RESMİ KAYNAK**)
- **Pay by Link (EN) FAQ (resmî):** *American Express, VISA, MasterCard* kredi/banka kartları + *UnionPay, Troy, BKM Express, Tosla*. — https://www.paytr.com/en/payment-links (**RESMİ KAYNAK**)
- **DOĞRULANAMADI:** Amex / UnionPay / BKM Express / Tosla'nın **WooCommerce Sanal POS modülü** kapsamında da aktif olduğu; bu geniş liste Pay by Link sayfasında belgelenmiştir. Sanal POS sayfası Visa/MC/Troy + "tüm banka kartları" ile sınırlı anlatır. Tek kesin kaynak mağaza panelindeki aktif POS/taksit ayarlarıdır (sahip teyit etmeli).
- %2,19 komisyon bir promosyon referansıdır, sözleşme oranı değil (anayasa §1.3) — canlıya çıkmadan resmî kaynaktan yeniden doğrulanır.

## 3) Onay şartı var mı? (tam metin / alıntı)
PayTR Destek Merkezi → Başvuru → "Başvuru koşulları nelerdir?" **tam metin:**
> "Web sitenizde gizlilik politikası, mesafeli satış sözleşmesi, teslimat ve iade şartları, sitenizin Hakkımızda ve İletişim sayfalarınız aktif olmalıdır. Satışını yaptığınız ürün/hizmetlerle ilgili tüm lisans ve ilgili makamlarca verilen belgelerinizin tam olması gerekmektedir."
Kaynak: https://www.paytr.com/destek-merkezi/basvuru (**RESMİ KAYNAK**)

**Sonuç:** Resmî başvuru koşullarında **ödeme yöntemi logolarına dair şart YOK** → sahibin duyduğu "logo zorunlu" bilgisi resmî kaynakla **DOĞRULANAMADI** (olası kaynak: başvuru formu/temsilci sözlü yönlendirmesi — bunun için kaynak yok, aksi iddia edilmez).
- **Sektör pratiği (GÜVENİLİR İKİNCİL):** footer'da ödeme logoları bir güven sinyalidir — https://mudosdigital.com/tr/e-ticaret-web-sitelerinin-altbilgisi-footer-nasil-tasarlanmali/ ; topluluk görüşü (BELİRSİZ): https://www.reddit.com/r/UI_Design/comments/11d6zlq/
- Pratik: logo bloğu onay için **zorunlu değil**; düşük maliyetli güven öğesi olarak eklenebilir. Görsel karar sahibe ait (§0.6).

## 4) Önerilen logo listesi (soldan sağa, öncelikle)
1. **Visa** · 2. **Mastercard** · 3. **TROY** (üçü de resmî desteklenen) · 4. **PayTR** (ödemeler PayTR altyapısından geçiyor; sahibin talebi).
- **İsteğe bağlı / DOĞRULANMADI:** Amex, UnionPay, BKM Express, Tosla, Maestro — yalnız mağaza panelinde aktiflerse (bkz. §2).
- **Asgari TR seti** (kaynak: PayTR resmî kart listesi): Visa + Mastercard + Troy + PayTR.
- **DOĞRULANAMADI:** "Türkiye'de beklenen asgari set" için yasal/resmî liste bulunamadı; öneri PayTR FAQ'undaki markalara dayanır.

## 5) Marka-uyum kuralları + asset kaynakları (URL)
- **Mastercard artwork:** https://www.mastercard.com/brandcenter/us/en/download-artwork.html (`mc_symbol_SVG.zip` / `mc_symbol_PNG.zip`).
- **Mastercard kuralları (RESMİ):** https://www.mastercard.com/brandcenter/us/en/brand-requirements/mastercard.html → Symbol **tam renk zorunlu** (gri/başka renk yasak); **net boşluk ≥ daire genişliğinin 1/4'ü**; **parity:** Symbol diğer ödeme markalarından küçük/soluk gösterilemez.
- **Visa Brand Center (RESMİ):** https://corporate.visa.com/en/about-visa/brand.html → PNG/SVG/EPS/AI; web için **PNG veya SVG** önerilir; Visa Blue + beyaz. İndirme: https://globalclient.visa.com/brand-mark .
- **TROY Medya Merkezi (RESMİ):** https://www.troyodeme.com/tr/troy-hakkinda/medya-merkezi → **TROY-Logolar.zip** + **Kurumsal Kimlik Kılavuzu 2025 PDF**.
- **PayTR logosu:** resmî basın/marka kiti **bulunamadı** (DOĞRULANAMADI). Üçüncü taraf siteler (brandfetch, pixelbag) **BELİRSİZ** — resmî asset yerine geçmez; PayTR destekten temin/izin önerilir.

## 6) Saha kanıtları (dosya:satır)
- **footer.php:49-52** — `49|<div class="sv-footer__bottom">` · `50|<p class="sv-footer__copyright">&copy; YYYY Sutre — Tüm hakları saklıdır</p>` · **`51|<p class="sv-footer__origin">İstanbul</p>`** · `52|</div>` → "İstanbul" **footer.php:51**; logo bloğu **51'in hemen öncesine** (copyright ile origin arası) eklenirse "İstanbul'un solu" karşılanır. Tek footer kaynağı → tüm sayfalarda görünür.
- **style.css:949-958** `.sv-footer__bottom` (flex, `space-between`, `align-items:center`, üst çizgi) · **959-964** `.sv-footer__copyright` · **965-971** `.sv-footer__origin` · **974-978** @max-781 (flex→column, ortalanır) · **2057-2061** @480 (column, ortalanır).
- **assets/img/**: `logo/` ve `site/` yerelde **boş**; `git ls-files theme/sutre-child-v2/assets` = **0 dosya** → tema görselleri repoda takip edilmiyor, yalnız sunucuda. Yeni hedef yol: `theme/sutre-child-v2/assets/img/payment/` (sunucuda `/wp-content/themes/sutre-child/assets/img/payment/`).
- **SUTRE_VERSION** — **functions.php:8**: `define( 'SUTRE_VERSION', '3.6.5' );` · **functions.php:18**: `wp_enqueue_style(... , SUTRE_VERSION)` → CSS değişince sürüm artırılır.

## 7) Uygulama planı (coder paketine girdi)
- **Format:** **SVG** (ölçeklenebilir, @2x sorunu yok; Visa web için SVG/PNG öneriyor, Mastercard SVG veriyor). PNG yalnız SVG edinilemezse.
- **Dosyalar:** `assets/img/payment/{visa,mastercard,troy,paytr}.svg`. **Boyut:** yükseklik ~20-24px (mobil ~18-20px), genişlik `auto`, aralık 12-16px; Mastercard net-boşluk kuralına uy.
- **Markup (footer.php:50 ile 51 ARASINA), mevcut dile uyumlu:**
```php
<div class="sv-footer__pay" role="group" aria-label="<?php esc_attr_e( 'Kabul edilen ödeme yöntemleri', 'sutre' ); ?>">
  <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/payment/visa.svg' ); ?>" alt="Visa" height="22" loading="lazy">
  <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/payment/mastercard.svg' ); ?>" alt="Mastercard" height="22" loading="lazy">
  <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/payment/troy.svg' ); ?>" alt="Troy" height="22" loading="lazy">
  <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/payment/paytr.svg' ); ?>" alt="PayTR" height="22" loading="lazy">
</div>
```
  (Footer ilk ekranda değil → `loading="lazy"` zararsız; `alt` marka adı olmalı — boş alt YANLIŞ olur.)
- **CSS (scoped, style.css FOOTER v3 bloğuna; global selector YAZILMAZ):**
```css
.sv-footer__pay { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.sv-footer__pay img { height: 22px; width: auto; display: block; }
@media (max-width: 781px) { .sv-footer__pay { justify-content: center; } .sv-footer__pay img { height: 20px; } }
```
  `.sv-footer__bottom` `space-between` olduğundan masaüstü sırası: copyright (sol) → logo bloğu (orta) → İstanbul (sağ).
- **Deploy:** footer.php + style.css = TÜM sayfalar → dosya listesi eksiksiz atılır (P68-fix kuralı); SVG'ler FTP ile mutlak yolla `assets/img/payment/`'e; **SUTRE_VERSION → 3.6.6**; LiteSpeed Purge All + `?v=` curl doğrulaması.

## 8) Açık sorular / riskler
- **DOĞRULANAMADI:** Logolar PayTR onayı için resmî şart değil → "logo eklendi, onay gelir" garantisi yok.
- **Marka lisansı:** Visa/Mastercard/Troy asset'leri marka merkezlerinden **değiştirilmeden**, renk/boşluk/**parity** kurallarına uygun kullanılmalı.
- **PayTR logosu:** resmî asset/izin kaynağı yok → tedarik yolu netleşmeli; üçüncü taraf logo siteleri ticari kullanım için güvenilir sayılmaz.
- **Yanıltıcı beyan riski:** panelde aktif olmayan bir yöntemin (Amex/Tosla vb.) logosu eklenirse yanlış izlenim doğar.
- **Repo dışı asset deseni:** SVG'ler repoya eklenirse desen değişir; FTP'de kalırsa yedeklenmez — karar verilmeli.

## KANIT
- Desteklenen yöntemler: https://www.paytr.com/paytr-sanal-pos · https://www.paytr.com/en/payment-links
- Başvuru koşulları tam metni (logo şartı YOK): https://www.paytr.com/destek-merkezi/basvuru
- Sektör pratiği: https://mudosdigital.com/tr/e-ticaret-web-sitelerinin-altbilgisi-footer-nasil-tasarlanmali/ (GÜVENİLİR İKİNCİL) · https://www.reddit.com/r/UI_Design/comments/11d6zlq/ (BELİRSİZ)
- Mastercard: https://www.mastercard.com/brandcenter/us/en/download-artwork.html · https://www.mastercard.com/brandcenter/us/en/brand-requirements/mastercard.html
- Visa: https://corporate.visa.com/en/about-visa/brand.html · https://globalclient.visa.com/brand-mark
- TROY: https://www.troyodeme.com/tr/troy-hakkinda/medya-merkezi
- PayTR marka kiti: DOĞRULANAMADI
- Repo: footer.php:49-52 (:51 İstanbul) · style.css:949-958, 959-964, 965-971, 974-978, 2057-2061 · functions.php:8, :18 · `git ls-files theme/sutre-child-v2/assets` = 0

## Temizlik / git durumu
Bu turda repoda değişiklik yapılmadı (yalnız bu rapor dosyası yazıldı) — commit/push yok. `git status -sb`:
```
## main...origin/main
?? dispatch/out/out-76-paytr-logos-research.md
?? dispatch/out/out-76b-agents-p76-record.md
?? dispatch/pack-76-paytr-payment-logos-research.md
?? dispatch/pack-76b-agents-p76-record.md
```
Not: `fea2080` (P76 AGENTS kaydı) tur sırasında başka bir ajan tarafından commit'lendi; HEAD = origin/main = `fea2080edae475b965105f7d08a57dc062371ed7`. Benim eklediğim tek dosya yukarıdaki `out-76-…md`'dir.