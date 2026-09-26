# PACK-93 (W3) — İçerik kategorizasyonu (Desktop kökü + SUTRE içi)

Tarih 2026-09-26. Kaynak+hedef aynı birim (`/dev/disk3s5`, `df` ile doğrulandı) → `mv` anlık rename; kopya yok, silme yok. Her taşımada öncesi/sonrası dosya sayısı+byte karşılaştırıldı: 43/43 OK.
Kısaltma bloğu (geri alma komutlarından önce çalıştır): `D=$HOME/Desktop; S=$HOME/Desktop/SUTRE; H=$HOME`

## 0. Ön ölçüm
- `du -sh ~/Desktop/SUTRE` ÖNCE: 1070.6 MB (1.122.582.498 B), 6636 dosya.
- `ls ~/*.png` ÖNCE: 13 dosya, hepsi SUTRE izli (yabancı proje png'si YOK): checkout-sutre, frontcheck, gitvc, home-full, home-s7, home-sutre, home-sutre1, nocache-check, page-after-typing, reading-settings, shop-sutre, site-editor, site-editor2.
- `~/Desktop` kök taraması ÖNCE: 19 satır (Referanslar, SUTRE, 8 png, 5 prompt, 3 yasal metin, sutre_eski_dosyalar, sutre-db-prod.txt).
- Git ÖNCE: HEAD `24a64af`, `git diff --stat` boş, 14 untracked.

## 1. Taşıma tablosu (hepsi OK)

|#|Kaynak → Hedef|Dosya|Boyut|GERİ ALMA KOMUTU|
|:-|:--|:-:|:-:|:--|
|A1|`$D/sutre_eski_dosyalar` → `$S/06-arsiv/eski-dosyalar`|10|9.8M|`mv "$S/06-arsiv/eski-dosyalar" "$D/"`|
|A2|`$D/Referanslar` → `$S/02-icerik/Referanslar`|3|18.9M|`mv "$S/02-icerik/Referanslar" "$D/"`|
|A3|`$D` 8×`p2{7,8,9}*.png` + `$H` 13×png → `$S/05-raporlar/ekran-goruntuleri`|21|9.4M|`mv "$S/05-raporlar/ekran-goruntuleri"/p2*.png "$D/"; mv "$S/05-raporlar/ekran-goruntuleri"/*.png "$H/"`|
|A4|`$D` 5×`*_promptun_çıktısı.txt` → `$S/05-raporlar/prompt-ciktilari`|5|46K|`mv "$S/05-raporlar/prompt-ciktilari"/* "$D/"`|
|A5|`$D` 3× yasal metin → `$S/00-proje/yasal-metinler`|3|19K|`mv "$S/00-proje/yasal-metinler"/* "$D/"`|
|A6|`$H/Downloads/sutrescarfs.rar` → `$S/06-arsiv/`|1|8.6M|`mv "$S/06-arsiv/sutrescarfs.rar" "$H/Downloads/"`|
|C1|`$S/Logo ve Marka` → `$S/02-icerik/Logo ve Marka`|9|16M|`mv "$S/02-icerik/Logo ve Marka" "$S/"`|
|C2|`$S/Site Görselleri` → `$S/02-icerik/Site Görselleri`|3|22M|`mv "$S/02-icerik/Site Görselleri" "$S/"`|
|C3|`$S/Ürün Fotoğrafları/İman Nour` → `$S/02-icerik/Ürün Fotoğrafları/İman Nour`|67→22|582M→205M|`mv "$S/02-icerik/Ürün Fotoğrafları/İman Nour" "$S/Ürün Fotoğrafları/"` — C5-C7 geri alındıktan SONRA|
|C4|`$S/Ürün Fotoğrafları/Jakarlı` → `$S/02-icerik/Ürün Fotoğrafları/Jakarlı`|32|334M|`mv "$S/02-icerik/Ürün Fotoğrafları/Jakarlı" "$S/Ürün Fotoğrafları/"`|
|C5|`…/İman Nour/_web` → `$S/03-yayin/İman-Nour-web`|16|39.6M|`mv "$S/03-yayin/İman-Nour-web" "$S/02-icerik/Ürün Fotoğrafları/İman Nour/_web"`|
|C6|`…/İman Nour/_deneme` → `$S/06-arsiv/İman-Nour-deneme`|29|337.5M|`mv "$S/06-arsiv/İman-Nour-deneme" "$S/02-icerik/Ürün Fotoğrafları/İman Nour/_deneme"`|
|C7|`…/İman Nour/_arsiv-bos-klasorler` → `$S/06-arsiv/bos-klasorler`|0|0|`mv "$S/06-arsiv/bos-klasorler" "$S/02-icerik/Ürün Fotoğrafları/İman Nour/_arsiv-bos-klasorler"`|
|C8|`$S/Dev Arşiv` → `$S/06-arsiv/dev-arsiv`|9|1.0M|`mv "$S/06-arsiv/dev-arsiv" "$S/"`|
|C9|`$S/8-Sozlesme-Promptlari` → `$S/00-proje/sozlesme-promptlari`|5|12K|`mv "$S/00-proje/sozlesme-promptlari" "$S/"`|

## 2. Doğrulama (ham)

```
TARGET                                  files(exp/act)  bytes(exp/act)      VERDICT
06-arsiv/eski-dosyalar                  10/10           10323264/10323264       OK
02-icerik/Referanslar                    3/3            19785150/19785150       OK
05-raporlar/ekran-goruntuleri           21/21            9837613/9837613        OK
05-raporlar/prompt-ciktilari             5/5              46409/46409          OK
00-proje/yasal-metinler                  3/3              19351/19351          OK
06-arsiv/sutrescarfs.rar                 1/1            9029357/9029357        OK
02-icerik/Logo ve Marka                  9/9           16830648/16830648       OK
02-icerik/Site Görselleri                3/3           23345300/23345300       OK
02-icerik/Ürün Fotoğrafları/Jakarlı     32/32         350589414/350589414      OK
02-icerik/Ürün Fotoğrafları/İman Nour   22/22         215381335/215381335      OK
03-yayin/İman-Nour-web                  16/16          41526254/41526254       OK
06-arsiv/İman-Nour-deneme               29/29         353879490/353879490      OK
06-arsiv/bos-klasorler                   0/0                  0/0             OK
06-arsiv/dev-arsiv                       9/9            1069774/1069774        OK
00-proje/sozlesme-promptlari             5/5              12475/12475          OK
ALL TARGETS MATCH: True
```

Boyut korunumu: SUTRE ÖNCE 1.122.582.498 B → SONRA 1.171.623.642 B = **+49.041.144 B** (+43 dosya); beklenen giriş 10.323.264+19.785.150+8.104.203+46.409+19.351+9.029.357+1.733.410 = 49.041.144 B — birebir. İç taşımalar net sıfır. `du -sh`: 1070.6MB → **1.1G**. Kategoriler: 02-icerik 597M · 06-arsiv 357M · 03-yayin 40M · 05-raporlar 9.5M · 00-proje 48K.

## 3. `_renk-eslesmesi.json` güncellemesi
5 `web_jpeg` satırı `_web/…` → `03-yayin/İman-Nour-web/…` (yeni değer `SUTRE/` köküne göre; `arsiv_png`/`referans` İman Nour klasörüne göre göreli kaldı). JSON parse OK; 1987→2082 B (+95).
ÖNCE `"web_jpeg": "_web/Karamel/karamel-pamuk-<tip>.jpg"` → SONRA `"web_jpeg": "03-yayin/İman-Nour-web/Karamel/karamel-pamuk-<tip>.jpg"` (aynı kalıp Gül Kurusu, Gri Bej, Lacivert, Vişne Çürüğü).
Geri alma: `cp "/Users/salihsungur/.hermes/profiles/devops/cache/scratch/renk-eslesmesi-W3-onceki.json" "$S/02-icerik/Ürün Fotoğrafları/İman Nour/_renk-eslesmesi.json"` (orijinal kopya SUTRE ağacı dışında; çöp dosya bırakılmadı).

## 4. Boş kalan dizinler
Boş dizin sayımı (`.git` hariç) 8; W3 kaynaklı **yeni boş dizin yok**: `01-repo/…/assets/img/logo` + `…/img/site` (repo, W3 dışı, önceden vardı), `04-hesaplar` (W1), `06-arsiv/bos-klasorler/{Açık Bej,Bej,Koyu Bej,Siyah,Sütlü Kahve}` (C7 hedefi, bilinçli).
`$S/Ürün Fotoğrafları/` **silinemedi**: içinde yalnız `.DS_Store` (6148 B). Kanıt: `rmdir "$S/Ürün Fotoğrafları"` → `Directory not empty` (exit 1). "Silme YASAK" gereği silinmedi; sahip komutu: `rm "$S/Ürün Fotoğrafları/.DS_Store" && rmdir "$S/Ürün Fotoğrafları"`.

## 5. `~/Desktop` kök artık taraması
`ls ~/Desktop | grep -iE 'sutre|p2[789]|promptun|referans'` → **`SUTRE`** + **`sutre-db-prod.txt`** (secret, W4). Geniş tarama (`bilgilendirme|kullanım|TİCARİ|*.png|prompt`) → boş. `ls ~/*.png` → yok. `~/Downloads/sutrescarfs.rar` → yok.

## 6. Repo dokunulmadı kanıtı
`git -C ~/dev/sutre status --porcelain`: 14 satır, hepsi W3 öncesi var olan untracked (README-POINTER.md, logs/run-pack-89..93.log, out-90/91/92, pack-89..93.md). `git diff --stat` **boş** (0 değişmiş izlenen dosya); HEAD hâlâ **`24a64af`**. W3 hiçbir repo dosyasını değiştirmedi; yalnız bu rapor yeni untracked olarak eklendi.

## 7. YAPILAMADI
1. `$S/Ürün Fotoğrafları/` boş dizini silinemedi (`.DS_Store`).
2. `04-hesaplar` içeriği taşınmadı (paket gereği W4).
3. `_renk-eslesmesi.json` içindeki `arsiv_png`/`referans` yolları güncellenmedi — talimat yalnız `_web/` yollarını kapsıyordu.