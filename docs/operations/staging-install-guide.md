# Staging WordPress Kurulum Talimatı — staging.sutre.store (Paket 15)

> Belge türü: İŞLETİM TALİMATI (sahibin cPanel Terminal'inde sırayla çalıştırılır).
> Sahip: Salih. Hazırlayan: @devops. Tarih: 2026-09-15.
> Bağlılık: ADR-002 onaylı (pull-only deploy, Softaculous YASAK, public_html'e yazma YASAK — anayasa §0.6).
> Kural: HER adımdan sonra terminal çıktısını bu dosyanın yanına kaydettiğin kanıt dosyasına yapıştır
> (örn. `staging-kurulum-kanit.txt`) — @devops'a göndermeden önce kanıt ZORUNLU.

## Kurulum öncesi durum (kanıt)
- PHP 7.1 uyum tablosu: make.wordpress.org/hosting compatibility — **WordPress 7.1: PHP 8.2–8.5**,
  MariaDB 10.11/11.4/11.8/12.3 → WP 7.1'in PHP 8.5 resmî test beyanı **VAR**.
- Bu nedenle sürüm hedefi: **PHP 8.5** (Salih'in 8.5 seçimi meşru; ADR-002 §3'ün 8.4 hedefi 8.5 beyanı yok varsayımına
  dayanıyordu — araştırma kapısı kapandı). Panel dropdown'da 8.5 YOKSA fallback: **PHP 8.4** (version-lock seçimi).
- MariaDB 11.8.8 (P13b) → WP 7.1 uyum tablosunda 11.8 VAR ✓.
- Staging DNS: `staging.sutre.store` → 89.252.180.243 (NOERROR, orkestratör paketinde doğrulandı).
- CHECKSUM: WordPress 7.1 tarball wp.org CDN'den 2026-09-15 indirildi ve doğrulandı:
  - MD5:    `51db0c3bd4f2332b25f9c23e17983047`
  - SHA256: `05a5f89138f632b7329f1202f2a0553c5f7fe4daf8e4b9ca7ebae9b9466b9e86`
  NOT: checksum'lar wp.org arşivinin bu taramadaki değeri; indirme sırasında fark görürsen kurulum yapma,
  çıktıyı @devops'a gönder.

---

## Adım 0 — Docroot'a gir ve boş olduğunu kontrol et

cPanel Terminal'i aç (port 2083'ten terminale ulaş: cPanel → Terminal).

```bash
cd ~/staging.sutre.store
ls -la
```

KANIT BEKLENEN ÇIKTI: `total 8` gibi başlayan, içinde yalnız `.` ve `..` girişleri olan boş liste
(toplamda 1–2 dosya üstü ÇIKTI GÖRMEMELİ — dosya varsa İLERLEME, kanıtı @devops'a gönder).

Dizin yoksa: cPanel → Alan Adları altında `staging.sutre.store` docroot'unun bu yolu gösterdiğini teyit
et; yalnız yolu eksikse oluştur: `mkdir -p ~/staging.sutre.store`. 

⚠️ YASAK: `cd ~/public_html` — orada spokenlab.com.tr mevcut statik site var (anayasa §0.6, EZİLMEMELİ).

---

## Adım 1 — WP 7.1 çekirdeğini sürüm-kilitli indir + checksum doğrula

```bash
curl -sSL https://wordpress.org/wordpress-7.1.tar.gz -o /tmp/wp.tar.gz
md5sum /tmp/wp.tar.gz
sha256sum /tmp/wp.tar.gz
```

Not: `/tmp` yazılabilir değilse dosyayı `~/` altına kaydet ve aşağıdaki komutlarda yolu ona göre değiştir.

KANIT BEKLENEN ÇIKTI (yukarıdaki iki hash bu metinde yazanla BİREBİR AYNI olmalı):

```
51db0c3bd4f2332b25f9c23e17983047  /tmp/wp.tar.gz
05a5f89138f632b7329f1202f2a0553c5f7fe4daf8e4b9ca7ebae9b9466b9e86  /tmp/wp.tar.gz
```

HASH EŞLEŞMEDİYSE İLERLEME — arşiv bozuk ya da değiştirilmiş olabilir; çıktıyı kanıt dosyasına yapıp @devops'a bildir.

İki hash de eşleştiyse arşivi docroot'a aç:

```bash
tar -xzf /tmp/wp.tar.gz -C ~/staging.sutre.store --strip-components=1
ls ~/staging.sutre.store | head -20
```

KANIT BEKLENEN ÇIKTI: `index.php  license.txt  readme.html  wp-admin  wp-content  wp-includes
wp-config-sample.php ...` — WP çekirdek dosyaları listelenmeli.

---

## Adım 2 — Veritabanı ve kullanıcı (cPanel arayüzünde ELLE; terminalden mysql YOK, API token çağrısı BU PAKETTE YAPILMIYOR)

cPanel → **Manage My Databases** (ya da "MySQL Databases") üzerinden:

1. **Veritabanı oluştur**: `spokenla_staging`
   - Not: cPanel önek ekleyebilir (`spokenla_spokenla_staging` gibi) — oluşan TAM adı kanıt dosyasına yapıştır.
2. **Kullanıcı oluştur**: `spokenla_stuser`
   - Parola: "Password Generator" ile üret; **parolayı bu rapora, kanıt dosyasına ve hiçbir dosyaya yazma**.
   - Parolayı yalnız kendi tarafında güvenli parola kasanda (browser vault / parola yöneticisi) sakla.
3. **Kullanıcıyı DB'ye bağla**: "Add User To Database" → `spokenla_stuser` + `spokenla_staging` seç →
   **ALL PRIVILEGES** işaretle, "Make Changes" / "Submit" onayla.
4. Veritabanı türü kontrolü: listede MariaDB/MySQL uyumsuzluk uyarısı görmemen gerek.

KANIT BEKLENEN ÇIKTI: panelde "Added `spokenla_stuser` to `spokenla_staging` with ALL PRIVILEGES" gibi
başarı mesajı + veritabanı listesinde `spokenla_staging` satırının göründüğü ekran görüntüsü/kanıt.

---

## Adım 3 — wp-config.php oluştur (DB bilgileri + salt'lar + utf8mb4 + table_prefix)

```bash
cd ~/staging.sutre.store
cp wp-config-sample.php wp-config.php
```

3a. Salt'ları çek (çıktıyı hemen sonraki düzenlemede kopyalıyorsun):

```bash
curl -s https://api.wordpress.org/secret-key/1.1/salt/
```

KANIT BEKLENEN ÇIKTI: 8 satırlık `define('AUTH_KEY', '…');` bloğu — bunu kopyala, wp-config.php'de
`/* Add any custom values … */` satırının altına yapıştır.

3b. wp-config.php'yi düzenle (Terminal'de nano mevcut: `nano wp-config.php`; Kaydet: Ctrl+O, Çık: Ctrl+X):

Değerleri ŞÖYLE bırak/uygula — sen parolayı elle yazacaksın, dosyanca placeholder:

```php
define( 'DB_NAME',     'spokenla_staging' );       // Adım 2'de oluşan TAM adı yaz (önek dahil)
define( 'DB_USER',     'spokenla_stuser' );        // Adım 2'de oluştur
define( 'DB_PASSWORD', '__DB_PASSWORD__' );        // SEN ELİNLE YAZACAKSIN — RAPORA YAZILMAZ
define( 'DB_HOST',     'localhost' );              // panel ayrı host vermeyorsa localhost kalsın
define( 'DB_CHARSET',  'utf8mb4' );
$table_prefix = 'sutr_';                            // wp_ yerine (sample'daki satırı düzenle)
```

⚠️ DB parolayı sadece wp-config.php'nin İÇİNE yaz; herhangi bir rapor/kanıt/chat verisi olmasın (§4.2).
   wp-config.php zaten Git dışı olacak — ama bu, parolayı raporda gösterme hakkı vermez.

KANIT BEKLENEN ÇIKTI: `cat wp-config.php | grep -E "DB_NAME|DB_USER|DB_HOST|DB_CHARSET|table_prefix"`
komutunda —

```
define( 'DB_NAME',     'spokenla_staging' );
define( 'DB_USER',     'spokenla_stuser' );
define( 'DB_HOST',     'localhost' );
define( 'DB_CHARSET',  'utf8mb4' );
$table_prefix = 'sutr_';
```

(DB_PASSWORD satırını asla kanıta yapıştırma.)

---

## Adım 4 — DISABLE_WP_CRON ekle (panel cron fallback, ADR-002 §4)

wp-cli yok; otomatik ekleme komutu YOK. wp-config.php'yi nano ile aç, salt bloğunun hemen üstüne elle ekle:

```php
define( 'DISABLE_WP_CRON', true );
```

Satırı `/* Add any custom values … */` bloğunun içine ekle (Adım 3'de zaten eklediysen tekrarlama).

KANIT BEKLENEN ÇIKTI:

```bash
grep -n "DISABLE_WP_CRON" wp-config.php
# → define( 'DISABLE_WP_CRON', true );
```

---

## Adım 5 — Cron işini panelde hazırla (kuruluma engel değil, WP kurulunca pasifleşen wp-cron'u tetikleyecek)

cPanel → **Cron Jobs** → aşağıdaki satırı 15 dakikada bir ekle (Tarih/Saat seçim de yapabilirsin; her 15 dk
öneri, sonra WooCommerce + PayTR uzlaştırma işleriyle ayrı satırlar eklenir):

```bash
cd /home/spokenla/staging.sutre.store; /usr/local/bin/php wp-cron.php >> /home/spokenla/staging-cron.log 2>&1
```

⚠️ PHP CLI path'i panelindeki PHP'ye göre değişebilir — cron'a eklemeden once Terminal'de
`which php` çalıştır ve çıkan yolu satıra yaz.
⚠️ Staging ve production cron satırları FARKLI docroot'lara işaret etmeli (çakışma yasağı §3.3).

KANIT BEKLENEN ÇIKTI: Cron Jobs sayfasında yeni satır listesi; WP kurulum bittikten sonra
`wc -l /home/spokenla/staging-cron.log` > 0 (wp-cron tetiklenmiş).

---

## Adım 6 — Tarayıcıda kurulum sihirbazını tamamlasın

`https://staging.sutre.store/wp-admin/install.php` adresini tarayıcıda aç. (Sayfa açılmıyorsa: staging
subdomain DNS zaten doğrulandı — 89.252.180.243; hâlâ açılmıyorsa cPanel → Alan Adları altında staging
subdomain'in docroot'unun `~/staging.sutre.store` olduğunu teyit et.)

Kurulum sihirbazında:

- Site başlığı: `Sutre Staging`
- Kullanıcı adı: `sutre-admin` — **düz `admin` YASAK (anayasa §8.2)**; ayrı yönetici tahminlenebilir ad da YASAK.
- Parola: "Generate password" ile üret, kendi parola kasende sakla; rapora/kanıta yazma YASAK.
- E-posta: kendi e-postanı gir — bu yalnız staging (Faz 2'de SMTP kurulumuyla değişecek).
- "Search engine visibility" checkbox'ı İŞARETLE (staging'in arama motorlarında indekslenmesi YASAK).

KANIT BEKLENEN ÇIKTI: kurulum sonunda "Success!" / "WordPress has been installed" ekranı
+ https://staging.sutre.store ön yüzünün açılması.

Kurulum sonrası Terminal'de admin kullanıcı adını doğrula:

```bash
cd ~/staging.sutre.store && mysql -u spokenla_stuser -p spokenla_staging -e "SELECT user_login, user_registered FROM sutr_users;"
```

(Parola isteyecek — elle yaz; `-p` arkasına değer yazma, history'de kalmasın. Sonra `history -c` koş.)

KANIT BEKLENEN ÇIKTI: `sutre-admin` satırı; `admin` adında başka satır OLMAMALI.

---

## Adım 7 — PHP sürümü: Select PHP Version'da 8.5 seç

cPanel → **Select PHP Version** (ya da MultiPHP Manager):

1. Dropdown'dan `staging.sutre.store` domain'ini işaretle / seç.
2. PHP sürümü: **8.5** — (araştırma kanıtı: WP 7.1 compatibility tablosu 8.2–8.5 aralığını destekliyor).
   Dropdown'da 8.5 yoksa: **8.4** seç ve kanıt dosyasına "8.5 bulunamadı → 8.4 fallback" notu ekle.
3. "Apply"/"Save" et.

KANIT BEKLENEN ÇIKTI: panelde aktif PHP sürümü 8.5 (veya fallback 8.4) görünüyor. Web-tier PHP CLI'den
farklı olabilir; kesin kanıt için geçici bir PHP bilgi dosyası oluştur, çıktıyı aldıktan HEMEN sonra SİL:

```bash
echo '<?php echo PHP_VERSION;' > ~/staging.sutre.store/phpinfo-evidence.php
curl -s https://staging.sutre.store/phpinfo-evidence.php
rm ~/staging.sutre.store/phpinfo-evidence.php
```

KANIT BEKLENEN ÇIKTI: `8.5.x` (veya fallback `8.4.x`) çıktısı; sonra `ls
~/staging.sutre.store/phpinfo-evidence.php` → "No such file" (dosya silinmiş olmalı).

---

## Adım 8 — SSL (AutoSSL / Let's Encrypt)

Staging subdomain'de AutoSSL takibi: cPanel → **SSL/TLS Status** → `staging.sutre.store` satırında
kilit simgesi (yeşil) olmalı; AutoSSL bekleme durumuysa "Run AutoSSL" butonuna bas; ya da cPanel →
**Let's Encrypt SSL** app'inden staging subdomain için sertifika oluştur.

KANIT BEKLENEN ÇIKTI:

```bash
curl -sI https://staging.sutre.store | head -6
```

başında `HTTP/2 200` + `server:` satırı; sertifika hatası (invalid/self-signed) OLMAMALI.

---

## Adım 9 — Kanıtı paketle ve @devops'a ilet

Kanıt dosyana `staging.sutre.store` için:

- Adım 0 boş docroot `ls -la` çıktısı
- Adım 1 hash doğrulama çıktısı + `ls` liste
- Adım 2 panel ekran kanıtı (dba/user ekleme başarı mesajı)
- Adım 3 `grep` çıktısı (DB_PASSWORD YOK)
- Adım 4 `grep DISABLE_WP_CRON` çıktısı
- Adım 5 Cron Jobs listesi ekranı
- Adım 6 WP kurulum success ekranı + sutr_users sorgu çıktısı (user_login yalnız `sutre-admin`)
- Adım 7 phpinfo-evidence çıktısı + silinme teyidi
- Adım 8 `curl -I https://staging.sutre.store` çıktısı

Sonra @devops'a "Paket 15 kurulum kanıt dosyası hazır" bilgisini ver. @devops wp-config Git dışı ve
dosya izinleri / cache kapalı / Admin diğ. güvenlik inişlerini (Paket 16) sırasıyla doğrulayacak.

---

## Yasaklar özeti (bu talimatta)
- Softaculous ile WordPress kurulumu YASAK (sürüm kilidi doğrulaması, ADR-002 §2).
- `~/public_html` içine yazmak YASAK (anayasa §0.6 — spokenlab.com.tr sitesi ezilmez).
- DB parolayı / admin parolasını rapora veya herhangi bir dosyaya yazmak YASAK (§4.2, SECRET_CONSTRAINT).
- Production domain (sutre.store) docroot işlemleri BU PAKETTE YOK.
- wp-config.php'yi Git'e koymak YASAK (ADR-002 §1).
