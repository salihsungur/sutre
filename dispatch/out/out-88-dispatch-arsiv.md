# PACK-88 — Dispatch arşivi repoya kaydedildi (tam kayıt disiplini)

**Tarih:** 23-09-2026 · **Rol:** docs · **Kapsam:** `dispatch/**` + `AGENTS.md` + `.gitignore`
**Commit:** `2ead00e` (=origin/main) · **Push:** `de7cdf4..2ead00e main -> main`

## Envanter (commit'e giren yeni dosyalar)

| Kategori | Adet | Not |
| :--- | ---: | :--- |
| `dispatch/pack-76*.md` → `pack-88.md` (paketler) | 14 | pack-76, 76b, 77–88 |
| `dispatch/out/out-7*.md`, `out-8*.md` (bot raporları) | 6 | out-76/76b/79/81/82/86 |
| `dispatch/out/logs/**` (koşu logları) | 37 | `run-pack-78..88.log` (12) + `coder-pack77/` (2) + `p84/` (13) + `p85/` (4) + `out-80-*` (5) + `p83-session-jsonl` (1) |
| `dispatch/out/evidence/*.png` (kanıt görselleri) | 4 | out-79 ×3, out-81 ×1 |
| `dispatch/out/backups/**/SHA256SUMS` (SHA kayıtları) | 3 | p83-prod, p85-prod, p85-staging (p76 + p87 zaten takipte) |
| **YENİ DOSYA TOPLAMI** | **64** | untracked 61 + 3 yeni SHA256SUMS |
| + `.gitignore` (M) + `AGENTS.md` (M) | 2 | — |
| **COMMIT TOPLAMI** | **66** | `git log --stat -1` → **66 files changed, 7385 insertions(+), 1 deletion(-)** |

**Boyut:** yeni içerik ≈ **2.055 KB** (2.104.124 B); commit geneli ≈ 2.132 KB.
**15 MB sınırı AŞILMADI** (2 MB < 15 MB) → normal commit; `git gc` yapılmadı.
Not: paket 46 untracked bekliyordu; gerçek durum **48 üst-seviye entry = 61 dosya** (dizinler genişletildi, sapma raporda).

## `.gitignore` kararı

`backups/` deseni tüm dispatch yedek ağacını (SHA256SUMS dahil) ignore ediyordu; git "ignore'lu dizin altındaki dosya geri alınamaz" kuralı SHA256SUMS'i engelliyordu. Çözüm — dizini geri al, İÇERİK dosyalarını türle dışla:

```
backups/
!dispatch/out/backups/
dispatch/out/backups/**/*.php
dispatch/out/backups/**/*.css
```

- İndirilen/yedeklenen arşiv dosyalarının **kendileri (php/css) repo DIŞI** kalır (13 dosya, `--ignored` ile doğrulandı).
- **SHA256SUMS kayıtları takip edilir** (5/5: p76, p83, p85-prod, p85-staging, p87 — p76/p87 önceden takipteydi).
- `*.php`/`*.css` için paketin verdiği `*/*/*` yerine `**` kullanıldı (p76/p83/p85 gibi 2-seviye dizinler de kapsanır; aksi hâlde sızarlardı).

## Doğrulama

- `git push origin main` → `de7cdf4..2ead00e main -> main`.
- `git rev-parse HEAD` == `git rev-parse origin/main` → **EŞİT (2ead00eb40f22cbe53dce9dbed0aa6fc36b33ebc)**.
- Commit sonrası `git status --porcelain` → **BOŞ (temiz)**; yalnızca `--ignored` listesinde beklenen backups php/css görünür.
- Commit'e backups php/css sızmadı: `0`.
- `git log --stat -1` → **66 files changed**.

## Kural kaydı

`AGENTS.md` §8 (Tarihsel Kayıt Defteri / kayıt defteri — dosyada §9 yok, §0.2 atfı §8'i işaret eder) tek satır eklendi ve **aynı commit'e** dahil edildi: "Dispatch arşivi (23-09-2026): pack-76→87 paketleri + tüm koşu logları + kanıt görselleri repoda kalıcı".

**YAPILAMADI:** yok.