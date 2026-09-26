# PACK-96 — PayTR logosu (300×100 PNG) + Instagram kare logo doğrulaması

## 0. İSTEK (sahip, birebir)
- *"paytr ye logo yüklemeliyim 'Logo boyutu en fazla 300px X 100px ölçülerinde, PNG formatında olmalıdır.' bunun için hangi fotoğrafı kullanıcam bunuda seç/hazırla"*
- Ayrıca: Instagram profil fotoğrafı olarak `02-icerik/Logo ve Marka/sutre-logo-square.png` seçildi → **daire kırpmasında klipsiz kaldığını kanıtla** (Instagram profil fotoğrafını daireye kırpar).

## 1. MARKA KURALLARI (bağlayıcı — AGENTS.md §4)
- **Yalnız `SUTRE` wordmark** — "SCARFS", slogan, alt yazı, açıklama **YASAK**.
- Palet: Bone `#F5F2EC` · Ink `#1A1A1A` · Silk `#C9A66B` · Marine `#1B3A4B` · Whisper `#B8B4AC`.
- Tipografi: Cormorant Garamond (logo/H1). Oran **bozulmaz** (stretch yok).

## 2. ÜRETİLECEKLER
| # | Dosya | Şart |
|:--|:--|:--|
| 1 | `~/Desktop/SUTRE/03-yayin/paytr-logo-300x100.png` | **Tam 300×100 px**, PNG, zemin **Bone `#F5F2EC`** (düz), wordmark **Ink `#1A1A1A`**, yatay+dikey ortalanmış, kenar boşluğu ≤10 px bandında |
| 2 | `~/Desktop/SUTRE/03-yayin/paytr-logo-300x100-seffaf.png` | **Tam 300×100 px**, PNG, **saydam zemin**, wordmark Ink (alternatif; PayTR koyu panelde kullanırsa diye) |
| 3 | `~/Desktop/SUTRE/05-raporlar/paytr-logo-preview.png` | Kontrol sayfası: iki varyantı **açık zemin + koyu zemin** üzerinde yan yana göster (küçük önizleme) |
| 4 | `~/Desktop/SUTRE/05-raporlar/instagram-kare-daire-onizleme.png` | `sutre-logo-square.png`'in **daire kırpma simülasyonu** (110 px çap) — wordmark klipsiz mi |

- **Kaynak dosya:** `~/Desktop/SUTRE/02-icerik/Logo ve Marka/` içinden **temiz wordmark** olanı seç. Adaylar: `sutre-logo-header-trans.png` (3504×2336, saydam kanvas), `sutre-logo-header.png` (2267×380), `sutre-hero-lockup.png` (2111×550, ≈3.8:1), `sutre-logo-square.png` (2880×2880). Kanvastaki boş alanı **kırp** (trim), wordmark'ı bozmadan 300×100'e **sığdır** (fit; ölçek korunur, kırpma/germe yok).
- Araç: önce `sips` dene; kırpma/kompozit için `python3 -c "import PIL"` veya `magick` varsa kullan. Yoksa `sips --cropToHeightWidth` + `--padToHeightWidth` zinciriyle yap. **Kurulum/indirme YOK.**

## 3. DOĞRULAMA (ham çıktı rapora)
1. `sips -g pixelWidth -g pixelHeight -g format <dosya>` → **300 × 100 · png** (üç dosya için)
2. Dosya boyutları (`ls -lh`) — PayTR limitine ek sınır yok, ama **≤200 KB** hedefle (değilse raporla, küçültme yöntemi yaz)
3. `paytr-logo-300x100.png` üzerinde **metin okunabilirliği**: wordmark yüksekliği ≥ 34 px olmalı (300×100'de okunurluk eşiği) — ölçüp rapora yaz; sağlanmıyorsa **hangi ölçüye ulaşıldığını** yaz (zorlama yapma, oranı bozma).
4. `instagram-kare-daire-onizleme.png` üretilirken: daireye giren alanda wordmark'ın **kırpılıp kırpılmadığını** ölç — kare genişliği 2880, daire çapı 2880; wordmark'ın gerçek piksel genişliği `W` ise `W ≤ 2880` ve dikey merkezde ise klipsizdir. Sonucu **sayıyla** yaz.
5. Görsel kanıt: ürettiğin 4 dosyanın yolunu raporda listele (chat'e eklenmek üzere).

## 4. ROL SINIRI
- Yalnız `03-yayin/` + `05-raporlar/` altına **yeni dosya** üret; mevcut marka dosyalarını **değiştirme/taşıma**.
- Repo'ya commit YOK (bu iş repo dışı varlık üretimi). FTP/canlı site YASAK. Provider/model override YASAK.
- Kabuk kuralı: iç içe `$(...)` + tırnaklı operand içeren tek satır komut YAZMA (koşulsuz bloklanır).

## 5. RAPOR
- `/Users/salihsungur/Desktop/SUTRE/01-repo/dispatch/out/out-96-paytr-logo.md` (≤400 kelime): (1) seçilen kaynak dosya + neden, (2) üretim yöntemi (komut/tool), (3) §3'ün ham çıktıları (ölçüler, boyutlar, okunurluk ölçüsü), (4) Instagram daire kontrolü sayısal hüküm, (5) dosya yolları, (6) `YAPILAMADI` listesi. Yargı cümlesi YASAK — ölçüm yaz.