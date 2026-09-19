# Sutre — Çalışma Notu (2026-09-15)
## Çevresel Kısıtlar (Hermes preview pane)
- Playwriter'ın preview pane üzerinden cPanel Terminal'e klavye yazma gönderilemiyor (Brave preview panosu sınırı). Komut yapıştırma çalışıyor, klavye yazmıyor.
- SSH erişimi yok (sağlayıcı devre dışı) — sunucuda manuel komut gerekiyor.
## Çalışan Yöntemler
- cPanel Terminal'e kullanıcı elle komut çalıştırır; çıktıyı buraya yapıştırır.
- Preview pane ile cPanel login oturum oku (read-only gezinme çalışıyor).
## Hızlı Referans
- Staging docroot: /home/spokenla/staging.sutre.store (WP 7.1)
- mu-plugins: /home/spokenla/staging.sutre.store/wp-content/mu-plugins/
- Repos: /home/spokenla/repositories/sutre (git clone)
- WP SMTP: güzelhosting (mail() kapalı) — harici SMTP şart
