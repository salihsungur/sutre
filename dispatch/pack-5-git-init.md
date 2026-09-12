# PAKET 5 — FAZ 1/1: GIT DEPOSU KURULUMU (@devops)

## Görev
Anayasa §13 Faz 1'in ilk kalemi: `/opt/data/workspace/proje/` altında Git deposu kur, anayasa §2.2'ye uygun .gitignore ile mevcut dokümantasyonu ilk commit'lere al, dallanma düzenini kur.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (`git status` veya read_file ile mevcut durumu kontrol et). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — §2.2 (depo yapısı), §3.4 (Git ve değişiklik yönetimi), §15 (asla yapılmayacaklar)
2. `/opt/data/workspace/proje/AGENTS.md`

## Kapsam / görev
1. `cd /opt/data/workspace/proje && git init -b main` (repo yok — doğrula: `git status` currently fails).
2. `.gitignore` yaz — anayasa §2.2 bağlayıcı: `wp-config.php`, `wp-content/uploads/`, cache dizinleri, yedek dosyaları (`*.sql`, `*.sql.gz`, `*.dump`), üretim DB dökümleri, secret dosyaları (`.env*` KÖK seviyede — ama `.env.example` hariçNot: `.env.example` include edilebilir), `dispatch/run-*.log`, `dispatch/out/` report dosyaları ÇALIŞMA KANITI olarak takip edilebilir kalsın (out-*.md'i takip et, run loglarını ignore et), `node_modules/`, `vendor/`, `.hermes/dispatch/tmp*`.
   - `dispatch/pack-*.md` takip EDİLSİN (iş geçmişi kanıtı).
3. İki commit:
   - Commit 1: `docs: proje anayasası ve Faz 0 girdi/envanter dosyaları` — anayasa .md, AGENTS.md, PROJECT_INPUTS.md, PRIVACY-DATA-MAP.md, SECURITY.md, docs/ ağacı.
   - Commit 2: `chore: dispatch altyapısı ve git kuralları` — dispatch/pack-*.md, dispatch/out/out-*.md, .gitignore.
4. Dallanma düzeni: `main` kalıcı; `git branch staging && git branch dev` oluştur (production ayrımı staging назваными dalınla kurulus advance'te netleşecek; şimdilik pattern kurulu olsun, ADR'ye not düşülebilir).
5. `git log --oneline`, `git status` çıktısını rapora VE `/opt/data/workspace/proje/dispatch/out/evidence-5-git-init.txt` (mutlak yol, report'un yanına) kaydet.

## Bağlayıcı sınırlar (out-of-scope)
- Remote ekleme/push YOK (remote seçimi OWNER_APPROVAL_REQUIRED — Salih'e sorulacak).
- WordPress/WooCommerce kurulumu YOK (sonraki paket).
- Mevcut doküman içeriklerini DEĞİŞTİRME.
- Secret yazma YOK. Provider/model override YOK.

## Kanıt / kapılar
- Kanıt: `git log --oneline` iki commit, `git status` temiz, iki branch (staging, dev) mevcut, evidence dosyası diskte.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-5-git-init.md`
Anayasa §16 formatı. Rapor 150-300 kelime.
