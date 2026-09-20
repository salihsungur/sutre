# OUT-33 — PREMIUM TASARIM YÜKSELTME (@designer)

Paket: pack-33-premium-refinement · Tarih: 2026-09-20 · Commit: feat + docs (aşağıda hash)

## Sonuç
- Tamamlanan hedef: Mevcut v3.1 tasarımı P1–P7 ince dokunuşlarıyla rafine etme;
  temel korunarak yalnızca tek CSS (style.css) + functions.php (2 satır) değişti.
- Değişiklik listesi:

  P1 — Tipografi:
  - body: line-height 1.7 → 1.75; letter-spacing .01em eklendi
  - nav link: transition color 250ms eklendi (öncesi yalnız global `a` .3s)
  - nav alt çizgi: width 0→100% animasyonu → transform scaleX(0→1) +
    transform-origin left center, 250ms
  - section başlığı: Cormorant 500 sabitlendi; Silk alt çizgi 40px → 60px (1px)
  - hero başlığı yok (lockup görseli) — dokunulmadı

  P2 — Hero:
  - overlay: 3 durak (.94 / .55@34% / 0@62%) → 2 durak, Bone .95 → 0, düz
    soldan sağa (tek izinli gradient korundu)
  - lockup: drop-in animasyonu (opacity 0→1 + translateY -10px→0, 800ms
    ease-out, both)
  - CTA hover: .3s ease → 350ms cubic-bezier(.4,0,.2,1) (yeni --ease-io token'ı)
  - parallax: hero <img> mimarisinde background-attachment: fixed
    uygulanamaz; JS'siz çözüm scroll-driven CSS: desktop (≥782px)
    @supports (animation-timeline: view()) içinde translateY ±3% (exit
    aralığı). Destekleyen: Chrome 115+, Safari 26+, Firefox 159+ (caniuse);
    desteklemeyende 2s giriş animasyonu kalır, hero statik. Mobilde sabit.

  P3 — Ürün kartları:
  - kart hover zoom 1.03 mevcuttu — korundu
  - ürün adı: ::after 24×1px Silk çizgi, hover/focus-within'de scaleX 0→1
    (400ms); title padding-bottom 4 → 12px
  - fiyat: .price flex row (space-between + baseline) — del solda 13px, ins
    sağda 15px
  - "Seçenekler" slide-up: .3s → 350ms ease-io (desktop, mevcut konum korunur)
  - desktop grid gap 28 → 32px

  P4 — Scroll-reveal:
  - .sv-reveal: 600ms/20px → 700ms/translateY 24px
  - stagger: kartlarda nth-child(3n+2) 100ms, nth-child(3n+3) 200ms
    (satır başına yinelenir; JS'e dokunulmadı)
  - reduced-motion bloğuna stagger güvence satırı eklendi

  P5 — Footer:
  - ayraç: rgba(245,242,236,.12) — mobilde .sv-footer__legal border-top +
    padding-top 24px (logo–link arası); desktop'ta .sv-footer__top
    border-bottom (tam genişlik), mobil kural geri alınır
  - link hover: transition color 250ms
  - copyright: 12 → 11px, .04 → .08em (gerçek sınıf sv-footer__copy)

  P6 — Sayfa geçişi:
  - body fade-in: @keyframes sv-page-in (opacity 0→1, 400ms, both) — CSS-only,
    JS yok; prefers-reduced-motion'da mevcut global kapatma kapsıyor

  P7 — Shop:
  - banner "Koleksiyon": font-style italic (Cormorant 400), 36 → 42px, beyaz;
    text-shadow yok
  - font URL'sine Cormorant Garamond ital ekseni (1,400) eklendi — aksi halde
    tarayıcı sahte italic üretir
  - sıralama select: kutu çerçeve → borderless + alt çizgi (rgba .25 → hover
    ink), appearance:none + inline SVG chevron (Jost 13px korunur)

  Altyapı: --ease-io token; SUTRE_VERSION 3.1.0 → 3.2.0 (cache kır);
  style.css header Version 3.0.0 → 3.2.0.
  Değişmeyenler: header.php, footer.php, home.php, IntersectionObserver
  (functions.php'te yalnızca font URL + version satırı), tüm sv-* sınıf
  adları, !important sayısı 4 (yeni eklenmedi).
- Değiştirilen dosyalar:
  - theme/sutre-child-v2/style.css
  - theme/sutre-child-v2/functions.php (2 satır)
  - tests/test_p33_premium_refinement.py (yeni — 42 kontrol)
  - dispatch/out/out-33-premium-refinement.md (bu rapor)
- Veritabanı/ayar etkisi: YOK (yalnız tema dosyaları; DEPLOY YAPILMADI).

## Doğrulama
- Çalıştırılan testler:
  - tests/test_p33_premium_refinement.py (yeni, zero-dep, statik)
  - tests/test_p31_classic_templates.py (regresyon)
  - kontrast hesabı (WCAG 2.x göreli luminans, aritmetik araçla)
- PASS sonuçları:
  - P33: 42/42 PASS (kısıtlar K1–K3, P1–P7, mobil M1–M3, versiyon V1–V2)
  - P31 regresyon: 21/21 PASS
  - süslü parantez dengesi 0; tek 782px media bloğu
  - Kontrast: copyright whisper/footer 9.14:1, footer link 13.16:1, hover
    silk 8.23:1, banner beyaz 16.04:1, select ink/bone 15.58:1 — hepsi AA
- FAIL / atlanan testler:
  - bilinen out-30 gerilimi korundu: `del` fiyat whisper #B8B4AC beyaz kart
    üzerinde 2.07:1 (P33 rengini değiştirmedi; sahibin kararı bekleniyor)
  - canlı görsel denetim yapılmadı (sahibin görsel onayına açık)

## Risk ve güvenlik
- Secret veya kişisel veri etkisi: YOK.
- Ödeme/fiyat/stok/fatura etkisi: YOK (fiyat yalnız görsel hizalama;
  Woo şablonları/md5'e dokunulmadı).
- Geri dönüş adımı: git revert (feat + docs commit'leri).
- Ek notlar: parallax desteklemeyen eski tarayıcıda sessizce statik hero'ya
  düşer (@supports kapısı); kart stagger'ı :nth-child tabanlıdır — Woo
  grid'e başka li eklenirse gecikme desenine yansır (görsel, işlevsel değil).

## Açık kapılar
- NEEDS_OWNER_INPUT: sahibin görsel onayı (desktop + 375px mobil; animasyon
  ritmi; parallax destekleyen tarayıcıda denenmeli).
- OWNER_APPROVAL_REQUIRED: `del` fiyat rengi whisper → charcoal alternatifi
  (out-30'tan taşındı; 2.07:1 — AA için karar gerekli).
- LEGAL_REVIEW_REQUIRED: YOK (hukuki link seti ve yapı korunmuş).
- Sonraki en küçük güvenli adım: Hermes deploy → ekran görüntüsü (desktop +
  mobil) → sahibin onayı → varsa nokta düzeltme paketi.
