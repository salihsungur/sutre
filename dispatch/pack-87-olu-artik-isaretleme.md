# PACK-87 — Ölü artık dizini İŞARETLE (yeniden adlandır) + AGENTS kayıtları

## 0. SAHİP KARARI (23-09-2026, birebir)
- *"sadece ismini değiştir ölü olduğu belli olsun ama kalsın"* → **SİLME YOK.** İç içe dizin olduğu yerde kalır, yalnız **adı** ölü olduğunu anlatacak şekilde değişir.
- *"Evet, uygula (arşivle→sil→404 doğrula)"* → yürütme izni + AGENTS kayıtları. **Çelişkiyi şöyle çöz:** içerik **korunur** (silinmez); yine de **yerel arşiv kopyası** alınır (güvence), sonra **yeniden adlandırma** yapılır, ardından erişim doğrulanır.

## 1. HEDEF (pack-86 incelemesi)
- Prod: `/sutre.store/wp-content/themes/sutre-child/staging.sutre.store/` → içinde `wp-content/themes/sutre-child/{functions.php, style.css}` (22 888 B + 36 915 B, `SUTRE_VERSION 3.4.0`, git `efc30be` ile SHA-özdeş; 21-09-2026 13:46 damgalı P43 göreli-yol kazası).
- Staging: `/staging.sutre.store/wp-content/themes/sutre-child/staging.sutre.store/` → **birebir aynı** yapı.
- Ölü artık: 0 referans (repo + canlı HTML + sitemap). Dizin listeleme kapalı (403), içteki `style.css` 200, içteki `functions.php` yorumlanıyor (PHP aktif).

## 2. GÖREV (sırayla, her adımda kanıt)
1. **Araçlar:** `python3 ~/.hermes/tools/ftp-tool.py {ls,get,put,rm,rmrf,verify,sha}` · `bash ~/.hermes/tools/check-live.sh <url>`. **Kabuk kuralı:** iç içe `$(...)` + tırnaklı operand içeren tek satır komut YAZMA (koşulsuz bloklanır).
2. **ARŞİV (güvence, silme yok):** her iki ortamdaki içteki `functions.php` + `style.css` dosyalarını indir →
   `dispatch/out/backups/p87-olu-artik/` (prod/ ve staging/ alt klasörleri) + **`SHA256SUMS`** yaz. İndirilen SHA'ların, pack-86'daki değerlerle eşleştiğini doğrula (değişmemişlik kanıtı).
3. **YENİDEN ADLANDIR (asıl iş):** FTP `RNFR`/`RNTO` ile **yalnız üst öğeyi** değiştir:
   - prod: `staging.sutre.store` → **`_OLU-ARTIK-staging.sutre.store-20260921`**
   - staging: aynı yeni ad
   (İç yapı olduğu gibi kalır; tek rename yeter. Alt dizinleri tek tek taşımak YASAK.)
4. **DOĞRULAMA:**
   - Eski yol → **404** (artık yok): `https://sutre.store/wp-content/themes/sutre-child/staging.sutre.store/` + aynısı staging.
   - Yeni yol → dizin **403** (listeleme kapalı), içteki `style.css` → **200** (içerik korunduğu kanıtı; boyut 36 915 B), `functions.php` → 200/0 bayt (davranış değişmedi).
   - FTP listesi: tema dizininde yeni ad görünüyor, eski ad **yok** (ham `ls` çıktısı rapora).
   - **Regresyon:** `check-live.sh` ile `/`, `/shop/`, `/cart/`, `/my-account/`, 404 sayfası, `staging.sutre.store/` → HTTP + `payments` + fatal=0; `geleceginbilimi.com` 200.
5. **AGENTS.md §7 kayıtları** (3 madde, aynı commit):
   - `P84 — LiteSpeed cache purge + noindex kaldırma + sitemap kurulumu — ZATEN YAPILDI (23-09-2026)`: ana sayfa bayat cache'i tek seferlik bakım betiğiyle (`blog_public=1` + rewrite flush + `litespeed_purge_all`, betik silindi→404) düzeltildi; `/wp-sitemap.xml` 200 (6 alt sitemap), `robots.txt`'e Sitemap satırı eklendi; kanıt `dispatch/out/out-84-purge-noindex-sitemap.md`.
   - `P86 — Üretim temasındaki 'staging.sutre.store' iç içe dizini — İNCELENDİ + İŞARETLENDİ (23-09-2026)`: köken P43 göreli-yol kazası (21-09); ölü artık; sahip kararı: **silme, yalnız isim değişikliği** → `_OLU-ARTIK-staging.sutre.store-20260921` (prod + staging); yerel arşiv + SHA256SUMS `dispatch/out/backups/p87-olu-artik/`; kanıt `dispatch/out/out-86-…md` + `out-87-…md`.
   - `KURAL — FTP parola hijyeni (23-09-2026)`: parola komut satırına/çıktıya yazılmaz; `ftp-tool.py` kullanılır; parola oturum transcript'ine sızarsa sahibe rotasyon önerilir (sahip 23-09'da riski kabul etti: döndürülmedi).
6. **Commit + push:** yalnız `AGENTS.md` + rapor + `backups/p87-olu-artik/SHA256SUMS` (indirilen dosyaların kendisi repoya GİRMEZ). Mesaj: `chore(hosting): olu artik dizini isaretlendi + P84/P86 kayitlari`. Sonra `git rev-parse HEAD origin/main` eşitliği.

## 3. ROL SINIRI
- **Hiçbir şey silinmez** (`rm`/`rmrf` YASAK). Yalnız rename + indirme + doğrulama.
- Dokunulabilir: iki ortamdaki o tek üst dizin adı, `AGENTS.md`, `dispatch/out/backups/p87-olu-artik/`, rapor/görseller.
- Tema dosyalarının içeriği, DB, plugin, başka site YASAK. Provider/model override YASAK.

## 4. RAPOR
- `/Users/salihsungur/dev/sutre/dispatch/out/out-87-olu-artik-isaretleme.md` (≤600 kelime): (1) yapılanlar, (2) arşiv tablosu + SHA'lar, (3) rename öncesi/sonrası ham `ls`, (4) eski yol 404 + yeni yol 403/200 kanıtları, (5) regresyon çıktıları, (6) AGENTS maddeleri, (7) commit/push + HEAD==origin, (8) **geri alma** (geri adlandırma komutu: `_OLU-ARTIK-…` → `staging.sutre.store`), (9) `YAPILAMADI` listesi.
- Yargı cümlesi YASAK; ham çıktı + ölçüm yaz. `git status -sb` + `git diff --check` rapora.