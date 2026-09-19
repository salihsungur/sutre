# PAKET 8 — FAZ 1/4: DOKÜMANTASYON COMMIT'İ + ADR↔VERSION-LOCK BAĞLANTISI (@docs)

## Görev
(pamuk6 ve P7 çıktılarının commit'i + iki küçük tutarlılık düzeltmesi)

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (git status). Plan metni yazma.

## Repo / beklenen durum
- Repo: `/opt/data/workspace/proje`
- Önce koş: `git status -sb`, `git rev-parse HEAD`
- Beklenen: temiz değil — şunlar UNTRACKED olmalı: `ADR/`, `dispatch/out/out-6-version-lock.md`, `dispatch/out/out-7-adr-env.md`, `dispatch/pack-6-version-lock.md`, `dispatch/pack-7-adr-env.md`, `docs/architecture/environment-plan.md`, `docs/architecture/version-lock.md`.
- `git reset --hard`, `git clean`, `git checkout --` ÇALIŞTIRMA — untracked dosyalar değerli iş çıktısıdır, asla ezme.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/AGENTS.md`
2. `/opt/data/workspace/proje/docs/architecture/version-lock.md` (yalnız §6 "kurulum anı tekrar doğrulama kapısı" bölümüne not eklenecek)
3. `/opt/data/workspace/proje/ADR/ADR-001-kurulum-stratejisi.md`

## Kapsam / görev
1. Küçük tutarlılık düzeltmeleri (yalnız bu iki düzenleme):
   - `ADR-001: ADR başındaki "version-lock.md henüz yok, bu ADR sürüm bağımsız yazılmıştır" satırını şu ile güncelle: "Sürüm kilidi P6'dan geldi: docs/architecture/version-lock.md (WP 7.1 / WC 11.1.0 / PHP 8.4, 2026-09-12 doğrulama). Kurulum anında §6 tekrar doğrulama kapısı geçerli."
   - `environment-plan.md`: aynı yönde başlıktaki "Sürüm notu" satırını güncelle (P6 çıktısına referans ver).
   - Başka hiçbir içerik değişikliği YOK.
2. Üç ayrı commit:
   - Commit 1: `docs(faz1): sürüm kilit belgesi (P6, @researcher)` — version-lock.md + pack-6 + out-6
   - Commit 2: `docs(adr): kurulum stratejisi taslağı ve ortam planı (P7, @architect)` — ADR/, environment-plan.md + pack-7 + out-7
   - Commit 3: `docs: ADR'ye sürüm kilidi bağlantısı (tutarlılık)` — iki dosyadaki küçük düzeltmeler
   - Tümü `feat`/`chore`/`docs` conventional öneki ile; her commit `git log --oneline` çıktısında ayrı satır.
3. Push YOK (remote yok — OWNER_APPROVAL_REQUIRED).

## Kanıt / kapılar
- Kanıt: `git log --oneline -10` + `git status` (temiz) çıktısını rapora veya `dispatch/out/evidence-8-commits.txt`'ye kaydet.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-8-docs-commit.md`
Anayasa §16 formatı. Rapor 100-250 kelime.
