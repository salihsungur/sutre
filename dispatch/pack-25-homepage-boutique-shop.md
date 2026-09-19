# PAKET 25 — ANA SAYFA: BUTİK GİYİM MARKETİ SATIŞ SAYFASI (@coder — max effort)

## SAHİBİN KARARI (2026-09-17, bağlayıcı)
"Koleksiyon dışında ana sayfada bir şey yok — gerçek bir satış sayfası yap; butik giyim marketi gibi:
  ana sayfa ÜRÜNLER + KATEGORİLER vb."

## İLK YANIT KURALI
İlk yanıt zorunlu tool çağrısı: read_file('/opt/data/design-system.md') + git status -sb.

## Okuma sırası
1. design-system.md (tam)
2. theme/sutre-child/page-templates/home.php (P24 hâli)
3. theme/sutre-child/parts/override.css (P24 hâli)
4. ADR/ADR-003-magaza-sema.md

## HEDEF — ana sayfa must-tam bir butik e-ticaret açılış sahfesi:

### KALAN VE GÜÇLENDİRİLECEK
- Hero (Sutre wordmark + tagline + CTA) ✓ korunur
- **"Koleksiyon" ürün grid'i** — ana içerik; WOOCommerce [products] (veya wc_get_products loop) ile
  İman Nour Şal + Jakarlı Şal — placeholder görselleri, normal/sale fiyat biçimi TAM WooCommerce çıktısı

### YENİ BÖLÜM ( satışa hizmet eden her biri — YALNIZ ÜRÜN/SATIŞ İÇERİK; moral-story YASAK):
1. **ÜRÜNLER ( Ana Grid)** Hero hemen altında: tüm ürünler tam kart gridde;
   kart: thumbnail (placeholder), ad, renk varyant sayısı, fiyat (regular çizgili + sale bold Silk).
   Mobil: 1 kolon; 782px+: 2 kolon. "Tümünü Gör →" linki /shop/.
2. **KATEGORİLER (Koleksiyon kartları mevcut ama CELSİYET)**: İpek / Pamuk / Bambu kartları —
   kart kalemin ft "Koleksiyon Dışı" ADI YOK; SADECE renk/grand sayı + "Keşfet" mikro-link.
   (Kart tasarım P23'ten korunur; içerik dilini satışa-appropriate kıl.)
3. **SATIŞ BANTI (top strip)**: hero üstü ince bant: "üfend: el dokuması şallarda BUGÜN teslim" gibi
   LÜKS kazıdı değil; NO satış hype. Alternatif: bant SIZ KARAR VER; koyarsa nötr bilgi:
   "Tüm siparişler Saglam paketleme — 24 saatte hazırlanır." (§9 mobil okunur, tek satır.)
   → işletme verisi TAHMİN YASAK (§0.7): "24 saat" gibi rakam_YOK; nötr "Zarif paketleme" YAZMAYIP
   bu bant YERİNE küçük "Marine/Ink" renk şerit olabilir — content MINIMAL.

### YASAK
- "Hikâyemiz" / "Neden Sutre?" geri GELMEYECEK (P24 kararı)
- pazarlama formu/abonelik/social YOK (§6.2/§10.1)
- hafif ya da premium' kaybet: hype-dilin yok; yalnız ürün+info.
- header/footer dokunma (tek kaynak korunur).

## DİL KURALLARI
- %100 Türkçe; "premium" ses: yalnız ürün tanımı + fiyat + kategori info. Hype YOK.

## KOD YERLEŞİMİ
- Hepsi page-templates/home.php + parts/override.css
- CSS: --sutre-* custom properties; mobil-first; 44px dokunma; reduced-motion
- WooCommerce hook'ları public API (`[products]` shortcode / WooCommerce block yorumlar yasak —Paylı kısa kod OK)

## Kabul kriterleri (sahibin elle doğrulaması sonrası PASS)
1. Ana sayfada sıra: hero → ürünler (grid) → kategori kartları (Koleksiyon) → her bölümde net satış odaklı içerik
2. Mobil 375px: tek kolon, x-overflow yok; desktop 2 kolon grid
3. [products] shortcode Fiyat + sale strikethrough düzgün render
4. WP debug fatal/warning: 0; Playwriter + curl kanıtı
5. Dil yalnız satış: hype/ikna bölümü sıfır

## Deliverable
- commit + push kanıtı: feat + docs SHA'ları; cPanel FTP DEPLOY BEN YAPIYOR — bot YAPMAZ; sadece push.
- Rapor: dispatch/out/out-25-homepage-boutique-shop.md (§16; 150-300 kelime)
- Rollback: git revert feat SHA

## Önemli: salih'in elle adımı YOK — bu paket yalnız local repo + push; deploy Hermes FTP auto-tarafından
