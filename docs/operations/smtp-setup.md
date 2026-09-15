# Sutre Staging — E-posta (SMTP) Kurulum Kılavuzu

PAKET 20 (Faz 2/4) · 2026-09-15 · yazan: @coder (bot) · okunacak kapı: LEGAL_REVIEW_REQUIRED (Faz 3 Doğrulama e-postaları bağlanana kadar test sınırlıdır)

## 0. Durum ve gereklilik

- Hosting güzelhosting paylaşımlı cPanel'de PHP `mail()` fonksiyonu KAPALI/önemsiz kabul edilir (kanıt: staging'de e-posta gönderimi çalışmıyor; WP şifre sıfırlama/posta çıkmıyor). **Harici SMTP şart.**
- Anayasa §3.3: e-posta servisi test/sandbox modu desteklemeli; §4.2: secret (SMTP API key) Git'e/DB düz metin ayara yazılmaz → `SECRET_REFERENCE_ONLY`.
- Yöntem sıralaması: (a) Brevo hesabı → (b) WP SMTP eklentisi (WP Mail SMTP) → (c) .htaccess/wp-config düzeltmeleri gerekmez, mail() KAPALI olmalı → (d) firewall/bağlantı testi.

## 1. Brevo kayıt talimatı (Sahip elle)

1. https://www.brevo.com → **Sign up free** → r回落bize.com işletme e-postası ile kayıt (MFA'yı etkinleştir — anayasa §8.1).
2. Doğrulama e-postasını onayla; gönderici alan adı olarak `sutre.store` doğrulamasını yap:
   - Brevo → Senders, Domains & Dedicated IPs → **Domains** → "Add a domain" → `sutre.store`
   - Verdiği **DKIM (TXT)** ve **DMARC** kayıtlarını güzellhosting cPanel → Zone Editor'e ekle; Brevo'da "Verify" yeşile dönmeli (SPF de önerilir).
3. Brevo → SMTP & API → **SMTP** sekmesinden: SMTP Server, Port (587), User (genelde mail adresin), **SMTP key** (SECRET — aşağıda).
4. Sebep: paylaşımlı cPanel `mail()` IP itibarı düşük (spam klasisifi) → relay doğrulanmış alan ile顺便藏. Test günlük ücreti ücretsiz paketle sınırlıdır (günde ~300 mail; Canlı Faz 6'da limit tekrar değerlendirilir).

## 2. WordPress SMTP konfigürasyonu (Sahip elle — WP-admin)

1. WP-admin → Plugins → Add New → **WP Mail SMTP** (by WPForms) → Install → Activate.
2. Settings → WP Mail SMTP → **Mailer: SMTP**:
   - SMTP Host: `smtp-relay.brevo.com`
   - Encryption: **TLS**, Port: **587**
   - SMTP Username/Password: Brevo SMTP sekmesindeki değerler. Password = SMTP key (SECRET_REFERENCE_ONLY — ekrandan girilir, chat'e/Git'e yazılmaz).
   - "Return-Path" ON; "Mailer Type" forced.
3. Save, sonra **Email Test** sekmesi → kendi gelişme adresine test yolla. Test uye:

```
Test To: <sahibin e-postası>
Subject: Sutre SMTP Test
```

## 3. wp-config / PHPMailer (alternatif — kod ile)

Eğer WP Mail SMTP yerine PHPMailer doğrudan istenirse (`plugins-load-overrides` veya mu-plugins küçük bridge):

```php
// wp-config.php — değerler ENV / cPanel env'den okunur (§3.3: ortam değeri koddan ayrı).
define( 'WP_MAIL_SMTP_HOST', getenv( 'SUTRE_SMTP_HOST' ) );
define( 'WP_MAIL_SMTP_PORT', getenv( 'SUTRE_SMTP_PORT' ) ?: 587 );
define( 'WP_MAIL_SMTP_USER', getenv( 'SUTRE_SMTP_USER' ) );
define( 'WP_MAIL_SMTP_PASS', getenv( 'SUTRE_SMTP_PASS' ) ); // SECRET_REFERENCE_ONLY
define( 'WP_MAIL_SMTP_TLS',  true );
```

- `SUTRE_SMTP_PASS` değerini cPanel → **os.environ / PHP-FPM pool env** veya .htaccess `SetEnv` üzerinden ver. Repo'ya .env koymak YASAK. Secret Git'e girmemeli.
- Kod örneği PHPMailer'a dokunmak yok: WP Mail SMTP eklentisi başına tercih. Çözüm: "small bridge" mu-plugin hâlinde anayasa §3.2 kaydında saklanır.

## 4. Firewall / bağlılık kontrolü (hosting ✓/✗ passage'ı)

Güzellhosting cPanel → Terminal (sahip elle):

```bash
# cPanel'de PHP-fpm SIT 587 outbound açık olmalı:
php -r '$r=@fsockopen("smtp-relay.brevo.com",587,$e,$s,8); echo $r? "PORT587_OK\n":"PORT587_FAIL($s)\n";'
```

- `PORT587_OK` → SMTP relay şekil; WP test e-postası bekleniyor.
- `PORT587_FAIL` → destek ticket'ı açılacak: "Paylaşımlı hosting'de outbound TCP 587'yi açınız (SMTP relay gerekli)". mail() fonksiyonu kalmaz — kapatınca mağazadaki loglara mani olur.
- Sonra WP-admin → WP Mail SMTP → Email Test → log: `200 OK` / ` SMTP Error` no dikkat.

## 5. Kabul kriteri

- [ ] DKIM + SPF + DMARC Brevo'da "Verified" (yeşil).
- [ ] WP Mail SMTP Email Test: `Success/E-posta gönderildi` kanıt dosya.
- [ ] WP şifre sıfırlama e-postası başarıyla geliyor.
- [ ] SMTP key SHA256 hash'i `SECRET_REFERENCE_ONLY` kapısına kaylanan olarak PROJECT_INPUTS.md'ye kaydedilir (değer asla yazılmaz).

## 6. Açık kapılar

- LEGAL_REVIEW_REQUIRED: sipariş onay e-postası içeriği (mesafeli satırı bilgisi) Faz 4 hukuki metin bazına bağlı — bu belge yalnız altyapı.
- SECRET_REFERENCE_ONLY: Brevo SMTP key değeri. Sahip, değeri elle WP panel + WP Mail SMTP "SMTP Password" alanına girer; bot asla secret'a erişmez.
- Gecici test e-postalarında MFA ve şifre gücü sınaması §8.1'e bakılır.
