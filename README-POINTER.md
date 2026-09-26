# 01-repo — Kod Deposu (bu klasörün KENDİSİ repodur)

`~/Desktop/SUTRE/01-repo/` = SUTRE git deposunun **gerçek fiziksel konumu**.
Depo bu klasörün tamamıdır (`.git/` burada, kök seviyede `AGENTS.md` + `theme/` + `dispatch/`).

| Alan | Değer |
| :--- | :--- |
| Gerçek repo yolu | `~/Desktop/SUTRE/01-repo/` (bu klasör) |
| Symlink | `~/dev/sutre` → `~/Desktop/SUTRE/01-repo` (2026-09-26, W2) |
| Taşıma öncesi kopya | `~/Desktop/SUTRE/06-arsiv/repo-eski-kopya-20260926/` (silinmedi, arşiv) |
| Remote | `git@github-sutre:salihsungur/sutre.git` (SSH alias; anahtar `~/.ssh/sutre_deploy`) |
| Dal | tek dal: `main` |
| Boyut (2026-09-26) | ~130 MB |

## Symlink neden var

Yoldaki `sutre` referansları (AGENTS.md, betikler, dispatch paketleri, ajan konvansiyonları)
sabit kodlanmıştır. Taşıma **kopya değil, taşıma + symlink** olarak yapıldı: gerçek dizin bu klasör,
`~/dev/sutre` sembolik bağ olarak kaldı → eski yollar çalışmaya devam eder, tek gerçek kopya olur.
Eski dosya kopyası `06-arsiv/repo-eski-kopya-20260926` altında **silinmeden** durur (geri alınabilir).

## Kural

- Repo durumu ve iş kaydı için tek kaynak: bu klasördeki `AGENTS.md` (§0 değişmez kural).
- Bu depoya yazma/commit yalnız görev paketinin açıkça izin verdiği kapsamda yapılır.
- Deploy bu klasörden yapılmaz; deploy kanalları `AGENTS.md` §3'te tanımlıdır.
- Secret bu depoya girmez (`AGENTS.md` §2). Kimlik dosyaları `~/Desktop/SUTRE/04-hesaplar/` (700/600).