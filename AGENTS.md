# Sutre — Ajan Çalışma Dosyası (bağlayıcı)

Bu dosya `sutre` deposunun (sutre.store) güncel ve bağlayıcı çalışma kaynağıdır.
Ürün dili Türkçedir; tüm metin ve kaynak dosyalar UTF-8 olmalıdır.
Git remote: `git@github.com:salihsungur/sutre.git` (deploy key: konteyner `/opt/data/home/.ssh/sutre_deploy`).
Teknik sözleşme: `attachments/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — HER ajan işe başlamadan önce TAMAMINI okur. Öncelik sırası: Hukuki güvenlik > ödeme ve veri bütünlüğü > güvenlik > sürdürülebilirlik > performans > özellik sayısı.

> **Son Doğrulama (20-09-2026):** Staging (`https://staging.sutre.store`) kararlı durumdadır. Aktif tema `theme/sutre-child-v2/` (sunucuda `wp-content/themes/sutre-child/`); TEK MİMARİ = klasik PHP (`header.php` + `footer.php` tek kaynak, tüm sayfalar aynı). Ürün kartı tasarımı **site geneli global** (P37 commit; `.sv-products` scoping'i kaldırıldı → `ul.products` global selector). Woo pseudo-element grid-item tuzağı fix'li (P36c). Header/footer sorunları KÖKTEN kapandı (P29). WP 7.x + Woo 11.1.0 + PHP 8.5.9 + MariaDB 11.8.8 + HPOS Enabled. PayTR başvurusu YAPILMADI (bilinçli gate: önce site görsel düzeni). Canlı üretim (production) YOK — her şey staging'de.

---

## 0. DEĞİŞMEZ KURAL — Her ajan yaptığı işi bu dosyaya işler

1. Her session başında bu dosyanın tamamını oku ve mevcut durumla çelişki olup olmadığını kontrol et.
2. Kod/görsel/içerik/deploy değişikliklerini ilgili güncel bölüme ve §9 kayıt defterine işle.
3. Doğrulanmayan işi "tamamlandı" YAZMA. Tamamlanan iş `ZATEN YAPILDI`, bekleyen iş `YAPILACAK` veya `ONAY BEKLİYOR` olarak, tarih ve commit ile kaydedilir.
4. Her çalışma turunun sonunda AGENTS.md dahil değişiklikleri test et, commit et ve `main`e push et. Kullanıcının ilgisiz değişikliklerini koru.
5. Yeni dağınık durum/plan `.md` dosyası OLUŞTURMA; tek kaynak bu dosyadır. (Dispatch paketleri ve bot raporları hariç — `dispatch/` orkestrasyon kanıtıdır, plan dosyası değil.)
6. **Görsel değişiklik kuralı (bağlayıcı):** Hermes/botlar görsel değişiklikleri KENDİ onaylamaz; her görsel değişiklikten sonra Salih'e gösterilir, revize isterse uygulanır. Bot/ajan raporlarında "başarılı/güzel görünüyor" İFADESİ KULLANILMAZ — sadece ne yapıldığı listelenir.

---

## 1. Depo Haritası

```text
sutre/
├── theme/sutre-child-v2/            AKTİF TEMA KAYNAĞI (sunucuda sutre-child)
│   ├── style.css                    TEK CSS dosyası — site geneli tüm stil burada
│   ├── functions.php                enqueue + Woo kuralları + shop banner + placeholder fix
│   ├── header.php / footer.php      TEK HEADER/FOOTER KAYNAĞI (klasik PHP, tüm sayfalar)
│   ├── page-templates/home.php      ana sayfa şablonu (get_header/get_footer kullanır)
│   ├── woocommerce/                 Woo klasik şablon override'ları (archive-product vb.)
│   └── assets/img/                  logo/ (transparan crop) + site/ (hero, kategori, banner)
├── docs/
│   ├── visual-prompts/              hijab-stil-rehberi.md, site-icerik-gorsel-plani.md
│   └── MATER-PLAN-PREMIUM.md        master plan (bloklar A-G)
├── dispatch/                        pack-*.md (bot paketleri) + out/ (bot raporları)
├── AGENTS.md                        BU DOSYA — tek durum kaynağı
└── WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md (attachments/ içinde; bağlayıcı sözleşme)
```

- `theme/sutre-child/` (v1) eski sürümdür; DOKUNULMAZ, silinmez ama kullanılmaz.
- Anayasa §15 "asla yapılmayacaklar" listesi tüm işlerde bağlayıcıdır.

### 1.1 Site Haritası (canlı durum — 20-09-2026)

| URL | Sayfa / İçerik | Ne yapılabilir / Davranış | Şablon kaynağı |
| :--- | :--- | :--- | :--- |
| `/` | Ana sayfa: hero (transparan lockup + "Koleksiyonu Keşfet"), Ürünler bölümü (2 kart), Kategoriler (Giyim kartı) | Statik vitrin; Ürünler bölümü Woo shortcode ile çekilir (kart tasarımı global'den gelir) | `page-templates/home.php` (WP'de "Sutre Ana Sayfa" şablonu atanmış) |
| `/shop/` | Koleksiyon: shop banner ("KOLEKSİYON"), sıralama select'i, ürün grid'i (2 ürün) | Ürün listesi Woo archive; banner `functions.php` hook; sayfa başlığı gizli | `woocommerce/archive-product.php` |
| `/product/jakarli-sal/` | Jakarlı Şal — değişken ürün (9 renk varyasyonu, ₺449,90 indirimli) | Varyasyon seçimi, sepete ekleme | `woocommerce/single-product.php` + Woo blok markup |
| `/product/iman-nour-sal/` | İman Nour Şal — değişken ürün (6 renk; görseller geçici Siyah Jakarlı) | Aynı | Aynı |
| `/cart/` | Sepet sayfası | Sepet düzenleme; hesapsız gezilebilir | `woocommerce/cart.php` ailesi |
| `/checkout/` | Ödeme (şu an Woo varsayılan; PayTR yok) | 302 → login/register ZORUNLU (bilinçli kural); Everyone-can-register AÇIK | `woocommerce/checkout.php` ailesi |
| `/my-account/` | Hesabım (giriş/kayıt + hesap panosu) | Giriş, kayıt, siparişler | `woocommerce/myaccount.php` ailesi |
| `/mesafeli-satis-sozlesmesi/`, `/iade-ve-cayma/`, `/gizlilik-politikasi/` | Hukuki sayfalar | **404 — henüz oluşturulmadı** (footer linkleri ölü; Blok A NEEDS_OWNER_INPUT) | Oluşturulunca `page.php` |
| `imunify-bot-check` | Hosting bot koruması ara sayfası | Sistem davranışı; hata değil | — |

- Kategori yapısı: Giyim → Kadın → Şal (tek kategori; shop = tüm ürünler).
- Sayfa oluşturma kuralı: yeni bir WP sayfası açıldığında header/footer/style.css otomatik gelir — o sayfaya AYRI CSS/markup yazılmaz (§5.1).

### 1.2 WordPress + FTP Etki Haritası (değişiklik → yayılma alanı)

**Sunucu docroot:** `/home/spokenla/staging.sutre.store/` (FTP kanalı B veya kullanıcının `git pull` + `cp -a` zinciriyle).

```text
staging.sutre.store/
├── wp-config.php                    DB bağlantısı + HPOS; DEĞİŞTİRİLMEZ (secret — okunmaz, rapora yazılmaz)
├── wp-content/
│   ├── mu-plugins/wc-rich-register.php  kayıt alanı zenginleştirme (site geneli etkiler)
│   ├── plugins/woocommerce/         ÇEKİRDEK — ASLA düzenlenmez (güncelleme siler)
│   ├── themes/sutre-child/          AKTİF TEMA (repo: theme/sutre-child-v2/) ← tüm tasarım işleri burada
│   │   ├── style.css                TEK CSS → TÜM sayfaları etkiler (header, footer, kartlar, banner, hero)
│   │   ├── header.php               TEK header kaynağı → TÜM sayfalar (ana sayfa + shop + ürün + cart + checkout + my-account + statik sayfalar)
│   │   ├── footer.php               TEK footer kaynağı → TÜM sayfalar (aynı)
│   │   ├── functions.php            Woo davranış kuralları → tüm Woo sayfaları (banner hook, başlık gizleme, sidebar yok, placeholder fix, block template kapama)
│   │   ├── page-templates/home.php  yalnız ana sayfa (WP admin şablon ataması gerekir)
│   │   ├── woocommerce/*.php        Woo şablon override'ları → ilgili Woo sayfası (archive-product → /shop/; single-product → /product/*; cart/checkout/myaccount aileleri)
│   │   └── assets/img/              logo/ (header+footer+lockup PNG) + site/ (hero, kategori kartı, shop banner) → hangi sayfada kullanılıyorsa oraya etki eder
│   └── uploads/                     medya kütüphanesi (ürün görselleri Woo'dan atanır; tema görselleri BURADA DEĞİL — tema assets/img)
└── refs/                            üretim referans görselleri (fal.media public URL için) — siteye etkisi yok
```

**Etki kuralları (ajan bunları bilecek):**
1. `style.css`'teki bir değişiklik sitede HER sayfayı etkiler → selector scoped yazılmalı (global selector yalnız bilinçli, site geneli istenen kurallar için — örn. kart).
2. `header.php` / `footer.php` değişikliği TÜM sayfalara aynı anda yansır — bu istenen davranıştır (tek kaynak).
3. `functions.php`'deki Woo hook'ları tüm mağaza yüzeylerini etkiler (shop + ürün + cart + checkout + hesap).
4. `woocommerce/` altındaki şablon sadece kendi sayfa tipini etkiler; orada header/footer çağrısı (get_header/get_footer) ASLA kaldırılmaz.
5. `uploads/`'a konan ürün görselleri WP admin'den ürüne atanır; tema görselleri `assets/img/`'ta tutulur ve FTP ile konur.
6. WP Admin (staging.sutre.store/wp-admin) — kullanıcı yönetir; ajan admin şifresi İSTEMEZ, admin panel işi kullanıcıya tek mini adımla anlatılır.


## 2. Kullanıcıyla Çalışma ve Güvenlik

1. Kullanıcı (Salih) adım adım ilerler: her turda TEK mini talimat; uzun toplu paket verilmez.
2. Secret, şifre, token, API key repoya, log'a, bu dosyaya, rapora ASLA yazılmaz. FTP bilgileri `/host/desktop/ftpinfo.txt`'den okunur (değerler ekrana basılmaz). PayTR merchant ID/key/salt hiçbir yere yazılmaz.
3. Kart/CVV verisi hiçbir koşulda saklanmaz/loglanmaz. Canlı müşteri verisi anonimleştirilmeden staging'e taşınmaz.
4. KDV/fiyat/vergi değişikliği staging'de doğrulanmadan ve Salih onayı alınmadan production'a taşınmaz (§5.1: KDV ASLA varsayılan girilmez — LEGAL_REVIEW_REQUIRED).
5. Canlı ödeme/iade/fatura işlemleri OWNER_APPROVAL_REQUIRED.
6. Bilinmeyen işletme verisi tahmin edilmez; `PROJECT_INPUTS.md`'e NEEDS_OWNER_INPUT olarak yazılır.
7. Güvenli, geri döndürülebilir teknik kararlar otonom verilir; ürün yönü/görsel kararları kullanıcıya sorulur (§0.6).

## 3. Ortam ve Deploy Kanalları

- **Hosting:** paylaşımlı cPanel (`mt-charon.guzelhosting.com`); staging docroot `/home/spokenla/staging.sutre.store/`. SSH YOK.
- **Deploy kanal A (ana):** ajan git push → kullanıcı cPanel Terminal'de `git pull` + `cp -a` ile themes/ altına kopyalar. (rsync yok → `cp -a`, önce `.bak` yedek.)
- **Deploy kanal B (ajan doğrudan):** ftplib ile FTP (`sutredeploy@spokenlab.com.tr`, 89.252.180.243; bilgiler `/host/desktop/ftpinfo.txt`), upload sonrası SHA-256/MD5 karşılaştırma ZORUNLU; ardından canlı doğrulama:
  `curl -skL "https://staging.sutre.store/?v=$(date +%s)" | grep -c '<kanıt-marker>'` (self-signed → `-k` şart).
- **FTP TAM ERİŞİM (21-09-2026, sahibin kararı):** sutre-child chroot kalktı; FTP giriş dizini = home kökü (`/`). **MUTLAK yol ZORUNLU:** tema dosyaları `/staging.sutre.store/wp-content/themes/sutre-child/...` şeklinde atılır — göreli yol home köküne yazar (P43 kazası: /functions.php çöpü; silindi). Home kökünde siteden bağımsız dizinler var (muharrembilalsungur.com.tr, sosyalcocuk.com.tr, geleceginbilimi*, detoneakademi_repo, spokenlab_repo, yedek, mail vb.) — SADECE sutre dizinlerine dokunulur, diğerlerine OKUNMAZ/YAZILMAZ.
- **Cache:** deploy sonrası kullanıcıya LiteSpeed Purge All hatırlatılır; doğrulama her zaman `?v=` cache-buster ile. Ayrıca CSS değişikliğinde `SUTRE_VERSION` artırılır (cache-buster kuralı, P42.2'den beri).
- **cPanel UAPI Fileman:** exists çıktıları güvenilmez (bug'lı) — kritik doğrulama FTP/curl ile ÇAPRAZ yapılır.
- **Deploy kanalları:** (a) git push → kullanıcı cPanel Terminal `git pull` + `cp -a`; (b) doğrudan FTP (ftplib, `ftpinfo.txt`'den) → `wp-content/themes/` altına.

## 4. Ürün, Marka ve Ticaret Kuralları

- Kategori: Giyim → Kadın → Şal (yalnız bu; Erkek ileride). Ürünler: Jakarlı Şal (9 renk), İman Nour Şal (6 renk, görseller geçici Siyah Jakarlı).
- SKU şeması `SUTRE-SKU-<KOD>-<4hane>` (onaylı; SKU = ürün kodu, asla değişmez).
- Checkout: sepet hesapsız gezilir, checkout'ta login/register ZORUNLU (Everyone-can-register AÇIK).
- Marka kimliği: yalnız **SUTRE** wordmark (slogan/alt yazı YASAK). Tipografi: Cormorant Garamond (logo/H1) + Jost (UI/CTA). Palet: Bone `#F5F2EC` · Ink `#1A1A1A` · Silk `#C9A66B` · Marine `#1B3A4B` · Whisper `#B8B4AC`. His: modern lüks, minimal site, CANLI+dolu fotoğraflar.
- Görsel üretim kuralları: insanlı görsellerde SAÇ ASLA görünmez; portre tipi yakında model kameraya bakar; dolu sahne serbest; 190cm × 70cm ürün prompt'a yazılır; paketleme/açık alan fotoğrafı İPTAL. Üretim: Hermes prompt paketi yazar → Salih Higgsfield'de üretir → `/host/desktop/SUTRE/Ürün Fotoğrafları/<Tür>/<Renk>/` arşivine.

## 5. Tasarım ve Mimari Kuralları ( lessons-learned gömülü)

1. **Tasarım bütünlüğü herşeyden önce** (sahibin bağlayıcı kuralı): ürün kartı tasarımı, header, footer SİTE GENELİnde aynı. Sayfa başına ayrı CSS/markup YASAK — tek CSS = `style.css`, tek header/footer = `header.php`/`footer.php`.
2. **Ürün kartı (P37, global):** `ul.products` global selector — görsel odaklı kart, alt gradient bandı, zarif İNDİRİM rozeti, hover'da ortalanmış Seçenekler (desktop). Yeni herhangi bir sayfaya ürün listesi eklenirse bu tasarımı otomatik alır; ayrı CSS YAZILMAZ.
3. **Bilinen tuzak — Woo pseudo-element:** `ul.products::before/::after {content:" "; display:table}` grid konteynerde grid item olur → boş hücre/çapraz dizilim. Fix style.css'te: `display:none !important; content:none !important`. Bu reset kaldırılmaz.
4. **Bilinen tuzak — Woo blok tema:** Woo 11 klasik şablonlara dönüşü resmen desteklemez ama child `woocommerce/*.php` template hierarchy override'ı çalışır (P29 kanıtlı). Block template-parts ile karışık mimari KURULMAZ.
5. **Bilinen tuzak — PHP 8.5:** `esc_url(wc_get_page_permalink(...))` array döndürebilir; `ltrim(array)` fatal. Tolerant helper + `is_array` guard'lar mevcut; kaldırılmaz.
5a. **Bilinen tuzak — Woo kart başlığı (P43, kesin kanıtlı):** Woo `assets/css/woocommerce.css`: `.woocommerce ul.products li.product .woocommerce-loop-product__title {padding:.5em 0}` → özgülük **(0,4,2)**; soldan 0 = başlık sol kenara yapışık. Tema kuralı bu özgülüğü AŞMALI: kazanan `#sutre-content ul.products li.product h2...` veya `.woocommerce ul.products li.product h2...` **(0,4,3)** (class sayısı element'ten ÖNCE karşılaştırılır — P42.4'ün h2 eklemesi yetmemişti). style.css, woocommerce.css'ten SONRA yüklenir (head link sırası curl-kanıtlı) → eşitlikte de biz kazanır.
5a-b. **Bilinen tuzak — Hesap nav key'leri (P50-fix):** "Hesap Detayları" öğesinin menu key'i `edit-account`'tır (`account-details` DEĞİL — o kayıt işleyicisinin nonce/action adı). Nav'dan öğe çıkarırken key listesi Woo `wc_get_account_menu_items` kaynak kodundan doğrulanmalı (P50'de yanlış key yüzünden öğe sırada kaldı).
6. Override.css / parts/ içindeki eski CSS parçaları kullanılmaz; her stil değişikliği `style.css`'e işlenir.
7. Mobil önceliklidir (anayasa §9 — Instagram/WhatsApp trafiği mobil varsayılır); WCAG 2.2 AA hedef.
8. Genel yerleşim/konum korunur; yalnız görsel/UX iyileştirilir (anayasa §9 UI politikası).

## 6. Hızlı Komut Özeti

```bash
# Depo (konteyner)
cd /opt/data/workspace/proje
git add <kapsam> && git commit -m "..." && git push origin main

# Doğrudan deploy (kanal B) — ftplib + SHA doğrulama; ftpinfo.txt'den oku
# Canlı doğrulama
curl -skL "https://staging.sutre.store/?v=$(date +%s)" | grep -c '<marker>'
curl -skL "https://staging.sutre.store/shop/?v=$(date +%s)" | grep -c 'Fatal'   # 0 OLMALI

# Kullanıcı tarafı (cPanel Terminal)
cd ~/repositories/sutre && git pull origin main
cp -a ~/repositories/sutre/theme/sutre-child-v2 ~/staging.sutre.store/wp-content/themes/  # .bak önce!
# → LiteSpeed Cache → Purge All
```

## 7. Güncel Açık İşler ve Onay Bekleyenler

- [x] **P37 — Ürün kartı tasarımının shop'a aktarımı — `ZATEN YAPILDI` (20-09-2026):** `.sv-products` scoping kaldırıldı → kart CSS'i global `ul.products`; SHA doğrulamalı FTP deploy + canlı grep kanıtı (header 3, banner 4, phperr 0). Kullanıcı görsel onayı BEKLİYOR.
- [ ] **AGENTS.md Sutre sürümü — `ZATEN YAPILDI` (bu commit):** Geleceğin Bilimi referans yapısından türetildi (§0 değişmez kural + durum etiketleri + kayıt defteri).
- [x] **P40 — Hesabım yüzeyi tasarımı — `ZATEN YAPILDI` (20-09-2026):** style.css'e scoped "HESABIM" bölümü: giriş/üye ol iki sütun + ayraç (mobil tek sütun), serif başlıklar, uppercase etiketler, tam genişlik butonlar, hesap içi yatay chip navigasyonu (is-active silk çizgi), sipariş/adres tablo-kart stilleri. Gizlilik metni Türkçe'ye çevrildi — kök neden: Woo 11.1'de `woocommerce_registration_privacy_policy_text` filtre değil OPTION adı; resmî kapı `woocommerce_get_privacy_policy_text` (canlı doğrulandı: EN 0 / TR 1). Kullanıcı görsel onayı BEKLİYOR.
- [x] **P41 — Hesabım içerik yenilemesi (dashboard + iletişim tercihleri + sipariş arama/filtre) — `ZATEN YAPILDI` (21-09-2026):** Pano formları (İletişim: Cep Telefonu +90 öntanımlı / E-Posta ayrı ayrı Kaydet; Üyelik: Ad-Soyad → user + billing meta; Opsiyonel: cinsiyet + doğum gün/ay/yıl → sv_gender / sv_birth_* meta, boş gönderim metayı siler); yeni `/my-account/iletisim-tercihleri/` endpoint'i (nav'da Siparişlerim sonrası; KVKK metni PLACEHOLDER — LEGAL_REVIEW_REQUIRED; E-Posta/SMS/Çağrı Merkezi toggle, meta `sv_contact_pref_email/sms/call`, varsayılan kapalı); Siparişlerim'de arama (GET sv_q: sipariş no + ürün adı, order item döngüsü) + dönem/sıralama (GET sv_period/sv_sort) server-side; Woo 11.1.0 dashboard.php/orders.php override (dashboard action'ları + tablo markup'ı korundu). POST işleyicileri template_redirect'te (paket 'init' diyor; is_account_page() init'te güvenilmez — Woo WC_Form_Handler pattern'i, anayasa §0.10 ile rapora not), form başına nonce, PRG + beyaz-listeli notice. CSS yalnız style.css HESABIM bölümü devamı; mobil ≤781px tek sütun. Commit'ler: 176c666 / 7b2648f / ebc4a2e. Rewrite flush kullanıcı adımı BEKLİYOR; kullanıcı görsel onayı BEKLİYOR.
- [x] **P39 — Kart görsel çözünürlüğü — `ZATEN YAPILDI` (20-09-2026):** Kök neden: Woo `thumbnail_image_width=350` → 504px kart 350px görseli büyütüyordu. Fix: `single_product_archive_thumbnail_size → 'large'` (mevcut yüklemelerde 'large' zaten üretilmiş → yeniden boyutlandırma gerekmez). Canlı kanıt: kart src 764×1024. Kullanıcı görsel onayı BEKLİYOR.
- [x] **P47 — Header sepet ikonu + sepet sayfası + stok mesajı düzeltmesi — `ZATEN YAPILDI` (21-09-2026):** Header'a Hesabım'ın sağına sepet ikonu (SVG çanta + silk rozet sayaç; `woocommerce_add_to_cart_fragments` ile AJAX güncellemeli, boşken gizli, /cart/ linkli). Stok mesajından adet kaldırıldı → "Acele et — stokta az kaldı!". Sepete ekle butonu Woo moru (#7f54b3/wp-element-button) → ink !important kilit. Adet inputu + buton alt alta tam genişlik (renk select'iyle aynı input dili). Sepet sayfası: Woo sepet BLOĞU (JS-i18n İngilizce + blok grid kartlar) klasik `[woocommerce_cart]` shortcode'a zorlandı (the_content filtresi; TEK MİMARİ klasik doktrini) → canlı teyit: "Sepetiniz şu anda boş." + "Alışverişe devam et", blok grid 0, Fatal 0. Ver 3.4.6. Kullanıcı görsel onayı BEKLİYOR.
- [x] **P53 — Ürün meta başlığın üstüne — `ZATEN YAPILDI` (21-09-2026):** `woocommerce_template_single_meta` hook 40→3 (başlıktan önce); meta ayracı alta alındı (border-bottom), başlıkla nefes payı. Canlı kanıt: HTML'de product_meta, h1'den önce. Ver 3.4.11.
- [x] **P52 — "Ek bilgi" → "Ürün Bilgileri" — `ZATEN YAPILDI` (21-09-2026):** gettext haritasına `Additional information => Ürün Bilgileri` eklendi (sekme + panel başlığı; panel h2 CSS ile zaten gizli). Canlı teyit: yeni 2 / eski 0, Fatal 0.
- [x] **P51 — Footer Alışveriş kolonu dinamik — `ZATEN YAPILDI` (21-09-2026):** "Pamuk Şallar" (ürün özelliği, kategori değil) kaldırıldı; Jakarlı Şal + İman Nour Şal linkleri Woo'dan `get_page_by_path(slug, product)` ile çekilir → ürün adı değişirse footer güncellenir, ürün kaldırılırsa link kaybolur (slug = kararlı kimlik). Canlı teyit: Pamuk 0, iki dinamik ürün linki var, Fatal 0.
- [x] **P50 — Hesap detayları → Hesap bilgileri tam taşıma — `ZATEN YAPILDI` (21-09-2026):** Native `form-edit-account.php` (ad, soyad, e-posta, şifre) dashboard'a "Üyelik Bilgileri" bölümü olarak gömüldü; e-posta İletişim bölümünden çıkarıldı (tek kaynak); 'account-details' nav'dan çıkarıldı + edit-account endpoint'i Hesap bilgileri'ne 302 (save handler template_redirect:20 önce çalışır); nav filter erken-dönüş bug'ı düzeltildi (P49'da 'Hesap Detayları' kalmasının nedeni). Fieldset/legend CSS'i eklendi. Ver 3.4.9; canlı teyit Fatal 0. Kullanıcı görsel onayı BEKLİYOR.
- [x] **P49 — Giriş ekranı baştan tasarım + Pano/Hesap detayları birleşimi — `ZATEN YAPILDI` (21-09-2026):** Çapraz görünümün kök nedeni: Woo core `.col2-set .col-1 {float:left;width:48%}` / `.col-2 {float:right}` grid ile karışıyordu → P49'da float/width !important sıfırlama + beyaz kart (max 1080px, iki sütun, ayraç; ≤900px tek sütun). Nav: Pano → **"Hesap bilgileri"**, 'account-details' nav'dan çıkarıldı (dashboard P41 içerikleriyle birleşik); dashboard'a serif başlık + sona "Şifre" bölümü (sv-btn-outline → kayıp şifre akışı). Ver 3.4.8; canlı teyit Fatal 0. Kullanıcı görsel onayı BEKLİYOR.
- [x] **P48 — Sepet dolu görünüm fix'leri — `ZATEN YAPILDI` (21-09-2026):** Kupon input+buton tasarımı; deaktif buton (gri dolu kutu değil: kontur+soluk yazı, aktif olunca ink); "Farklı bir adrese gönder" linki dikdörtgen butona; miktar kutuları tek dil (48px, silk focus, sepet kompakt/ürün tam genişlik); sepet tablosu nefes payı + 72×92 görsel. Ver 3.4.7.
- [x] **P46 — Stok gösterimi politikası — `ZATEN YAPILDI` (21-09-2026):** "X adet stokta" müşteriye ASLA gösterilmez; yalnız stok eşiği (varsayılan 3, `sv_stock_low_threshold` filtresi) altında "Acele et — stokta yalnızca X adet kaldı!" silk-vurgulu not. Hem ana ürün (`woocommerce_get_stock_html`) hem varyasyon AJAX'ı (`woocommerce_available_variation`) kapsanır; Tükendi durumu varsayılan korunur. Ver 3.4.3; canlı teyit Fatal 0. Kullanıcı görsel onayı BEKLİYOR.
- [x] **P45 — Tek ürün sayfası tasarım katmanı — `ZATEN YAPILDI` (21-09-2026):** style.css'e scoped "TEK ÜRÜN" bölümü: galeri çerçevesiz + zoom trigger chip, İNDİRİM rozeti tek-ürün varyantı, serif başlık 38px, fiyat flex tek satır, varyasyon select input dili + Temizle linki, adet+buton tek satır (mobil tam genişlik), meta ince ayraç, sekmeler minimal alt-çizgili (Woo default kutu sekme ezildi), ilgili ürünler ayracı + serif başlık (global kart düzeni otomatik), değerlendirme formu. Ver 3.4.2; canlı teyit: CSS 43.8KB P45 mevcut, Fatal 0. Kullanıcı görsel onayı BEKLİYOR.
- [x] **P44 — Rozet banda taşındı + hover merkezi — `ZATEN YAPILDI` (21-09-2026):** `woocommerce/content-product.php` override (Woo 11.1.0 @9.4.0 temelli): sale flash görselin üstünden alındı → `.sv-card__meta` flex satırında fiyatın solunda (indirimsiz üründe rozet çıkmaz, fiyat sola oturur); rating hook'u tasarımda yok, doğrudan çağrı. Seçenekler butonu `top: calc(50% - 46px)` → görsel alanının tam merkezi. Ver 3.4.1; canlı teyit: meta satırı `onsale → price` sıralı, Fatal 0. Kullanıcı görsel onayı BEKLİYOR.
- [x] **P43 — Kart başlığı solda yapışık: KESİN kök neden + fix — `ZATEN YAPILDI` (21-09-2026, coder bot commit `efc30be` + Hermes deploy):** Kök neden dosya-kanıtlı: Woo `woocommerce.css` başlığa `padding:.5em 0` veriyor, özgülük (0,4,2) > P42.4'ün (0,4,3 değil 0,3,3'üydü) → padding-left hep 0. Fix: tema seçiciye `.woocommerce` prefix → **(0,4,3)** üstün; SUTRE_VERSION 3.4.0. Deploy kanıtı: canlı style.css?ver=3.4.0 içinde kazanan kural + `padding: 20px 26px 0`. Ayrıca bu turda FTP tam erişim açıldı; göreli yol kazası (home köküne çöp) giderildi → §3 MUTLAK yol kuralı eklendi. Kullanıcı görsel onayı BEKLİYOR.
- [ ] **Blok A — hukuki sayfalar (Gizlilik, mesafeli satış, iade, cayma) — `NEEDS_OWNER_INPUT`:** İşletme bilgileri (unvan, vergi no, adres) kullanıcıdan bekliyor; metinler `docs/legal-placeholders/` şablonlarından doldurulacak; final doğrulama dış uzman (avukat) + kullanıcı onayı.
- [ ] **KDV/vergi kurulumu — `LEGAL_REVIEW_REQUIRED`:** §5.1 gereği hiçbir varsayılan girilmez; mali müşavir doğrulaması beklenir.
- [ ] **İman Nour (Pamuk) ürün görselleri — `YAPILACAK`:** Referans yok; geçici Siyah Jakarlı temsili. Referans gelince pamuk prompt adaptasyonu (mat, satin parlaklık YOK) ile 6 renk × 3 görsel üretilecek.
- [ ] **PayTR başvurusu — `ONAY BEKLİYOR` (kullanıcı):** Site görsel tamamlanması sonrası production domain (sutre.store) ile başvuru. Merchant bilgileri yalnız kurulum wizard'ına girilir.
- [ ] **Production'a geçiş — `ONAY BEKLİYOR`:** Anayasa §0.6: staging test → yedek → sahip onayı → production. Açık hukuk/ödeme kapısı varken DURUR.
- [ ] **Sonraki görsel tur — `YAPILACAK`:** Site içerik görselleri (`docs/visual-prompts/site-icerik-gorsel-plani.md` ADIM 3-4) + kullanıcı revizeleri.

## 8. Tarihsel Kayıt Defteri (özet zincir)

- **Faz 0 (12-09):** Anayasa okundu/onaylandı; PROJECT_INPUTS / PRIVACY-DATA-MAP / SECURITY iskeletleri `dispatch/out/` raporlarıyla kapandı.
- **Altyapı (13-16/09):** GitHub repo + deploy key; DNS/zone fix (IP 89.252.180.243); WP staging kurulumu; Woo 11.1.0 hash-verified; HPOS; Türkçe çeviriler; wc-rich-register mu-plugin; ürün-1 ₺100.
- **Tema görünürlük krizi (17-09):** cPanel Fileman "exists" yalanları → repo klonu boş çıktı → re-clone → ilk deploy. **Ders: Fileman tek başına güvenilmez; FTP/curl çapraz doğrula.**
- **P22b (17-09):** TT5 block theme footer pattern kök nedeni; commit 2fbbdc6 blok tek kaynak denemesi (sonra terk edildi).
- **PHP 8.5 fatals (18-09):** ltrim(array) + placeholder array → a3c9760 tolerant fix'ler.
- **Görsel üretim dönemi (18-19/09):** fal.media referans oturumu → Higgsfield renk serisi modeli (Jakarlı 9 renk × 3 görsel TAMAM); hijab-stil-rehberi.md; site-icerik-gorsel-plani.md; logo L1-L4 transparan crop.
- **P28 (19-09):** E1-E7 defekt fix'leri; P28b vision 6/6 PASS.
- **P29 (20-09):** Kullanıcı kökten çözüm talebi → klasik PHP TEK KAYNAK (header.php/footer.php + Woo template override'ları) → tüm sayfalarda aynı header/footer; kullanıcı editleri (düz siyah strip, footer düzeni, KOLEKSİYON hizası).
- **P36c (20-09):** Staggered grid kök nedeni = Woo `ul.products::before/::after` grid item → pseudo reset; kullanıcı onayı: "tammadır sorun düzeldi!"
- **P37 (20-09):** Kart tasarımı global (scoping kaldırma) — shop dahil site geneli tek kart; deploy SHA-doğrulamalı.
- **P41 (21-09):** Hesabım içerik yenilemesi: Pano bilgi formları (İletişim/Üyelik/Opsiyonel, nonce'lu PRG), İletişim Tercihleri endpoint'i (KVKK PLACEHOLDER), Siparişlerim arama/dönem/sıralama (GET sv_q/sv_period/sv_sort, server-side). Ders: (1) `is_account_page()` init'te güvenilmez → form handler'lar template_redirect'te; (2) WC_Order_Query 'total' orderby'ı desteklemez (fallback 'date') → fiyat sıralaması PHP usort'ta; (3) PHP binary ortamda yok → sözdizimi kanıtı npm php-parser (gerçek PHP 8 grammar) ile. KVKK metni PLACEHOLDER (LEGAL_REVIEW_REQUIRED); rewrite flush kullanıcı adımı bekliyor.
