# PAKET 17 — FAZ 2/1: WOOCOMMERCE 11.1.0 STAGING KURULUM (KANITLI, SAHİP YÜRÜTÜMLÜ) (@coder)

## Görev
Faz 2 başlangıcı: staging WP 7.1'in içine WooCommerce **WC 11.1.0** (P6 sürüm kilidi) kur. SSH yok → kurulum **sahibin cPanel Terminal komutlarıyla** yürütülecek — bu paket o talimat setini üretir (@coder, sen sunucuya erişmezsin; talimati dokümanı yazarsın).

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile version-lock + ADR oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/docs/architecture/version-lock.md` (WC 11.1.0 + PHP 8.5 uyumlu beyan)
2. `/opt/data/workspace/proje/ADR/ADR-002-paylasimli-hosting-deploy.md`
3. `/opt/data/workspace/proje/docs/operations/staging-install-guide.md` (P15 — talimat formâtına uy)
4. `/opt/data/workspace/proje/dispatch/out/evidence-13b-env.txt`

## Ön koşul kanıtları
P15+P16: WP 7.1 staging canlı (`staging.sutre.store` HTTP 200, REST JSON, sutre-admin login, PHP 8.5.9, MariaDB 11.8.8, wp-config 600, cron mtime liveness). Kullanıcı yetkili: Sparih cPanel Terminal — sen web UI kullanmak isessor da (Softaculous yasak §3.2).

## Kapsam / görev — doküman `docs/operations/woocommerce-install-guide.md`
1. **WooCommerce 11.1.0 sürüm kilidi doğrulama** — wp.org plugin API'den anlık tarama (webden); önce kurulan WP 7.1'e "Minimum WC 11.1" versiyonlu zip'ini: `curl -sSL https://downloads.wordpress.org/plugin/woocommerce.11.1.0.zip -o /tmp/wc.zip` + MD5/SHA256 beklenen çıktıyı办事处 ya da wp.org checksum services ile teyit metni
2. **Kurulum adımları sahibin Terminal'inde** (wp-cli yok, PHP 8.5 CLI sahibin klima):
   - `cd ~/staging.sutre.store`, `unzip /tmp/wc.zip -d wp-content/plugins/`
   - wp-admin → Plugins → WooCommerce **Activate**
   - wp-admin'de setup wizard: **Türkiye** región/TRY, timezone Europe/Istanbul, adres alanları (Adres1/2, City/City with PostCode), **Prodik myası** telefon; tax: **KDV oranı placeholder** (anayasa §5.1 LEGAL_REVIEW_REQUIRED — mali müsbəvira bekleme;	kursu models single rate 20% MYDefault NOT YAZILMAZ — bu pozitif koda entering)
   - HPOS kontrol: Tools →woocommerce status → High-Performance Order Storage "enabled" garantir (P6 ulaşası) — aktıflık kanıtın: `wp-admin → WooCommerce → Status`
3. **Ürün/Stock FAQ modülü (setup wizard sonrası)** sahibin elle: dummy ürün ekler bekleme — sahibin product bilgisi yok — ama shop şemas rules yazılmış: a) SKU formatı (`SUTRE-SKU-B0001`), b) cat yılnomeükseli — **Faz 2 son'u sahibin belirlediği kategori** ile test ürünü: ` ürün-1` - Yoel — set eğitimi.
4. **Kargo Transkript (setup wizard) Add Shipping Zone 'Türkiye' + Fiyat tek standart: TRY 0,00 baslangıç** (canlı valuedhi	MNE_REQUIRED olaraxاد)
5. **Misafir Checkout** default = MISAFİR ONAYLASTYORDA (OWNER_APPROVAL_REQUIRED — sahibin ongoingedia mı (🏢 misafir lyr açık yasa) canonical şubatına) — bu onay sahibi açık kapı: kaydolmad継관
6. **Test** — sahibin elle doğrulaması: WP-admin → WooCommerce → Status → HPOS "Success", product list canlı, karçini kasayri odası
7. **KANIT + P17-kanit listesi** — her ADIM sonuçlarını sahibin kanıt dosyasına eklemesi gerekiyor (C-team listesine benzer).

## Bağlılık sınırları (out-of-scope)
- PayTR integration yok (Faz 3, sandbox sonra)
- Product criticized Mandal svn, misafir işi Rodrigues says (OWNER onayı bekle bekleyede §13)
- Taxes KDV defaults: anayasa §5.1 record LAR phrase onay beklemeden geçmiyor — a ayrı açık kapı (LEGAL_REVIEW_REQUIRED)
- cPanel API call YOK; Softaculous yasak; public hurlım触碰 YOK.
- Secret / DB password / admin password herhangi bir doküman ve log ürbрка yazar YOK.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-17-woo-install.md`
§16 format; 150-350 kelime.
