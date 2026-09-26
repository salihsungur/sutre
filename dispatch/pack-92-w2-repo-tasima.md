# PACK-92 (W2) — Git deposunu taşı + symlink kur (KRİTİK DALGA, geri alınabilir)

## 0. KARAR (orkestratör)
`~/dev/sutre` → **`/Users/salihsungur/Desktop/SUTRE/01-repo/`** taşınacak; eski yolda **symlink** kalacak → 15 profil SOUL.md'si, checkpoint'ler, dispatch paketlerindeki yüzlerce mutlak yol, araçlar ve bot oturumları **hiç kırılmaz**.
Sahiplik: sahip *"tüm kararları kendin verebilirsin, hepsi tek klasörde olsun"* dedi. W1'de Masaüstü yaz/oku probu **geçti** (TCC engeli yok).
**Yöntem:** kopyala → doğrula → eskiyi yedek adına al → symlink → doğrula. **Hiçbir aşamada silme YOK.**

## 1. ÖN UÇUŞ (kanıt topla, sonra devam)
1. `git -C ~/dev/sutre status --porcelain` (je kirli liste — beklenen: yalnız untracked log/rapor dosyaları)
2. `git -C ~/dev/sutre rev-parse HEAD` → **beklenen `1695c79…`** (farklıysa DUR ve rapora yaz)
3. `git -C ~/dev/sutre fsck --no-progress` → hatasız
4. `git -C ~/dev/sutre rev-list --count HEAD` (commit sayısı) + `find ~/dev/sutre -type f -not -path '*/.git/*' | wc -l` (dosya sayısı) + `du -sh ~/dev/sutre`
5. `df -h /` → boş alan (kopya için yeterli mi)

## 2. KOPYALA
- `cp -a ~/dev/sutre/. /Users/salihsungur/Desktop/SUTRE/01-repo/` (nokta önemli: gizli dosyalar dahil; `README-POINTER.md` W1'den orada duruyor, korunur)
- Kopya sürerken **kesme**; bitince §3 doğrulaması.

## 3. DOĞRULA (kopya canlı repo ile birebir mi)
- `git -C /Users/salihsungur/Desktop/SUTRE/01-repo fsck --no-progress` → hatasız
- `git -C <yeni> rev-parse HEAD` == §1.2'deki HEAD · `git -C <yeni> rev-list --count HEAD` == §1.4
- `git -C <yeni> status --porcelain` == §1.1 çıktısı (aynı kirli liste)
- dosya sayısı (`find … -not -path '*/.git/*' | wc -l`) == §1.4 · `du -sh <yeni>` ≈ §1.4 (birebir ya da ±%1)
- **SHA-256 5 dosya** (eski ↔ yeni birebir): `theme/sutre-child-v2/{style.css,functions.php,footer.php}`, `AGENTS.md`, `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` + `.git/config`
- `git -C <yeni> remote -v` → `git@github.com:salihsungur/sutre.git` ✓ · `git -C <yeni> config core.sshCommand` görünür (değeri rapora yazma)

## 4. ESKİYİ YEDEĞE AL + SYMLINK
- `mv ~/dev/sutre ~/dev/sutre-eski-20260926`  (silme DEĞİL — güvenlik yedeği)
- `ln -s /Users/salihsungur/Desktop/SUTRE/01-repo ~/dev/sutre`
- Doğrula: `ls -l ~/dev/sutre` → symlink görünür · `readlink ~/dev/sutre` → yeni yol

## 5. SYMLINK ÜZERİNDEN TAM DOĞRULAMA (asıl kabul kapısı)
1. `cd ~/dev/sutre && git rev-parse HEAD origin/main` → ikisi de aynı (`1695c79…`) ve **origin ile eşit olmalı**
2. `cd ~/dev/sutre && git status --porcelain` → §1.1 ile aynı
3. **Yazma probu (bot süreci TCC):** `echo tcc-probe >> ~/dev/sutre/dispatch/out/logs/tcc-write-probe.txt` → dosya gerçekten oluştu mu (gerçek yol `01-repo/dispatch/...`) → sonra sil
4. `git -C ~/dev/sutre rev-parse --show-toplevel` çıktısını rapora yaz (yol çözümü kanıtı)
5. Araçlar: `bash ~/.hermes/tools/check-live.sh https://sutre.store/ | head -4` · `python3 ~/.hermes/tools/ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/ | head -3`
6. **Bot erişim probu:** `hermes -p coder sessions list --limit 3` çalışıyor mu (profil bozulmadı) — salt-okuma
7. `grep -rl 'dev/sutre' ~/.hermes/profiles/*/SOUL.md | wc -l` (kaç profil eski yolu referans ediyor — symlink sayesinde kırılmaz; sayıyı rapora yaz)

## 6. ZORUNLU DEĞİŞİKLİK KAYDI (repo içi)
- `AGENTS.md` §3 (Ortam/Deploy) ve §6 (Hızlı Komut) + §1 depo haritası başına **tek not** ekle:
  *"Repo fiziksel konumu: `~/Desktop/SUTRE/01-repo/` — `~/dev/sutre` bu yola **symlink**'tir (2026-09-26, W2 dalgası). Tüm yollar symlink üzerinden geçerli; eski kopya `~/dev/sutre-eski-20260926` doğrulama sonrası silinir."*
- Commit: `chore(repo): depo Desktop/SUTRE/01-repo'ya tasindi (symlink dev/sutre)` + `git push origin main`
- **Push sonrası:** `git -C /Users/salihsungur/Desktop/SUTRE/01-repo rev-parse HEAD origin/main` eşitliğini doğrula (push'un symlink üzerinden değil gerçek yoldan yapıldığını kanıtla)

## 7. ROL SINIRI
- Dokunulabilir: repo taşıma, `~/dev/sutre` (symlink), `~/dev/sutre-eski-20260926` (ad değişimi), `AGENTS.md`, rapor.
- **Silme YASAK** (`rm -rf` yok). FTP'ye ve canlı siteye yazma YOK. `01-repo` içindeki mevcut içerikten hiçbir şey silinmez.
- Secret değerleri (sshCommand, remote user) rapora yazılmaz. Kabuk kuralı: iç içe `$(...)` + tırnaklı operand yasak.

## 8. DURMA KOŞULLARI (fail-fast)
- §1.2 HEAD beklenenden farklı · §3'te tek bir SHA uyuşmazlığı · `fsck` hata verirse → **DUR**, symlink kurma, `~/dev/sutre` yerinde kalsın, rapora `DURDURULDU: <neden>` yaz.

## 9. RAPOR
- `/Users/salihsungur/dev/sutre/dispatch/out/out-92-w2-repo-tasima.md` (≤600 kelime): ön uçuş çıktıları, kopya doğrulaması (SHA tablosu), symlink kanıtı, §5'in tüm çıktıları, commit/push + HEAD==origin, geri alma (`mv ~/dev/sutre-eski-20260926 ~/dev/sutre` — symlink'i önce sil), `DURDURULDU`/`YAPILAMADI` listesi.