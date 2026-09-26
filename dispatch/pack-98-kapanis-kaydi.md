# PACK-98 — Kapanış kaydı: kalan dispatch dosyaları + P96/P97 AGENTS kaydı

## 0. GÖREV (kısa tut — ≤10 tool çağrısı, keşif yok)
1. `cd /Users/salihsungur/Desktop/SUTRE/01-repo && git status --porcelain` → kalan untracked/modified dosyaları listele (beklenen: `dispatch/pack-9*.md`, `dispatch/out/out-9*.md`, `dispatch/out/logs/run-pack-9*.log`).
2. **Hepsini** `git add` + commit: `chore(dispatch): pack-95..97 + raporlar + loglar arsivlendi` → `git push origin main` → `git rev-parse HEAD origin/main` eşitliğini doğrula.
3. **AGENTS.md güncelle** (aynı commit ya da ikinci küçük commit):
   - §7 açık işler bölümüne **P96/P97** maddesi ekle:
     *"**P96/P97 — PayTR logo varlığı + Instagram kare logo doğrulaması — `ZATEN YAPILDI` (26-09-2026):** PayTR için 300×100 PNG üretildi → `~/Desktop/SUTRE/03-yayin/paytr-logo-300x100.png` (Bone `#F5F2EC` zemin, Ink `#1A1A1A` wordmark, yatay pay 12 px, ink yüksekliği 43 px, kontrast 15.58:1) + `…-seffaf.png` (açık zemin) + `…-seffaf-beyaz-yazi.png` (koyu zemin, Bone yazı, kontrast 10.72:1). Kaynak master `02-icerik/Logo ve Marka/sutre-logo-header-trans.png` (trim + LANCZOS). Instagram profil fotoğrafı: `02-icerik/Logo ve Marka/sutre-logo-square.png` (2880×2880; daire kırpmasında dışta kalan piksel **0**, kenar payı 467 px). Önizlemeler `05-raporlar/{paytr-logo-preview,instagram-kare-daire-onizleme}.png`; kanıt `dispatch/out/out-96-paytr-logo.md` + `out-97-paytr-logo-ince-ayar.md`."*
   - Tarih/commit bilgisiyle birlikte yaz; mevcut maddeleri bozma.
4. **Son kanıt:** commit sonrası `git status --porcelain` çıktısını rapora koy (boş olmalı) + `ls ~/Desktop/SUTRE/03-yayin/` ham çıktısı.

## 1. ROL SINIRI
- Yalnız `dispatch/**` + `AGENTS.md` + rapor. Siteye/FTP'ye yazma YOK. Tema dosyalarına dokunma. Kabuk kuralı: iç içe `$(...)` + tırnaklı operand yasak.

## 2. RAPOR
- `/Users/salihsungur/Desktop/SUTRE/01-repo/dispatch/out/out-98-kapanis-kaydi.md` (≤250 kelime): commit hash'leri · push kanıtı (HEAD==origin) · AGENTS maddesi · son `git status` (boş) · `YAPILAMADI` varsa nedeni.