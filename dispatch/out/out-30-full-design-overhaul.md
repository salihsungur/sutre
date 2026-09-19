# OUT-30 — SUTRE TEMA FULL DESIGN OVERHAUL (@designer)

Paket: pack-30-full-design-overhaul · Tarih: 2026-09-19 · Commit: feat 69a8031 (+docs)

## Sonuç
- Tamamlanan hedef: Mevcut tema yamalanmadı; STİL KATMANI TAMAMEN YENİDEN yazıldı.
  Mimari dokunulmaz kaldı (klasik PHP, 6 dosya, tek kaynak).
- Değiştirilen dosyalar:
  - style.css — sıfırdan, 732 satır ek (v3.0.0): quiet luxury sistem
    (palet 8 sabit hex; Cormorant Garamond display + Jost UI; 8px grid;
    köşe 0 / gölge yok; Silk hover underline; hero 82/70vh + tek seferlik
    scale animasyonu; kartlar bordersız White; kategori banner 420/320px
    full-bleed; footer 3 satır Ink; shop banner 280px; scroll-reveal;
    prefers-reduced-motion tam kapatma; WCAG focus-visible Silk 2px,
    touch 44px).
  - footer.php — 3 satır yapı: logo sol + hukuki linkler sağ (flex
    space-between), 32px ara, © satırı whisper 12px.
  - home.php — hero content katmanı, fetchpriority="high", lazy görseller,
    sv-reveal sınıfları, kategori kart inner sarmalayıcı.
  - functions.php — sidebar remove_action, shop sayfa başlığı gizleme,
    shop banner hook (is_shop || is_product_taxonomy), font preconnect,
    IntersectionObserver scroll-reveal (~0.4KB, reduced-motion güvenli).
  - header.php — yalnız alt metin + decoding düzeltmesi.
- Veritabanı/ayar etkisi: YOK (yalnız tema dosyaları; deploy Hermes FTP).

## Doğrulama
- Çalıştırılan testler: CSS süslü parantez dengesi (0), izin dışı hex
  taraması (YOK), border-radius/box-shadow/system-ui taraması (yalnız
  `: 0` / `none` reset yazımları), PHP etiket dengesi. `php -l` yerel
  ortamda YOK (PHP kurulu değil) — statik kontrol yapıldı.
- PASS sonuçları: yukarıdaki statik kontrollerin tamamı.
- FAIL / atlanan testler: canlı görsel denetim — bilinçli olarak
  YAPILMADI (sahip talimatı: "kendin görsel denetim yapma, bana sor").

## Risk ve güvenlik
- Secret veya kişisel veri etkisi: YOK.
- Ödeme/fiyat/stok/fatura etkisi: YOK (checkout/ödeme akışına yalnız
  nötr form/buton stilleri dokundu; akış değişmedi).
- Geri dönüş adımı: `git revert 69a8031` + FTP'de v2 dosyaların geri
  yüklenmesi.
- Bilinen gerilimler (sahip kararına açık):
  1) Regular fiyat whisper 13px, White kart üzerinde < 4.5:1 kontrast
     (spec'in kendi içinde çelişkisi; üstü çizili eski fiyat bilgiyi
     taşımıyor, sale fiyat Ink ile taşıyor — körlük riski düşük ama
     tester ölçsün).
  2) Footer ≤200px hedefi yalnızca desktop'ta tutarlı; mobilde 44px
     touch target + 3 satır yapı ~280px olur (WCAG önceliklendirildi).
  3) Mobil nav 11px'e düşer (3 link tek satır için; spec izni "font
     küçülür").

## Açık kapılar
- NEEDS_OWNER_INPUT: Görsel onay — Playwright/tarayıcı ekran görüntüsü
  (desktop+mobil) sahibin ve Hermes'in incelemesi; hero lockup ve
  banner görselleri sunucudaki gerçek dosyalarla doğrulanmalı.
- OWNER_APPROVAL_REQUIRED: whisper fiyat rengi alternatifi (charcoal)
  kabul/reddi; mobil nav etiket kısaltma ("Ana Sayfa" → "Ana"?).
- LEGAL_REVIEW_REQUIRED: YOK (hukuki link seti korundu).
- Sonraki en küçük güvenli adım: Hermes FTP deploy → ekran görüntüsü →
  sahibin görsel onayı → varsa P31 ince ayar paketi.

## Tester kabul ölçütleri
1. Home mobile 375px: header 60px, 3 nav link tek satır, hero %70vh,
   lockup ≤280px ortalanmış, CTA ortalanmış.
2. Home desktop 1280px: header 72px, nav 13px gap 32px, hover'da Silk
   underline genişler; hero %82vh, lockup ≤480px solda.
3. Ürün kartı: border/gölge yok, görsel 3/4, hover zoom 1.03,
   "Seçenekler" yalnız hover'da slide-up (klavye focus'ta da görünür).
4. Footer: Ink zemin, logo solda 40px, linkler sağda, © alt satırda;
   desktop toplam yükseklik ≤200px.
5. prefers-reduced-motion: animasyon/geçiş yok; scroll-reveal anında
   görünür.
6. Kontrast: CTA/başlık/metin ≥4.5:1 (whisper fiyat istisnası raporda).
