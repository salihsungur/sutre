# PAKET 26 — ANA SAYFA GÖRSEL BUGLARIN DÜZELTİLMESİ (@coder — max effort)

## SAHİBİN KARARI (2026-09-17):
1. KATEGORİLER bölümü: yalnız **"Giyim" kartı** (tek kart; İpek/Pamuk/Bambu KALDIRILIR).
   Kart "Giyim" yazsın, tıklanınca /shop/ (Giyim kategorisinin ürünleri) gitsin.
2. Görsel/CSS hataları düzelt —_PLAYWRITER screenshot analizi kanıtıyla verilen defekt listesi_:

## Doğrulanmış defektler (sahibin screenshot analizinden — kanıt):
[D1] ÜRÜNLER grid dağınık — kartlar farklı satır/konum/offset; eşit kolon yok
[D2] "SEÇENEKLER" butonu kart dışına taşıyor (box-sizing/width hatası)
[D3] "İndirim / Sale" rozeti kartın dışına fırlıyor (overflow hidden yok)
[D4] Kırık ürün görselleri (src boş/hatalı) — WooCommerce placeholder görsel yolunu doğru ala
[D5] Hero → ÜRÜNLER arası dev boşluk; section'lar arası tutarsız aralık (margin/padding)
[D6] ÜRÜNLER başlığı ile grid hizasız; "TÜMÜNÜ GÖR" linkin yeri bozuk (sağ üstte faca)
[D7] Başlık divider çizgileri tutarsız; boşta duran altın çizgi var
[D8] hero tagline kontrastı düşük (WCAG AA ihlali)
[D9] Footer © metni kontrastı düşük; footer'da gereksiz boşluk
[D10] Nav admin-bar çakışması (wp-admin bar görülen kısıt — public'te yok, dokunma YOK)

## İLK YANIT KURALI
İlk yanıt zorunlu tool: read_file('design-system.md' 60 satır) + git status -sb.

## OKUMA
1. design-system.md
2. theme/sutre-child/page-templates/home.php (P25 hâli)
3. theme/sutre-child/parts/override.css (P25 hâli)

## GÖREV — yalnız home.php + override.css:

### A. Grid düzeni (D1, D6):
- `.sutre-featured` grid container: `display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; align-items: stretch;`
- Mobil 375px: 1 kolon; 782px+: 2 kolon
- İçinde "TÜMÜNÜ GÖR →" lokasyonu: başlık satırının SAĞINDA (flex header) — grid altına değil.
- Başlık + kırmızı divider hizası: `<h2> + divider` aynı flex row'da.

### B. Kart taşması (D2, D3):
- `.woocommerce ul.products li.product` benzeri kart konteyneri: `overflow: hidden`
- Sale badge: `position:absolute; top:12px; left:12px; right:auto`; kart radius'a kilit
- Buton: `box-sizing: border-box; width: 100%; max-width: 100%`; padding-fixed

### C. Kırık görsel (D4):
- Ürün就是你 WooCommerce placeholder - resmi URL woo core'dan: 
  `plugins_url('woocommerce/assets/images/placeholder.png')` yolu nasıl doğru çalışıyorsa yalnız kullan.
- KOD gerekçesi: `[products]` shortcode thumb render'ı bozuk ise loop-level
  `woocommerce_placeholder_img()` hook'u ile düzelt; kırmızıtsız temp sonu FIND s.

### D. Spacing (D5, D6):
- section dikey margin: 48px mobil / 64px desktop standard; hero-bottom: 48px
- Divider'ı yalnız section başlığının altında: `border-bottom: 2px solid Silk` + width 64px

### E. Kontrast (D8, D9):
- hero tagline: Whisper → **Ink #1A1A1A** (AA ≥ 4.5:1)
- footer ©: rgba(255,255,255,.85)+ → tam beyaz #FFFFFF ya da .95

### F. KATEGORİLER tek kart "Giyim" (sahibin kararı):
- Yalnız: **Giyim** (01 numaralı büyük kart) — link: `/urun-kategori/giyim/` ya da `/shop/`
- İpek/Pamuk/Bambu kartlarını HEPSİ SİL
- Grid layout'ta tek kart genişliği tasarımla uyumlu olsun (örn. tam genişlik hero-benzeri panel)
- "İpek/Pamuk/Bambu" dilini SADECE bir alt NOT satırında (minik "İpek · Pamuk · Bambu kumaşları") tutabilir
  — ama kart bağımsız no-обIцly.

## KOD KURALLARI
- Yalnız home.php + override.css — diğer dosyalara DOKUNMAZ
- CSS: --sutre-* custom properties, 44px dokunma, reduced-motion; hardcoded hex §YÖN bu SUTRE palette
- WooCommerce public shortcode/hooks kanıtında dürüst ol; private stub YOK

## Deliverable
- commit + push (feat + docs SHA'ları). FTP DEPLOY HERMES YAPAR — bot PUSH edece sakıngalık YOK
- Rapor: dispatch/out/out-25b-homepage-css-fixes.md (§16, 150-250 kelime)
- Rollback: git revert

## Kabul kanıtı (Hermes FTP deploy + curl)
- kartlar tek satıra hizalı; badge/buton kart içinde (screenshot kontrol)
- Yeni kategori bölümü: yalnız 1 Giyim kartı
- Dil AA kontrastı; overflow 0; PHP warning 0
