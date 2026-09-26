# PACK-91 (W1) — İskelet + hijyen + sistem işaretçileri

## 0. KARARLAR (orkestratör — sahip "tüm kararları kendin ver" dedi)
- Hedef kök: `~/Desktop/SUTRE/` (tek ev). Sahip kararı: gizli bilgiler **taşınacak** (W4'te, 600 izinle); repo **taşınacak + eski yola symlink** (W2'de).
- **Bu dalgada risk yok**: yalnız klasör iskeleti + işaretçi dosyaları + izin sıkılaştırma + güvenlik hijyeni. Hiçbir mevcut dosya taşınmaz.

## 1. GÖREV (sırayla, her adımda kanıt)
1. **İskelet kur** (varsa dokunma):
   `mkdir -p ~/Desktop/SUTRE/{00-proje,01-repo,02-icerik,03-yayin,04-hesaplar,05-raporlar,06-arsiv,99-gecici}`
2. **İzin sıkılaştır:** `chmod 700 ~/Desktop/SUTRE/04-hesaplar` · `chmod 600 ~/Desktop/ftpinfo.txt ~/Desktop/sutre-db-prod.txt ~/Desktop/staging-db-yedek-2026-09-22.sql` → `ls -l` çıktısıyla kanıtla.
3. **🔴 GÜVENLİK HİJYENİ (zorunlu):** `~/Downloads/sutre` = `~/.ssh/sutre_hosting` özel anahtarının **644 izinli kopyası** (pack-90 kanıtı: SHA-256 `99f008f3…` özdeş). Anahtarın aslı `~/.ssh/`'te duruyor → kopyayı **sil** ve silindiğini kanıtla (`ls: No such file`). `~/Downloads/sutre.pub` (public, zararsız) **kalabilir**. Rapora not: *"master SSH key dünya-okunur kopyası kaldırıldı; anahtarın aslı yerinde; rotasyon sahibin kararı."*
4. **TCC/erişim probu (kritik — repo taşınmadan önce):**
   - `touch ~/Desktop/SUTRE/99-gecici/tcc-probe.txt` + içine yaz + geri oku → kanıt.
   - `python3 ~/.hermes/tools/ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/ | head -3` → Desktop erişimi varken FTP aracı çalışıyor mu (referans).
   - `bash ~/.hermes/tools/check-live.sh https://sutre.store/ | head -4` → canlı kontrol aracı çalışıyor mu.
5. **`00-proje/POINTER-SISTEM.md` yaz** (tek sayfa, tablo): sistemde kalacak her şeyin yeri + nedeni. En az: git repo (şimdilik `~/dev/sutre`, W2'de taşınacak + symlink), Hermes profilleri (`~/.hermes/profiles/*`, 15 adet — neden taşınmaz), OpenViking verisi, Obsidian vault (`~/Documents/SalihSungurVault`), araçlar (`~/.hermes/tools/{ftp-tool.py,check-live.sh,bot-activity.sh}`), SSH anahtarları (`~/.ssh/sutre_*`, neden taşınmaz), venv (`~/.hermes/tools/higgsfield-venv`).
6. **`~/Desktop/SUTRE/README.md` yaz** — klasörün giriş kapısı: her kategorinin (00–99) ne içerdiği tek satır + W2–W5'te neyin nereye geleceği + "kimlik dosyaları 04-hesaplar'da, 600" notu.
7. **`01-repo/README-POINTER.md`**: repo şu an `~/dev/sutre` (W2'de `01-repo`'ya taşınacak, `~/dev/sutre` symlink olacak) + remote `git@github.com:salihsungur/sutre.git` + tek dal `main`.
8. **Commit YOK, push YOK** (repo bu dalgada değişmiyor; bu paket repo dışı düzenleme). Raporda `git -C ~/dev/sutre status --porcelain` çıktısını göster (bozulmadığının kanıtı).

## 2. ROL SINIRI
- Dokunulabilir: `~/Desktop/SUTRE/**` (yeni iskelet), `~/Desktop/{ftpinfo.txt,sutre-db-prod.txt,staging-db-yedek-2026-09-22.sql}` (yalnız `chmod`), `~/Downloads/sutre` (yalnız silme).
- **Mevcut SUTRE içeriği TAŞINMAZ, YENİDEN ADLANDIRILMAZ** (W2–W5). Repo'ya yazma. FTP'ye yazma. Site YASAK.
- Secret içerikleri rapora/çıktıya YAZILMAZ. Kabuk kuralı: iç içe `$(...)` + tırnaklı operand yasak.

## 3. RAPOR
- `/Users/salihsungur/dev/sutre/dispatch/out/out-91-w1-iskelet-hijyen.md` (≤400 kelime): (1) kurulan iskelet `ls -l` çıktısı, (2) izinler (`ls -l` ham), (3) Downloads anahtar kopyası silme kanıtı, (4) TCC/araç probları ham çıktı, (5) yazılan 3 doküman (yol + kelime sayısı), (6) repo durumu (`git status --porcelain`), (7) `YAPILAMADI` listesi.