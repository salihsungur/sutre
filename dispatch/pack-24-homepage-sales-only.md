# PAKET 24 — ANA SAYFA %100 SATIŞ ODAKLI REFACTOR (@coder — neredeyse P23'i KISMİ GERİ AL)

## KONTEKS (sahibin 2026-09-17 son kararları)
1. Bu bir satış mağazası — ana sayfada SADECE ürünler ve satışla ilgili içerik.
2. "Hikâyemiz" bölümü ana sayfadan KALDIRILSIN (ilerde /biz-kimiz sayfası taşınacak).
3. "Neden Sutre?" bölümü ANA SAYFADAN TAMAMEN KALDIRILSIN — sözlü ikna metni ucuz görünüyor; ürün açıklamalarına ileride EKLER (ana sayfada asla).
4. Koleksiyon kartları: PREMIUM görünüm korunder; sözlü-satıcı mikro-CTA yazılması YASAK (promosyon dil İSTENMİYOR).
5. Header/footer, hero CTA — P23'te kalan hiçbir şey bozulmayacak (footer fix zaten PUSH'lu).

## İLK YANIT KURALI
İlk yanıt zorunlu tool çağrısı: read_file('/opt/data/design-system.md' ilk 60 satır) + git status -sb.

## Okuma
1. design-system.md (§3-§7)
2. theme/sutre-child/page-templates/home.php (mevcut — P23'te yazıldı; D EXPERT)
3. parts/override.css (P23 CSS 205 satır; silinen bölümle eşlen)
4. dispatch/out/out-23-homepage-redesign.md (P22 referans)

## GÖREV — home.php satır bazlı söğülecek:
### KALDIRILACAK (hepsi)
- <section class="sutre-story"> — bütün blok
- <section class="sutre-values"> — bütün blok ("Neden Sutre?" 3 sütun)
- Hero tagline altındaki ek satır varsa — yalnız hero'da "Sutre + tagline + CTA" kalır

### KALSIN
- hero (Wordmark + tagline + Koleksiyonu Keşfet CTA)
- <section class="sutre-collection"> (koleksiyon kartları — İpek/Pamuk/Bambu; premium; düzenleme yok)
- <section class="sutre-featured"> — "Öne Çıkan Ürünler" → başlığını "%100 gram oranı, NATURAL ipek, el dokuması" gibi satıcı dili YOK; sade "Koleksiyon" başlığına sabitle
  → `[products limit="2" columns="2" visibility="featured"]` hook'u ÇALIŞMASINA DEVAM EDER —
  Shuttle Managed: WooCommerce Products shortcode resmi hook.
  Ayrıca: "[recent_products limit=2]" veya "[sale_products limit=2]" option dili ANAYASA
  kurallarına uygundur — bu premium "Opsiyon".
- Paragraf metn 1 satır cümle (Türkçe, kısa ve premium): 
  "Sutre şal koleksiyonu — el dokuması ipek, pamuk ve bambu şallar."
  düşük satıcı; slogan değil, sadece seri tanımları.

### HEADER/FOOTER — DOKUNMAYACAKSIN
- block-template-parts/* + parts/override.css — sadece sutre-values /
  sutre-story CSS bloklarını SIL (override dalamak), sutre-collection CSS paylaşıldığı yerden
  kalan bölüm de İYİLEŞTİRE (kart stil gücü mevcut).

## Kanal kuralları (kesin)
- Ana sayfa yapısı %100 satış; hiçbir sosyal/moral/prosed metinden geçmiyor.
- Yalnız public WooCommerce `[products]` shortcode hook — core plugin/gizli core yok.
- Mobil-first (§9): 44px dokunma, 375px'te tek kolon featured grid
- exec local PHP olmadığından `php -l` sahibin cPanel adımına; dokümanta.
- Sadeliğin yegane kaidesi: anayasa §3.1, §10.1, §6.2, §6.1'e uygun.

## Deliverable
- home.php kısmen kırpılmış, override.css'ten sutre-story/values blokları silinir; collection korunur
- Commit + push: rapor `dispatch/out/out-24-homepage-sales-only.md` (§16) — 150-250 kelime
- Sahibin elle adım: `docs/operations/p24-owner-manual-steps.md` (pull + purge + görüntü)
- Rollback: git revert yeni SHA

## Kanıt gereksinimi
- home.php son hali: yalnız sutre-hero/sutre-collection/sutre-featured section'lar (+hero CTA)
- `grep -c "sutre-story\|sutre-values" home.php` → 0 dönen kanıt
- Push: 2 SHA (feat + docs) kanıtla
