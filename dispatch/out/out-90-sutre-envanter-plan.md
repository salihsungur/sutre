# PACK-90 — SUTRE tam envanter + hedef klasör yapısı + taşıma risk analizi (READ-ONLY PLAN)

Tarih: 2026-09-26 · Rol: devops · Mod: **yalnız okuma** (hiçbir dosya taşınmadı/silinmedi/yeniden adlandırılmadı)
Kaynaklar okundu: `~/dev/sutre/AGENTS.md` (tamamı), canlı `git status`, `df -h`, FTP/curl araçları (salt-okuma çağrı).

---

## 1. ÖZET

| Ölçüt | Değer |
| :-- | :-- |
| Envanterlenen öğe | **43** (klasör/dosya/repo/venv) |
| SUTRE ile doğrudan ilgili toplam boyut | **~1.13 GB** (Desktop/SUTRE 956M + repo 130M + eski dosyalar 9.9M + referanslar 19M + rar 8.6M) |
| Secret nitelikli öğe | **9** (2 metin + 1 SQL dökümü + 3 SSH anahtarı + 2 `.env` + 1 anahtar kopyası) |
| Git deposu | 1 repo, 3 branch (main/staging/dev), 283 takip dosyası, 4 kirli (untracked) |
| Boş disk | **693 Gi (~%24 dolu)** — kopyala-doğrula için fazlasıyla yeterli |

**En kritik bulgu:** `~/Downloads/sutre` (1856 B, izin **644**) içeriği `~/.ssh/sutre_hosting` ile **SHA-256 birebir aynı** (99f008f3…). Yani ana özel anahtarın dünya-okunur bir kopyası Downloads'ta duruyor. (İçerik okunmadı; eşleşme yalnız hash ile kanıtlandı.)

**iCloud bulgusu:** `~/Desktop` iCloud'a bağlı **DEĞİL** — gerçek dizin (symlink yok), `~/Library/Mobile Documents/com~apple~CloudDocs` **boş**, Desktop/Documents hiçbir brctl container'ında görünmüyor. Bugün Desktop'a konan bir secret buluta gitmez; ancak Apple ID tarafında "Masaüstü ve Belgeler" sonradan açılırsa gider (yıkıcı risk).

---

## 2. TAM ENVANTER TABLOSU

`yol · tip · boyut · son değişiklik · git'te mi · SUTRE mi · hassasiyet · taşınabilir mi`

### 2.1 `~/Desktop/SUTRE/**` (956M, 127 dosya)
| # | Yol | Tip | Boyut | Son değ. | git | SUTRE | Hassas | Taşınır |
|:--|:--|:--|--:|:--|:--|:--|:--|:--|
| 1 | SUTRE/8-Sozlesme-Promptlari/ | klasör | 20K | 09-22 | hayır | Evet | normal | Evet |
| 2 | SUTRE/Dev Arşiv/ (8 png) | klasör | 1.0M | 09-19 | hayır | Dolaylı | normal | Evet |
| 3 | SUTRE/Logo ve Marka/ (9 png) | klasör | 16M | 09-19 | hayır | Evet | normal | Evet |
| 4 | SUTRE/Site Görselleri/ (3 png) | klasör | 22M | 09-19 | hayır | Evet | normal | Evet |
| 5 | SUTRE/Ürün Fotoğrafları/Jakarlı/ (9 renk) | klasör | 334M | 09-18 | hayır | Evet | normal | Evet |
| 6 | SUTRE/Ürün Fotoğrafları/İman Nour/{5 renk} | klasör | ~205M | 09-23 | hayır | Evet | normal | Evet |
| 7 | SUTRE/Ürün Fotoğrafları/İman Nour/_web/ (15 jpg) | klasör | 40M | 09-23 | hayır | Evet | normal | Evet |
| 8 | SUTRE/Ürün Fotoğrafları/İman Nour/_deneme/ (11 alt) | klasör | 338M | 09-23 | hayır | Dolaylı | normal | Evet |
| 9 | SUTRE/Ürün Fotoğrafları/İman Nour/_referans/ (5 jpeg) | klasör | 1.4M | 09-23 | hayır | Evet | normal | Evet |
| 10 | …/_arsiv-bos-klasorler/ | klasör | 0B | 09-19 | hayır | Dolaylı | normal | Evet |

### 2.2 `~/Desktop` kökü — SUTRE ilgili
| # | Yol | Tip | Boyut | Son değ. | git | SUTRE | Hassas | Taşınır |
|:--|:--|:--|--:|:--|:--|:--|:--|:--|
| 11 | ftpinfo.txt | dosya | 167B | 09-17 | hayır | Evet | **secret** | **ŞARTLI** (ftp-tool.py:19 sabiti) |
| 12 | sutre-db-prod.txt | dosya | 140B | 09-22 | hayır | Evet | **secret** | **ŞARTLI** (AGENTS.md §2.2 referansı) |
| 13 | staging-db-yedek-2026-09-22.sql | dosya | 2.2M | 09-22 | hayır | Evet | **secret** (DB dökümü) | ŞARTLI |
| 14 | sutre_eski_dosyalar/ (10 dosya) | klasör | 9.9M | 09-19 | hayır | Evet | normal | Evet |
| 15 | Referanslar/ (3 jpg) | klasör | 19M | 09-18 | hayır | Dolaylı | kişisel | Evet |
| 16 | p27-final, p27-hero-check, p28-final, p28-home, p28b-final, p29-final, p29-shop, p29b (.png) | dosya ×8 | 8.3M | 09-19 | hayır | Dolaylı | normal | Evet |
| 17 | ilk/ikinci/üçüncü/dördüncü/beşinci_promptun_çıktısı.txt | dosya ×5 | 46K | 09-22 | hayır | Evet | normal | Evet |
| 18 | ön_bilgilendirme_formu.txt · websitesi_kullanım_koşulları.txt · TİCARİ ELEKTRONİK İLETİ AÇIK RIZA METNİ.txt | dosya ×3 | 19K | 09-22 | hayır | Evet | normal | Evet |

### 2.3 Repo & araçlar
| # | Yol | Tip | Boyut | Son değ. | git | SUTRE | Hassas | Taşınır |
|:--|:--|:--|--:|:--|:--|:--|:--|:--|
| 19 | ~/dev/sutre/ | **repo** | 130M (.git 29M) | 09-23 | Evet, main@`1695c79` | Evet | normal | **HAYIR** |
| 20 | ~/dev/sutre/.p57-woo-src/ | klasör | 86M | 09-22 | gitignored | Dolaylı | normal | Evet |
| 21 | ~/dev/sutre/dispatch/ (222 dosya) | klasör | 11M | 09-23 | Evet | Evet | normal | **HAYIR** (repo içi) |
| 22 | ~/dev/sutre/docs/visual-prompts/ | klasör | 3.2M | 09-24 | Evet | Evet | normal | HAYIR |
| 23 | ~/dev/sutre/theme/sutre-child-v2/ | klasör | 400K | 09-23 | Evet | Evet | normal | HAYIR |
| 24 | ~/.hermes/tools/ftp-tool.py | dosya | 5.4K | 09-23 | hayır | Evet | normal | **HAYIR** (araç yolu) |
| 25 | ~/.hermes/tools/check-live.sh | dosya | 1.2K | 09-23 | hayır | Evet | normal | HAYIR |
| 26 | ~/.hermes/tools/bot-activity.sh | dosya | 1.7K | 09-23 | hayır | Dolaylı | normal | HAYIR |
| 27 | ~/.hermes/tools/higgsfield-venv/ | **venv** | 25M | 09-23 | hayır | Evet | normal | **HAYIR** (yorumlayıcı yolu) |
| 28 | ~/Library/Application Support/Hermes/sutre-cpanel.env | dosya | 301B | 09-23 | hayır | Evet | **secret** | **HAYIR** |
| 29 | …/Hermes/sutre-higgsfield.env | dosya | 422B | 09-23 | hayır | Evet | **secret** | HAYIR |
| 30 | ~/.ssh/sutre_deploy (+.pub) | anahtar | 419B | 09-23 | hayır | Evet | **secret** | **HAYIR** |
| 31 | ~/.ssh/sutre_hosting (+.pub) | anahtar | 1856B | 09-23 | hayır | Evet | **secret** | HAYIR |
| 32 | ~/.ssh/sutre_cpanel (+.pub) | anahtar | 419B | 09-23 | hayır | Evet | **secret** | HAYIR |
| 33 | ~/.ssh/config (`Host github-sutre`) | dosya | 161B | 09-23 | hayır | Evet | normal | HAYIR |
| 34 | ~/Downloads/sutre | anahtar kopyası | 1856B | 09-12 | hayır | Evet | **secret (644!)** | **HAYIR — sil/rotasyon** |
| 35 | ~/Downloads/sutre.pub | anahtar | 382B | 09-12 | hayır | Dolaylı | normal | Evet |
| 36 | ~/Downloads/sutrescarfs.rar | arşiv | 8.6M | — | hayır | Evet | normal | ŞARTLI (içerik doğrulanmadı) |

### 2.4 Hermes profilleri / bilgi katmanları (yalnız liste — taşınmaz)
| # | Yol | Tip | Boyut | git | SUTRE | Hassas | Taşınır |
|:--|:--|:--|--:|:--|:--|:--|:--|
| 37 | ~/.hermes/profiles/{architect,coder,data,debugger,designer,devops,docs,finance,legal,marketing,ops,product,researcher,reviewer,tester}/SOUL.md | dosya ×15 | ~7K/ad | hayır | Evet (`~/dev/sutre` satırı geçiyor) | normal | **HAYIR** |
| 38 | …/profiles/{coder,devops,docs,researcher,tester}/checkpoints/…/5ed30a…json | dosya ×5 | ~150B | hayır | Evet (`workdir=/Users/salihsungur/dev/sutre`) | normal | **HAYIR** |
| 39 | ~/.hermes/profiles/memory/cron/output/ + sessions/ | klasör | — | hayır | Dolaylı (rapor metinleri) | normal | HAYIR |
| 40 | ~/Documents/SalihSungurVault/entities/sutre.md | dosya | ~2.6M vault | 09-25 | hayır | Evet | kişisel | **HAYIR** (Obsidian) |
| 41 | ~/.openviking/data/ | **vektör DB** | — | 09-26 | hayır | Dolaylı (SUTRE kayıtları var) | kişisel | **HAYIR** |
| 42 | ~/*.png (home-sutre, home-sutre1, checkout-sutre, shop-sutre, home-full, home-s7, nocache-check, frontcheck, gitvc, site-editor×2, reading-settings, page-after-typing) | dosya ×13 | ~1.3M | 09-16/17 | Dolaylı | normal | Evet |
| 43 | ~/.hermes/cache, /tmp, ~/Library/Logs, ~/Movies, ~/Pictures, Desktop/Desktop | — | — | — | **SUTRE izi YOK** | — | — |

---

## 3. HEDEF YAPI (öneri) ve EŞLEME

```text
~/Desktop/SUTRE/
├── 00-proje/        AGENTS.md kopyası DEĞİL → POINTER-AGENTS.md (tek kaynak ~/dev/sutre/AGENTS.md)
├── 01-repo/         POINTER-REPO.md (repo taşınmıyor)
├── 02-icerik/       Logo ve Marka/ · Site Görselleri/ · Ürün Fotoğrafları/{Jakarlı,İman Nour}/ · Referanslar/ · _referans/
├── 03-yayin/        İman Nour/_web/ (Woo'ya yüklenen JPEG seti)
├── 04-hesaplar/     ← ÖNERİM: BOŞ BIRAKILIR (bkz §4.1)
├── 05-raporlar/     Dev Arşiv/ · *.png (p27–p29) · *promptun_çıktısı.txt ×5 · 8-Sozlesme-Promptlari/ · yasal *.txt ×3 · staging-db-yedek*.sql · ~/*.png ×13
├── 06-arsiv/        sutre_eski_dosyalar/ · İman Nour/_deneme/ (338M) · _arsiv-bos-klasorler/ · Downloads/sutrescarfs.rar
└── 99-gecici/       (şimdilik boş; scratch)
```
**Boş kalan kategoriler:** `00-proje`, `01-repo`, `99-gecici` → **klasör olarak açılmaz**, yerine `POINTER-*.md` dosyası konur (boş klasör = sahte yapı). En büyük kazanç: Ürün Fotoğrafları'nın 3 katmanını (`_web` yayın / renk arşivi / `_deneme` çalışma) ayırmak ve 338M deneme artığını `06-arsiv`e çekmek.

---

## 4. KARARA BAĞLANACAK NOKTALAR

**4.1 Kimlik dosyaları — ÖNERİM: Desktop/SUTRE içine TAŞIMA.**
`~/Desktop` şu an iCloud'a bağlı değil (kanıt §1), yani bugün taşımak buluta sızdırmaz — ama Apple tarafındaki bir ayar değişikliği (`Desktop & Documents Folders`) bunu sessizce bozar ve `04-hesaplar` içindeki 4 secret'ı buluta basar. Ayrıca `ftp-tool.py:19` sabiti `~/Desktop/ftpinfo.txt`'i sabit kodlar.
Önerim: secret'lar **Desktop ağacının dışında** kalsın; ideal hedef `~/.hermes/secrets/sutre/` (izin 600) + `ftp-tool.py` sabitinin güncellenmesi. Sahip Desktop'ta kalmasını isterse kabul edilebilir tek biçim: 04-hesaplar + `chmod 600` + iCloud "Desktop & Documents" **kapalı kalma şartı** yazılı kural olarak AGENTS.md'ye. `staging-db-yedek*.sql` (2.2M, canlı müşteri verisi olabilir) kesinlikle 04-hesaplar'a değil, `06-arsiv`e ya da Desktop dışına.

**4.2 Repo taşıma — ÖNERİM: TAŞIMA.**
`~/dev/sutre` → `~/Desktop/SUTRE/01-repo` olursa kırılan referanslar: (a) 5 profilin checkpoint projesi (`workdir=/Users/salihsungur/dev/sutre`, ayrıca `workdir_parent_dev`/`ino` saklı → yol değişince eşleşme bozulur), (b) 15 profil SOUL.md'deki `~/dev/sutre/AGENTS.md` bağlayıcı referansı, (c) AGENTS.md §3/§6 mutlak yolları, (d) `dispatch/pack-*.md` içindeki 9 mutlak Desktop yolu, (e) Desktop'ın TCC korumalı klasör olması (araç erişim izinleri) ve (f) iCloud Desktop açılırsa 130M'luk `.git`'in senkronlanması.
**Symlink çözümü** (`~/dev/sutre` → yeni yol) path-tabanlı okuyucuları kurtarır (git, araçlar, `cd`, `cp -a`), ama checkpoint inode/dev eşleşmesini kurtarmaz ve `.git`'i iCloud'a taşıma riskini ortadan kaldırmaz. Ayrıca symlink + TCC kombine hataları sessiz olabilir. Net öneri: **repo yerinde kalsın**, `01-repo/README-POINTER.md` repo yolunu ve remote'u yazsın.

**4.3 Taşınmayacaklar — ÖNERİM: hepsi yerinde + pointer.**
Hermes profilleri (canlı oturum state'i + auth), OpenViking verisi (canlı vektör DB, taşınırsa sunucu yolu kırılır), Obsidian vault (kendi senkron/uygulama yolu), venv'ler (yorumlayıcı yolu gömülü — taşınırsa `bin/python` shebang kırılır), `~/.hermes/tools/*.py|sh` (agent araç yolu), `~/.ssh/*` (izin + ssh-agent). Yerine: `~/Desktop/SUTRE/00-proje/POINTER-SISTEM.md` — her sistem yolunun ne olduğu ve nerede durduğu tek sayfada.

**4.4 `_web/` ve büyük medya — ÖNERİM: git DIŞINDA kalsın.**
Desen: tam set 917M (arşiv PNG'ler), `_web` 40M (yayın JPEG), `_deneme` 338M (artık). Repo 130M ve `.gitignore` zaten `*.sql`, `backups/**/*.php|css`, `.p57-woo-src/` dışlıyor. Medya git'e girerse repo ~1 GB'a çıkar ve clone/push süreleri bozulur. Tek istisna adayı: `_web/` (40M, doğrudan yayın artefaktı) — sahip isterse `docs/visual-prompts/` yanına ayrı bir arşiv olarak eklenebilir; önerim eklenmemesi.

---

## 5. RİSK / KIRILMA LİSTESİ + GERİ ALMA

| Taşıma | Neyi kırar | Okuyan araç | Geri alma |
|:--|:--|:--|:--|
| `ftpinfo.txt` → SUTRE/04-hesaplar | FTP deploy tamamen durur | `ftp-tool.py:19` (`CREDS`) | `mv ~/Desktop/SUTRE/04-hesaplar/ftpinfo.txt ~/Desktop/` |
| `sutre-db-prod.txt` taşıma | WP/DB notları yanlış yolu gösterir | AGENTS.md §2.2, §3 | `mv` geri |
| `~/dev/sutre` taşıma | agent bağlamı + checkpoint + dispatch yolları | 15 SOUL.md, 5 checkpoint, AGENTS.md §3/§6 | `mv` geri + `git fsck` |
| Desktop/SUTRE yeniden adlandırma | referans görselleri/`higgsfield` betiği yolları | `scripts/higgsfield/*.py` argümanları | `mv` geri |
| `~/.ssh/sutre_deploy` taşıma | push/pull imzası ölür | `core.sshCommand`, `.ssh/config` | `mv` geri + `ssh -T git@github-sutre` |
| `higgsfield-venv` taşıma | venv shebang kırılır | `~/.hermes/tools/higgsfield-venv/bin/python` | `mv` geri |
| `Downloads/sutre` silme | yok (anahtarın aslı `.ssh/`'te) | — | gerek yok; **rotasyon önerilir** |

**Yıkıcı olmayan sıra (öneri):** `cp -a` ile hedefe kopyala → `shasum -a 256` iki tarafta karşılaştır → araçları çalıştırıp doğrula → **ancak o zaman** kaynağı `mv`/sil. `git mv` bu iş için gereksizdir (taşıma repo dışı). Disk: 693 Gi boş → kopyala-doğrula fazlasıyla sığar.
**Doğrulama araçları (sahip onaylı):** `git -C ~/dev/sutre fsck && git -C ~/dev/sutre status --porcelain` · `bash ~/.hermes/tools/check-live.sh https://sutre.store/` · `python3 ~/.hermes/tools/ftp-tool.py ls /sutre.store/wp-content/themes/sutre-child/`
**Bugünkü durum (bu pakette koşuldu):** check-live → `HTTP=200 cache=hit payments=1 css=ver=3.4.1,ver=3.6.8,ver=3.7.1 fatal=0`; ftp-tool `ls` → tema dizini listelendi (404.php 4055B, functions.php 95257B, `_OLU-ARTIK-staging.sutre.store-20260921` yerinde). **Her iki araç taşımadan önce ÇALIŞIYOR** — referans değeri bu.

---

## 6. UYGULAMA DALGALARI (sonraki paketler — bu pakette YAPILMADI)

| Dalga | Kapsam | Geri alma | Doğrulama | Süre |
|:--|:--|:--|:--|:--|
| **W1 — Pointer + hijyen** (risk yok) | `00-proje/POINTER-SISTEM.md` + `01-repo/README-POINTER.md` yaz; `chmod 600 ftpinfo.txt sutre-db-prod.txt`; `Downloads/sutre` kaldır + sahibe **anahtar rotasyonu** öner | dosyaları sil | `ls -l` izinler; ftp-tool hâlâ çalışıyor | ~15 dk |
| **W2 — Desktop kök toplama** | 16–18. satırlardaki 16 öğe → `05-raporlar/` / `06-arsiv/` (`cp -a` + SHA + `mv`) | `mv` geri (tek satır) | SHA listesi + `find` | ~20 dk |
| **W3 — SUTRE iç kategorizasyon** | Logo/Site/Ürün Fotoğrafları → `02-icerik`; `_web` → `03-yayin`; `_deneme` → `06-arsiv` | `mv` geri | `du -sh` ağaç karşılaştır; `_renk-eslesmesi.json` referansları güncelle | ~25 dk |
| **W4 — Secret hedefi** (sahip kararı §4.1) | `~/.hermes/secrets/sutre/` (600) + `ftp-tool.py` sabiti güncelle | sabiti geri al, dosyaları geri taşı | `ftp-tool.py ls …` PASS | ~20 dk |
| **W5 — Arşiv budama** | `.p57-woo-src/` (86M, gitignored kazıma) ve `_deneme` kararı; silme YOK, `06-arsiv`e ya da harici disk | `mv` geri | `git status` temiz | ~15 dk |

Her dalga bağımsız geri alınabilir; **W1 dışındakiler W4'ten bağımsız değildir** (W4 seçimi `ftpinfo.txt` yerini belirler) → sıra: W1 → W4 → W2 → W3 → W5.

---

## 7. DOĞRULANAMADI

1. **iCloud'un Apple ID tarafındaki durumu** — yalnız yerel kanıt var (CloudDocs boş, Desktop symlink değil). Apple ID panelinde "Masaüstü ve Belgeler" ayarı okunamadı.
2. **`~/Downloads/sutre` kimliği** — içerik okunmadı; `~/.ssh/sutre_hosting` ile SHA-256 eşleşmesi "aynı anahtar" olduğunu çok güçlü biçimde gösterir ama kriptografik kimlik doğrulaması yapılmadı.
3. **`staging-db-yedek-2026-09-22.sql` içinde canlı müşteri verisi var mı** — dosya açılmadı (secret); "olabilir" varsayımıyla en hassas sınıfa yazıldı.
4. **`~/Downloads/sutrescarfs.rar` içeriği** — arşiv açılmadı; SUTRE malzemesi varsayımı ad-benzerliğine dayanır.
5. **OpenViking'deki SUTRE girdi sayısı** — indeks içinde `sutre` eşleşmeleri görüldü, sayım yapılmadı (vektör DB ikili biçim).
6. **`~/.hermes/profiles/*/cron` işleri** — global `~/.hermes/cron/jobs.json` boş (`jobs: []`); profil cron'ları taranmadı, SUTRE'ye bağlı zamanlanmış iş olup olmadığı kesinleşmedi.

SONUÇ: PASS (read-only envanter + plan tamam; hiçbir öğe taşınmadı/silinmedi)