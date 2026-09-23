# out-80 — P76 Footer Ödeme Logoları: PRODUCTION Deploy + AGENTS Kapanışı

Tarih: 2026-09-23 · Paket: PACK-80 · Başlangıç HEAD = origin/main = `be6dc4320c7554b5d80d4026ecb4ddacc08e50e9` (beklenen ile aynı). Sahip onayı alındı (23-09-2026: "Evet, production'a al").

## 1) Yapılanlar
Prod hedefi FTP listelemesiyle tespit edildi (tahmin yok) → deploy ÖNCESİ yedek alındı → 3 dosya MUTLAK yolla yüklendi → yerel↔uzak 3/3 SHA-256 PASS → canlı curl + gerçek-Brave ekran görüntüsü/ölçüm → AGENTS §7 güncellendi. Ham loglar: `dispatch/out/logs/out-80-{ftp-probe,ftp-deploy,curl-production,shots}.txt`.

## 2) Prod hedef yolu (nasıl doğrulandı)
- FTP kök listelemesinde `sutre.store/` dizini; `/sutre.store/wp-content/themes/` listesinde `sutre-child` (+ `.bak` ve twentytwenty* default'lar) — `dispatch/out/logs/out-80-ftp-probe.txt`.
- Canlı HTML'de `themes/sutre-child` izi 7 kez (başka varyant yok).
- Yükleme hedefi (MUTLAK): `/sutre.store/wp-content/themes/sutre-child/{footer.php,style.css,functions.php}`. Login: `@spokenlab.com.tr` (diğer aday 530 ile düştü).

## 3) Yedek tablosu — `dispatch/out/backups/p76-prod-pre-deploy/`
| dosya | boyut | SHA-256 (deploy öncesi prod) |
|---|---|---|
| footer.php | 5626B | `a4fbdfe731599b517c0f34b9061202e124eecc8270fe0050082e3419add1201f` |
| style.css | 104337B | `8b61dc9fa3072e72a7757dab5a464f044a6c1f332bb8eba1ee4ac863bf7290f7` |
| functions.php | 95257B | `7a90e8d95e07346bbef4f12c0409f5c2a49073e87f67ea3332fa599d03af22b0` |

Üç değer `fea2080` (HEAD~1) blob'larıyla birebir → deploy öncesi drift yok. `SHA256SUMS` aynı dizinde (`backups/` .gitignore → yalnız SHA listesi `-f` ile commit'te).

## 4) Yerel↔uzak SHA-256 (RETR ile geri indirilerek) — 3/3 PASS
| dosya | yerel B | SHA-256 (yerel = uzak) | uzak B | sonuç |
|---|---|---|---|---|
| footer.php | 12760 | `20effb742cb0bf695beedaf5c1cba0e20a95e28d51f64affa79a22669e74f1b0` | 12760 | PASS |
| style.css | 104975 | `43326422532504f4e1af0bbe8f2a97e2fe7888074626c1bd512f91a56e0a9f6d` | 104975 | PASS |
| functions.php | 95257 | `20492e8a1d5ecfa69e0bdaea1ee530353fc8cbb7a6974c76d6cf5ed1d53d0841` | 95257 | PASS |

Yerel dosyalar = repo HEAD kopyaları; üç SHA pack-78 staging kanıtıyla da birebir.

## 5) Canlı curl ham çıktıları (production, `?v=1790169197`, 2026-09-23T13:13:17Z; tam dosya `dispatch/out/logs/out-80-curl-production.txt`)
```
1) HTTP 200
2) sv-footer__payments = 1
3) aria-label (sort -u): "Kabul edilen ödeme yöntemleri" · Visa · Mastercard · TROY · PayTR
   (+ site etiketleri: Ana menü, Menü, Menüyü aç/kapat, Mobil menü, Sepet,
      Çerez bildirimi, İlk siparişe %10 indirim — SUTRE10 kodu, ürün seçenekleri)
4) ver izleri: ver=3.6.6 (+ eklenti 3.4.1/3.7.1)
5) tema CSS linki: themes/sutre-child/style.css?ver=3.6.6
6) Fatal/Warning: 0      8) /shop/ Fatal/Warning: 0
7) canlı style.css: 104975B · sha256 43326422…a9f6d = yerel (birebir)
```

## 6) Ekran görüntüleri + ölçümler (gerçek Brave / Playwright-core, canlı prod)
- `dispatch/out/evidence/out-80-footer-production-desktop.png` (2880×1160 = 1440@2x)
- `dispatch/out/evidence/out-80-footer-production-mobile.png` (750×2294 = 375@2x)
- Ölçüm JSON: `~/.hermes/profiles/coder/cache/scratch/out80/measurements-prod.json`

| viewport | chip | satır | chip h / svg | yatay taşma | not |
|---|---|---|---|---|---|
| 1440 | 4 | 1 | 30px / 18px | 0px | ödeme sağ kenarı 960.9 < İstanbul x 1264.9 (solda); bar merkez sapması ≤1.5px |
| 768 | 4 | 1 | 30px / 18px | 0px | İstanbul ≤781px mevcut kuralıyla blok altında |
| 375 | 4 | 1 | 26px / 16px | 2px | footer gizlenince de 2px → kaynak footer değil = **P76 DIŞI** |

Vision: 4 chip tam render; kesilme/örtüşme yok.

## 7) AGENTS.md güncellemesi (commit `%%SHA1%%`)
- P76: `STAGING DOĞRULANDI — production ONAY BEKLİYOR` → `ZATEN YAPILDI (production canlı, 23-09-2026)`; prod curl, 3/3 SHA, yedek SHA'ları, görsel yolları eklendi.
- Yeni **P76c** (düşük öncelik, ertelendi) kaydı eklendi; `/magaza/`→404 notu pack-81'e bağlandı.

## 8) GERİ ALMA — git revert GEREKMEZ (yedekten FTP geri yükleme)
```bash
python3 ~/.hermes/profiles/coder/cache/scratch/out80/ftp_restore_prod.py
```
Prosedür: script `SHA256SUMS`'i doğrular → 3 dosyayı `/sutre.store/wp-content/themes/sutre-child/` üstüne yazar → RETR ile doğrular (3/3 SHA). Geri yükleme sonrası beklenen prod SHA'ları: footer.php `a4fbdfe7…` (5626B) · style.css `8b61dc9f…` (104337B) · functions.php `7a90e8d9…` (95257B); `sv-footer__payments`=0 ve tema CSS `?ver=3.6.5`. Alternatif: üç yedeği FTP istemcisiyle aynı MUTLAK yola elle yükle.

## 9) Açık noktalar
- Sahip adımı: **LiteSpeed → Purge All**. Kanıtlar `?v=` cache-buster'lı.
- Mobil 2px taşma → **P76c** (düşük öncelik, ertelendi). `/magaza/` 404 → pack-81.
- Geri alma varlıkları: yedek dosyaları bilinçli olarak repo DIŞINDA (yalnız SHA listesi commit'li); rollback script'i scratch'te (`out80/`).
- Kapsam: prod kontrol ana sayfa + `/shop/`; 9×3 viewport matrisi staging'de pack-79'da.

## Temizlik
`git status -sb` (commit öncesi):
```
## main...origin/main
 M AGENTS.md
?? dispatch/out/evidence/out-79-footer-desktop-1440.png
?? dispatch/out/evidence/out-79-footer-mobile-375.png
?? dispatch/out/evidence/out-79-footer-tablet-768.png
?? dispatch/out/evidence/out-80-footer-production-desktop.png
?? dispatch/out/evidence/out-80-footer-production-mobile.png
?? dispatch/out/logs/
?? dispatch/out/out-76-paytr-logos-research.md
?? dispatch/out/out-76b-agents-p76-record.md
?? dispatch/out/out-79-footer-verification.md
?? dispatch/out/out-80-p76-production.md
?? dispatch/pack-76-paytr-payment-logos-research.md
?? dispatch/pack-76b-agents-p76-record.md
?? dispatch/pack-77-footer-payment-logos.md
?? dispatch/pack-78-footer-staging-deploy.md
?? dispatch/pack-79-footer-verification.md
?? dispatch/pack-80-p76-production-deploy.md
?? dispatch/pack-81-magaza-404-audit.md
```
`git diff --check` → çıktı yok (temiz). Geçici dosya: yok (scratch repo dışı; loglar `dispatch/out/logs/`).