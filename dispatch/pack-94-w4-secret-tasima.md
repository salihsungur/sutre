# PACK-94 (W4) — Gizli bilgiler `04-hesaplar` + araç sabiti güncellemesi

## 0. KARAR (orkestratör)
Sahip: *"Taşı ama iCloud senkronizasyonundan çıkarsın (izin 600) — tek yerde toplansın"* + *"tüm kararları kendin ver"*.
- **Desktop kökündeki** secret'lar → `~/Desktop/SUTRE/04-hesaplar/` (dizin 700, dosyalar 600): tek yer.
- **Sistem konumunda yaşaması zorunlu** olanlar (`~/Library/Application Support/Hermes/*.env`, `~/.ssh/sutre_*`): **taşınmaz** (Hermes/ssh bunları sabit yoldan okur). `04-hesaplar/` içine:
  - `.env` dosyaları için **symlink** (kopya yok → drift yok, içerik 04-hesaplar'a fiziksel olarak girmez),
  - SSH anahtarları için **yalnız README işaretçisi** (özel anahtara symlink kurulmaz — en yüksek değerli secret).
- iCloud: Masaüstü **şu an senkron değil** (pack-90 kanıtı). Koruma kuralı AGENTS.md'ye yazılır (§6).

## 1. GÖREV (sırayla; her adımda kanıt)
1. **Ön kontrol:** `ls -l ~/Desktop/{ftpinfo.txt,sutre-db-prod.txt,staging-db-yedek-2026-09-22.sql}` + `du -sh ~/Desktop/SUTRE/04-hesaplar`.
2. **Taşı (mv):**
   ```bash
   mv ~/Desktop/ftpinfo.txt ~/Desktop/SUTRE/04-hesaplar/
   mv ~/Desktop/sutre-db-prod.txt ~/Desktop/SUTRE/04-hesaplar/
   mv ~/Desktop/staging-db-yedek-2026-09-22.sql ~/Desktop/SUTRE/04-hesaplar/
   ```
   İzinleri teyit: dizin **700**, dosyalar **600** (`ls -l` ham çıktısı).
3. **Symlink'ler (04-hesaplar içine):**
   ```bash
   ln -s "/Users/salihsungur/Library/Application Support/Hermes/sutre-cpanel.env" ~/Desktop/SUTRE/04-hesaplar/sutre-cpanel.env
   ln -s "/Users/salihsungur/Library/Application Support/Hermes/sutre-higgsfield.env" ~/Desktop/SUTRE/04-hesaplar/sutre-higgsfield.env
   ```
   `ls -l` ile symlink hedeflerini kanıtla; **dosya içeriklerini açma/yazdırma.**
4. **`04-hesaplar/README.md` yaz** (secret DEĞERİ içermez): hangi dosya ne, nerede kanonik, hangi araç okuyor (ftp-tool.py → ftpinfo.txt · AGENTS.md §2/§3 → db-prod · Hermes → .env · SSH anahtarları `~/.ssh/sutre_{deploy,hosting,cpanel}` → `~/.ssh/config` `Host github-sutre`), ve **KURAL: Masaüstü iCloud senkronizasyonu ("Masaüstü ve Belgeler") AÇILMAYACAK; açılırsa bu klasör derhal bulut dışına çıkarılır.**
5. **`ftp-tool.py` sabitini güncelle:** `~/.hermes/tools/ftp-tool.py` içindeki `CREDS` satırı `~/Desktop/ftpinfo.txt` → `~/Desktop/SUTRE/04-hesaplar/ftpinfo.txt`. **Önce yedek:** `cp ~/.hermes/tools/ftp-tool.py ~/.hermes/tools/ftp-tool.py.bak-20260926`.
6. **Kanıt — araçlar çalışıyor mu (asıl kabul kapısı):**
   - `python3 ~/.hermes/tools/ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/ | head -3` → liste gelmeli
   - `python3 ~/.hermes/tools/ftp-tool.py sha /sutre.store/wp-content/themes/sutre-child/404.php` → hash gelmeli
   - `bash ~/.hermes/tools/check-live.sh https://sutre.store/ | head -3`
7. **Başka okuyucu var mı:** `grep -rn 'ftpinfo\\|sutre-db-prod' ~/.hermes/tools/ ~/Desktop/SUTRE/01-repo/scripts/ 2>/dev/null | head -20` — çıkan satırları rapora yaz; `ftp-tool.py` dışında okuyan varsa **yolunu güncelle** (yalnız bu iki dosya adını referans eden satırlar).
8. **AGENTS.md güncelle** (repo içi): `~/Desktop/ftpinfo.txt` → `~/Desktop/SUTRE/04-hesaplar/ftpinfo.txt` ve `~/Desktop/sutre-db-prod.txt` → `~/Desktop/SUTRE/04-hesaplar/sutre-db-prod.txt` (geçtiği tüm satırlar). §6'ya **KURAL** satırı: *"Kimlik dosyaları `~/Desktop/SUTRE/04-hesaplar/` (700/600). Masaüstü iCloud senkronizasyonu KAPALI kalacak; açılırsa secret'lar derhal bulut dışına çıkarılır."*
   - Commit: `chore(secrets): kimlik dosyalari SUTRE/04-hesaplar'a tasindi (yollar + iCloud kurali)` + `push origin main`
   - **Repo yolu artık gerçek konumdan:** commit/push'u `/Users/salihsungur/Desktop/SUTRE/01-repo` dizininden yap ve `git rev-parse HEAD origin/main` eşitliğini doğrula.

## 2. ROL SINIRI
- Dokunulabilir: §1'deki 3 dosya, `04-hesaplar/**`, `~/.hermes/tools/ftp-tool.py` (+yedek), `AGENTS.md`, rapor.
- **Secret içerikleri hiçbir çıktıya/rapora yazılmaz** (yalnız dosya adı/izin/boyut).
- SSH anahtarları taşınmaz, symlink kurulmaz. FTP'ye/canlı siteye yazma YOK. Kabuk kuralı: iç içe `$(...)` + tırnaklı operand yasak.

## 3. DURMA KOŞULU
§6'daki araç testlerinden **biri başarısız olursa**: `ftp-tool.py` sabitini yedekten geri al (`cp ...bak-20260926 ...`), dosyaları `~/Desktop/`'a geri taşı ve rapora `DURDURULDU: <neden>` yaz.

## 4. RAPOR
- `/Users/salihsungur/Desktop/SUTRE/01-repo/dispatch/out/out-94-w4-secret-tasima.md` (≤500 kelime): (1) taşıma öncesi/sonrası `ls -l` (izinler), (2) symlink hedefleri, (3) README özeti, (4) `ftp-tool.py` diff'i (yalnız CREDS satırı), (5) araç test çıktıları (ham), (6) okuyucu grep sonuçları, (7) AGENTS değişiklikleri + commit/push + HEAD==origin, (8) geri alma komutları, (9) `DURDURULDU`/`YAPILAMADI`.