# PAKET 16 — STAGING GÜVENLİK DOĞRULAMA (READ-ONLY) (@tester)

## Görev
P15'te kurulan staging WordPress'i bağımsız doğrula. Şu an Tek kanıt kaynağı sahibin terminal çıktısı — sen bunu read-only ünvano; doğrulanmış kanıtlar rapora kaydet. **Server erişimi MUHTESEM SAHİBİN cPanel Terminal'i yoluyla — SALAH çalıştıracak; hiçbir ssh/sutil bağlantı denemeye.** (SSH yOK — sağlayıcı devre dışı.)

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file evidence-13b + guide). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/docs/operations/staging-install-guide.md` (P15 talimat seti — her adımdan beklenen çıktılar burada)
2. `/opt/data/workspace/proje/ADR/ADR-002-paylasimli-hosting-deploy.md`
3. `/opt/data/workspace/proje/dispatch/out/evidence-13b-env.txt`

## Kapsam / görev
1. **Doğrulama talepleri** — sahibin cPanel Terminal'e koşacağı kontrol seti yaz (@paketin sonunda hazırlanmış COMMAND listesine, sahibin hangi çıktıyı kanıt olarak askılirime yazın):
   - `find ~/staging.sutre.store -name "wp-config.php" -exec stat -c "%a %U %n" {} \;` — dosya izinleri (600 beklen)
   - `cat ~/staging.sutre.store/wp-config.php | grep -c "DB_PASSWORD" ` — parola satır var ama GÖRÜNTÜ asla verilmez (just count)
   - `grep -rn "sutre-admin" ~/staging.sutre.store --include="*.php"` — wp-config salt dışında girlenmemelí
   - `ls ~/staging.sutre.store/wp-content/plugins` — WooCommerce Kırılmamış, boş plugins dizin listesi bekleniyor (kurulum Faz 2)
   - `find ~/public_html -name "wp-config.php" 2>/dev/null` — YOK — public_html'e yazma yasağı kanıtı (spokenlab.com.tr dokunulmamış)
   - `find ~/staging.sutre.store -type d -exec stat -c "%a %n" {} \; 2>/dev/null | head -20`
   - WP REST onay: `curl -s https://staging.sutre.store/index.php?rest_route=/ | head -c 200`
   This list is SENT TO OWNER to execute — sahibin kırığı döner.
2. **Faz 1 güvenlik eşleşme tablosu**: anayasa §8.1/§8.2'den Faz 2 hazırık kontrol listesi — her maddeye "Uygulandı/BEKLEMEDE notu.
3. **Rapor formatını draft**: çıktı kanıtlı PASS'ler.
4. **DNS timezone**: staging WP-Cron dosyası çalıştı kontrolü (`wc -l /home/spokenla/staging-cron.log` çıktısı sahibin croв yazdık).
5. `PRIVACY-DATA-MAP.md`'ye küçük satır ekleme: pitunnel — "staging DB user + şifreler SECRET" (do NOT commit) — ve her ortam için ayrı docroot confirm.

## Bağlılık sınırları (out-of-scope)
- SADECE kanıtsiz organises — hiçbir kurulum/kod/run CHANGES yapma.
- cPanel'e giriş/web kontrol HİÇBİR şey YOK — bize mail at; sahibin kanıtına dayan.
- Secret içerik ASLA yaklaşılma (datum engine parola veya PHP ile username değerleri).
- cPanel API call YOK; wp-config içeriği DOĞRULAMAK İÇİN GREP YOK; yalnız satır sayısı ve değer maskeli olmalı.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-16-staging-verify.md`
§16 format; 150-300 kelime.
