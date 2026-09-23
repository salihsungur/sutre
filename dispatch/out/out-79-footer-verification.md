# out-79 — P76 Footer Ödeme Logoları: Bağımsız Doğrulama (tester, read-only)

Tarih: 2026-09-23 · Rol: bağımsız doğrulayıcı · Kapsam: **staging** (`https://staging.sutre.store`) · HEAD = origin/main = `be6dc4320c7554b5d80d4026ecb4ddacc08e50e9` (beklenen ile aynı).
Ortam: gerçek Brave (Playwright-core, headless) + curl; ölçüm JS `getBoundingClientRect`. Tüm ham dosyalar: `~/.hermes/profiles/tester/cache/scratch/out79/raw/`.

## 1) HÜKÜM: CONDITIONAL PASS
Engelleyici kural (§4): madde 1-5 FAIL değil → FAIL değil. Ancak madde 7'nin PHP-kaynak SHA alt-iddiası HTTP ile yeniden üretilemedi (sınırlama, uyuşmazlık değil) → tam PASS değil.
**Koşullar:** (a) `footer.php`/`functions.php` kaynak SHA bağımsız teyidi FTP/cPanel erişimi ister (bu pakette yasak); dolaylı kanıt güçlü (aşağıda). (b) Mobil 2px taşma `.sv-cat-card` kaynaklı — **P76 dışı**, ayrı paket. (c) Görsel onay sahipte (§0.6).
**Kapanış milestone:** sahip görsel onayı → production deploy (bu paket production'a dokunmadı).

## 2) Madde madde

| # | İddia | Yöntem | Ham kanıt | Sonuç |
|--|--|--|--|--|
| 1 | Ana sayfada `.sv-footer__payments` + 4 inline SVG, harici istek yok | curl HTML + grep/sayım | `sv-footer__payments`=1; `aria-label="Visa/Mastercard/TROY/PayTR"`=4; blok içi `<img>`=0, `src=`=0, `<svg>`=4 | **PASS** |
| 2 | Logo bloğu İstanbul'un SOLUNDA (DOM + geometri, 1440) | DOM `compareDocumentPosition` + rect | DOM: `payments BEFORE origin`; pay.right=**960.86** < origin.x=**1264.88** | **PASS** |
| 3 | 4 SVG yükseklik farkı ≤2px, aralıklar eşit (±2px) | rect ölçümü 3 viewport | svg h=[18,18,18,18]@1440, [16×4]@375; gaps [12/11.99/12], [10/9.99/10.01] | **PASS** |
| 4 | Footer kendi yatay taşmasını ÜRETMİYOR | scrollWidth−clientWidth; footer gizlenince yeniden | 1440/768 inv=**0**; 375=**2**, footer gizlenince **yine 2** (kaynak `.sv-cat-card` w=379) | **PASS** (mobil 2px **P76 DIŞI**) |
| 5 | Sayfa yelpazesi 200 + 4 logo + Fatal/Warning 0 + konsol 0 | curl tarama + Playwright 9×3 | aşağı matris: 27/27 yükleme 200, pay=1/chip=4/logo=4, cerr=0; HTML Fatal/Warn/Depr=0 | **PASS** |
| 6 | CSS `?ver=3.6.6` + canlı style.css'te kural | curl indir + grep | `style.css?ver=3.6.6`; kural satır **974/1000/2085** `.sv-footer__payments` | **PASS** |
| 7 | Yerel↔uzak 3/3 SHA | curl ile indir + sha256 | style.css **birebir PASS**; footer.php/functions.php HTTP **0 byte** (PHP çalışır, kaynak inmez) → **DOĞRULANAMADI** | **CONDITIONAL** |
| 8 | AGENTS §7 P76 kaydı gerçekle uyumlu | kayıt vs kanıt + `git show` | commit `619c2e3` var; footer.php +31 / style.css +26 / functions.php +2−1; screenshot yolları+dims doğru; SHA'lar repo+canlı ile aynı | **PASS** (yalnız "yerel↔uzak 3/3" alt-iddiası teyit edilemedi) |
| 9 | Doğru tema kaynağı (`sutre-child`) | canlı HTML tema yolu | `themes/sutre-child/` = 7 kez; başka varyant yok | **PASS** |

## 3) Sayfa × viewport matrisi (Playwright; `pay`/`chip`/`logo`=footer; `cerr`=konsol hatası)

```
vp    page                         status finalUrl              pay chip logo cerr
1440  /                            200    /                     1   4    4    0
1440  /shop/                       200    /shop/                1   4    4    0
1440  /product/jakarli-sal/        200    /product/jakarli-sal/ 1   4    4    0
1440  /cart/                       200    /cart/                1   4    4    0
1440  /checkout/                   200    /cart/ (302 boş sepet)1   4    4    0
1440  /mesafeli-satis-sozlesmesi/  200    (aynı)                1   4    4    0
1440  /iade-ve-cayma/              200    (aynı)                1   4    4    0
1440  /gizlilik-politikasi/        200    (aynı)                1   4    4    0
1440  /my-account/                 200    (aynı)                1   4    4    0
768   ... (9 sayfa, aynı)          200    (aynı)                1   4    4    0
375   ... (9 sayfa, aynı)          200    (aynı)                1   4    4    0
```
curl ek taraması: `/` `/shop/` `/magaza/` `/product/jakarli-sal/` `/cart/` `/checkout/` `/mesafeli-satis-sozlesmesi/` `/iade-ve-cayma/` `/gizlilik-politikasi/` `/my-account/` → hepsi `payments=1 chips=4 logos=4 Fatal=0 Warn=0 Depr=0`. **`/magaza/`=404** (gerçek rota değil; nav "Mağaza" → Woo shop `/shop/`) — P76 dışı. `/checkout/` boş sepette 302→`/cart/` (P75'te belgelenmiş davranış).

## 4) Bağımsız SHA-256 tablosu

| dosya | repo HEAD SHA-256 | canlı (HTTP) SHA-256 | sonuç |
|--|--|--|--|
| footer.php | `20effb74…f1b0` (12760 B) | — (HTTP 0 byte; PHP çalışır) | **DOĞRULANAMADI** |
| style.css | `43326422…a9f6d` (104975 B) | `43326422…a9f6d` (104975 B) | **PASS (birebir)** |
| functions.php | `20492e8a…d0841` (95257 B) | — (HTTP 0 byte; PHP çalışır) | **DOĞRULANAMADI** |

Dolaylı PHP kanıtı: canlı HTML'deki footer markup'ı repo `footer.php` kaynağıyla karakter düzeyinde örtüşüyor (benzersiz SVG path `m651.19.5c-…` ve `m308,0a309…` her ikisinde mevcut; 4 `aria-label` birebir); canlı `?ver=3.6.6` = repo `SUTRE_VERSION`. Theme blob'ları `619c2e3`↔HEAD **SAME** (deploy sonrası tema kodu değişmemiş).

## 5) Bulgular

**P76 içi:** bulgu yok. Madde 1-6, 8, 9 kanıtla uyumlu.
**P76 dışı (bloklamaz):**
- F1 — Mobil (375) 2px yatay taşma; footer gizlense de kalır → kaynak `.sv-cat-card` (genişlik 379px, ana sayfa kategori kartı). Yeniden üretim: 375px viewport, `/`, `document.scrollingElement.scrollWidth−clientWidth`=2. Coder raporuyla tutarlı.
- F2 — `/magaza/` 404 (rota yok; gezinme `/shop/`'a gider).
**Uyuşmazlık yok:** coder'ın bildirdiği ekran görüntüsü boyutları (`2880×1160`, `750×2294`) ve yolları doğru.

## 6) `git status -sb`
```
## main...origin/main
?? dispatch/out/evidence/out-79-footer-desktop-1440.png
?? dispatch/out/evidence/out-79-footer-mobile-375.png
?? dispatch/out/evidence/out-79-footer-tablet-768.png
?? dispatch/out/logs/
?? dispatch/out/out-76-paytr-logos-research.md
?? dispatch/out/out-76b-agents-p76-record.md
?? dispatch/pack-76-paytr-payment-logos-research.md
?? dispatch/pack-76b-agents-p76-record.md
?? dispatch/pack-77-footer-payment-logos.md
?? dispatch/pack-78-footer-staging-deploy.md
?? dispatch/pack-79-footer-verification.md
```
Bu paketin repodaki izi yalnız **yeni** dosyalar (`??`): 3 kanıt PNG + bu rapor. Takip edilen dosyada değişiklik/commit YOK.

## 7) Açık riskler
- PHP kaynak SHA (footer.php/functions.php) yalnız FTP/cPanel ile teyit edilebilir; bu pakette read-only/HTTP sınırı nedeniyle yapılamadı (sunucu PHP'yi çalıştırıyor, kaynağı vermiyor).
- Mobil 2px taşma ayrı paket gerektirir (`.sv-cat-card`).
- Görsel onay sahipte; production deploy yapılmadı (OWNER_APPROVAL_REQUIRED).

## Ekran görüntüleri
`dispatch/out/evidence/out-79-footer-{desktop-1440,tablet-768,mobile-375}.png` (footer alt barı; 4 renkli marka işareti sola, İstanbul sağda — görsel teyit edildi).