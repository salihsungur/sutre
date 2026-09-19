# ADR-002 — Paylaşımlı Hosting (cPanel) Deploy Zinciri — Yol A2 (TASLAK)

- Durum: TASLAK — SAHİP ONAYI BEKLENİYOR (OWNER_APPROVAL_REQUIRED)
- Tarih: 2026-09-12. Sahip: @architect.
- İlişki: ADR-001'in koşullu ikinci seçeneği **A**'yı bağlar; B (Docker/VPS) FAZ 6'YA ERTELENDİ (Salih kararı: bütçe yok, VPS geçişi canlıya çıkış öncesi yeniden değerlendirilecek).
- Bu ADR kurulum komutlarını İÇERMEZ (sonraki devops paketi). Yalnız düzen/karar.

## Bağlam (kanıtlı)
- SSH KAPALI: sağlayıcı politikası; P13 kanıtı — port 22 `Connection refused`, 2222 `timed out`, yalnız 2083/2087/443 (cPanel) açık. 2026-09-12 destek onayı (`out-13-ssh-discovery.md`).
- P13b terminal envanteri (`evidence-13b-env.txt`, sahibin elle çalıştırdığı): PHP MultiPHP 8.3 paketi + CLI 8.5.9; MariaDB 11.8.8 (WP gereksiniminin üstünde ✓); git cPanel path-bin'de ✓; **wp-cli YOK**; sudo YOK; crontab boş; WP kurulu DEĞİL (`wp-config.php` bulunamadı).
- spokenlab.com.tr primary → `~/public_html` DOLU (mevcut statik site — EZİLMEMELİ, §0.6).
- sutre.store → ayrı docroot olarak bağlanacak (`~/sutre.store`); staging ayrı subdomain + ayrı docroot.
- Limitler (panel Sunucu bilgisi): Giriş Süreçleri (LVE) 10/10 max, RAM 2 GB, Inodes 249k.

## Karar
### 1. Deploy kanalı — cPanel Git™ Version Control
- Deploy = cPanel Git Version Control, GitHub deposundan çekme.
- Uzaktan erişim iki alt yol: (a) cPanel SSH Access'e anahtar bırakma — cPanel'in kendi key yönetimi; (b) GitHub HTTPS endpoint + Personal Access Token. SEÇİM: (a) öncelikli; (b) fallback. Token gerekirse SECRET_REFERENCE_ONLY — token değeri hiçbir repo/raporda yazılmaz (§4.2, §2.2).
- P13b'de SSH banner doğrulanamadığı için (a) yolunun cPanel "SSH Access" sayfasından panel anahtarıyla çalışması beklenir; deploy bu yüzden "Create/Pull" (pull-only) yürütmeye indirgenir, push-from-server yok.
- `wp-config.php`, `.env.*`, uploads, cache, yedek/DB dökümü Git DIŞI (§2.2).

### 2. WP kurulum planı
- Staging docroot: `staging.sutre.store` → `~/staging.sutre.store`; production `sutre.store` → `~/sutre.store`.
- Veritabanı: cPanel MySQL API Token ile `UAPI Mysql::create_database/create_user` (sahibin token'ı ile) veya Terminal — her ortam AYRI DB + AYRI admin kullanıcı.
- **Softaculous ile WP kurulumu YASAK** (anayasa §3.2 eklenti politikası + tam kontrol şartı: sürüm kilidi doğrulaması Softaculous'un otomatik/vendor sürüm seçimine muhtaç olamaz).
- Kurulum elle: wp-cli yok → Terminal üzerinden sürüm-kilitlenmiş WP çekirdek arşivi çekilir + wp-config elle düzenlenir; ya da wp-cli.phar'ın kullanıcı dizinine alınması sağlanır (sonraki devops paketi kararı).

### 3. PHP sürümü
- Staging domain: cPanel "Select PHP Version" ile sürüm seçimi → hedef 8.4; CLI 8.5.9 tespit edildi ama WP 7.1'in PHP 8.5 resmi beyanı YOK → kurulum anında version-lock §6 (P6) TEKRAR TEYİT kapısı; uyumsuzlukta 8.3 MultiPHP paketi fallback.

### 4. Cron (SSH yok → sistem cron fiziksel olarak yok)
- WP-Cron fallback: panel **Cron İşleri** (cPanel Cron Jobs, mevcut ✓) ile `wp-cron.php` düzenli tetikleme + `wp-config.php`'de `DISABLE_WP_CRON=true` (trafik bağımlı çift tetikleme önlenir; sistem cron eşdeğeri panel cron olur — anayasa §3.3 ruhunun paylaşımlı karşılığı).
- PayTR günlük uzlaştırma işi (§4.5) + callback retry/dead-letter da panel cron'dan kurulur; her iş tanımlı log dosyasına yazar, başarısızlık §11 izlemede görünür.
- Çakışma kontrolü: staging ve production ayrı docroot/DB'ye işaret edecek şekilde ayrı komut satırları (§3.3 çift tetikleme yasağı).

### 5. Yedek
- JetBackup 5 panelde mevcut (host belgesi) + anayasa §8.3: otomatik, günlük artımlı + periyodik tam, şifreli, ayrı konum hedefi — restore testi staging'de kâğıt üstü DEĞİL, çalıştırma kanıtlı yapılır. RPO/RTO hâlâ OWNER_APPROVAL_REQUIRED.
- JetBackup arşivleri Git'e girmez (§2.2).

### 6. Limit katmanı (riskleri kod/kurulum'a işler)
Paylaşımlı LVE limitleri (10 giriş süreci / 2 GB RAM / 249k inode) WooCommerce + cache + staging'i aynı hesapta taşır:
- Production'da onpage cache ASGARİ tutulur: WooCommerce uyumlu basit page-cache yeterli. Ağır önyüz optimizasyon yığınları (ör. W3 Total Cache'in aggressive preload/aggregate katmanları) yasak DEĞİL ama VARSAYILAN KAPALI gelir; açılması limit izlemesiyle (entry process/IO uyarıları) koşullandırılır.
- Staging'de cache devre dışı (test doğruluğu §12).
- Inodes bütçesi: staging + production + JetBackup arşivleri 249k altında tutulur; backup arşiv sayısı politikasıyla sınırlanır.

## Alternatifler (neden reddedildi)
- SSH wp-cli/Git push deploy: sağlayıcı politikası nedeniyle imkânsız (P13 kanıtlı) — YOK.
- Docker/VPS (ADR-001 B): bütçe kararıyla ertelendi; bu ADR'yi geçersizleştirmez — Faz 6 şartına bağlı.
- sFTP-only dosya deploy: Git Version Control'den daha az tekrarlanabilir; yalnız acil bandı (break-glass) olarak not edilir.

## Risk tablosu
| Risk | Etki | Önlem |
|---|---|---|
| Elle Terminal kurulumu (sahip elle, tarif sayfası) | Deploy gecikmesi / insan hatası | Tek tek adımları cmd-talimat sayfası; her adımın çıktısı kanıt dosyasına |
| wp-cli yok | Kurulum/ maintenance zorluğu | wp-cli.phar kullanıcı dizinine alma (developer paketi) veya sürüm kilitlenmiş arşiv + elle wp-config |
| Softaculous cazibesi (hatalı oto-sürüm) | Sürüm kilidi bozulması | Kurulum talimatında açık uyarı; §3.2a |
| Deploy pull-only + token/panel anahtarı | Token sızma riski | SECRET_REFERENCE_ONLY; token repoya girmez |
| LVE 10/10 + 2 GB (checkout spike + cache + staging) | Callback gecikmesi, 503 | Cache asgari, staging ayrı docroot, yük testi staging'de; Faz 6'da VPS yeniden değerlendirme ŞART |
| Panel cron sessiz kalırsa (alarm yok) | Uzlaştırma/§4.5 kayması | Log dosyası + §11 izleme; cron çıktı dosyası boş kalamaz maddesi |
| public_html'e dokunma riski | Mevcut site bozulması | sutre.store ve staging ayrı docroot; public_html'e yazma YASAK (§0.6) |

## Açık kapılar
- OWNER_APPROVAL_REQUIRED: bu ADR'nin onayı; Faz 6 canlıya çıkış öncesi VPS'in yeniden değerlendirilmesi ŞART.
- NEEDS_OWNER_INPUT: cPanel "SSH Access" sayfasından panel anahtarı durumunun teyidi; GitHub PAT oluşturma (yol (b)'ye geçilirse).
- LEGAL_REVIEW_REQUIRED: KVKK veri konumu — host Türkiye'de (mt-charon, 89.252.180.242 TR bloğu) olsa bile JetBackup uzak konumu ve SMTP sağlayıcısının veri lokasyonu PRIVACY-DATA-MAP'e yazılmalı; bu ADR'de "Türkiye ✓" SADECE varsayım olarak not edilir, kanıt değildir.
