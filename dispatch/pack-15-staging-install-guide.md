# PAKET 15 — DEPLOY KURULUM TALİMAT SETİ + VERİTABANI KURULUMU (@devops)

## Görev
ADR-002 onaylandı (+ortam tamam: staging subdomain DNS doğrulanmış 89.252.180.243; PHP 5.1–8.5 tüm sürümler mevcut; sürüm hedefi 8.4'e geçiş; API token kurulum). Bu paket staging WordPress kurulumü için SAHİBİN cPanel'de adım adım çalıştıracağı talimat setini hazırlar (@devops bile yapabilir; işletim kanıtı sahip komutuyla geçerlidir — SSH YOK).

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile ADR ve evidence oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/ADR/ADR-002-paylasimli-hosting-deploy.md`
2. `/opt/data/workspace/proje/dispatch/out/evidence-13b-env.txt`
3. `/opt/data/workspace/proje/docs/architecture/version-lock.md`

## Kapsam / görev
1. **WP 7.1 / PHP sürüm beyanı hızlı araştırması** (web araması): "WordPress 7.1 PHP 8.5" resmi beyanı var mı (make.wordpress.org/hosting test beyanları veya WP requirements sayfası)? Ver:
   - Varsa: PHP 8.5 hedef (Salih zaten 8.5 seçti)
   - Yoksa: staging'de **PHP 8.4**'ü Select PHP Version'dan seçilecek — sürüm düşürme komut değil; panelde sürüm dropdown; bu adımı talimat sayfasına EKLE.
2. `docs/operations/staging-install-guide.md` yaz — SAHİBİN cPanel Terminal'DE SIRAYLA YAPIŞTIRACAĞI KOMUTLAR; her komutun altına "PLICATE BEKLENEN ÇIKTI" satırı ekle. İçerik:
   - Adım 0: `cd ~/staging.sutre.store` (docroot boş olmalı — kontrol `ls -la`)
   - Adım 1: WP çekirdeği sürüm-kilitli indir: `curl -sSL https://wordpress.org/wordpress-7.1.tar.gz -o /tmp/wp.tar.gz` (P6 version-lock WP 7.1; SHA/MD5 teyit komutunu da içer — checksum bakım). Arşivi: `tar -xzf /tmp/wp.tar.gz -C ~/staging.sutre.store --strip-components=1`
   - Adım 2: DB oluştur ve kullanıcı — API TOKEN yerine terminalde cPanel uapi komutu YOktur (CLI'de yok) → yerine Terminal'den `mysql` yok; bu adımı **SAHİBİN cPanel arayüzü: Manage My Databases** üzerinden elle yapması için net tarif (DB adı önerisi: `spokenla_staging`, user: `spokenla_stuser`, güçlü parola sahibin gireceği; gösterilme yok, ekranda oluşturup not etmeyecektir) — WALayer not: parola salt kendi tarafında tutulacak (Browser vault önerisi).
   - Adım 3: `wp-config-sample.php` kopyala → `wp-config.php`; DB bilgilerini girecek (sahibin gireceği değer – Terminal encouraged, value '__DB_ placeholder' dosyaca bırakılır); `DB_CHARSET utf8mb4`, `table_prefix sutr_`, salt'lar için: `curl https://api.wordpress.org/secret-key/1.1/salt/`
   - Adım 4: `DISABLE_WP_CRON=true` ekler (satır – ADR-002 panel cron fallback uyumlu)
   - Adım 5: tarayıcıda `https://staging.sutre.store/wp-admin/install.php` adresine gidip kurulum sihirbazını tamamlarsın (başlangıç adımları: site adı, **admin user 'ne sutre-admin (düz 'admin' YASAK anayasa §8.2)**)
   - Adım 6: SSL notu (staging subdomain'deящ AutoSSL Logları; takip Le'ts Encrypt™ SSL app via cPanelde çalıştır)
   - Her adım sonunda sahibin çıktısını rapora yapıştırması notu.
3. AGENTS.md'ye tek satır doc linki YOK — kurulum START olduğunu vurgulama; yerine `docs/operations` dizini açıldıysa yolunu not al.
4. Kanıt: guide dosyası + ADR uyum kontrolü; DNS doğrulama zaten orkestratör'de yapıldı (staging NX düşmüş → şimdi 89.252.180.243 NOERROR).

## Bağlayıcı sınırlar (out-of-scope)
- WordPress çekirdeğine dokunma; cPanel API çağrı YOK (sahibin elle kurulumu).
- Production domain (sutre.store) docroot işi BU PAKETTE YOK.
- Secret/DB parola rapora yazma YASAK.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-15-staging-guide.md` (§16, 200 kelime dip).
