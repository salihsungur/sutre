# PAKET 30 — SUTRE TEMA FULL DESIGN OVERHAUL (@designer — SÖZÜ GEÇMEZ, TAMAMEN YENİDEN TASARIM)

## KRİTİK BAĞLAM (okumadan işe başlama)
Kullanıcı son durumu böyle tanımlıyor: "tasarım/design/css'den eser yok, REZALET durumda."
Önceki tüm paketlerin (P20-P28) çıktısı YETERSİZ bulundu. Bu paket MEVCUT TEMAYI TAMAMEN
YENİDEN TASARLAMAK İÇİN — yamamak için DEĞİL.

## MİMARİ (dokunulmaz — sadece stil/tasarım katmanı)
- Klasik PHP tema: header.php, footer.php, index.php, functions.php, page-templates/home.php
- style.css TÜMÜ YENİDEN YAZILACAK
- header.php/footer.php markup'ı İYİLEŞTİRİLEBİLİR ama yapısı korunur (klasik PHP tek kaynak)
- Görseller sunucuda: /assets/img/logo/ + /assets/img/site/ (zaten yüklü)
- Ürün görselleri placeholder (İman Nour fotoğrafları yok) — tasarım placeholder'la bile güzel görünmeli

## OKUMA SIRASI (ilk yanıt ZORUNLU tool çağrıları)
1. read_file('/opt/data/design-system.md') — marka kit TAMAMINI oku
2. read_file('/opt/data/workspace/proje/theme/sutre-child-v2/style.css') — mevcut durumu gör
3. read_file('/opt/data/workspace/proje/theme/sutre-child-v2/header.php')
4. read_file('/opt/data/workspace/proje/theme/sutre-child-v2/footer.php')
5. read_file('/opt/data/workspace/proje/theme/sutre-child-v2/page-templates/home.php')
6. git status -sb

## TASARIM HEDEFİ — "QUIET LUXURY" (sessiz lüks, Vogue tarzı)
Marka: Sutre — el dokuması ipek/pamuk şal, Türkçe pazar, premium fiyat segmenti (449-499 TL).
Referans his: The Row, Loro Piana, Zegna web siteleri — sıcak minimalizm, bol beyaz alan,
ince tipografi, yavaş zarif animasyonlar, fotoğraf odaklı.

### TİPOGRAFİ SİSTEMİ
- Display/başlık: Cormorant Garamond — weight 400-500, letter-spacing geniş (.02em-.06em),
  font-size: h1 clamp(42px, 7vw, 76px) / h2 clamp(28px, 4vw, 44px) / h3 clamp(20px, 3vw, 26px)
- Body/UI: Jost — weight 300-400, font-size 15px-17px, line-height 1.7
- Ürün adı: Jost 400, 16px, letter-spacing .04em
- Fiyat: Jost 300, 15px
- Buton metin: Jost 400, 13px, letter-spacing .18em, UPPERCASE
- Section başlığı + ince alt çizgi (Silk, 40px genişlik, 1px kalınlık)

### RENK SİSTEMİ (sadece bunlar, başka hex YASAK)
- Bone #F5F2EC — ana zemin
- Ivory #FAF8F4 — kart/section alternatif zemin
- Ink #1A1A1A — ana metin
- Charcoal #2D2D2D — ikincil metin
- Silk #C9A66B — vurgu (hover, divider, fiyat vurgusu, CTA border)
- Marine #1B3A4B — koyu section arka planı (footer, banner)
- Whisper #B8B4AC — nötr metin, ayraçlar
- White #FFFFFF — ürün kartı zemini

### SPACING SİSTEMİ (8px grid)
- Section padding: mobil 64px 20px / desktop 96px 40px
- Hero padding: mobil 0 (full-bleed) / desktop 0 (full-bleed)
- Kart gap: mobil 16px / desktop 28px
- Elemanlar arası: 16px / 24px / 40px / 64px (hiçbir zaman 12px değil, 100px değil)

### HEADER (header.php + CSS)
- Sticky değil, statik. Zemin: Bone, border-bottom 1px rgba(26,26,26,.08)
- Logo görseli: height 44px (desktop) / 36px (mobil) — sutre-logo-header.png (transparan)
- Nav: sağda, Jost 400 13px, letter-spacing .16em, UPPERCASE, gap 32px
- Nav hover: altında 1px Silk çizgi animasyonu (width 0→100%, 300ms)
- Header yüksekliği: 72px desktop / 60px mobil — padding ile değil height ile
- Mobil: hamburger MENÜ YOK (3 link sığar, font küçülür)

### HERO (home.php + CSS) — tam ekran premium
- Yükseklik: mobil 70vh / desktop 82vh
- Arka plan: hero-banner-sutre.png, object-fit cover, object-position right center
- Overlay: sol yarısı Bone→transparan gradient (metin okunabilirliği)
- Sol tarafta (desktop): hero-lockup.png görseli max-width 480px
- Altında CTA: border 1px Silk, padding 16px 48px, hover → background Silk + color Bone
- Mobil: lockup ortada, max-width 280px, CTA ortalanmış
- HAFİF animasyon: hero görsel scale(1.05→1) 2s ease-out (sadece sayfa yüklenince, tekrarsız)

### ÜRÜN KARTLARI (WooCommerce override CSS)
- Grid: repeat(3, 1fr) desktop / 1fr mobil (2 ürün olduğu için 2 kolon da düşünülebilir —
  limit=6, 3 kolon, boş hücre görünmemeli)
- Kart: zemin White, border YOK, gölge YOK (quiet luxury = gölgesiz)
- Görsel: aspect-ratio 3/4, object-fit cover, hover'da hafif zoom (scale 1.03, 600ms)
- İndirim rozeti: SİYAH zemin, beyaz metin, font 10px, letter-spacing .1em, padding 4px 10px
- Ürün adı: Jost 400 15px, letter-spacing .04em
- Fiyat: regular → whisper renkte line-through 13px; sale → Ink 15px 500
- "Seçenekler" buton: görünmez, kart hover'da alt kısımdan slide-up (desktop only)
- Mobil: buton hep görünür, full-width

### KATEGORİ KARTI (Giyim)
- Tam genişlik banner: height mobil 320px / desktop 420px
- Görsel: kategori-giyim-kart.png, object-fit cover
- Overlay: linear-gradient(to top, rgba(26,26,26,.75), rgba(26,26,26,.15) 60%, transparent)
- İçerik sol alt köşede: numara (01, Cormorant 48px, Silk), kategori adı (Cormorant 32px, beyaz),
  CTA "Keşfet →" (Jost 13px, letter-spacing .18em, beyaz, hover → Silk)

### FOOTER (footer.php + CSS) — en kritik bölüm, önceki tüm denemeler başarısız
- Zemin: Ink #1A1A1A (koyu siyah)
- YAPI (3 satır, net hiyerarşi):
  1. ÜST SATIR: logo görseli SOLDA (height 40px, transparan sutre-logo-footer.png)
     + hukuki linkler SAĞDA (aynı satırda, flex space-between)
  2. ORTA: boşluk 32px
  3. ALT SATIR: © 2026 Sutre — Tüm hakları saklıdır (tek satır, 12px, whisper renk)
- Padding: 48px 24px (mobil) / 56px 40px (desktop)
- Footer height max 200px — ekranın %25'inden fazla kaplamaz
- Linkler: bone renk, 13px, hover → Silk
- Logo görseli sutre-logo-footer.png TRANSPARAN — zemin görünmez

### SHOP SAYFASI (WooCommerce archive)
- Üst banner: shop-banner-sutre.png, height 280px, background cover, üzerinde
  "Koleksiyon" başlığı Cormorant 36px beyaz + altında Jost 13px "El dokuması şallar" beyaz
- Banner altında 48px boşluk, sonra ürün grid
- Sidebar YOK (WooCommerce sidebar gizle: `add_action('woocommerce_before_main_content', function(){ ... }, 10)` yerine CSS ile gizle veya functions.php remove_action)

### ANIMASYON KURALLARI
- Sadece: hover geçişleri (300ms), scroll-reveal (opacity 0→1, translateY 20px→0, 600ms)
- prefers-reduced-motion: tüm animasyonlar kapat
- Hero scale animasyonu hariç hiçbir yerde loop/sürekli animasyon YOK

### ERIŞILEBİLIRLİK (WCAG 2.2 AA)
- Tüm metin kontrast ≥ 4.5:1
- focus-visible: 2px Silk outline, offset 2px
- touch target ≥ 44px
- alt metinler tüm img'lere

## YASAKLAR
- Gradient banner arka planı YOK (sadece hero overlay)
- Drop-shadow YOK (kartlarda, butonlarda, hiçbir yerde)
- Border-radius YASAK (0px — keskin köşeler, quiet luxury)
- Emoji ikon YOK
- Degrade renk geçişleri YOK (sadece hero overlay gradient)
- Font-family'de system-ui/Inter/Roboto YASAK — sadece Cormorant + Jost

## ÇIKTI
- style.css TAMAMEN YENİDEN (sıfırdan, tek dosya, tüm stiller)
- header.php: nav markup iyileştirme (gerekirse)
- footer.php: 3 satır yapıya göre düzenleme (gerekirse)
- home.php: hero/section markup iyileştirme (gerekirse)
- functions.php: sidebar gizleme + küçük hook'lar (gerekirse)
- commit + push (feat + docs ayrı)
- RAPOR: dispatch/out/out-30-full-design-overhaul.md (§16 format, 200-400 kelime)
  Rapor kesinlikle "başarılı" demeyecek — sadece ne yaptığını listeleyecek,
  görsel onay SAHİBİN (Playwriter ile ben kontrol edeceğim).
- DEPLOY YOK — Hermes FTP ile yapacak.

## ÖNEMLİ UYARI
Sahibin önceki mesajı: "kendin görsel denetim yapma, bana sor" — yani bot kesinlikle
"kaydettim, kontrol ettim, güzel görünüyor" DEMEYECEK. Sadece kod yaz + push + raporla.
Görsel doğrulama Hermes (ben) + sahibin tarayıcı onayı ile olacak.
