# PACK-86 — Üretim tema klasöründeki `staging.sutre.store` girdisi: TAM İNCELEME (read-only)

## 0. NEDEN
pack-83 raporu şunu işaretledi: üretim tema klasörünün FTP listesinde beklenmedik bir girdi var. Sahip sorusu (birebir): *"staging.sutre.store test domainin linki onla alakalıdır? ne olduğunu iyice öğren ve bana ne yapıcağını tekrar sor."*

Bu paket **yalnız araştırır ve raporlar** — hiçbir şey silmez, taşımaz, değiştirmez.

## 1. ÖN KOŞUL
- Araçlar hazır: `python3 ~/.hermes/tools/ftp-tool.py {ls,get,sha,verify}` (parola komut satırına/çıktıya yazılmaz; `~/Desktop/ftpinfo.txt`'i kendisi okur). Canlı kontrol için `bash ~/.hermes/tools/check-live.sh <url>`.
- **Kabuk kuralı:** iç içe `$(...)` + tırnaklı operand içeren tek satır komut YAZMA (koşulsuz bloklanır).
- İlk tool çağrıların: (1) `AGENTS.md` TAMAMI, (2) `dispatch/out/out-83-404-template.md`, (3) `git log --oneline -3`.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**

## 2. İNCELENECEK SORULAR (her biri kanıtlı)
Hedef: `/sutre.store/wp-content/themes/sutre-child/` **ve** aynı yerdeki diğer beklenmedik girdiler.

1. **Ne olduğu:** tam ad/yol (`staging.sutre.store` bir dizin mi, dosya mı?), boyut, mtime, izinler (`ls` çıktısı ham).
2. **İçeriği:** özyinelemeli yapı — kaç dosya/dizin, hangi türler (`*.php`, `*.css`, `*.png`, `*.sql`, `*.zip`, `.env`, `wp-config*`), toplam boyut. En az 3 örnek dosya yolu + boyut. İçerik gerçek tema kopyası mı, yedek mi, çöp mü? Dosyalardan 2-3 tanesini `get` ile indirip **içeriğine bak** (tema kodu mu, yoksa başka bir şeyin kopyası mı — dosya başlıkları, mtime'lar).
3. **Web'den erişilebilir mi (GÜVENLİK):** `https://sutre.store/wp-content/themes/sutre-child/staging.sutre.store/` ve içindeki 2-3 bilinen dosya için HTTP durumu. **PHP çalışıyor mu** — içinde PHP varsa, zararsız bir keşif yolu (ör. dizin listeleyen `index.php` varsa onun HTTP yanıtı) ile çalışıp çalışmadığını **kanıtla** (yeni dosya YÜKLEME; sadece mevcut dosyaları çağır). Dizin listelemesi açık mı (`autoindex`)?
4. **Ne zaman/neyle oluştu:** mtime'ları deploy geçmişiyle karşılaştır — pack-77/78/80/83 deploy'ları (`git log` + `dispatch/out/logs/run-pack-7*.log`, `8*.log`) ve staging deploy betikleri (`ftp_deploy_staging.py`) ile ilişkisi. Hipotez: göreli yoldan yapılmış bir FTP yüklemesi bu iç içe yapıyı üretmiş olabilir — **kanıtla ya da çürüt** (hangi koşu, hangi komut).
5. **staging.sutre.store ile ilişkisi:** içerik staging temasının kopyası mı (staging'deki dosya SHA'larıyla karşılaştır: `ftp-tool.py sha /staging.sutre.store/wp-content/themes/sutre-child/<dosya>` vs bu dizindeki aynı dosya)? Yoksa bağımsız bir şey mi? Net hüküm ver.
6. **Aktif kullanımda mı:** dizin herhangi bir yerde referans ediliyor mu (tema kodu, canlı HTML, sitemap)? Edilmiyorsa "ölü artık" hükmü ver.
7. **Riskler:** PHP çalışıyorsa ve içerik eski/yabancıysa → **yüksek risk** (eski açık sürüm, gizli dosya, index). Çalışmıyorsa → düşük risk (disk/karışıklık).
8. **Seçenekler + öneri:** (a) hiç dokunma, (b) arşivle-indir-sonra-sil, (c) doğrudan sil. Her seçeneğin **etkisi ve riski** + senin önerin (gerekçeli). **UYGULAMA YOK.**

## 3. ROL SINIRI (read-only — bağlayıcı)
- **Silme, taşıma, yükleme YOK.** Yalnız `ls/get/sha`/HTTP GET.
- Repoda dosya değiştirme; commit YOK (rapor dosyası hariç). Production'da hiçbir şey değişmez.
- Provider/model override YASAK. İndirdiğin örnekleri scratch'te tut, repoya koyma; iş sonunda geçici dosyaları temizle.

## 4. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-86-staging-klasor-incelemesi.md` (≤700 kelime).
- Bölümler: (1) ÖZET HÜKÜM (ne, ne zaman, risk, aktif mi), (2) ham `ls` çıktısı, (3) içerik envanteri (dosya sayısı/tür/boyut + örnekler), (4) web erişimi + PHP çalışıyor mu (ham HTTP kanıtları), (5) köken analizi (hangi koşu/komut — kanıtlı), (6) staging ile ilişki (SHA karşılaştırması), (7) referans taraması (ölü artık mı), (8) risk değerlendirmesi, (9) SEÇENEKLER + ÖNERİ (gerekçeli, uygulama yok), (10) `YAPILAMADI` listesi.
- Kanıtsız hüküm YASAK; doğrulanamayanı `DOĞRULANAMADI: <neden>` yaz.