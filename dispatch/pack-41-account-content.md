# P41 — Hesabım içerik yenilemesi (dashboard + iletişim tercihleri + sipariş arama/filtre)

**Kanal:** coder profili · **Tarih:** 21-09-2026 · **Tema repo:** `/opt/data/workspace/proje` (main)
**Anayasa:** `attachments/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — İŞE BAŞLAMADAN TAMAMINI OKU. Çıktın §16 AI ÇIKTI SÖZLEŞMESİ formatında `dispatch/out/out-41-account-content.md` dosyasına yazılacak.

## Okunacak dosyalar (sırayla)
1. `AGENTS.md` — BAĞLAYICI canlı durum: §0 değişmez kurallar, §1.1 site haritası, §1.2 etki haritası, §5 tasarım/mimari kuralları + bilinen tuzaklar.
2. `theme/sutre-child-v2/functions.php` — mevcut Woo hook katmanı (banner, endpoint'ler buraya eklenecek).
3. `theme/sutre-child-v2/style.css` — TEK CSS; §"HESABIM (P40)" bölümü mevcut, yeni stiller o bölüme eklenir.
4. `theme/sutre-child-v2/header.php`, `footer.php` — SADECE oku; DOKUNMA.

## Sahibin talebi (birebir amaç)
Hesabım sayfalarının İÇERİĞİ yenilenecek (tasarım dili mevcut minimal lüks çizgide kalır, yeni CSS P40 bölümünün devamına scoped eklenir):
1. **Dashboard (Pano):** mevcut boş içerik anlamsız → yerine referans yapı: **İletişim Bilgileri** (Cep Telefonu — ülke kodu +90 öntanımlı — ve E-Posta, her biri ayrı Kaydet), **Üyelik Bilgilerim** (Adı, Soyadı), **Opsiyonel Bilgiler** (Cinsiyet select, Doğum Tarihi gün/ay/yıl üç select), Kaydet butonları.
2. **Yeni sayfa — İletişim Tercihleri:** navigasyona eklensin. İçerik: üstte KVKK onay metni (Aydınlatma Metni + pazarlama izni — **LEGAL_REVIEW_REQUIRED: metin PLACEHOLDER'dır, `docs/legal-placeholders/` şablonuyla uyumlu yazılır, dosya içine ve rapora LEGAL_REVIEW_REQUIRED etiketi düşülür**), altında üç açma/kapama: **E-Posta / SMS / Çağrı Merkezi**, Kaydet butonu. Tercihler user meta'ya kaydedilir (`sv_contact_pref_email/sms/call`, değer 'yes'/'no').
3. **Siparişlerim:** tablonun üstüne **arama kutusu** (sipariş no, ürün adı ile arar — server-side) ve **dönem/sıralama dropdown'u** (Son 3 ay / Son 6 ay / Son 1 yıl / Tümü + Fiyata göre artan/azalan, Tarihe göre yeni/eski). GET parametreleri ile server-side filtre (GET `sv_q`, `sv_period`, `sv_sort`).

## Teknik gereksinimler
- Dashboard içerik override'ı: child tema `woocommerce/myaccount/dashboard.php` (Woo 11.1.0 template'inden kopyalanıp özelleştirilir; `do_action('woocommerce_account_dashboard')` uyumu korunur). Form POST işleyicileri `init` hook'unda nonce + `is_account_page()` guard ile; yetkisiz kullanıcı için `wp_safe_redirect`.
- İletişim Tercihleri: `add_rewrite_endpoint('iletisim-tercihleri', EP_ROOT | EP_PAGES)` + `woocommerce_account_menu_items` (Siparişlerim'den sonra) + `woocommerce_account_iletisim-tercihleri_endpoint` content callback. Rewrite flush NOTU: kodda `register_activation` yok — **KULLANICI ADIMI** rapora yaz: cPanel Terminal'de `wp rewrite flush` YOK (wp-cli belirsiz) → Ayarlar→Kalıcı Bağlantılar→Kaydet akışı kullanıcıya anlatılacak (Hermes kullanıcıya tek mini adım verecek).
- Sipariş arama: `WC_Order_Query` / `wc_get_orders` ile customer'ın kendi siparişleri; ürün adı araması order item'lar üzerinden (performans: tek müşteri, sipariş sayısı düşük — basit döngü kabul).
- Telefon alanı: mevcut `wc-rich-register` mu-plugin'inden bağımsız, billing_phone ile senkron (kullanıcının sipariş telefonu olarak da kullanılır) — kaydet'te `update_user_meta` + billing_phone güncelle.
- Cinsiyet/Doğum tarihi: user meta (`sv_gender`, `sv_birth_day/month/year`). KVKK: opsiyonel alanlar — formda "(opsiyonel)" etiketi.
- Tüm metinler Türkçe; dil dosyası yerine doğrudan Türkçe string (site tek dilli).
- **Tuzaklar (AGENTS §5):** PHP 8.5 (array-returning permalink fonksiyonlarına guard), Woo block template karışmaz (klasik PHP), header/footer'a dokunulmaz, CSS yalnız `style.css` HESABIM bölümüne.

## Kabul ölçütleri
1. `/my-account/` (girişli): İletişim/Üyelik/Opsiyonel bölümleri görünür; telefon ve e-posta ayrı kaydedilir; ad-soyad kaydı Woo customer profiline yansır; cinsiyet+doğum tarihi meta'ya kaydedilir. Tümü nonce'lu.
2. `/my-account/iletisim-tercihleri/`: nav'da görünür, üç toggle durumları meta'dan okur, Kaydet kalıcı.
3. `/my-account/orders/`: arama kutusu sipariş no + ürün adıyla filtreler; dönem + sıralama dropdown çalışır (GET parametreleri URL'de taşınır).
4. `php -l` tüm değişen dosyalar temiz; canlı sayfalarda Fatal yok (Hermes doğrular).
5. Yeni CSS yalnız `style.css` HESABIM bölümünde; mobil öncelikli (≤781px düzenler çalışır).

## Kapsam dışı
- Alışveriş Listem / Hediye çekleri / Puanlar (referans menüde vardı ama istenmedi; ekleme YAPMA).
- Hukuki metinlerin final hali (LEGAL_REVIEW_REQUIRED — placeholder ile geç).
- Header/footer, ürün kartları, checkout tasarımı.
- FTP deploy (Hermes yapar; sen push'a kadar).

## Teslim formatı
- Commit'ler atomik (dashboard / tercihler / siparişler ayrı commit), main'e push, commit hash'leri raporda.
- `dispatch/out/out-41-account-content.md`: §16 formatı (Sonuç / Doğrulama / Risk ve güvenlik / Açık kapılar) + KULLANICI ADIMLARI bölümü (rewrite flush akışı).
- AGENTS.md §7'ye ZATEN YAPILDI + §8'e P41 kaydı EKLE (SEN yazacaksın, §0.2 kuralı).
