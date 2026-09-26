# PACK-97 — PayTR logosu ince ayar (hızlı, ≤5 dk)

## 0. GİRDİ
pack-96 çıktıları ölçüldü ve görsel önizleme incelendi. İki eksik:
1. **Kenar payı çok dar:** Bone varyantında wordmark `295×46`, `x=2` → yatayda **2 px** pay var. PayTR'nin kendi konteynerinde sıkışık görünür; ~12 px pay daha güvenli ve dengeli.
2. **Saydam varyant koyu zeminde kayboluyor:** Saydam sürümün yazısı **Ink** (`#1A1A1A`) → Marine/koyu zeminde okunmuyor. Koyu zeminler için **Bone `#F5F2EC` yazılı** saydam sürüm gerekiyor.

## 1. ÜRET (pack-96'daki yöntemi kullan: Pillow 11.3.0, LANCZOS, trim'li master)
| Dosya | Şart |
|:--|:--|
| `~/Desktop/SUTRE/03-yayin/paytr-logo-300x100.png` | **ÜZERİNE YAZ**: 300×100, zemin Bone `#F5F2EC`, wordmark Ink, **yatay pay ~12 px** (wordmark ≈276×43), dikey ortalı, oran korunur |
| `~/Desktop/SUTRE/03-yayin/paytr-logo-300x100-seffaf.png` | **ÜZERİNE YAZ**: 300×100, saydam zemin, wordmark **Ink** (açık zeminler için) — pay aynı ~12 px |
| `~/Desktop/SUTRE/03-yayin/paytr-logo-300x100-seffaf-beyaz-yazi.png`  | **YENİ**: 300×100, saydam zemin, wordmark **Bone `#F5F2EC`** (koyu zeminler için) |
| `~/Desktop/SUTRE/05-raporlar/paytr-logo-preview.png` | **ÜZERİNE YAZ**: 4 varyantı Bone + Marine zeminlerde yan yana; her birinin altında dosya adı |

Master kaynak: pack-96'da kullandığın trim'li wordmark katmanı (yeniden trim etmen gerekirse aynı yöntem).

## 2. DOĞRULA (ham çıktı rapora)
- Dört dosya için `sips -g pixelWidth -g pixelHeight -g format` → **300 × 100 · png**
- Yerleşim ölçüsü: wordmark bbox (x0,y0,x1,y1) + yatay/dikey pay → **pay ≥10 px** olmalı; wordmark ink yüksekliği **≥34 px** (okunurluk eşiği) — 43 px bekleniyor
- Oran sapması < %0,1
- `paytr-logo-300x100-seffaf-beyaz-yazi.png`: yazı pikselleri Bone (245,242,236) ±2 · alfa zemini 0
- `ls -lh` dosya boyutları

## 3. ROL SINIRI
- Yalnız `03-yayin/paytr-logo-*` + `05-raporlar/paytr-logo-preview.png`; başka dosyaya dokunma. Commit YOK. FTP YASAK.
- Kabuk kuralı: iç içe `$(...)` + tırnaklı operand içeren tek satır komut YAZMA.

## 4. RAPOR
- `/Users/salihsungur/Desktop/SUTRE/01-repo/dispatch/out/out-97-paytr-logo-ince-ayar.md` (≤300 kelime): yapılan değişiklik + §2 ölçümleri (ham) + dosya yolları + `YAPILAMADI`.