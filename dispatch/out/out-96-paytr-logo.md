# PACK-96 — PayTR logosu (300×100 PNG) + Instagram kare daire kontrolü

2026-09-26 13:36 +03 · Çıktı: `03-yayin/` + `05-raporlar/` (repo commit YOK, FTP YOK)

## 1. Seçilen kaynak

`02-icerik/Logo ve Marka/sutre-logo-header-trans.png` (3504×2336, RGBA).

Ölçülen gerekçe: ink bbox (635,978,2873,1327) → wordmark 2238×349, oran 6.4126; bbox dışında alfa>0 piksel YOK (maks alfa 0); çekirdek harf rengi (alfa>250 & lum<70) (27,27,26) ≈ Ink `#1A1A1A`; kırpılan içerikte yalnız SUTRE harfleri var (ek metin/"SCARFS" yok, teyit).

Diğer adaylar: `sutre-logo-square.png` 2880×2880 bone (wordmark 1891×298); `sutre-logo-header.png` 2267×380 (2234×345); footer çifti (3504×370 / 3504×2336) → harf Bone + koyu gölge (açık zeminde kullanılamaz), oran 7.39–7.59; `sutre-hero-lockup-trans.png` oran 4.008.

## 2. Üretim yöntemi

`/usr/bin/python3` 3.9.6 + Pillow 11.3.0 (numpy yok): ink bbox trim → alfa kapsamalı düz Ink `#1A1A1A` katmanı → kademeli yarıya indirme + LANCZOS → 300×100 tuvale yatay+dikey ortalama → Bone zeminli RGB + saydam RGBA; önizleme ve daire simülasyonu aynı betikte. Betik: `~/.hermes/profiles/designer/cache/scratch/p96/build_paytr_logo.py`. Kurulum yapılmadı; `sips` yalnız doğrulamada.

## 3. Ham doğrulama çıktıları

sips:
- paytr-logo-300x100.png → pixelWidth 300, pixelHeight 100, format png
- paytr-logo-300x100-seffaf.png → pixelWidth 300, pixelHeight 100, format png
- paytr-logo-preview.png → pixelWidth 700, pixelHeight 400, format png
- instagram-kare-daire-onizleme.png → pixelWidth 760, pixelHeight 560, format png

ls -l (hedef ≤200 KB): 6.571 B · 4.491 B · 36.791 B · 128.949 B.

Yerleşim (300×100): wordmark 295×46 x=2 y=27; yatay marj 2/3, dikey 27/27 px; oran 6.4130 (kaynak 6.4126, sapma %0.0068); zemin (0,0) (245,242,236) = Bone `#F5F2EC`; saydam alfa 0; ink bbox (2,27,297,73) ikisinde aynı.

Okunurluk: wordmark ink yüksekliği 46 px (eşik ≥34 px). Bone zemin ink bbox (2,27,297,73); saydam aynı.

Kontrast (WCAG): Ink/Bone 15.58:1 · Ink/Marine 1.45:1.

## 4. Instagram daire kontrolü (sayısal)

`sutre-logo-square.png` 2880×2880; wordmark ink bbox x 508..2398, y 1275..1572 (W=1891, H=298). Kare merkezi (1440.0 ; 1440.0); wordmark merkezi (1453.0 ; 1423.5); sapma (13.0 ; −16.5) px. Köşe yarıçapları 946.5 / 941.3 / 972.1 / 967.1; en uzak 972.1 px; daire R=1440 → pay 467.9 px. 110 px gösterimde yarıçap 37.1 px, R 55 → pay 17.9 px. Daire dışında kalan wordmark pikseli: **0**. W=1891 ≤ 2880.

## 5. Üretilen dosyalar

1. `/Users/salihsungur/Desktop/SUTRE/03-yayin/paytr-logo-300x100.png`
2. `/Users/salihsungur/Desktop/SUTRE/03-yayin/paytr-logo-300x100-seffaf.png`
3. `/Users/salihsungur/Desktop/SUTRE/05-raporlar/paytr-logo-preview.png`
4. `/Users/salihsungur/Desktop/SUTRE/05-raporlar/instagram-kare-daire-onizleme.png`

## 6. YAPILAMADI

- PayTR paneline yükleme YAPILMADI (paket §4) → sahip adımı.
- §3.1 "üç dosya 300×100" varsayımı uyuşmuyor: iki dosya 300×100; önizlemeler 700×400 ve 760×560.
- 300×100'de dikey marj 27 px (6.41:1 oranın 3:1 tuvale sığdırılması).
- Saydam varyantta Ink koyu zeminde 1.45:1; Bone zeminli varyantta 15.58:1.