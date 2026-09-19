# PAKET 12 — Hosting Keşif Raporu (sutre.store) — @devops

Tarih: 2026-09-12 UTC. Kapsam: TEXT-KEŞİF; WP/Nginx kurulum YOK (P14/salı).

## Sonuç
- Tamamlanan hedef: SSH aracı doğrulaması, key varlık/dosya izni kontrolü, DNS keşfi, staging/production plan şablonu belgelendi.
- Değiştirilen dosyalar: yalnız bu rapor.
- Veritabanı/ayar etkisi: yok (yalnız okuma/sorgu).

## Kanıt (gerçek çıktılar)
- `ssh -V` → OpenSSH_10.0p2 Debian-7+deb13u4, OpenSSL 3.5.6 — SSH bağlantı aracı HAZIR.
- `/opt/data/attachments/sutre.pub` var; `ssh-keygen -lf` → `2048 SHA256:zXTK/FMajUoIwQqce4FyIoxKIv8SVCGoSma9WyYUQbU no comment (RSA)` — fingerprint, pakette beklenen değerle BİREBİR EŞLEŞTİ.
- Dosya izni: `-rw-r--r-- hermes hermes` (644). PUBLIC key için sorun değil; private key gelirse MUST 600 (rapor rozetiyle notlandı).
- Private key durum: `grep -rl "sutre" ~/.ssh/` → eşleşme YOK; `~/.ssh` boş. Private key BU MAKİNEDE YOK — SSH bağlantısı şu anda denemez (denenemez); paket gereği beklemeye alındı.
- DNS: `getent ahosts sutre.store` ve `www.sutre.store` / `staging.sutre.store` → `rc=2` (kayıt yok) → sutre.store henüz host'a NS/A kaydıyla bağlı değil. cPanel DNS girişine bağlı yönlendirme hazırlığı bu bulguya göre planlanır.

## Plan (hosting bilgisi gelince uygulanır)
- Bağlantı şablonu: `ssh -i /opt/data/.ssh/<key> -o StrictHostKeyChecking=accept-new -p <port> <user>@<host>` (private key `/opt/data/.ssh` altına, chmod 700 dizin / 600 dosya).
- Production docroot: cPanel default `public_html`; staging: `staging.sutre.store` ayrı docroot (domain için NS host'ta işaretlendiğinde eklenecek). WP 7.1 / WC 11.1.0 / PHP 8.4 sürüm kilidi version-lock.md'den teyit edilecek (kurulum anı kapısı).
- P10 local compose → hosting staging taşıma YALNIZ plan; bu pakette ikili iş yok.

## Doğrulama
- Çalıştırılan testler: `ssh -V`, `ssh-keygen -lf`, `ls -la attachments`, `grep -rl sutre ~/.ssh/`, `getent ahosts`, `python3 socket.gethostbyname` (3 hostname).
- PASS: SSH araç seti, pub key fingerprint eşleşmesi, keşif komutlarının makinede mevcutluğu.
- FAIL/atlanan: SSH bağlantı denemesi (private key yok — beklenen durum); DNS sorgusu (domain NS'siz → NEEDS_OWNER_INPUT).

## Risk ve güvenlik
- Secret/kişisel veri etkisi: yok — host adı/user bilgisi sahibin iletişimine kadar bilinmiyor; gelmesi hâlinde raporlarda maskeli yazılır (§15, §16 formatı).
- Ödeme/fiyat/stok etkisi: yok.
- Geri dönüş adımı: yalnız okuma/keşif; geri alınacak değişiklik yok.

## Açık kapılar
- NEEDS_OWNER_INPUT (sahibin vereceği eksik bilgi listesi — raporu kilitleyen öğeler):
  1. SSH host (cPanel hostname, domain veya IP) — rapor yazımında maskeli kullanacağım.
  2. SSH port (default 22 değilse).
  3. cPanel_USERNAME (SSH user, tipik olarak cPanel user ile aynıdır).
  4. Private key claim: ya private key'i güvenli `/opt/data/.ssh/` altına koyup haber ver, ya cPanel'de "Download key: private" ile indirdiğin dosyanın yerini bildir (key çiftinin private yarısı şu an host tarafında).
  5. staging subdomain docroot tercihi: `staging.sutre.store` ayrı docroot/container — cPanel'de oluşturulduğunu onayla ya da bana hosting kapsamını bildir.
  6. Production docroot default `public_html` onayı.
- OWNER_APPROVAL_REQUIRED: host bilgisi iletimi sonrası İLK SSH bağlantısı (kesif amaçli bile, yetki beyanı; yoksa bağlanılmaz).
- LEGAL_REVIEW_REQUIRED: yok bu pakette.
- Sonraki en küçük güvenli adım: sahibin yukarıdaki 1-4. maddeleri vermesi → chmod 600 key → ilk SSH bağlantısı + ortam envanteri metni (bağlantı başarısına kadar kurulum yok).
