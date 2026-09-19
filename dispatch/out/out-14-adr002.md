# PAKET 14 RAPORU — ADR-002 Paylaşımlı Hosting Deploy Zinciri (@architect)

## Sonuç
- Tamamlanan hedef: ADR-002 (Yol A2 — paylaşımlı cPanel deploy düzeni) taslağı üretildi. SSH'siz cPanel ortamının deploy kanalı (Git Version Control + panel anahtarı/FB PAT fallback), WP kurulum planı (Softaculous yasak, elle Terminal kurulum), PHP sürüm politikası (Select PHP Version hedef 8.4, CLI 8.5.9 WP beyansız — version-lock §6 teyit kapısı), panel-Cron WP-Cron fallback + PayTR §4.5 uzlaştırma düzeni, JetBackup yedek + §8.3 restore-test şartı, LVE limit katmanı (cache asgari + staging cache kapalı + inode bütçesi), 7 satırlık risk tablosu.
- Değiştirilen dosyalar: `ADR/ADR-002-paylasimli-hosting-deploy.md` (yeni, 6.8KB); `docs/architecture/environment-plan.md` §9 'Paylaşımlı cPanel şeması' eklendi; `AGENTS.md` §5 hosting satırları (orkestratör tarafından P13b sonrası yazıldı — bot onay katmanına takıldı; içerik orkestratör diff'inde mevcut ve doğrulandı).
- Veritabanı/ayar etkisi: yok (dokümantasyon).

## Doğrulama
- PASS: ADR-002 diskte (65 satır); environment-plan §9 mevcut; AGENTS.md hosting gerçekleri (SSH yok / deploy kanalı / PHP / MariaDB / public_html koruması) git diff'te doğrulandı.
- FAIL/atlanan: bot'un `out-14-adr002.md` yazımı turn kapanmadan yarım kaldı — rapor orkestratör tarafından bu dosya ile tamamlandı (içerik yukarıdaki kanıtlardan derlendi, yeni iddia eklemeden).

## Risk ve güvenlik
- ADR'de secret değeri YOK; GitHub PAT/panel anahtarı SECRET_REFERENCE_ONLY bandında (§4.2 uyumlu).
- Softaculous yasağı ve public_html'e yazma yasağı (§0.6) ADR metninde bağlayıcı olarak işlendi.

## Açık kapılar
- OWNER_APPROVAL_REQUIRED: ADR-002 onayı (salah); Faz 6 öncesi VPS yeniden değerlendirme ŞART.
- NEEDS_OWNER_INPUT: cPanel Git Version Control anahtar/PAT; staging subdomain oluşturma; Select PHP Version sürüm seçimi onayı.
- LEGAL_REVIEW_REQUIRED: KVKK veri konumu (JetBackup uzak konum + SMTP lokasyonu) — Faz 4'te PRIVACY-DATA-MAP'e işlenecek.
