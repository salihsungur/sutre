# PACK-95 (W5) — Kapanış: kalıntı temizliği + doküman finali + tam doğrulama

Tarih 2026-09-26 · local Hermes (devops) · canlı/FTP yazımı YOK.
Kapsam: `~/Desktop/SUTRE/**`, `~/dev/sutre-eski-20260926` (taşıma), repo `AGENTS.md` + `dispatch/**`.

## 1. Temizlik (öncesi/sonrası)

`Ürün Fotoğrafları/` içinde **yalnız `.DS_Store`** vardı → boş dizin, silindi.

```text
ÖNCE : …/SUTRE/Ürün Fotoğrafları   +   .DS_Store   (tek dosya)
SONRA: ls: …/SUTRE/Ürün Fotoğrafları: No such file or directory
$ ls ~/Desktop | grep -iE 'sutre|p2[789]|promptun|referans|İman|Jakarlı'  →  SUTRE
$ ls ~/*.png ~/*.txt 2>/dev/null                                          →  (yok)
```

Diğer masaüstü öğeleri başka projelere ait → **DOKUNULMADI**.

## 2. Eski repo kopyası arşive (silme yok)

```text
$ test -L ~/dev/sutre          → symlink OK
$ test ! -e 06-arsiv/repo-eski-kopya-20260926 → hedef boş OK
$ mv ~/dev/sutre-eski-20260926 ~/Desktop/SUTRE/06-arsiv/repo-eski-kopya-20260926   exit=0
$ ls -la ~/dev
drwxr-xr-x@ 40 salihsungur staff  1280 Sep  9 11:15 flutter
lrwxr-xr-x@  1 salihsungur staff    40 Sep 26 12:50 sutre -> /Users/salihsungur/Desktop/SUTRE/01-repo
```

`~/dev`'de sutre-eski dizini KALMADI (flutter önceden var). Arşiv 130M, kendi `.git`'iyle duruyor.

## 3. Kalıcı kayıt (commit + HEAD==origin)

```text
chore(dispatch): pack-90..95 + raporlar + loglar arsivlendi   ce34ae6..0a9c57c   (18 dosya)
docs(agents): SUTRE ev duzeni W1-W5 kaydi + yol guncellemeleri 0a9c57c..dca0444
$ git rev-parse HEAD origin/main  →  dca04444… / dca04444…   EŞİT
$ git status --porcelain          →  (boş)
```

## 4. Güncellenen dokümanlar

- `~/Desktop/SUTRE/README.md`: tam ağaç (00–99 + alt klasörler), `01-repo` symlink notu, `04-hesaplar` 700/600 kuralı, "sistem yolları: `00-proje/POINTER-SISTEM.md`", `du -sh` tablosu.
- `00-proje/POINTER-SISTEM.md`: repo **taşındı/symlink**, `06-arsiv/repo-eski-kopya-20260926`, secret'lar `04-hesaplar` + iCloud kuralı, taşınmaz sistem katmanı tablosu.
- `01-repo/README-POINTER.md`: "bu klasörün kendisi repodur; `~/dev/sutre` buraya symlink".

## 5. AGENTS.md değişiklikleri

| Yer | Değişiklik |
| :-- | :--- |
| §1/§3/§6 blokquote (×3) | "doğrulama sonrası silinir" → **W5'te `06-arsiv/repo-eski-kopya-20260926`'ya arşivlendi (silinmedi)** |
| §4 | `_deneme/Karamel-motif-test/…` → `…/06-arsiv/İman-Nour-deneme/Karamel-motif-test/…` |
| §7 P64 | `SUTRE/8-Sozlesme-Promptlari/` → `…/00-proje/sozlesme-promptlari/` |
| §7 görsel tur | `…/Site Görselleri/` → `…/02-icerik/Site Görselleri/` |
| §7 İman Nour | `Ürün Fotoğrafları`→`02-icerik/…`; `_web/`→`03-yayin/İman-Nour-web/`; `_deneme/`→`06-arsiv/İman-Nour-deneme/` (4 yer) |
| §8 kayıt defteri | **Yeni madde:** "SUTRE ev düzeni (26-09-2026, W1–W5)…" |
| §6 KURAL | **W4 satırı zaten var → tekrar eklenmedi** |

Stale ref kontrolü: `_deneme/`=0 · `` `_web/ ``=0 · `8-Sozlesme-Promptlari`=0.
**Sapma:** AGENTS.md'de **§9 yok** (§0–§8; kayıt defteri §8) → madde §8'e eklendi.

## 6. 12 kontrol — ham çıktı

| # | Kontrol | Çıktı |
| :-- | :--- | :--- |
| 1 | `git fsck` | `exit=0`; yalnız 5 `dangling blob` (hata YOK) |
| 2 | `rev-parse HEAD origin/main` | `dca04444…` / `dca04444…` → EŞİT |
| 3 | `status --porcelain` | (boş) |
| 4 | `check-live.sh` | `/` → `HTTP=200 cache=hit payments=1 svg=5 fatal=0`; `/404-test-xyz/` → `HTTP=404 cache=miss payments=1 svg=5 fatal=0` |
| 5 | `ftp-tool.py ls` | `404.php 4055 · footer.php 12760 · functions.php 95257 · header.php 4429 · index.php 214 · style.css 109400 · assets/ page-templates/ woocommerce/ _OLU-ARTIK-staging.sutre.store-20260921/` |
| 6 | `cd ~/dev/sutre && git log -1` | `pwd -P`=…/Desktop/SUTRE/01-repo; `dca0444 docs(agents): SUTRE ev duzeni…` |
| 7 | `ls -la ~/dev` | `flutter/`, `sutre -> …/01-repo` |
| 8 | `04-hesaplar` | `drwx------` (700); `README.md·ftpinfo.txt·sutre-db-prod.txt·*.sql` → `-rw-------` (600); 2 `.env` symlink |
| 9 | `find -maxdepth 2 -type d` | 23 dizin, temiz ağaç; kök `Ürün Fotoğrafları/` YOK |
| 10 | `du -sh` | toplam **1.2G**: 00-proje 48K · 01-repo 130M · 02-icerik 597M · 03-yayin 40M · 04-hesaplar 2.1M · 05-raporlar 9.5M · 06-arsiv 487M · 99-gecici 4K |
| 11 | Desktop grep | `SUTRE` (yalnız) |
| 12 | `hermes -p coder sessions list --limit 2` | `PACK-85… 20260923_180044`, `PACK-83… 20260923_165427` → profil sağlam |

## 7. Ağaç + boyut

Tam ağaç + `du` tablosu: **`~/Desktop/SUTRE/README.md`** (ham çıktı: kontrol 9/10).

## 8. Geri alma

- Arşiv: `mv 06-arsiv/repo-eski-kopya-20260926 ~/dev/sutre-eski-20260926`
- Silinen dizin: yalnız `.DS_Store` (veri kaybı yok).
- Commit'ler: `git revert dca0444 0a9c57c`.
- Canlı site/FTP: **yazım yapılmadı** → deploysuz tur.

## 9. YAPILAMADI

- §9 kayıt defteri bölümü dosyada yok → madde §8'e yazıldı.
- `~/dev`'de `flutter/` de var (SUTRE dışı, dokunulmadı).
- `04-hesaplar` `.env` symlink'leri `lrwxr-xr-x` görünür (symlink izni hedefe değil bağa aittir).
- **Eşzamanlı süreç:** commit'lerimden sonra `dispatch/pack-96-paytr-logo.md` + `dispatch/out/logs/run-pack-96.log` (13:27) untracked belirdi → **kapsam dışı, dokunulmadı**; kontrol 3 (boş status) commit anında geçerliydi.
- fsck'teki 5 `dangling blob` bilgi çıktısıdır, etkisi yok.