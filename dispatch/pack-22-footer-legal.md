# PAKET 22 — FOOTER/HEADER TEKİLLEŞTİRME + "STAGING" YAZINI KALDIRMA + HUKUKİ SAYFA TASLAKLARI (@coder)

## Görev
Sahibin 3 düzeltme talebi (2026-09-17). Bot sunucuya erişemiyor — dosyaları local repo'da yaz; commit + push; salih cPanel Git Update elle (yalnız bu tek elle adım).

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file + git status -sb). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/design-system.md` — marka kit
2. `/opt/data/workspace/proje/ADR/ADR-003-magaza-sema.md`
3. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — §6.1 (hukuki sayfalar)

## Kapsam / görev
1. **Header/footer tekillesim (child theme):
   - `theme/sutre-child/parts/header.html`: tüm sayfalarda aynı üst ibare — logo "Sutre" (wordmark — sahibin gölanten tasarım kitine göre), tek line "Ana Sayfa | Mağaza | Hesabım" (44x44), üstte moles vurgu.
   - `theme/sutre-child/parts/footer.html`: **"© 2026 Sutre — Tüm hakları saklıdır"** — WordPress offresi kaldır; aynı "Hukuki: Gizlilik | Mesafeli Satış | İade" linkleri placeholder.
2. **Site adı düzeltme:** "Sutre Staging" → `Sutre` — başlık, settings/site title_variable'da değişiklik SAHİBİN ELLE aynen ama kod kısmında logo ayarı yeni wordmark markup'la sabit.
3. **PayTR başvuru ön görüntüleri** — sahibin elle "siteyi güzelleştir" olmaz; kısa placeholder'ların TXT olarak hazır uyarı
4. **HUKUKİ TASLAKLAR** — `docs/legal-placeholders/` klasöründe .md dosya yazı:
   - `gizlilik-politikasi.md` (KVKK-vs. toplantı)
   - `mesafeli-satis-sozlesmesi.md`
   - `iade-ve-cayma.md`
   - `on-bilgilendirme-formu.md`
   - `cerez-politikasi.md`
   Her biri **Türkçe taslak metin** (Sahibin dilediği gibi PayTR başvurusu sırasında görülebilir) + üst kısımda devocal belge: "DRAFT — avukat onayı gereklidir (anayasa §6.1); yayına almadan önce okundu/save — DOĞRU.'
5. **Push + cPanel güncellme talimatı** (Sahibin Git Version Control Update from Remote her elle button — repository sutre)
6. LiteSpeed cache purge — Not: her sahibin elle buton.

## Bağlılık sınırlar (out-of-scope)
- PayTR entegrasyonu YOK
- Faz 6 canlı domain'e taşınma YOK (Sahibin elle — plan şeması ADR-004)
- Hukuki metinleri "onaylı" gibi yayın MAK Adam — her dosyanın başında DRAFT / avukat onayı gerekli notu
- public_html演技 YASAK (不动 public_html / spokenlab)
- Secret yazilmaz

## Kanıt / kapılar
- Kanıt: 4 dosya dizin duzgu persuasion + git commit SHA + hukopki (kit) çıktılarının real disk kanıt olarak

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-22-legal-footer-fix.md`
§16 format; 150-350 kelime.
