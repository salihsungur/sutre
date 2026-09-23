# PACK-88 — Dispatch arşivini repoya kaydet (tam kayıt disiplini)

## 0. NEDEN
Sahip kuralı (birebir): *"yapılan her işlemin her zaman tamamen kaydolmasını istiyorum."* Şu an `dispatch/` altındaki **46 dosya untracked** — paketler, bot raporları, koşu logları, ekran görüntüleri, ham HTML/FTP çıktıları. Bunlar orkestrasyon kanıtıdır (AGENTS §0.5) ve git'e girmelidir.

## 1. GÖREV
1. Önce envanter çıkar: `git status --porcelain` çıktısındaki **tüm `??` yollarını** listele; her biri için boyutlandırılmış tablo (dosya · tip · boyut KB).
2. **Hepsini tek commit'te repoya ekle** (bu dosyalar için `.gitignore` EKLEME; amaç tam kayıt):
   - `dispatch/pack-76*.md` → `dispatch/pack-87*.md` (paketler)
   - `dispatch/out/out-7*.md`, `out-8*.md` (bot raporları)
   - `dispatch/out/logs/**` (koşu logları, `coder-pack77/`, `p83-session-jsonl`, `p84/`, `p85/*.html`, `out-80-*.txt/html`)
   - `dispatch/out/evidence/*.png` (kanıt görselleri)
   - `dispatch/out/backups/**/SHA256SUMS` (SHA kayıtları — indirilen arşiv dosyalarının **kendileri** repo DIŞI kalır; `*.php`/`*.css` dosyaları `backups/p87-olu-artik/{prod,staging}/` içindeyse **eklenmez**, `.gitignore` ile dışla: `dispatch/out/backups/*/*/*.php` + `*.css`)
3. **Boyut kontrolü:** toplam eklenen boyut 15 MB'ı aşarsa PNG'leri `git add` ile ekle ama **`git gc` yapma**; rapora toplam KB yaz. (Aşmazsa normal commit.)
4. Commit mesajı: `docs(dispatch): pack-76..87 paketleri + raporlar + loglar + kanitlar arsivlendi`
5. `git push origin main` → `git rev-parse HEAD origin/main` eşitliğini doğrula.
6. **AGENTS.md §9 (kayıt defteri):** tek satır ekle: `- **Dispatch arşivi (23-09-2026):** pack-76→87 paketleri + tüm koşu logları + kanıt görselleri repoda kalıcı (`dispatch/out/logs/run-pack-*.log`, tam çıktı, `tail` yok).` → bu satırı **aynı commit'e dahil et** (varsa ikinci küçük commit de kabul).
7. Doğrulama: commit sonrası `git status --porcelain` → yalnız `.gitignore`'a alınan `*.php/*.css` kalıntıları görünmeli (ya da hiçbir şey); `git log --stat -1 | tail -5` ile dosya sayısını rapora yaz.

## 2. ROL SINIRI
- Yalnız `dispatch/**` + `AGENTS.md` + (gerekirse) `.gitignore`. Tema kodu, DB, FTP, canlı site YASAK.
- `git push` bu paketin parçasıdır (sahip kuralı: her tur commit+push).
- Provider/model override YASAK.

## 3. RAPOR
- `/Users/salihsungur/dev/sutre/dispatch/out/out-88-dispatch-arsiv.md` (≤400 kelime): envanter tablosu (dosya sayısı + toplam KB), commit hash, push kanıtı (HEAD==origin), `git status` sonrası durum, `.gitignore` kararı, `YAPILAMADI` varsa nedeni.