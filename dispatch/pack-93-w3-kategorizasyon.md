# PACK-93 (W3) — İçerik kategorizasyonu (Desktop kökü + SUTRE içi)

## 0. NOT — YÖNTEM (kritik)
Kaynak ve hedef **aynı disk biriminde** (`~/Desktop`) → `mv` **anlık rename**'dir: kopyalama yok, yer kaybı yok, içerik değişmez. Bu yüzden SHA yeniden hesaplamak yerine:
- Her taşıma için **geri alma komutu** rapora yazılır (tek satırlık `mv` geri).
- Taşıma sonrası **sayım/boyut** doğrulaması yapılır (`find | wc -l`, `du -sh`).
- Hiçbir dosya **silinmez**, yeniden adlandırılmaz (kendi hedef adı dışında).
- Taşıma **küçük partiler hâlinde** yapılır; her partiden sonra sayım karşılaştırılır.

## 1. TAŞIMA TABLOSU (kaynak → hedef)

### A) Desktop kökü → kategoriler
| Kaynak | Hedef |
|:--|:--|
| `~/Desktop/sutre_eski_dosyalar/` (9.9M, 10 dosya) | `~/Desktop/SUTRE/06-arsiv/eski-dosyalar/` |
| `~/Desktop/Referanslar/` (19M, 3 jpg) | `~/Desktop/SUTRE/02-icerik/Referanslar/` |
| `~/Desktop/p27-*.png`, `p28-*.png`, `p29-*.png` (8 dosya, 8.3M) | `~/Desktop/SUTRE/05-raporlar/ekran-goruntuleri/` |
| `~/Desktop/{ilk,ikinci,üçüncü,dördüncü,beşinci}_promptun_çıktısı.txt` (5 dosya) | `~/Desktop/SUTRE/05-raporlar/prompt-ciktilari/` |
| `~/Desktop/ön_bilgilendirme_formu.txt`, `websitesi_kullanım_koşulları.txt`, `TİCARİ ELEKTRONİK İLETİ AÇIK RIZA METNİ.txt` | `~/Desktop/SUTRE/00-proje/yasal-metinler/` |
| `~/Downloads/sutrescarfs.rar` (8.6M) | `~/Desktop/SUTRE/06-arsiv/` |

### B) `~/` (home kökü) ekran görüntüleri
- `~/` kökündeki SUTRE ilgili **13 png** (`home-sutre*.png`, `checkout-sutre*.png`, `shop-sutre*.png`, `home-full*.png`, `home-s7*.png`, `nocache-check*.png`, `frontcheck*.png`, `gitvc*.png`, `site-editor*.png`, `reading-settings*.png`, `page-after-typing*.png`) → `~/Desktop/SUTRE/05-raporlar/ekran-goruntuleri/`
- Önce `ls ~/*.png` ile listeyi **rapora yaz**; başka projelere ait png'ler (örn. geleceginbilimi, salihsungur.site) **TAŞINMAZ** — yalnız SUTRE izi taşıyanlar.

### C) `~/Desktop/SUTRE/` içi yeniden kategorizasyon
| Kaynak | Hedef |
|:--|:--|
| `SUTRE/Logo ve Marka/` (16M) | `SUTRE/02-icerik/Logo ve Marka/` |
| `SUTRE/Site Görselleri/` (22M) | `SUTRE/02-icerik/Site Görselleri/` |
| `SUTRE/Ürün Fotoğrafları/Jakarlı/` (334M) | `SUTRE/02-icerik/Ürün Fotoğrafları/Jakarlı/` |
| `SUTRE/Ürün Fotoğrafları/İman Nour/` (renk klasörleri + `_referans/` + `_renk-eslesmesi.json`) | `SUTRE/02-icerik/Ürün Fotoğrafları/İman Nour/` |
| `SUTRE/Ürün Fotoğrafları/İman Nour/_web/` (40M, 15 jpg) | `SUTRE/03-yayin/İman-Nour-web/` |
| `SUTRE/Ürün Fotoğrafları/İman Nour/_deneme/` (338M) | `SUTRE/06-arsiv/İman-Nour-deneme/` |
| `SUTRE/Ürün Fotoğrafları/İman Nour/_arsiv-bos-klasorler/` | `SUTRE/06-arsiv/bos-klasorler/` |
| `SUTRE/Dev Arşiv/` (1M, 8 png) | `SUTRE/06-arsiv/dev-arsiv/` |
| `SUTRE/8-Sozlesme-Promptlari/` (20K) | `SUTRE/00-proje/sozlesme-promptlari/` |

- `SUTRE/Ürün Fotoğrafları/` boş kalırsa **sil** (yalnız boş dizin silme serbest); `mv` sonrası artık boş klasörleri (`_arsiv-bos-klasorler` hedefe taşındıktan sonra kalanlar) raporda listele.
- `_renk-eslesmesi.json` içindeki yollar `_web/` klasörüne işaret ediyorsa **W3 sonrası yolları güncelle** (dosya içeriğini oku, `_web/` yollarını `03-yayin/İman-Nour-web/` olarak yaz) ve değişikliği rapora işle.

## 2. DOĞRULAMA
- Her kategori için: `find <hedef> -type f | wc -l` + `du -sh <hedef>` — kaynak sayımıyla karşılaştır (taşıma öncesi sayımı da rapora yaz).
- Toplam boyut korunumu: `du -sh ~/Desktop/SUTRE` taşıma öncesi/sonrası (≈1.1 GB; mantıklı fark: hiç yok).
- `~/Desktop` kökünde SUTRE ilgili artık **kalmasın**: `ls ~/Desktop | grep -iE 'sutre|p2[789]|promptun|referans'` çıktısını rapora yaz (beklenen: yalnız `SUTRE` klasörü + izin verilen istisnalar).
- **Repo etkilenmedi kanıtı:** `git -C ~/dev/sutre status --porcelain | head -5`

## 3. ROL SINIRI
- Yalnız §1 tablosundaki kaynak/hedefler + `_renk-eslesmesi.json` + rapor.
- **Silme YASAK** (yalnız boş kalan dizinler silinebilir). `04-hesaplar` içeriği bu dalgada taşınmaz (W4). Repo dosyaları taşınmaz.
- Secret içerik okunmaz/yazılmaz. Kabuk kuralı: iç içe `$(...)` + tırnaklı operand yasak.

## 4. RAPOR
- `/Users/salihsungur/dev/sutre/dispatch/out/out-93-w3-kategorizasyon.md` (≤700 kelime):
  1. **Taşıma tablosu:** her satır `kaynak → hedef · dosya sayısı · boyut · GERİ ALMA KOMUTU` (kopyala-yapıştır çalışır biçimde)
  2. Doğrulama çıktıları (sayım/boyut karşılaştırmaları, ham)
  3. `_renk-eslesmesi.json` güncellemesi (önce/sonra satırları)
  4. Boş kalan dizinler + silindiyse kanıtı
  5. `~/Desktop` kök artık taraması
  6. Repo dokunulmadı kanıtı
  7. `YAPILAMADI` listesi