# out-78 — Footer Ödeme Yöntemi Logoları: Staging Deploy + Canlı Kanıt

Tarih: 2026-09-23 · Paket: PACK-78 · Başlangıç durumu: HEAD = origin/main = `619c2e36d88ecc84f93c13e497b60040730d4eb1` (kod önceki koşuda bitmişti; **bu turda tema kodu DEĞİŞTİRİLMEDİ** — yalnız deploy + kanıt + rapor + AGENTS kaydı).

## 1) Yapılanlar
- `619c2e3` doğrulandı (`git show`): footer.php +31 satır — `.sv-footer__payments` bloğu **satır 52**, dört marka **inline SVG** (Visa · Mastercard · TROY · PayTR); style.css +26 satır (974, 1000, 2085); functions.php `SUTRE_VERSION` 3.6.6. Eksik marka YOK → yeni kod commit'i gerekmedi.
- `php -l` iki dosyada 0 hata (PHP 8.5.10).
- **Staging FTP deploy (kanal B, mutlak yol `/staging.sutre.store/wp-content/themes/sutre-child/`)**: 3 dosya; deploy ÖNCESİ uzak kopyalar indirildi (backup) ve HEAD~1 `fea2080` blob'larıyla **birebir eşleşti → drift yok**; upload sonrası yerel↔uzak **3/3 SHA-256 PASS**.
- Canlı doğrulama (staging): blok HTML'de 1, dört `aria-label` etiketi var, CSS `?ver=3.6.6`, Fatal 0; **indirilen canlı CSS SHA-256 = yerel dosya SHA-256**.
- Footer ekran görüntüleri + ölçümler: Playwright-core (gerçek Brave, `ignoreHTTPSErrors`) — masaüstü 1440px ve mobil 375px; çerez bandı gerçek "Tamam, Anladım" tıklamasıyla kapatıldı.
- AGENTS.md §7 P76 durumu güncellendi (`SÜRÜYOR` → `STAGING DOĞRULANDI — production ONAY BEKLİYOR`).

## 2) php -l çıktıları (PHP 8.5.10, gerçek binary)
```
$ php -l theme/sutre-child-v2/footer.php
No syntax errors detected in theme/sutre-child-v2/footer.php
$ php -l theme/sutre-child-v2/functions.php
No syntax errors detected in theme/sutre-child-v2/functions.php
```
(Bu turda PHP kodu değiştirilmedi; çıktılar mevcut durumun kanıtıdır.)

## 3) Commit/push SHA'ları
- Başlangıç: `git rev-parse HEAD origin/main` → her ikisi de `619c2e36d88ecc84f93c13e497b60040730d4eb1`.
- Bu tur: `6a30695e097b8219360c8dbdc01fe49aa95d1cf3` `docs(agents): P76 staging dogrulandi - production onay bekliyor` (AGENTS.md) · `35572e5558f58bc5649e24855fa53ffac4d4087f` `docs(p76): out-78 staging deploy + canli kanit raporu` (bu rapor + 2 PNG).
- Push (`619c2e3..35572e5 main -> main`) sonrası doğrulama:
```
$ git rev-parse HEAD origin/main
35572e5558f58bc5649e24855fa53ffac4d4087f
35572e5558f58bc5649e24855fa53ffac4d4087f
```
(Bu kayıt commit'i — SHA/temizlik güncellemesi — son HEAD'dir.)

## 4) FTP upload + SHA-256 karşılaştırma (yerel ↔ uzak, RETR ile geri indirilerek)
| dosya | yerel B | yerel SHA-256 | uzak B | uzak SHA-256 | sonuç |
|---|---|---|---|---|---|
| footer.php | 12760 | `20effb742cb0bf695beedaf5c1cba0e20a95e28d51f64affa79a22669e74f1b0` | 12760 | aynı | **PASS** |
| style.css | 104975 | `43326422532504f4e1af0bbe8f2a97e2fe7888074626c1bd512f91a56e0a9f6d` | 104975 | aynı | **PASS** |
| functions.php | 95257 | `20492e8a1d5ecfa69e0bdaea1ee530353fc8cbb7a6974c76d6cf5ed1d53d0841` | 95257 | aynı | **PASS** |

Deploy ÖNCESİ uzak kopyalar (backup, `scratch/out78/backup-before/`):
footer.php 5626B `a4fbdfe731599b517c0f34b9061202e124eecc8270fe0050082e3419add1201f` · style.css 104337B `8b61dc9fa3072e72a7757dab5a464f044a6c1f332bb8eba1ee4ac863bf7290f7` · functions.php 95257B `7a90e8d95e07346bbef4f12c0409f5c2a49073e87f67ea3332fa599d03af22b0` — üçü de `fea2080` (HEAD~1) blob SHA'larıyla birebir.

Canlı CSS indirme doğrulaması: `HTTP 200 · 104975B · SHA-256 433264…` = yerel dosya (birebir).

## 5) Canlı curl çıktıları (staging, `?v=` cache-buster, ham)
```
--- 1) sv-footer__payments sayisi ---
1
--- 2) aria-label listesi ---
aria-label="Kabul edilen ödeme yöntemleri"     <- blok grubu
aria-label="Visa" / "Mastercard" / "TROY" / "PayTR"   <- 4 marka (liste tam)
--- 3) CSS surum izi ---
sutre-style-css' href='https://staging.sutre.store/wp-content/themes/sutre-child/style.css?ver=3.6.6
--- 4) Fatal sayisi ---
0
```
(Tam liste çıktısı `scratch/out78/evidence-curl-staging.txt`; blok dışı etiketler — menü/sepet/çerez — kısaltıldı.)

## 6) Ekran görüntüleri + ölçülebilir olgular
- `dispatch/out/evidence/out-78-footer-staging-desktop.png` (2880×1160 px; 1440px @2x)
- `dispatch/out/evidence/out-78-footer-staging-mobile.png` (750×2294 px; 375px @2x)

Ölçümler (canlı sayfa, JS `getBoundingClientRect`):
- Masaüstü 1440px: 4 chip **tek satır**; chip yüksekliği 30px (svg 18px); chip merkezleri alt bar merkezinden ≤1.5px sapma; ödeme bloğu x=620–961, "İstanbul" x=1265 → **tamamen solda**; yatay taşma **0px**; Fatal 0.
- Mobil 375px: 4 chip **tek satır, sarmasız**; chip yüksekliği 26px (svg 16px); blok x=40.6–334.4 (<375); "İstanbul" bloğun altında (mevcut ≤781px kolon/ortalama kuralı); yatay taşma **2px** — kaynağı `.sv-cat-card` (ana sayfa kategori kartı, 379px genişlik; **P76 dışı, mevcut durum**), footer taşma üretmiyor; Fatal 0.

## 7) AGENTS.md güncellemesi
§7 P76 maddesi: durum `SÜRÜYOR` → `STAGING DOĞRULANDI — production ONAY BEKLİYOR`; kanıt özeti (commit `619c2e3`, 3/3 SHA PASS, curl sonuçları, görsel yolları, ölçümler) eklendi — commit `<A1>`.

## 8) Geri alma komutu
```bash
cd ~/dev/sutre
git revert --no-edit <A2> <A1>   # yalnız kodu geri almak için: git revert 619c2e3
git push origin main
# staging FTP geri yükleme (revert sonrası yerel = P76 öncesi):
python3 ~/.hermes/profiles/coder/cache/scratch/out78/ftp_deploy_staging.py   # backup+upload+3/3 SHA
```
Alternatif (scratch silinmişse): `git checkout fea2080 -- theme/sutre-child-v2/footer.php theme/sutre-child-v2/style.css theme/sutre-child-v2/functions.php` içeriğini FTP ile aynı mutlak yola at (kalıcı kaynak git'tir). Production'a dokunulmadı → production geri alma gerekmez.

## 9) Açık noktalar / riskler
- **PRODUCTION deploy YAPILMADI** (OWNER_APPROVAL_REQUIRED) — sutre.store hâlâ P76 öncesi (ver 3.6.5).
- **Görsel onay sahipte** (§0.6): iki ekran görüntüsü teslim edildi.
- Mobil 2px taşma `.sv-cat-card` kaynaklı; P76 kapsamı dışında bırakıldı (ayrı paket).
- Marka/lisans: PayTR resmî basın kiti yok (out-76 §5) → PayTR logosu paytr.com yüklemesinden; DOĞRULANMADI markalar (Amex/Tosla vb.) bilinçli olarak EKLENMEDİ.
- Staging'de JS'li tarayıcı doğrulaması yapıldı; site geneli regresyon tester paketine kalır.
- YAPILAMADI: yok (tüm adımlar kanıtlı).

## Temizlik / git durumu
`git status -sb` (A2 push'u sonrası, takip edilen dosyalarda değişiklik yok):
```
## main...origin/main
?? dispatch/out/logs/
?? dispatch/out/out-76-paytr-logos-research.md
?? dispatch/out/out-76b-agents-p76-record.md
?? dispatch/pack-76-paytr-payment-logos-research.md
?? dispatch/pack-76b-agents-p76-record.md
?? dispatch/pack-77-footer-payment-logos.md
?? dispatch/pack-78-footer-staging-deploy.md
```
Not: `??` satırları başka ajanların paket/log dosyalarıdır — PACK-78 rol sınırı gereği commit EDİLMEDİ. `git diff --check` temiz (exit 0); bu paketin geçici dosyaları repo DIŞINDA (`scratch/out78/`) tutuldu.