# PAKET 20 — FAZ 2/4: SUTRE CHILD THEME FULL REDESIGN (MOBILE-FIRST, %100 TÜRKÇE) (@coder)

## Görev
Staging WP 7.1 + WC 11.1.0 üzerine tam görsel redesign:
1. `sutre-child` child theme (Twenty Twenty-Five üstü) — Cormorant Garamond + Jost fontları (Google Fonts CDN), Sutre paleti
2. Sayfa içerikleri %100 Türkçe: Ana Sayfa / Shop / Ürün detay / Checkout / My Account / Footer
3. Mobil-öncelikli layout (anayasa §9), desktop hataları minimal
4. SMTP talimatı ayrı doküman: `docs/operations/smtp-setup.md` (hosting mail() KAPALI kanıtlı — harici SMTP şart)
5. Sahibin checkout Login block'u CHECKOUT'ta görünmeye devam etmeli (P17 kural)

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file tasarım kitini oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/design-system.md` (marka kit — font pairing + palet + CDN linkleri + ölçüler)
2. `/opt/data/workspace/proje/ADR/ADR-003-magaza-sema.md` (onaylı şema: Kategori Giyim→Kadın→Şal)
3. `/opt/data/workspace/proje/dispatch/out/out-17-woo-install.md` (P17 kanıt)
4. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` §3.1-3.3, §5, §9

## Erişim / sistem kriterleri
Sahibin yetki verbali (2026-09-15): "docker'daki agent'ına local bilgisayarı言葉 içi tam erişim; mont /host-home; Playwriter+Brave; GitHub"
- **Kullanacaksın erişim: (a) /host-home (Mac dosyalar; bunu local repo çakışması için kullanma—staging sunucuda değil Mac'te)').'
   (b) Sunucuda staging: SSH yok. WP-admin → Tools → Plugin/Theme File Editor (%100 güvenli yol: .htaccess + disallow file edit true Islam) — sahibin WP-admin'i açık olabilir; seninPLUGIN dosyalama erişimi yok; bu pakette kod üret, sahibin elle yapışır.
   (c) **PLAYWRITER_HOST/TOKEN env vars konfigre** (konteyner env içinde zaten PATH — build): eğer host.docker.internal:19988'a CURL OK dönerse Sen (kısmen) uzun süre uzak browser komutlarını koşabilirsin:
     `npx playwriter -s 2 -e "await page.goto(...)"` → sahibin Bravo'da canlı çalışır!
   (d) GitHub: https://github.com/salihsungur/suture.git (deploy key ekli) — push/pull erişim via GIT_SSH_COMMAND sutre_deploy key
   (e) staging theme files: sunucu dosyasında FTP/SSH yok — Ama hosting cPanel terminal'de git pull command'renni:
     **cPanel → Git Version Control → "Update from remote" button (sahibin elle)**
     → Bu nedenle temaファイル yazımını **local repo → git push → Salih cPanel "Update"** akışı ZORUNLU;
- Bu redesign'da **cpanel'ün Git Version Control kullanım çok iyi WORK, temafı github push layipel — sahibin tek button update.**

## Kapsam / görev
1. **Child theme dosyaları** `/opt/data/workspace/proje/theme/` dizininde üret:
   - `style.css` (child theme header + fonts)
   - `functions.php` (enqueue Cormorant+Jost, theme support, WooCommerce hooks)
   - `style.css` palet değişkenleri (CSS custom properties: --bone/--ink/--silk/--marine/--whisper)
   - `page-templates/` klasörü: `home.php`, `shop-page.php`, `product-detail.php` (mobil-first)
   - `block-templates/` klasörü: `checkout.html` (P17 sahibin Login block yapısını KORUP devam ettir)
   - `fonts.css` (Google Fonts CDN linki kod bloğu — kit'ten hazır `preconnect` + family=...;)
   - custom CSS mobile: 44×44px buttons, 32-40px logo, tabular padding
2. **İçerik çevirisi Türkçe (%100):** Ana sayfa hero tagline: "Deniz ve dokumanın zarafeti" gibi; ürün başlıkları; k oben; anaorain; category tablosu **Giyim → Kadın → Şal** kısmı (Salih'in ADR-003'te onaylı şema; fiili "Şal" kategorisi WooCommerce'de otomatik grow'da)
3. **Playwriter kontrol (Sahibin Brave):** `PLAYWRITER_HOST=host.docker.internal` + `PLAYWRITER_TOKEN` env ile test   - Sayfada tasarım görüntüüler; mobile preview emülatör testi, screenshot proofs.
4. **SMTP setup guide** `docs/operations/smtp-setup.md`: (a) Brevo signup talimatı, (b) .htaccess/mail config, (c) SMTP relay wp-config (PHPMailer SMTP), (d) firewall check (mail fonk sign-off) — Send test rails
5. **Kanıt / evidence** files:
   - `theme/main.css` içerik dosyası, `functions.php` etc. yazarken `write_file` verified:true kanıt.
   - Playwriter testponde: staging.sutre.store/shop.png, ürün sayfa screenshot, out-20 evidence-20.txt
6. Git işlemi: commit + push (bot kağıt — sahibin hiçbiri elle gerekli yapmasa; sadece cPanel'de Update from Remote sağ)
   - **Commit:** `feat(theme): Sutre child theme full redesign (Cormorant/Jost/palet/Türkçe/mobil-first)`
   - Push origin main (sutre_deploy key)
7. **Rapor**: `/opt/data/workspace/proje/dispatch/out/out-20-woo-theme-redesign.md` (§16 format, 200-350 kelime)

## Bağlılık sınırlar (out-of-scope)
- PayTR entegrasyonu YOK (Faz 3) — bu paket YOK
- Ürün/stock yükleme YOK (sahibin elle — Faz 2/5)
- cPanel'e doğrudan API çağrı YOK (Sahibin "Update from remote" elle — ya da cPanel cron zamanlı pull)
- wp-admin'deyken Login block'un değiştirme YASAK (P17 Karar: sahibin checkout login form koruma)
- public_html dokunma YASAK
- Secret yazma YASAK (SMTP key gibi bilgiler SECRET_REFERENCE_ONLY)
- Provider/model override YOK; HER ONAY GERİ DÖNÜŞÜM kanıt glance.

## Kanıt / kapılar
- Kanıt: child theme dosyalar diskte `theme/` altında + git push commit SHA + Playwriter screenshot kanıtı
- Kapılar: OWNER_APPROVAL_REQUIRED (child tema aktive edilip /shop sayfa görünümü kanıt; sahibin onayı)
- LEGAL_REVIEW_REQUIRED: KDV (henüz boş), kargo rakamları; SMTP e-posta gönderimi (Faz 3 bağlana)
