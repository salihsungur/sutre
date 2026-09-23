# PACK-79 — P76 footer logoları: BAĞIMSIZ DOĞRULAMA (tester, read-only)

## 0. ÖN KOŞUL / İLK ADIMLAR
- Repo: `/Users/salihsungur/dev/sutre` — beklenen HEAD = origin/main = `be6dc4320c7554b5d80d4026ecb4ddacc08e50e9`.
- İlk tool çağrıların: (1) `AGENTS.md` TAMAMI, (2) `dispatch/out/out-78-footer-payment-logos.md` TAMAMI (iddiaların listesi), (3) `git log --oneline -3` + `git rev-parse HEAD origin/main`.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**
- Sen **bağımsız doğrulayıcısın**: coder'ın raporu "yapıldı" kanıtı DEĞİLDİR. Her iddiayı **kendin yeniden üret**; coder'ın anlattığına dayanarak PASS yazma.

## 1. DOĞRULANACAK İDDİALAR (her biri için ham kanıt topla)
Hedef: **staging** (`https://staging.sutre.store`) — production'a DOKUNMA (sutre.store hâlâ 3.6.5, P76 orada yok).

1. **Footer bloğu canlıda**: ana sayfada `.sv-footer__payments` var; içinde dört marka inline SVG (`aria-label` = Visa, Mastercard, TROY, PayTR); **harici logo isteği yok** (SVG inline — ağ sekmesinde/HTML'de `img src` ile logo yüklenmiyor).
2. **Konum**: logo bloğu "İstanbul" yazısının **SOLUNDA** (hem DOM sırası hem geometri: logo bloğunun sağ kenarı < İstanbul öğesinin sol kenarı; masaüstü 1440px).
3. **Parity (eşit görsel yükseklik)**: dört SVG'nin render edilen yükseklikleri arasındaki fark **≤ 2px**; aralarındaki boşluklar eşit (±2px).
4. **Layout regresyonu — footer kendi taşmasını ÜRETMİYOR**: yatay kaydırma (`document.scrollingElement.scrollWidth - clientWidth`) 0 olmalı. (Coder raporunda mobilde `.sv-cat-card` kaynaklı 2px taşma bildirildi — bunun footer'la ilgisi olmadığını kendi ölçümünle **ayrıca göster**: footer gizlenince taşma kalıyor mu?) — bu madde P76 kapsamı dışı bir bulguysa "P76 DIŞI" olarak işaretle.
5. **Sayfa yelpazesi (regresyon)**: en az şu sayfalarda HTTP 200 + footer'da dört logo + **PHP Fatal/Warning 0** + konsol hatası 0:
   `/` · `/magaza` (veya mağaza linki) · bir ürün sayfası · `/sepet` · `/odeme` · hukuki sayfalardan en az 2 (KVKK/mesafeli satış/teslimat-iade) · `/hesabim` (veya hesap/giriş sayfası).
   Viewport: **1440 · 768 · 375**.
6. **CSS cache-buster**: canlı HTML'de stil dosyası `?ver=3.6.6` ile yükleniyor VE canlı `style.css` içinde `.sv-footer__payments` kuralı var (indir, `grep` et).
7. **SHA-256 iddiası bağımsız yeniden üretimi**: coder "yerel↔uzak 3/3 eşleşti" dedi. Canlı `footer.php`, `style.css`, `functions.php` dosyalarını indir (HTTP ile — mümkünse `?v=<ts>` ile), SHA-256 hesapla ve **repo'daki HEAD kopyalarının** SHA-256'sıyla karşılaştır. Eşleşmiyorsa hangi dosya/nasıl farklı raporla.
8. **AGENTS.md §7 P76 kaydı**: durum etiketi, iddia edilen kanıtların (SHA'lar, curl sonuçları, ekran görüntüsü yolları) **gerçekle uyuşup uyuşmadığı**.
9. **Migration/tema kaynağı kontrolü**: staging'de yüklenen tema klasörünün doğru tema olduğunu göster (canlı HTML'de tema yolu / `sutre-child` izi).

## 2. YÖNTEM
- Playwriter (gerçek Brave) izinli; gerekiyorsa `curl` + `grep` ile ham HTML kanıtı topla. Ölçümler için tarayıcıda `evaluate` kullan (scrollWidth, boundingBox'lar, computed height).
- Her madde için: **İDDİA → YÖNTEM → HAM ÇIKTI → SONUÇ**. Coder'ın raporundaki bir ifadeyi doğrulayamıyorsan `DOĞRULANAMADI` yaz.
- Bulgu bulursan: yeniden üretilebilir adımlar + ekran görüntüsü. **Düzeltme YAPMA** (read-only) — yalnız raporla.

## 3. ROL SINIRI (read-only — bağlayıcı)
- Repoda **hiçbir dosyayı değiştirme**, commit/push YOK, FTP YOK, production YOK.
- Yalnız şunları yazabilirsin: `dispatch/out/out-79-footer-verification.md` + `dispatch/out/evidence/out-79-*.png`.
- Provider/model override YASAK.

## 4. SINIR — bu bir kabul kapısıdır
- Sonuç etiketlerin: her madde için **PASS / CONDITIONAL PASS / FAIL / BLOCKED / NOT RUN**. Prose ile "iyi görünüyor" YASAK (§0.6).
- Nihai hüküm: **PASS** (tüm maddeler PASS) · **CONDITIONAL PASS** (koşulları ve kapanış milestone'unu yaz) · **FAIL** (bloklayan madde + kanıt).
- Production onayı için tek engelleyici kural: madde 1-5'ten biri FAIL ise hüküm FAIL olur.

## 5. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-79-footer-verification.md` (≤900 kelime).
- Yapı: (1) Hüküm + koşullar, (2) madde madde tablo (iddia/yöntem/kanıt/sonuç), (3) sayfa×viewport matrisi, (4) bağımsız SHA-256 tablosu, (5) bulgular (P76 içi / P76 dışı ayrımıyla), (6) `git status -sb` çıktısı (repoda senin değişikliğin olmamalı), (7) açık riskler.
- Ham çıktıları olduğu gibi yapıştır (kırpma yok); imkânsızsa dosya yolu ver.