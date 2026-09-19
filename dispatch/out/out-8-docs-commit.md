# PAKET 8 RAPOR — DOKÜMANTASYON COMMIT'İ + ADR↔VERSION-LOCK BAĞLANTISI (@docs)

## Değişen dosya yolları
- `dispatch/out/evidence-8-commits.txt` — YENİ (kanıt)
- `dispatch/out/out-8-docs-commit.md` — YENİ (bu rapor)
- Aşağıdaki üç commit'te eklenen 7 dosya (bkz. kanıt)

## Yapılan iş
1. Commit 1 (`2c0d9bc`): P6 çıktıları — `docs/architecture/version-lock.md` (WP 7.1 / WC 11.1.0 / PHP 8.4, 2026-09-12 doğrulama; §6 kurulum anı tekrar doğrulama kapısı içerir), `dispatch/pack-6-version-lock.md`, `dispatch/out/out-6-version-lock.md`.
2. Commit 2 (`6d24a9a`): P7 çıktıları — `ADR/ADR-001-kurulum-stratejisi.md`, `docs/architecture/environment-plan.md`, `dispatch/pack-7-adr-env.md`, `dispatch/out/out-7-adr-env.md`.
3. Commit 3 (`3a88d70`): İki tutarlılık düzeltmesi — ADR-001 başındaki ve environment-plan.md başlığındaki "version-lock.md henüz yok" notları P6 çıktısına referans veren biçime çevrildi. Başka içerik değişikliği YOK.

## Kanıt (git log --oneline -10 / git status, evidence-8-commits.txt)
```
3a88d70 docs: ADR'ye sürüm kilidi bağlantısı (tutarlılık)
6d24a9a docs(adr): kurulum stratejisi taslağı ve ortam planı (P7, @architect)
2c0d9bc docs(faz1): sürüm kilit belgesi (P6, @researcher)
b36fd57 chore: paket 5 rapor ve kanıt dosyaları (git kurulumu)
```
`git status`: temiz (yalnız bu paketin kendi rapor/pack dosyaları untracked — teslimat anındaki normal durum). Push Yok: remote tanımlı değil (OWNER_APPROVAL_REQUIRED).

## Açık kapılar
- NEEDS_OWNER_INPUT: yok.
- OWNER_APPROVAL_REQUIRED: ADR-001 onayı; hosting tipi/bütçe/domain girdileri; remote/push yetkisi.
- LEGAL_REVIEW_REQUIRED: yok.

## Not
Commit 1 atılırken .git/index.lock geçici kilit dosyasına rastlandı; süreç herhangi bir git işlemi çalışmıyorken dosya zaten temizlenmişti (ortam git history'sinde belirtilen bir işlem yoktu). Üçüncü commit'ten görece daha önce atılan commit 2'nin orijinal mesajı yanlışlıkla "docs(faz1): sürüm kilit belgesi (P6...)" idi; `git commit --amend` ile doğru mesaja (`docs(adr): ...`) düzeltildi. İçerik kaybı yok.
