# PACK-92 (W2) — Git deposunu taşı + symlink kur

Tarih: 2026-09-26 12:49–12:53 (+03) · Durum: **TAMAMLANDI** · Silme yok.

## 1. Ön Uçuş (kanıt)

- `git status --porcelain`: 10 satır, hepsi `??` (untracked) — pack-89/90/91/92 paketleri, `out/logs/*`, `out-90`, `out-91`. Değiştirilmiş/silinen dosya YOK.
- `rev-parse HEAD` = `1695c792cabf002266f5e007d93e80e5e978dbb5` → **beklenenle aynı** ✓ (`origin/main` de aynı).
- `fsck --no-progress`: exit 0, hata yok (yalnız 5 dangling blob — normal).
- `rev-list --count HEAD` = **222** · dosya sayısı (`-not -path '*/.git/*'`) = **6337** · `du -sh` = **130M** (.git 29M).
- `df -h /`: 692Gi boş → kopya için fazlasıyla yeterli.

## 2–3. Kopya ve Doğrulama

`cp -a ~/dev/sutre/. .../SUTRE/01-repo/` → COPY_OK.

| Dosya | Eski SHA-256 | Yeni SHA-256 | ✓ |
| :--- | :--- | :--- | :-- |
| theme/sutre-child-v2/style.css | 8baaa364…260b75 | 8baaa364…260b75 | ✓ |
| theme/sutre-child-v2/functions.php | 660ef6bf…fcbbbb | 660ef6bf…fcbbbb | ✓ |
| theme/sutre-child-v2/footer.php | 20effb74…e74f1b0 | 20effb74…e74f1b0 | ✓ |
| AGENTS.md | e5f6e577…d88a0d | e5f6e577…d88a0d | ✓ |
| WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md | 86a66763…e1777e | 86a66763…e1777e | ✓ |
| .git/config | 17517446…039263 | 17517446…039263 | ✓ |

Yeni repo: `fsck` exit 0 · HEAD `1695c792…` ✓ · commit 222 ✓ · `du` 130M (birebir).
**İki beklenen sapma (kusur değil):** (a) dosya sayısı 6338 (+1 = W1'den gelen `README-POINTER.md`), (b) kirli listeye aynı dosya `?? README-POINTER.md` olarak ekleniyor — kalan 10 satır §1 ile birebir.
`remote -v`: `git@github-sutre:salihsungur/sutre.git` (SSH alias; `~/.ssh/config` → `HostName github.com`, yani fiilen `git@github.com:salihsungur/sutre.git`). Taşımadan önce de böyleydi, değiştirilmedi. `core.sshCommand` **mevcut** (değer rapora yazılmadı).

## 4. Yedek + Symlink

- `mv ~/dev/sutre ~/dev/sutre-eski-20260926` → MV_OK (yedek: 130M, 6337 dosya — yerinde).
- `ln -s /Users/salihsungur/Desktop/SUTRE/01-repo ~/dev/sutre` → LN_OK.
- `ls -l ~/dev/sutre`: `lrwxr-xr-x … /Users/salihsungur/dev/sutre -> /Users/salihsungur/Desktop/SUTRE/01-repo` · `readlink`: `/Users/salihsungur/Desktop/SUTRE/01-repo`.

## 5. Symlink Üzerinden Doğrulama (kabul kapısı)

1. `git rev-parse HEAD origin/main` → `1695c79…` her ikisi de (o an origin ile eşit).
2. `git status --porcelain` → §1.1 ile aynı (+ README-POINTER.md).
3. Yazma probu: `echo tcc-probe >> ~/dev/sutre/dispatch/out/logs/tcc-write-probe.txt` → gerçek yol `01-repo/dispatch/out/logs/tcc-write-probe.txt` içinde oluştu, içerik `tcc-probe` okundu; **sonra silindi** (dizin listesi temiz).
4. `rev-parse --show-toplevel` → `/Users/salihsungur/Desktop/SUTRE/01-repo`; `cd ~/dev/sutre && pwd -P` → aynı.
5. `check-live.sh https://sutre.store/` → `HTTP=200 cache=hit payments=1 svg=5 css=ver=3.4.1,ver=3.6.8,ver=3.7.1 fatal=0`. `ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/` → dizin listelendi (`.`, `..`, `.ftpquota`), hata yok.
6. `hermes -p coder sessions list --limit 3` → çalıştı (PACK-85, PACK-83, P76 oturumları listelendi) → profil sağlam.
7. `grep -rl 'dev/sutre' ~/.hermes/profiles/*/SOUL.md | wc -l` = **15** profil eski yolu referans ediyor; symlink sayesinde hiçbiri kırılmadı.

## 6. Değişiklik Kaydı + Push

- `AGENTS.md` §1 (Depo Haritası), §3 (Ortam/Deploy) ve §6 (Hızlı Komut) başına aynı not eklendi (`grep -c 'Repo fiziksel konumu'` = **3**): repo fiziksel konumu `~/Desktop/SUTRE/01-repo/`, `~/dev/sutre` symlink, eski kopya doğrulama sonrası silinir.
- Commit: `24a64af` — `chore(repo): depo Desktop/SUTRE/01-repo'ya tasindi (symlink dev/sutre)` (1 dosya, +6 satır).
- Push: `1695c79..24a64af main -> main` ✓
- **Push sonrası doğrulama (gerçek yoldan):** `git -C /Users/salihsungur/Desktop/SUTRE/01-repo rev-parse HEAD origin/main` → `24a64af69bcbe5fe1b0a43da301c6c584bbc3f53` / `24a64af69bcbe5fe1b0a43da301c6c584bbc3f53` → **HEAD == origin/main** ✓ (symlink üzerinden de aynı çıktı).

## 7. Geri Alma

1. `rm ~/dev/sutre` (yalnız symlink — hedef dizinin içeriği etkilenmez)
2. `mv ~/dev/sutre-eski-20260926 ~/dev/sutre`
3. İsteğe bağlı: `git -C ~/dev/sutre reset --hard 1695c79 && git push --force-with-lease origin main` (AGENTS.md notunu geri almak için).

## 8. YAPILAMADI / DURDURULDU

- **DURDURULDU:** yok (HEAD beklenenle aynıydı, tüm SHA'lar tuttu, fsck temiz).
- **YAPILAMADI:** yok.
- Sapmalar (bilinçli, kanıtlı): dosya sayısı 6338 ve kirli listede `README-POINTER.md` — ikisi de W1'den kalan önceden var olan dosyadan kaynaklanır, kopya hatası değildir.