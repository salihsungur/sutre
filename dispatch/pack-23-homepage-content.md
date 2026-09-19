# PAKET 23 — ANA SAYFA İÇERİK DOLGUSU + KATEGORİ BÖLÜMÜ REDESİGN (@designer + @coder birleşik)

## KONTEKST (bağımsız oku)
- Proje: Sutre — Türkiye şal markası (Giyim→Kadın→Şal; ADR-003)
- Staging: https://staging.sutre.store/ — WordPress 7.1 + WooCommerce 11.1, TT5 child tema
- Marka kit: `/opt/data/design-system.md` (Cormorant Garamond serif + Jost sans; Bone #F5F2EC, Ink #1A1A1A, Silk #C9A66B, Marine #1B3A4B, Whisper #B8B4AC)
- Reflexyon: premium, el işi, yacht/deniz metaforu — "modern lüks"
- Logo: "SUTRE" wordmark (alt yazı YOK)
- Kullanıcı (Salih) 2026-09-17 talebi: "ana sayfayı doldurabildiğin kadar doldur güzel içeriklerle, kategoriler kısmını BAŞTAN yaz (kod+tasarım), örnek referans dilvin.com.tr fikir versin ama KOPYALAMA, premium hissi koru"
- Anayasa §9: mobil öncelikli; WCAG 2.2 AA hedef

## İLK YANIT KURALI
İlk yanıtın ZORUNLU tool çağrısı: read_file(`/opt/data/design-system.md`).
Sonra `git status -sb` — çalışma dalı temiz mi kontrol.

## Okuma sırası
1. `/opt/data/design-system.md` (tam)
2. `/opt/data/workspace/proje/ADR/ADR-003-magaza-sema.md`
3. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — §9, §3.1, §3.2
4. `theme/sutre-child/part-templates/home.php` (mevcut — kodunu oku, sonra yeniden yaz)
5. `theme/sutre-child/parts/override.css` (mevcut stil dilini anla)
6. `/opt/data/workspace/proje/dispatch/out/out-22b-tt5-footer-pattern.md` — son deploy raporlar

## Kapsam / Görevler

### A) KATEGORİ BÖLÜMÜ — BAŞTAN (farklı fikir, dilvin'in dokunuşu yalnız ilham)
Mevcut "sutre-cats tablo breadcrumb" ATILSIN. Yerine: 3 kolonlu (mobilde 1 kolon dikey akış)
"Koleksiyon Kartları":
- Her kart: büyük sayı (01/02/03), altında kategori adı (İpek / Pamuk / Bambu ya da talep üzerine model adları: İman Nour / Jakarlı), "Keşfet →" mikro-CTA.
- Hover'da kart hafif yükselir + silk (C9A66B) alt çizgi animasyonu. Mobil: dikey stack, 44px dokunma.
- Kart zeminleri: Beyaz (#FFFFFF) — Bone body üzerinde kontrast; kart kenar 1px rgba(Ink, .08).
- HTML/yapıyı klasik *PHP* (page-templates/home.php) içine ddosyalayabilirsin — bu şablon zaten klasik (do_blocks part'ları include ediyor). Kategoriler bölümünü yeni `<section class="sutre-collection">` olarak uzat.
- İpek / Pamuk / Bambu üçlü kartı: sabit tanım; ADR-003 Kategori Giyim→Kadın→Şal sıkı yerine (kullanıcı tercih). SEN DÜŞÜN VE LAY out OLUŞTUR — mevcut "tablo" silinir.

### B) ANA SAYFA İÇERİK DOLGUSU (premium e-ticaret dili, %100 Türkçe)
Yeni bölüm ekle (page-templates/home.php içinde):
1. **Hero** (var; geliş etree): büyük "Sutre" wordmark + tagline; altında Season split değil, SADECE bi kısa "Koleksiyonu Keşfet" CTA (mevcut yeterli).
2. **marka hikayesi** (mini carry) — `<section class="sutre-story">`:
   - Başlık: "Sutre Nedir?" ya da "Hikâyemiz"
   - 1 kısa paragraf: Sutre markası 2026'da İstanbul'da kuruldu; ipek/pamuk/bambu; el dokumanın maya (yacht + el işçiliği metaforu). 60-90 kelime.
3. **"Öne Çıkan Ürünler"** `<section class="sutre-featured">`:
   - WooCommerce hook ile 2 ürün: İman Nour Şal, Jakarlı Şal — WC_Query 'featured' yor... SADECE WOOCOMMERCE COMMERCIAL GK APIs (wc_get_products / woocommerce_shortcode) KULLAN — çekirdek yok.
   - Ürün kart: Thumbnail placeholder + ad + fiyat (regular ve sale strikethrough). wc-style "loop" hooklarını kullan; custom HTML YAZMA (Woo loop'a file).
   - En kolay: `<?php echo do_shortcode( '[products limit="2" columns="2" visibility="featured"]' ); ?>` — bu WooCommerce resmi short-code; iş mantığı core-plugin değil bu (herkese açık hook'dur).
4. **"Neden Sutre?"** `<section class="sutre-values">` — 3 sütun (mobil stack):
   - 🌿 / İkon metinsiz de olur: "Doğal İpek" → açıklama 1-2 cümle
   - ✋ Ejder el işçiliği..." El Dokuması"
   - ***Sunucupon iade*** "14 gün cayma hakkı" (legal_review_required işaretli — metin ona göre placeholder)
   → Metinler DRAFT; her başlığa kısa açıklama; hukuki YASAK (§6.1: iade/cayma garantisi TAKDIR EDİLEZ) — iade metnini "iade koşulları için gizlilik ve mesafeli satış sayfalarına bakınız" diye geç.
5. **Instagram/WhatsApp mesajı** — ürün check-out testlerinin后续 KAPANMIŞ durumda; pazarlama İZNİ HİÇ yazılmaz (anayasa §6.2/§10.1) — mail listesi formu YOK.

## Tasarım kuralları
- Bütün yeni section Bone zemin üstünde; `--sutre-*` custom property'leri kullan (hardcode hex YASAK yeni kodda)
- Serif H2'ler Cormorant Garamond; sans Jost; **44px dokunma hedefleri**
- `prefers-reduced-motion` destekçisi; hover animasyonları 200ms max
- Mobile-first; desktop ≥782px grid
- A11y: her section'da aria-label→Türkçe; focus-visible 2px Silk outline; kontrast AA.
- WCAG 2.2 AA hedef — alt-text için placeholder Metin yok (görsel placeholder'lar alt="").

## Kod yerleşimi
- Section'lar "page-templates/home.php" içine klasik HTML yazılır + `parts/override.css`e yeni CSS (orada sutre- block custom property'ler var, mevcut).
- APCu cache风险 düşük — Yalnızca php dosyaları; her edit mtime otomatik cache-safe version (functions.php'de asset_version zaten).

## Acceptance criteria (CANLI kanıt, sahibin elle):
- / sayfada hero + hikaye + featured ürünler + values + collection bölümü ayrımlı görünüyor
- Mobil 375px'de x-overflow yok; kartlar dikey akışta tek kolon
- "Koleksiyonu Keşfet" → /shop/ çalışıyor; "Tümünü Gör" → shop ✓
- HUD: LSCache purge atte: 0 hata (WP debug boş — PHP fatal yok)
- Sahibin Playwriter kontrolünden PASS.

## Out-of-scope
- PayTR / gizlilik / pazarlama formu YOK
- Real footer link doc sayfaları YOK
- public_html (spokenlab) YASAK
- Analitik/İYS/cookie YOK
- Secret ekleme yok

## Deliverable
- Düzenlenen dosyalar + her commit SHA + push kanıtı
- Rapor: `/opt/data/workspace/proje/dispatch/out/out-23-homepage-redesign.md` (§16 format; 200-350 kelime)
- Sahibin elle adım çıktısı: `docs/operations/p23-owner-manual-steps.md`
