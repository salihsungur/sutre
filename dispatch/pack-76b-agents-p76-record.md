# PACK-76b — AGENTS.md: P76 footer ödeme logoları iş kaydı (docs, küçük)

## 0. ÖN KOŞUL / İLK ADIMLAR
- Repo: `/Users/salihsungur/dev/sutre` — beklenen HEAD = `ceef9e67dd2228b500df8ebb32aee184800169c8` (origin/main ile aynı).
- İlk adım: `read_file` ile `/Users/salihsungur/dev/sutre/AGENTS.md` TAMAMINI oku (§7 açık işler bölümünü bul).
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**

## 1. GÖREV (tek iş, tek commit)
`AGENTS.md` §7 (P75'in bulunduğu açık işler listesi) içine, **liste sırasını bozmadan** şu maddeyi EKLE:

```
- [ ] **P76 — Footer ödeme yöntemi logoları — `SÜRÜYOR` (25-09-2026):** Sahip talebi (birebir): footer'da **"istanbul" yazısının SOLUNA** PayTR'nin desteklediği ödeme yöntemlerinin logoları eklenecek (visa/mastercard vb.; "kısaca paytr'nin desteklediği ödeme yöntemlerinin logosu"). Gerekçe: **PayTR onayı için sitede ödeme yöntemi logolarının bulunması gerektiği** bilgisi (sahip beyanı — resmi şart doğrulaması araştırma paketinde). Kanal: pack-76 (researcher, read-only araştırma → `dispatch/out/out-76-paytr-logos-research.md`) → uygulama (coder: `theme/sutre-child-v2/footer.php` + `style.css` + `assets/img/`, scoped selector, `SUTRE_VERSION` bump, staging'e deploy + canlı curl kanıtı) → bağımsız doğrulama (tester: canlı sayfa kanıtı + regresyon). Görsel değişiklik kuralı §0.6 geçerli: sahibe gösterilir; bot "güzel görünüyor" YAZMAZ.
```

Kurallar:
- Madde metnini **yukarıdaki haliyle birebir** ekle (kısaltma/ifade değişikliği yapma).
- Başka hiçbir yere dokunma; §7'nin diğer maddelerini yeniden yazma.
- AGENTS.md'nin geri kalanı değişmemeli (`git diff --stat` tek dosya + az satır olmalı).

## 2. ROL SINIRI
- Yalnız `AGENTS.md` düzenlenir. Kod dosyalarına, tema dosyalarına, `dispatch/` içeriğine DOKUNMA.
- Provider/model override YASAK.

## 3. KAPSAM DIŞI
- Footer kodlaması, logo dosyası üretimi, araştırma, deploy.
- Başka `.md` dosyası oluşturmak.

## 4. TESLİM
- Commit mesajı: `docs(agents): P76 footer odeme yontemi logolari is kaydi (sahip talebi)`
- `git push origin main` (deploy key `/Users/salihsungur/.ssh/sutre_deploy`; repo `core.sshCommand` ayarlı).
- Rapor dosyası: `/Users/salihsungur/dev/sutre/dispatch/out/out-76b-agents-p76-record.md` (≤300 kelime): eklenen maddenin satır no'su, commit SHA, push sonrası `git status -sb` + `git rev-parse HEAD origin/main`.

## 5. TEMİZLİK
- `git diff --check` temiz olmalı; geçici dosya bırakma.