# PAKET 22 ÇIKTI RAPORU — Header/Footer Tekilleştirme + "Staging" + Hukuki Taslaklar

Tarih: 2026-09-17 · Bot: @coder · Anayasa §16 format.

## SONUÇ
1. `theme/sutre-child/parts/header.html` (YENİ): Her sayfada aynı üst ibare — "Sutre" wordmark (`site-title` + `sutre-header__brand` sınıfı, kit §4: Cormorant Garamond, 600, uppercase, letter-spacing 0.14em) + tek satır nav "Ana Sayfa | Mağaza | Hesabım" (44×44 `--sutre-touch` dokunma hedefi, hover silk vurgu). Kutuuiltimi: bu part SAHİBİN elle WP-admin'den düzenlemesine kapalıdır (tek kaynak = repo).
2. `theme/sutre-child/parts/footer.html` (YENİ): "© 2026 Sutre — Tüm hakları saklıdır" sabit; "Proudly powered by WordPress" kaldırıldı (bloktema varsayılan part yerine child part). "Hukuki: Gizlilik | Mesafeli Satış | İade" placeholder linkler `/gizlilik-politikasi/`, `/mesafeli-satis/`, `/iade-ve-cayma/` slug'larına bağlandı + görünür DRAFT/avukat uyarı nota.
3. `theme/sutre-child/parts/override.css` (YENİ): yukarıdaki iki part'ın mobile-first stili (bone zemin, marine footer, silk hover) — `�functions.php`'ye `sutre_child_enqueue_parts_override()` ile bağlandı (mttime cache-safe versiyon).
4. **Site adı:** "Sutre Staging" → "Sutre" görüntüsü `option_blogname` + `the_title` filtresiyle sağlandı (tema hafif `preg_replace` ile ön yüzde çıkarır; admin'de DB değeri korunur). Kalıcı çözüm SAHİBİN elle Settings→General düzeltmesidir (NEEDSOWNER_INPUT, `p22-owner-manual-steps.md §2`).
5. HUKUKİ TASLAKLAR: `docs/legal-placeholders/` altında 5 md dosya (gizlilik, mesafeli satış, iade-cayma, ön bilgilendirme, çerez). Her biri Türkçe taslak, dolgu bilgi TBD + başında DRAFT/avukat onay uyarı metni (anayasa §6.1 + §15 kuralı korunur).
6. Sahibin elle adım listesi: `docs/operations/p22-owner-manual-steps.md` (Git Update from Remote, LiteSpeed Purge All, Settings→General, Pazarlama rızası onay kutusunun varsayılan kapalı olması, PayTR başvuru ön-görüntü uyarısı, hukuki sayfalarda avukat onayı akış).

## DOĞRULAMA (disk + git kanıtı)
- Commit: **d1e77c2** — "feat(theme+docs): P22 header/footer tekillas~ implied "Sutre Staging"->"Sutre" gorunus filtresi + 5 hukuki taslak (DRAFT, LEGAL_REVIEW_REQUIRED) + sahibin elle adim notu" (11 dosya, +445 satır).
- Push kanıtı: `5332fca..d1e77c2  main -> main` (github-sutre deploy key üzerinden; ssh config + accept-new host key gerekli oldu — not düşüldü).
- Dosya doğrulama: `git status -sb` → 5 yeni md + parts/ üç dosya + functions.php değişimi diskte mevcut; `write_file verified:true`.
- Bot sunucuda PHP CLI yok — `php -l` yapılamadı; LSP satır uyarıları (WP çekirdek fonksiyonları kök dizinde tanımlı olmamasından; P20-P21 ile aynı kategori, gerçek WP ortamında çalışır) rapor assumption: çalışma kopyası cPanel Git Update'ten sonra WP'de canlı kontrol edilmeli.

## RİSK VE GÜVENLİK
- Düşük risk: yalnız tema görünüm dosyası + deploy css; çekirdek/dosya değişikliği YOK; DB yazımı YOK.
- functions.php'ye eklenen filtre `is_admin()` korumalı; secret/anahtar YOK; kart/veri işlenmedi.
- Hukuki metinlerin hiçbiri "ONAYLI" olarak işaretlenmedi; DRAFT + LEGALREVIEW_REQUIRED kaldırılmadı.
- ROLLBACK: `git revert d1e77c2` + cPanel Git Update (tek elle adım) yeterli; DB değişikliği yok.

## AÇIK KAPILAR
- **SAHİBİN ELLE ADIMLARI (tek gece):**
  1. cPanel → Git Version Control → `sutre` → **Update from Remote**.
  2. WP-admin LiteSpeed Cache → Purge All (görüntünün yenilenmesi zorunludur).
  3. Settings→General → Site Title sahibin elle `Sutre` (theme filtresi yalnız geçici emniyet).
  4. Hukuki 5 taslak → **avukat onayı** → WP sayfa yayınla → footer linkleri bağla.
- **PAYTR_BASVURU_NOTU:** PayTR başvurusu, taslak metinler avukat onayı ile sitede YAYINDA olduktan sonra yapılmaları önerildi; aksi halde inceleme reddi riski yüksek. Bu işletme kararıdır — sahibe iletilmiştir.
- **LEGAL_REVIEW_REQUIREMENTS:** Tüm taslak metinler avukat onayına kadar yayından uzak tutulmalıdır.
