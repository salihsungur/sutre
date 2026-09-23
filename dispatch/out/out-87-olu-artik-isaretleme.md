# out-87 — Ölü artık dizini işaretleme (yeniden adlandırma) + AGENTS kayıtları

devops · 23-09-2026 · pack-87 · **Silme YOK** (yalnız rename + indirme + doğrulama)

## 1. Yapılanlar
1. Her iki ortamdaki içteki `functions.php` + `style.css` indirildi → `dispatch/out/backups/p87-olu-artik/{prod,staging}/` + `SHA256SUMS`.
2. SHA'lar pack-86 + git `efc30be` ile karşılaştırıldı (§2).
3. FTP `RNFR`/`RNTO` ile **yalnız üst öğe** yeniden adlandırıldı (prod + staging): `staging.sutre.store` → `_OLU-ARTIK-staging.sutre.store-20260921`. Alt dizin taşıma YAPILMADI.
4. Doğrulama: eski yol 404, yeni yol 403 + iç `style.css` 200/36915 B, `functions.php` 200/0 B (§4); regresyon (§5).
5. AGENTS.md §7'ye 3 kayıt eklendi (§6); commit + push (§7).

Araç: `ftp-tool.py` (ls/get/verify) + rename için scratch betiği `p87-rename.py` (RNFR/RNTO; hedefte varsa durur).

## 2. Arşiv + SHA-256 (pack-86 ile birebir)
| Ortam | Dosya | Boyut | SHA-256 |
| :-- | :-- | --: | :-- |
| prod | functions.php | 22888 B | `87619be3…1afaf01` |
| prod | style.css | 36915 B | `cd6e23e3…a24a86e1` |
| staging | functions.php | 22888 B | `87619be3…1afaf01` |
| staging | style.css | 36915 B | `cd6e23e3…a24a86e1` |

Dördü de pack-86 değerleri **ve** git `efc30be` ile birebir. İndirilen dosyalar repoya GİRMEZ; yalnız `SHA256SUMS` takip edilir.

## 3. Ham `ls` — rename öncesi / sonrası
ÖNCESİ (her iki tema dizini):
```
drwxr-xr-x 3 spokenla spokenla 24 Sep 21 13:46 staging.sutre.store
```
SONRASI (prod ve staging):
```
drwxr-xr-x 3 spokenla spokenla 24 Sep 21 13:46 _OLU-ARTIK-staging.sutre.store-20260921
```
Eski ad her iki listede YOK. Yeni ad içi (iç yapı korundu):
```
-rw-r--r-- 1 spokenla spokenla 22888 Sep 21 13:46 functions.php
-rw-r--r-- 1 spokenla spokenla 36915 Sep 21 13:46 style.css
```
`rename` çıktısı: `RENAME OK · eski var_mi=False · yeni var_mi=True` (×2).

## 4. HTTP kanıtları (`?v=87`)
| Yol | prod | staging |
| :-- | :-- | :-- |
| eski dizin `/staging.sutre.store/` | **404** | **404** |
| yeni dizin `/_OLU-ARTIK-…-20260921/` | **403** | **403** |
| yeni `…/style.css` | **200** 36915 B text/css | **200** 36915 B text/css |
| yeni `…/functions.php` | **200** 0 B text/html | **200** 0 B text/html |

`functions.php` 0 B = PHP yürütülüyor (davranış değişmedi).

## 5. Regresyon (`check-live.sh`; fatal=0)
```
https://sutre.store/                    HTTP=200 cache=hit  payments=1 svg=5 fatal=0
https://sutre.store/shop/               HTTP=200 cache=hit  payments=1 svg=5 fatal=0
https://sutre.store/cart/               HTTP=200 cache=miss payments=1 svg=5 fatal=0
https://sutre.store/my-account/         HTTP=200 cache=miss payments=1 svg=5 fatal=0
https://sutre.store/404-test-xyz-p87/   HTTP=404 cache=miss payments=1 svg=5 fatal=0
https://staging.sutre.store/            HTTP=200 cache=hit  payments=1 svg=5 fatal=0
https://staging.sutre.store/shop/       HTTP=200 cache=miss payments=1 svg=5 fatal=0
https://geleceginbilimi.com/            HTTP=200 cache=hit  payments=0 svg=1 fatal=0
```

## 6. AGENTS.md §7 kayıtları
- **P84** — LiteSpeed cache purge + noindex kaldırma + sitemap kurulumu — ZATEN YAPILDI (23-09-2026).
- **P86** — Üretim temasındaki `staging.sutre.store` iç içe dizini — İNCELENDİ + İŞARETLENDİ (23-09-2026) → `_OLU-ARTIK-…` (prod + staging).
- **KURAL** — FTP parola hijyeni (23-09-2026): parola komut satırına/çıktıya yazılmaz; `ftp-tool.py` (veya aynı sözleşmeye uyan yardımcı) kullanılır; sızıntıda sahibe rotasyon önerilir (sahip riski kabul etti, döndürülmedi).

## 7. Commit / push
```
chore(hosting): olu artik dizini isaretlendi + P84/P86 kayitlari
git status -sb   : ## main...origin/main  (yalnız kapsam içi 3 dosya)
git diff --check : (boş)
git rev-parse HEAD origin/main : eşit — push sonrası doğrulandı (tip SHA rapora yazılmaz:
yazımı commit hash'ini değiştirir)
```
Kapsam: `AGENTS.md` + `out-87-…md` + `backups/p87-olu-artik/SHA256SUMS` (ignore'lu `backups/` → `git add -f`).

## 8. Geri alma
```
python3 ~/.hermes/profiles/devops/cache/scratch/p87-rename.py rename \
  /sutre.store/wp-content/themes/sutre-child/_OLU-ARTIK-staging.sutre.store-20260921 \
  /sutre.store/wp-content/themes/sutre-child/staging.sutre.store
```
Aynı komut staging yolu ile de çalıştırılır. İçerik zaten yerde; arşiv `dispatch/out/backups/p87-olu-artik/` ayrıca durur.

## 9. YAPILAMADI
- Cache purge YAPILMADI (gerekmez: HTML çıktısına girmez).
- `ftp-tool.py`'de rename verb'ü yok → yardımcı scratch betiği yazıldı (repoya konmadı).
- `out-86` / `pack-86` / `pack-87` dosyaları paket kapsamı gereği bu commit'e ALINMADI (untracked).