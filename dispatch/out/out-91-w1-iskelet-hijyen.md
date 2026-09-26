# PACK-91 (W1) — İskelet + Hijyen + Sistem İşaretçileri

Tarih: 2026-09-26 · Kapsam: `~/Desktop/SUTRE/**` iskeleti + izinler + Downloads hijyeni. **Hiçbir mevcut dosya taşınmadı, repo değişmedi, FTP'ye yazılmadı, siteye dokunulmadı.**

## 1) Kurulan iskelet
```
drwxr-xr-x  00-proje  01-repo  02-icerik  03-yayin  05-raporlar  06-arsiv  99-gecici
drwx------  04-hesaplar   (+ pre-existing: 8-Sozlesme-Promptlari, Dev Arşiv, Logo ve Marka, Site Görselleri, Ürün Fotoğrafları)
README.md
```
`mkdir -p ... EXIT=0`; mevcut 5 içerik klasörüne dokunulmadı.

## 2) İzin sıkılaştırma
```
$ chmod 700 ~/Desktop/SUTRE/04-hesaplar   -> EXIT=0
drwx------@ 2 salihsungur staff 64 /Users/salihsungur/Desktop/SUTRE/04-hesaplar
$ chmod 600 ftpinfo.txt sutre-db-prod.txt staging-db-yedek-2026-09-22.sql
-rw-------@ 1 salihsungur staff      167 Sep 17 20:28 /Users/salihsungur/Desktop/ftpinfo.txt
-rw-------@ 1 salihsungur staff  2211265 Sep 22 10:58 /Users/salihsungur/Desktop/staging-db-yedek-2026-09-22.sql
-rw-------@ 1 salihsungur staff      140 Sep 22 11:14 /Users/salihsungur/Desktop/sutre-db-prod.txt
```

## 3) Güvenlik hijyeni — master SSH key kopyası
Özdeşlik (silme öncesi, SHA-256 kesik): `99f008f350776df7…` her iki dosyada aynı.
Silme:
```
$ rm ~/Downloads/sutre ; RM_EXIT=0
$ ls -l ~/Downloads/sutre
ls: /Users/salihsungur/Downloads/sutre: No such file or directory
-rw-------  1 salihsungur staff 1856 Sep 23 03:20 /Users/salihsungur/.ssh/sutre_hosting   <- aslı yerinde
-rw-r--r--  1 salihsungur staff  382 Sep 12 21:20 /Users/salihsungur/Downloads/sutre.pub  <- public, kaldı (zararsız)
```
**Not:** master SSH key'in dünya-okunur (644) kopyası kaldırıldı; anahtarın aslı yerinde; rotasyon sahibin kararı.

## 4) TCC / araç probları
```
$ printf ... > ~/Desktop/SUTRE/99-gecici/tcc-probe.txt ; WRITE_EXIT=0
TCC probe W1 2026-09-26 12:44:20            (geri okundu, 33 byte)
$ python3 ~/.hermes/tools/ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/ | head -3
drwxr-xr-x 6 spokenla spokenla 231 Sep 23 19:00 .
drwxr-xr-x 7 spokenla spokenla 157 Sep 19 19:30 ..
-rw------- 1 spokenla spokenla   9 Sep 17 19:26 .ftpquota
$ bash ~/.hermes/tools/check-live.sh https://sutre.store/ | head -4
  HTTP=200 cache=hit etag=W/"6ab670a4-3d22" payments=1 svg=5 css=ver=3.4.1,ver=3.6.8,ver=3.7.1 fatal=0
  robots=yok
```
Desktop + FTP aracı + canlı kontrol aracı çalışıyor.

## 5) Yazılan dokümanlar
| Yol | Kelime |
| :--- | ---: |
| `~/Desktop/SUTRE/00-proje/POINTER-SISTEM.md` | 335 |
| `~/Desktop/SUTRE/README.md` | 253 |
| `~/Desktop/SUTRE/01-repo/README-POINTER.md` | 157 |

## 6) Repo durumu (değişmedi)
```
$ git -C ~/dev/sutre status --porcelain ; EXIT=0
?? dispatch/out/logs/run-pack-89.log / run-pack-90.log / run-pack-91.log
?? dispatch/out/out-90-sutre-envanter-plan.md
?? dispatch/pack-89-kapanis-kisa.md / pack-90-... / pack-91-w1-iskelet-hijyen.md
main · origin git@github-sutre:salihsungur/sutre.git
```
Yalnız önceki paketlerden kalan **untracked** dosyalar; değiştirilmiş/silinen dosya yok, commit/push yok.

## 7) YAPILAMADI
- Yok. Tek sapma: görev metni "15 Hermes profili" diyor; canlı sayım **19** (architect, automation, coder, cybersec, data, debugger, designer, devops, docs, finance, legal, marketing, memory, operator, ops, product, researcher, reviewer, tester) — dokümanda 19 yazıldı.
- W2–W5 taşımaları bu dalganın dışında; kasıtlı yapılmadı.