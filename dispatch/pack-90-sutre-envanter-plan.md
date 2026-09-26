# PACK-90 — SUTRE tam envanter + hedef klasör yapısı + taşıma risk analizi (READ-ONLY PLAN)

## 0. SAHİP TALEBİ (birebir)
*"bilgisayarımdaki sutre ile alakalı tüm dosya, klasör, kodlar, kullanıcı bilgileri vb. vb. herşeyi masaüstümdeki sutre klasörünün içine taşıyıp sonrada doğru düzgün detaylı şekilde dosya/klasör yapısını düzenler ve kategorize et (genel olarak zaten herşey masaüstümde)"*

**BU PAKET UYGULAMA YAPMAZ.** Yalnız envanter + plan + risk üretir. Taşıma, sahip onayından sonra ayrı dalga paketleriyle yapılacak.

## 1. TARANACAK YERLER (hepsini tara; bulduğun her şeyi listele)
- `~/Desktop` (kök + `SUTRE/` + tüm alt klasörler) — özellikle `ftpinfo.txt`, `sutre-db-prod.txt`, `SUTRE/Ürün Fotoğrafları/**`
- `~/dev/sutre` (git repo — branch, remote, dirty durum, boyut, `.git` boyutu)
- `~/.hermes/tools/**` (sutre ile ilgili olanlar: `ftp-tool.py`, `check-live.sh`, `bot-activity.sh`, `higgsfield-venv/` boyutu)
- `~/Library/Application Support/Hermes/**` (`sutre-higgsfield.env`, `sutre-cpanel.env` + başka sutre dosyası)
- `~/.hermes/profiles/*` → **hangi profiller sutre ile çalıştı** (son oturum workspace'i `sutre` olan profiller) — yalnız liste, dosya taşınmaz
- `~/.ssh/**` (sutre deploy key — adı/yetkisi, içeriği YAZILMAZ)
- `~/Documents` + `~/Documents/SalihSungurVault` (sutre notları), `~/.openviking/**` (yalnız sutre girdisi var mı bilgisi)
- `~/Downloads`, `~/Movies`, `~/Pictures`, `~/` kökü ve `~/Desktop` altında `*sutre*` / `*SUTRE*` desenli **her** dosya-klasör
- Ayrıca: `/tmp`, `~/Library/Logs`, Hermes cache klasörlerinde sutre izleri (varsa yalnız bilgi)

## 2. HER ÖĞE İÇİN TABLO (zorunlu kolonlar)
`yol · tip (dosya/klasör/repo/venv) · boyut · son değişiklik · git'te mi (repo içiyse) · SUTRE mi (Evet/Dolaylı/Hayır) · hassasiyet (secret/kişisel/normal) · taşınabilir mi (Evet/ŞARTLI/HAYIR)`
- "ŞARTLI" = taşınır ama bir yolu/referansı güncellenmeli (hangi yol olduğunu yaz).
- "HAYIR" = sistem yolu, taşınırsa bir şey kırılır (nedenini yaz).

## 3. HEDEF YAPI ÖNERİSİ (ağaç, gerekçeli)
`~/Desktop/SUTRE/` altında **kategorili** öneri üret. En az şu kategorileri değerlendir (kendi önerini de ekleyebilirsin):
```
SUTRE/
├── 00-proje/          (AGENTS.md, anayasa, planlar, durum özetleri)
├── 01-repo/           (git deposu — tema kaynağı, scripts, docs, dispatch)
├── 02-icerik/         (ürün fotoğrafları, marka görselleri, metin/içerik taslakları)
├── 03-yayin/          (canlıya çıkmış sürümler, deploy yedekleri, SHA kayıtları)
├── 04-hesaplar/       (kimlik/erişim bilgileri — AYRI DEĞERLENDİR, §4)
├── 05-raporlar/       (bot raporları, denetimler, kanıtlar, ekran görüntüleri)
├── 06-arsiv/          (eski sürümler, kullanılmayan dosyalar, supplanter)
└── 99-gecici/         (scratch/deneysel)
```
- Her kategorinin **içine giren mevcut dosyaları tek tek eşle** (hangi öğe nereye).
- Boş kalan kategorileri işaretle; gereksiz olanı önerme.

## 4. KARARA BAĞLANACAK NOKTALAR (sahibe sorulacak; sen öneri ver + gerekçe)
1. **Kimlik dosyaları** (`ftpinfo.txt`, `sutre-db-prod.txt`, `sutre-*.env`, ssh deploy key): Desktop içine taşınırsa **iCloud Desktop senkronizasyonu** ile buluta gidebilir mi? (kontrol et: `~/Desktop` iCloud/`Mobile Documents` bağlantılı mı — `ls -la ~/Desktop`, `brctl status` vb. güvenli yöntemle). Önerin: taşı / taşıma / taşı+izin 600 + iCloud dışı.
2. **Git repo taşıma:** `~/dev/sutre` → `~/Desktop/SUTRE/01-repo` olursa hangi referanslar kırılır? (Hermes dispatch paketlerindeki mutlak yollar, `core.sshCommand`, cron/varsa, profil workspace kayıtları). **Symlink çözümü** (`~/dev/sutre` → yeni yol) artı/eksileri; nelerin symlink ile kırılmadan çalışmaya devam edeceğini listele.
3. **Taşınmayacaklar listesi:** Hermes profilleri (`~/.hermes/profiles/*`), OpenViking verisi, Obsidian vault, venv'ler — neden taşınmamalı + yerine ne yapılmalı (README/pointer dosyası).
4. **`_web/` ve büyük medya:** boyutları; git içine mi dışına mı.

## 5. RİSK VE GERİ ALINABİLİRLİK
- Taşıma kaynaklı kırılma listesi: **hangi yolu hangi araç okuyor** (ör. `ftp-tool.py` içindeki `~/Desktop/ftpinfo.txt` sabiti, paketlerdeki mutlak yollar, `check-live.sh`, bot oturumları).
- Her taşıma için **geri alma** yöntemi (ham komut).
- Yıkıcı olmayan sıra: önce kopyala-doğrula, sonra kaynağı sil mi yoksa `git mv`/`cp -a` + kaynak sil mi — önerin.
- **Disk ön kontrolü:** `df -h` ile boş alan (kopyala-doğrula için yeterli mi).

## 6. UYGULAMA DALGALARI (sonraki paketler için öneri — şimdi YAPMA)
- Dalga başına: kapsam, geri alma, doğrulama, tahmini süre. Her dalga bağımsız geri alınabilir olmalı.
- Doğrulama araçları: `git -C <yol> fsck` + `git status` + `bash ~/.hermes/tools/check-live.sh https://sutre.store/` + `python3 ~/.hermes/tools/ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/` (araçlar taşınmadan sonra da çalışıyor mu).

## 7. ROL SINIRI (BAĞLAYICI)
- **Hiçbir dosya taşınmaz, yeniden adlandırılmaz, silinmez, taşınmaz.** Yalnız okuma: `ls/find/du/stat/git status/df`.
- `find` çıktısını sınırla (rubbish yolları atla: `node_modules`, `.git/objects`, `Library/Caches`).
- Secret **içerikleri rapora/çıktıya YAZILMAZ** (yalnız dosya adı + izin + sahiplik). Ham komut çıktısında secret görürsen rapora `<REDACTED>` yaz.
- Kabuk kuralı: iç içe `$(...)` + tırnaklı operand içeren tek satır komut YAZMA (koşulsuz bloklanır).
- Provider/model override YASAK.

## 8. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-90-sutre-envanter-plan.md` (≤1200 kelime + zorunlu tablolar).
- Bölümler: (1) özet (kaç öğe, toplam boyut, kaç secret), (2) TAM ENVANTER TABLOSU (§2 kolonları), (3) hedef ağaç + eşleme, (4) karara bağlanacak noktalar (§4, her biri için net önerin), (5) risk/kırılma listesi + geri alma, (6) dalga planı, (7) `DOĞRULANAMADI` listesi.
- İlk tool çağrıların: `AGENTS.md` (TAMAMI) okuma + envanter taraması; **ilk yanıtın ZORUNLU tool çağrısı olsun, plan metni yazma**.