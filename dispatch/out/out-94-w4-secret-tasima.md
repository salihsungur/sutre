# out-94-w4 — Gizli bilgiler `04-hesaplar` + araç sabiti (SONUÇ: TAMAM)

Tarih: 2026-09-26 · Sahip: devops (W4) · Secret içeriği bu rapora YAZILMADI.

## 1. Taşıma — öncesi / sonrası (izinler)

ÖNCESİ (Desktop kökü):
```
-rw-------@ 1 salihsungur staff      167 Sep 17 20:28 /Users/salihsungur/Desktop/ftpinfo.txt
-rw-------@ 1 salihsungur staff      140 Sep 22 11:14 /Users/salihsungur/Desktop/sutre-db-prod.txt
-rw-------@ 1 salihsungur staff  2211265 Sep 22 10:58 /Users/salihsungur/Desktop/staging-db-yedek-2026-09-22.sql
drwx------@ 2 salihsungur staff       64 Sep 26 12:44 04-hesaplar   (boş, 700)
```
SONRASI (`~/Desktop/SUTRE/04-hesaplar/`; kaynaklar Desktop kökünden YOK):
```
drwx------@ 5 salihsungur staff      160 Sep 26 13:16 /Users/salihsungur/Desktop/SUTRE/04-hesaplar
-rw-------@ 1 salihsungur staff      167 Sep 17 20:28 ftpinfo.txt
-rw-------@ 1 salihsungur staff      140 Sep 22 11:14 sutre-db-prod.txt
-rw-------@ 1 salihsungur staff  2211265 Sep 22 10:58 staging-db-yedek-2026-09-22.sql
```
Dizin **700**, dosyalar **600**. `du -sh`: 2.1M. `mv` (anlık rename, kopya/silme yok).

## 2. Symlink hedefleri (`ls -l` ham)
```
sutre-cpanel.env     -> /Users/salihsungur/Library/Application Support/Hermes/sutre-cpanel.env
sutre-higgsfield.env -> /Users/salihsungur/Library/Application Support/Hermes/sutre-higgsfield.env
```
`.env` dosyaları sabit sistem yolundan okunduğu için taşınmadı; kopya yok → drift yok. SSH anahtarları (`~/.ssh/sutre_{deploy,hosting,cpanel}`) **taşınmadı, symlink kurulmadı** — README'de yalnız işaretçi.

## 3. README özeti
`04-hesaplar/README.md` (600) yazıldı: dosya haritası (hangi dosya / kanonik konum / hangi araç okur), symlink notu, SSH işaretçileri ve **KURAL: Masaüstü iCloud senkronizasyonu ("Masaüstü ve Belgeler") AÇILMAYACAK; açılırsa bu klasör derhal bulut dışına çıkarılır.** Secret değeri içermez.

## 4. `ftp-tool.py` diff (yalnız CREDS + açıklama satırı)
Yedek: `~/.hermes/tools/ftp-tool.py.bak-20260926` (5436 B).
```
14c14  < Kimlik: ~/Desktop/ftpinfo.txt ...
       > Kimlik: ~/Desktop/SUTRE/04-hesaplar/ftpinfo.txt ...
19c19  < CREDS = os.path.expanduser("~/Desktop/ftpinfo.txt")
       > CREDS = os.path.expanduser("~/Desktop/SUTRE/04-hesaplar/ftpinfo.txt")
```
Satır 33 (hata mesajı) yol değil dosya adı taşıdığı için dokunulmadı.

## 5. Araç testleri (ham çıktı — ASIL KABUL KAPISI: 3/3 PASS)
```
ls:  drwxr-xr-x 6 spokenla ... . / drwxr-xr-x 7 spokenla ... .. / -rw------- 9 .ftpquota   → liste geldi
sha: edb099cfead17f0ac53e3b8233dde4fc45136dcdc99d90ddc66cb3a597b5a037  .../sutre-child/404.php (4055 B)
check-live: https://sutre.store/  HTTP=200 cache=miss etag=- payments=1 svg=5 css=ver=3.4.1,ver=3.6.8,ver=3.7.1 fatal=0
```
Üçü de başarılı → durdurma koşulu tetiklenmedi.

## 6. Okuyucu grep
`grep -rn 'ftpinfo\|sutre-db-prod' ~/.hermes/tools/ ~/Desktop/SUTRE/01-repo/scripts/`:
```
~/.hermes/tools/ftp-tool.py:14  (açıklama satırı)  → GÜNCELLENDİ
~/.hermes/tools/ftp-tool.py:19  (CREDS)            → GÜNCELLENDİ
~/.hermes/tools/ftp-tool.py:33  (hata mesajı, yol yok) → dokunulmadı
```
`01-repo/scripts/` altında başka okuyucu YOK. Repo geneli grep: ek referanslar yalnız `dispatch/pack-*.md` (P77/P80/P82/P83/P86/P90) ve `dispatch/out/…` geçmiş arşivlerinde — **tarihsel kanıt; canlı okuyucu değil, düzenlenmedi.**

## 7. AGENTS.md + commit/push
Değişen satırlar: 8, 109, 120, 122, 202 (`~/Desktop/ftpinfo.txt` → `…/04-hesaplar/ftpinfo.txt`; `~/Desktop/sutre-db-prod.txt` → `…/04-hesaplar/sutre-db-prod.txt`) + §6'ya KURAL satırı (satır 165). Kalan eski referans: **TEMIZ**.
Commit: `chore(secrets): kimlik dosyalari SUTRE/04-hesaplar'a tasindi (yollar + iCloud kurali)` → push origin main. HEAD==origin/main: bkz. §10 kanıt.

## 8. Geri alma komutları
```bash
mv ~/Desktop/SUTRE/04-hesaplar/ftpinfo.txt ~/Desktop/
mv ~/Desktop/SUTRE/04-hesaplar/sutre-db-prod.txt ~/Desktop/
mv ~/Desktop/SUTRE/04-hesaplar/staging-db-yedek-2026-09-22.sql ~/Desktop/
rm ~/Desktop/SUTRE/04-hesaplar/sutre-cpanel.env ~/Desktop/SUTRE/04-hesaplar/sutre-higgsfield.env
cp ~/.hermes/tools/ftp-tool.py.bak-20260926 ~/.hermes/tools/ftp-tool.py
cd ~/Desktop/SUTRE/01-repo && git revert HEAD && git push origin main
```

## 9. DURDURULDU / YAPILAMADI
**YOK** — tüm adımlar tamam; kabul kapısı 3/3 PASS.