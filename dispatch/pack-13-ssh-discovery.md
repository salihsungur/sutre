# PAKET 13 — HOSTING SSH KEŞİF VE ORTAM ENVANTERİ (@devops)

## Görev
`spokenla@mt-charon.guzelhosting.com` SSH bağlantısı kur; hosting ortamını anayasa version-lock'a göre envanterle; staging/production docroot yapıları + Mail + PHP + MariaDB + ssh kullanılabilirliğini kanıtla. YALNIZ KEŞİF: WordPress/kurulum/DB oluşturma YOK.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (ssh -V veya read_file). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — §3.1, §3.3, §8.1, §13 Faz 1, §15
2. `/opt/data/workspace/proje/docs/architecture/version-lock.md` (WP 7.1 / WC 11.1.0 / PHP 8.4)
3. `/opt/data/workspace/proje/docs/architecture/hosting-requirements.md`

## Host bilgisi (sahibin verdiyi — kanıt line-of-contact'dan)
- hostname: mt-charon.guzelhosting.com
- user: spokenla
- key: /opt/data/home/.ssh/sutre_cpanel (ed25519, passphrase'siz — kanıt: ssh-keygen -lf fingerprint SHA256:wYI8pi23RvK961zOFjOH2N9/rM0ma1fn+X4kjqxA3UU)
- port: önce 22 dene; reddedilirse raporla (cPanel "SSH Access" sayfasında açık port sahibin panelinden görülebilir, bu makinede bilinmiyor).

## Kapsam / görev
1. **Bağlantı testi:**
   ```
   ssh -i /opt/data/home/.ssh/sutre_cpanel -o StrictHostKeyChecking=accept-new -o ConnectTimeout=10 \
       -o BatchMode=yes spokenla@mt-charon.guzelhosting.com 'whoami; hostname; uname -a'
   ```
   Port 22 reddin/bloke ise `-p 2222` de dene; iki bağlantı kanıtı yoksa RAPORUNA hata + port gerekçesini VE `OWNER_APPROVAL_REQUIRED` notu düş (hosting sağlayılı SSH aktif mi diye sahibinden paneli kontrol etmesi istenir — hangi porte kullanımda, "SSH Access" sayfasında "Deactivate/Activate" durumunu).
2. **Ortam envanteri (bash komut seti; her biri `dispatch/out/evidence-13-env.txt`'de DINLENDIği halde kayıt altına al)**:
   - `whoami; id; pwd; echo $HOME`
   - `uname -a; cat /etc/redhat-release 2>/dev/null; cat /etc/os-release | head -5`
   - `cpanel` sürüm: `cat /usr/local/cpanel/version 2>/dev/null || true`
   - docroot: `ls -la ~/ ~/public_html ~/www 2>/dev/null | head -40`; `ls -la ~/domains 2>/dev/null`
   - PHP: `which php; php -v 2>/dev/null; ls /opt/cpanel/ea-php*/root/usr/bin/php 2>/dev/null` (MultiPHP sürümleri en yell.) — bunlar §3.1 ve version-lock.md Phaser
   - MySQL/MariaDB: `mysql --version 2>/dev/null; ls /opt/cpanel/ea-mysql*/ 2>/dev/null; sqlite, jetaavet filtering`
   - cron: `crontab -l 2>&1 | head -10` (boş olması bekleniyor)
   - WordPress mevcut mu: `find ~/public_html -maxdepth 2 -name 'wp-config.php' 2>/dev/null; ls ~/public_html 2>/dev/null | head -20` (YOK BEKLENİYOR — host yeni; VARSA hiçbir değişiklik yapma, raporda işaret).
   - disk: `df -h ~ | tail -2; quota -s 2>/dev/null | head -5`
   - ssl: `ls -la ~/ssl 2>/dev/null | head -5; ls /etc/ssl/certs 2>/dev/null | head -3`
   - Node (versiyon geçmiş): `node -v 2>/dev/null; git --version 2>/dev/null; which wp-cli wp 2>/dev/null` (wp-cli varsa version çek, yoksa not düş — kurulum Faz'da ekleneği; onay kapısına).
    `- os version / limit: ulimit -a | head -12`
3. **Uzak tarafın dosya yazma testi YAPMA** (bu paket salt-okuma); yalnız `ssh + info`.
4. **sutre.store DNS kontrolü (ikinci kanıt):** bu makineden bağımsız SSH tarafında `getent hosts sutre.store staging.sutre.store || echo kayıt-yok` çalıştır; zone REFUSED mı / vaniller A missing mi anla ve rapora yaz (orchestrator buldus zaten REFUSED — tekrar teyit).

## Bağlayıcı sınırlar (out-of-scope)
-**WP install / DB create / cPanel API call / config değişiklik YOK.** (anayasa §0.6: kurulum stajlı, aksine ayrı paket)
- Command history veya shell log calısması — yaln данных SöRİŞTİRİlecek kanıt; secret/hosts'a özel değer logulama (hostname masking yok — sabit bilgi OK).
- SSH-BASH herşey Timouts ≤15s (hostun hangisi up olmayabilir).
- yeniden başlatma/restart / kurulum / installed package trying. YOK.

## Kanıt / kapılar
- Kanıt: `dispatch/out/evidence-13-ssh.txt` (hata/host bağlantı logları), `evidence-13-env.txt` (envanter komutun sonuç her biri), `evidence-13-dns.txt` (remote DNS check).
- Kapılar: coding/PHP sürüm uzlaşması version-lock ile NOT edilir; uyumsuz ba existing PHP (ör. sadece 8.2) sahibi ile değiştirimiz (cPanel MultiPHPManager'dan sahibin 8.4'ü seçmesi gerekebilir — ona adım yaz).

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-13-ssh-discovery.md`
Anayasa §16 formatı; SADECE keşif, kurulum riskyologies; Rapor 150-350 kelime.
