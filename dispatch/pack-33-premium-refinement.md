# PAKET 33 — PREMIUM TASARIM YÜKSELTME (@designer — max effort, tek CSS: style.css)

## DURUM (temiz başlangıç)
- Tek mimari: klasik PHP — header.php/footer.php TEK KAYNAK, tüm sayfalarda aynı (teyitli)
- Tek CSS: style.css (19KB, ver 3.1.0) — override.css YOK
- Görseller sunucuda hazır: assets/img/logo/ (transparan) + assets/img/site/
- Sahibin standardı: quiet luxury — The Row / Loro Piana hissi. Gölgeler, gradient'ler,
  border-radius YASAK (hero overlay hariç). Keskin köşeler. Bol beyaz alan.

## İLK YANIT KURALI
İlk yanıt zorunlu tool çağrıları: read_file('theme/sutre-child-v2/style.css') + git status -sb

## OKUMA
1. theme/sutre-child-v2/style.css (mevcut — TEMELİ KORU, üzerine geliştir)
2. theme/sutre-child-v2/header.php + footer.php
3. theme/sutre-child-v2/page-templates/home.php
4. /opt/data/design-system.md (marka kit)

## GÖREVLER — mevcut tasarımı premium seviyeye taşıyan İNCE dokunuşlar:

### P1 — TİPOGRAFİ ZENGİNLİĞİ
- Hero başlık varsa: Cormorant italic varyant düşünülmeli (ama lockup görsel kullanıyor — dokunma)
- Section başlıkları: Cormorant 500, harf sonrası ince Silk çizgi (60px, 1px) — mevcut varsa rafine et
- Body metin: line-height 1.75, letter-spacing .01em (okunabilirlik premium'u)
- Nav linkler: hover'da color geçişi 250ms + alt çizgi animasyonu (scaleX 0→1)

### P2 — HERO RAFİNESİ
- Hero overlay gradient'i yumuşat: bone %95 → %0 (45° değil, düz soldan sağa)
- Lockup görseline hafif drop-in animasyon (opacity 0→1 + translateY -10px→0, 800ms ease-out)
- CTA buton: hover'da background Silk + color Bone geçişi 350ms cubic-bezier(.4,0,.2,1)
- Scroll'da hero parallax (background-attachment: fixed — desktop only, mobil sabit)

### P3 — ÜRÜN KARTLARI RAFİNESİ
- Kart hover: görsel zoom 1.03 (600ms cubic-bezier) + ürün adı altında ince Silk çizgi belirme
- Fiyat hizalama: del (üstü çizili) solda küçük, ins sağda büyük — flex row
- "SEÇENEKLER" butonu: desktop hover'da bottom'dan slide-up (translateY 100%→0, 350ms)
- Kartlar arası nefes: gap 32px desktop

### P4 — SCROLL-REVEAL (mevcut IntersectionObserver zaten functions.php'de)
- .sv-reveal sınıflı tüm section'lara: opacity 0→1, translateY 24px→0, 700ms
- stagger: ürün kartlarında sıralı gecikme (0ms/100ms/200ms)

### P5 — FOOTER RAFİNESİ
- Logo ile linkler arasında ince ayraç çizgisi (rgba(245,242,236,.12), tam genişlik)
- Link hover: Silk renk 250ms
- Copyright: letter-spacing .08em, 11px

### P6 — SAYFA GEÇİŞLERİ
- Tüm sayfalarda ilk yüklemede body fade-in (opacity 0→1, 400ms) — preload flash engelleme
  (CSS animasyon, JS yok)

### P7 — SHOP SAYFASI RAFİNESİ
- Banner'daki "Koleksiyon" başlığı: Cormorant 400 italic, 42px, beyaz + text-shadow yok
- Ürün grid üstü sıralama dropdown: Jost, borderless, alt çizgi seçici

### KISITLAR
- Tek CSS: style.css — başka dosya OLUŞTURMA
- Mevcut class isimleri KORUNUR (sv-*)
- !important KULLANMA (mevcut sayılabilir)
- mobil-first, 782px breakpoint
- prefers-reduced-motion destekle
- php/JS: sadece mevcut IntersectionObserver korunur, yeni JS EKLEME

## ÇIKTI
- commit + push (feat + docs)
- Rapor: dispatch/out/out-33-premium-refinement.md — SADECE değişiklik listesi,
  'güzel/başarılı' İFADE YASAK (görsel onay sahibin)
- DEPLOY YOK
- Rollback: git revert

## KABUL (sahibin görsel onayı sonrası)
- Tüm sayfalarda tutarlı premium his
- Mobil 375px taşma yok
- Animasyonlar rahatsız etmiyor
- Kontrast AA korunur
