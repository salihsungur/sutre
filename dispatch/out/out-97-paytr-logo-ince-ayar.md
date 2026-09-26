# PACK-97 — PayTR logosu ince ayar

2026-09-26 13:58 +03 · Çıktı: `03-yayin/` + `05-raporlar/` (commit YOK, FTP YOK)

## 1. Değişiklik

pack-96 yöntemi: Pillow 11.3.0, trim'li master (`sutre-logo-header-trans.png`, ink bbox (635,978,2873,1327) → 2238×349, oran 6.412607), kademeli yarıya indirme + LANCZOS. Ölçü, yatay pay 10–16 px aralığında en düşük oran sapmasıyla seçildi → **276×43, x=12, y=28** (pack-96: 295×46, x=2). Yeni: saydam zemin + **Bone `#F5F2EC` yazı**.

## 2. Ham ölçümler

sips: üç logo **300 × 100 · png**; önizleme 988 × 374 · png.

Yerleşim: ink bbox **(12,28,288,71)** → 276×43 · yatay pay **12/12** · dikey 28/29 · oran 6.418605 → **sapma %0,09352** (< %0,1). Ink yüksekliği **43 px** (eşik ≥34).

- `…-300x100.png`: zemin (0,0)=(245,242,236)=Bone · ink çekirdeği (26,26,26)
- `…-seffaf.png`: 4 köşe alfa 0 · alfa>200=1085 px, RGB (26,26,26)
- `…-seffaf-beyaz-yazi.png`: alfa bbox (12,28,288,71) · alfa>0=3207 px, RGB min/max (245,242,236)/(245,242,236) → **Bone sapması 0** · alfa>200=1085 · alfa=0=26793 · köşe alfa 0

Kontrast: Ink/Bone 15.58:1 · Ink/Marine 1.45:1 · Bone/Marine 10.72:1.

ls -lh: 6.4K · 4.4K · 4.4K · 45K.

## 3. Önizleme

6 panel (3 dosya × 2 zemin: Bone, Marine), altında dosya adı + not. Marine satırının etiketleri beyaz yazı + beyaz sayfa zemininde görünmezdi → renkli çip + koyu alt yazı; piksel satır taraması (y 351–359, 364–370 dolu) ve görsel okuma ile teyit edildi.

## 4. Dosyalar

1. `/Users/salihsungur/Desktop/SUTRE/03-yayin/paytr-logo-300x100.png`
2. `/Users/salihsungur/Desktop/SUTRE/03-yayin/paytr-logo-300x100-seffaf.png`
3. `/Users/salihsungur/Desktop/SUTRE/03-yayin/paytr-logo-300x100-seffaf-beyaz-yazi.png`
4. `/Users/salihsungur/Desktop/SUTRE/05-raporlar/paytr-logo-preview.png`

Betik: `~/.hermes/profiles/designer/cache/scratch/p97/build_paytr_logo_v2.py`

## 5. YAPILAMADI

- "4 varyant" istendi; dosya 3 → 6 panel (3×2) kuruldu.
- Oran sapması %0,09352: sınırın altında ama yakın; 276×43 en iyi tam sayı seçenek (komşu genişlikler %0,27–0,99).
- PayTR paneline yükleme YAPILMADI (sahip adımı).
- AGENTS.md işlenmedi (paket §3 rol sınırı).