# PACK-95 (W5) — Kapanış: kalıntı temizliği + doküman finali + tam son doğrulama

## 0. DURUM
W1–W4 tamam: iskelet · repo taşındı (`~/dev/sutre` → symlink, hedef `Desktop/SUTRE/01-repo`) · içerik kategorize (`02-icerik/03-yayin/05-raporlar/06-arsiv`) · secret'lar `04-hesaplar` (700/600) + araç sabiti güncel.
**Bu dalga son**: kalan artıklar, doküman finali, eski repo kopyasının arşive alınması, tam doğrulama.

## 1. GÖREV (sırayla, kanıtlı)
1. **Kalıntı temizliği:**
   - `~/Desktop/SUTRE/Ürün Fotoğrafları/` içinde yalnız `.DS_Store` kaldı → **dizin silinsin** (boş dizin; içinde başka dosya varsa DUR ve raporla). Kanıt: `find` öncesi/sonrası.
   - `~/Desktop` kökünde ve `~/` kökünde kalan SUTRE artıklarını tara: `ls ~/Desktop | grep -iE 'sutre|p2[789]|promptun|referans|İman|Jakarlı'`, `ls ~/*.png ~/*.txt 2>/dev/null` → kalan varsa **tabloya yaz** ve uygun kategoriye taşı (`05-raporlar` / `06-arsiv` / `02-icerik`); başka projelere ait olanlara **DOKUNMA**.
2. **Eski repo kopyasını arşive al:** `mv ~/dev/sutre-eski-20260926 ~/Desktop/SUTRE/06-arsiv/repo-eski-kopya-20260926`
   - Sonra `~/dev` içinde yalnız `sutre` symlink'i kalmalı → `ls -la ~/dev` çıktısını rapora yaz.
   - **Silme YOK** (arşivde duruyor; tek komutla geri alınır).
3. **Kalıcı kayıt temizliği (repo):** `git -C ~/dev/sutre status --porcelain` ile untracked kalan **tüm** paket/rapor/log dosyalarını (`pack-8x/9x`, `out-8x/9x`, `logs/run-pack-8x/9x*.log`) `git add` + commit:
   `chore(dispatch): pack-90..95 + raporlar + loglar arsivlendi` → `git push origin main`
   → `git -C ~/dev/sutre rev-parse HEAD origin/main` eşitliğini doğrula. Son `git status --porcelain` çıktısı **boş** olmalı (tek istisna: `.gitignore`'lu `*.php/*.css` yedek içerikleri).
4. **Doküman finali:**
   - `~/Desktop/SUTRE/README.md` **tam ağacı** yazsın (00–99 kategorileri + içlerindeki klasörler + `01-repo` symlink notu + `04-hesaplar` 700/600 kuralı + "sistem yolları: `00-proje/POINTER-SISTEM.md`"). `du -sh` boyut tablosu ekle.
   - `~/Desktop/SUTRE/00-proje/POINTER-SISTEM.md` güncelle: repo artık **taşındı** (symlink bilgisi), `06-arsiv/repo-eski-kopya-20260926` var, secret'lar `04-hesaplar`'da.
   - `~/Desktop/SUTRE/01-repo/README-POINTER.md` → artık gerçek repo bu klasörde; içeriği "bu klasörün kendisi repodur; `~/dev/sutre` buraya symlink" olarak düzelt.
5. **AGENTS.md yollarını gerçek konumlara göre düzelt** (repo içi):
   - §4'teki ürün fotoğrafı arşiv yolları: `~/Desktop/SUTRE/Ürün Fotoğrafları/…` → `~/Desktop/SUTRE/02-icerik/Ürün Fotoğrafları/…`; `_deneme/Karamel-motif-test/…` → `~/Desktop/SUTRE/06-arsiv/İman-Nour-deneme/Karamel-motif-test/…`; `_web/` → `~/Desktop/SUTRE/03-yayin/İman-Nour-web/`
   - §6'ya **KURAL** satırı: *"Kimlik dosyaları `~/Desktop/SUTRE/04-hesaplar/` (700/600). Masaüstü iCloud senkronizasyonu KAPALI kalacak; açılırsa secret'lar derhal bulut dışına çıkarılır."* (W4 eklediyse tekrar ekleme, teyit et.)
   - §9 kayıt defterine madde: *"**SUTRE ev düzeni (26-09-2026, W1–W5):** tüm proje varlıkları `~/Desktop/SUTRE/` altında tek ağaçta; repo `01-repo` (+ `~/dev/sutre` symlink), içerik `02-icerik`, yayın seti `03-yayin`, kimlik `04-hesaplar`, raporlar `05-raporlar`, arşiv `06-arsiv`. Eski repo kopyası `06-arsiv/repo-eski-kopya-20260926`."*
   - Commit: `docs(agents): SUTRE ev duzeni W1-W5 kaydi + yol guncellemeleri` + push + HEAD==origin.
6. **TAM SON DOĞRULAMA (hepsinin ham çıktısı rapora):**
   | # | Kontrol | Beklenen |
   |:--|:--|:--|
   | 1 | `git -C ~/dev/sutre fsck --no-progress` | hata yok |
   | 2 | `git -C ~/dev/sutre rev-parse HEAD origin/main` | eşit |
   | 3 | `git -C ~/dev/sutre status --porcelain` | boş |
   | 4 | `bash ~/.hermes/tools/check-live.sh https://sutre.store/ https://sutre.store/404-test-xyz/` | HTTP 200/404 · payments=1 · fatal=0 |
   | 5 | `python3 ~/.hermes/tools/ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/` | dosyalar listelenir |
   | 6 | `cd ~/dev/sutre && git log --oneline -1` | symlink üzerinden çalışır |
   | 7 | `ls -la ~/dev` | yalnız `sutre` symlink |
   | 8 | `ls -ld ~/Desktop/SUTRE/04-hesaplar` + içi | 700 · dosyalar 600 |
   | 9 | `find ~/Desktop/SUTRE -maxdepth 2 -type d -not -path '*/01-repo/*' \| sort` | temiz kategori ağacı (ham çıktı) |
   | 10 | `du -sh ~/Desktop/SUTRE` + alt kategoriler | tablo |
   | 11 | `ls ~/Desktop \| grep -iE 'sutre\|p2[789]\|promptun\|referans'` | yalnız `SUTRE` |
   | 12 | `hermes -p coder sessions list --limit 2` | profil sağlam (salt-okuma) |

## 2. ROL SINIRI
- Dokunulabilir: `~/Desktop/SUTRE/**`, `~/dev/sutre-eski-20260926` (taşıma), repo içi `AGENTS.md` + `dispatch/**`, rapor.
- **Silme:** yalnız `.DS_Store`'dan ibaret boş dizin. Başka hiçbir silme yok. FTP/canlı site yazımı YOK.
- Secret içerikleri rapora/çıktıya yazılmaz. Kabuk kuralı: iç içe `$(...)` + tırnaklı operand yasak.

## 3. RAPOR
- `/Users/salihsungur/Desktop/SUTRE/01-repo/dispatch/out/out-95-w5-kapanis.md` (≤700 kelime): (1) temizlik öncesi/sonrası, (2) repo arşivleme + `ls -la ~/dev`, (3) commit'ler + HEAD==origin + son `git status`, (4) güncellenen dokümanlar, (5) AGENTS değişiklikleri, (6) **§1.6'daki 12 kontrolün ham çıktıları**, (7) tam ağaç + boyut tablosu, (8) geri alma özeti, (9) `YAPILAMADI`.