# PACK-89 — KAPANIŞ (KISA TUT): ftp-tool mv + kalıntı log + AGENTS satırı

**Bu paket KISA olacak.** Uzun keşif/araştırma YAPMA; sadece aşağıdaki 3 adım. Hedef: ≤10 tool çağrısı, ~5 dk.

## 0. BAĞLAM
- Repo: `/Users/salihsungur/dev/sutre` · beklenen HEAD == origin/main = `96e0701`.
- `git status --porcelain` → tek kalıntı: `M dispatch/out/logs/run-pack-88.log`.
- Mevcut durum: P84/P86/P87/P88 kapandı; production canlı; ölü artık `_OLU-ARTIK-staging.sutre.store-20260921` olarak işaretli.

## 1. ADIM A — `ftp-tool.py`'ye `mv` ekle
Dosya: `~/.hermes/tools/ftp-tool.py`. Yeni alt komut:
- `mv <eski_yol> <yeni_yol>` → FTP `RNFR` + `RNTO` ile yeniden adlandırır; başarıysa `OK: <eski> -> <yeni>` basar, hata olursa `HATA: …` + exit 1.
- Aynı desende yaz (mevcut `rm` dalının yanına ~8 satır); `--help`/docstring'e `mv` satırını ekle; parola hiçbir yere yazılmaz (mevcut `creds()` kullanılır).
- **Kanıt:** geçici bir uzak dizin açmadan test et → `python3 ~/.hermes/tools/ftp-tool.py mv <gerçek_olmayan_yol> /tmp/x` çağrısının `HATA:` + exit 1 verdiğini göster (komutun kablolaması doğru, hedef yok). **Gerçek dosya/dizin taşıma YOK.**

## 2. ADIM B — kalan log kalıntısını commit et
- `git add dispatch/out/logs/run-pack-88.log` → commit mesajı: `chore(dispatch): run-pack-88 log kalinti kapatildi`.
- Push: `git push origin main` → `git rev-parse HEAD origin/main` eşitliği.

## 3. ADIM C — AGENTS.md §9 tek satır
- §9 kayıt defterine ekle: `- **Dispatch arşivi (23-09-2026):** pack-76→88 paketleri + tüm koşu logları + kanıt görselleri repoda kalıcıdır (tam çıktı; \`tail\` yok).`
- `git add AGENTS.md` + commit `docs(agents): dispatch arsivi kalici kaydi` + push; HEAD==origin teyit.

## 4. ROL SINIRI
- Dokunulabilir: `~/.hermes/tools/ftp-tool.py`, `dispatch/out/logs/run-pack-88.log`, `AGENTS.md`, rapor.
- FTP'de hiçbir şey taşınmaz/silinmez. Tema/DB/canlı site YASAK. Provider/model override YASAK.

## 5. RAPOR (kısa)
- `/Users/salihsungur/dev/sutre/dispatch/out/out-89-kapanis.md` (≤250 kelime): (A) eklenen kod + test çıktısı ham, (B) commit hash'leri + HEAD==origin, (C) AGENTS satırı, (D) son `git status --porcelain` çıktısı (temiz olmalı), (E) `YAPILAMADI` varsa nedeni.