# PAKET 14 — ADR-002: PAYLAŞIMLI HOSTING PAYTR DEPLOY ZİNCİRİ (Yol A) (@architect)

## Görev
Salih kararı gündemde: VPS'e şimdi para harcanmayacak → paylaşımlı hosting'ten devam. SSH YOK (sağlayıcı politikası). Bu ADR, paylaşımlı hosting kısıtlarındaki deploy/operasyon düzenini taslak olarak netleştirir ve ADR-001'in B seçeneğini değil, A2 ("yönetilen cPanel + Git Version Control deploy") stratejisini bağlar.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile ADR-001 + AGENTS oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/ADR/ADR-001-kurulum-stratejisi.md`
2. `/opt/data/workspace/proje/AGENTS.md` (Ortam Durumu — güncel bulgular)
3. `/opt/data/workspace/proje/dispatch/out/evidence-13b-env.txt` + `out-13-ssh-discovery.md`
4. hosting-requirements.md

## Kapsam / görev
1. `/opt/data/workspace/proje/ADR/ADR-002-paylasimli-hosting-deploy.md` yaz:
   - **Bağlam:** SSH kapalı (destek 2026-09-12); hosting paylaşımlı cPanel; sutre.store domaine addon/subdomain docroot olarak bağlanacak; spokenlab.com.tr primary, public_html dolu.
   - **PPT Kanal Decision:** deploy = cPanel Git™ Version Control (GitHub HTTPS endpoint or SSH anahtarını cPanel panele bırakma — cPanel kendi key yönetimi; ya da HTTPS + Personal Access Token, token saklama SECRET_REFERENCE_ONLY)
   - **WP Kurulum planı:** staging subdomain `staging.sutre.store` docroot: `~/staging.sutre.store` — cPanel Terminal (sahibin elle çalıştıracak) veya API Token (Mysql::create db) — Db ve WP Admin kurulumu Softaculous YASAK (anayasa §3.2a eklenti politikası + tam kontrol şartı) → elle wp-cli veya Terminal üzerinden kurulum talimatı planı.
   - **PHP:** Select PHP Version'dan staging domain'e 8.4 (veya 8.5 WP beyanına göre) — version-lock P6 yeniden teyit görevi.
   - **Cron:** SSH YOK → sistem cron yok → WP-Cron fallback [DONE workaround]: panel Cron İşleri arayüzü (cPanel Cron İşleri var!) ile `wp-cron.php` VEYA UAPI Cron. Ayrıca PayTR uzlaştırma işi (§4.5) için cron gereği bu panel üzerinden kurulacak plan.
   - **Yedek:** JetBackup 5 panelde var (host zaten bundan ok) + anayasa §8.3 otomatik yedek altı-beş gün.
   - **Limitler:** Giriş Süreçleri 10/10 max, RAM 2GB, Inodes 249k — bu kadarda paylaşımlı hosting'te WooCommerce + cache + staging birden fazla sirkülasyon riski → staging'de W3 Total Cache yerine hasar'ı yazan sınırlar (Sunucu bilgi'ten).
   - **Risk Tablosu:** deploy gecikmesi (Terminal'de elle komut), tek tek sürecin postfixpush limitleri, Softaculous teması.
2. `/opt/data/workspace/proje/docs/architecture/environment-plan.md`'e "Paylaşımlı cPanel şeması" bölümü ekle:
   - sutre.store → ~/sutre.store (docroot)
   - staging.sutre.store → ~/staging.sutre.store (ayrı DB, ayrı admin kullanıcı)
   - WP-Cron fallback, sistem cron yerine panel cron.
3. AGENTS.md §5'e tek satır bağlantı notu düş.

## Bağlayıcı sınırlar (out-of-scope)
- Yalnız 2 dosya (ADR-002 + environment-plan güncellesme) + AGENTS tek satır.
- Kurulum komutları bu pakette YOK (sonraki devops paketi).
- VPS kârı/İyi planı — bu ADR'de YOK (sahip karar: bütçe yok).
- Provider model herry yok; secret değer yazılmaz.

## Kanıt / kapılar
- Kanıt: ADR + plan dosyaları diskte; AGR "TASLAK — SAHİP ONAYI BEKLIYOR" bandı açık.
- Kapılar: OWNER_APPROVAL_REQUIRED (ADR onayı + Faz 6 canlı VPS tekrar değerlendirme nso), LEGAL_REVIEW_REQUIRED (hosting KVKK veri konumu — Türkiye ✓ varsayımı yinede not düş).

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-14-adr002.md`
§16 format; 200-400 kelime.
