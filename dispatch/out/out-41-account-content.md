# P41 — Hesabım içerik yenilemesi · Çıktı Raporu

**Kanal:** coder · **Tarih:** 21-09-2026 · **Format:** Anayasa §16 AI ÇIKTI SÖZLEŞMESİ + KULLANICI ADIMLARI

## Sonuç

- Tamamlanan hedef:
  - **A — Pano (dashboard):** `/my-account/` içerik override'ı (`woocommerce/myaccount/dashboard.php`, Woo 11.1.0 template @4.4.0'dan türetildi; `do_action('woocommerce_account_dashboard')` + deprecated action'lar korundu). İçerik: İletişim Bilgileri (Cep Telefonu — placeholder +90 öntanımlı, ve E-Posta; her biri AYRI Kaydet formu), Üyelik Bilgilerim (Adı, Soyadı), Opsiyonel Bilgiler (Cinsiyet select; Doğum Tarihi gün/ay/yıl üç select; tümü "(opsiyonel)" etiketli, boş gönderim meta'yı siler — veri minimizasyonu).
  - **B — İletişim Tercihleri:** yeni hesap endpoint'i `/my-account/iletisim-tercihleri/` (`add_rewrite_endpoint('iletisim-tercihleri', EP_ROOT|EP_PAGES)` + nav'da Siparişlerim'den sonra + `woocommerce_account_iletisim-tercihleri_endpoint` content callback). Üstte KVKK aydınlatma + pazarlama izni metni (**PLACEHOLDER — LEGAL_REVIEW_REQUIRED**, `docs/legal-placeholders/gizlilik-politikasi.md` taslağıyla uyumlu, dosya içine HTML comment olarak etiket düştü); altında E-Posta / SMS / Çağrı Merkezi toggle'ları + Kaydet. Meta: `sv_contact_pref_email/sms/call` ('yes'/'no'; meta yoksa 'no' — rıza varsayılan kapalı, anayasa §6.2).
  - **C — Siparişlerim:** `/my-account/orders/` override'ı (Woo @9.5.0'dan türetildi; tablo markup'ı + `woocommerce_account_orders_columns`/`actions` korundu). Tablo üstü GET formu: arama (`sv_q` — sipariş no `get_order_number()` VEYA ürün adı, order item döngüsüyle contains), dönem (`sv_period`: Son 3 ay / Son 6 ay / Son 1 yıl / Tümü), sıralama (`sv_sort`: Tarihe göre yeni/eski, Fiyata göre artan/azalan). Server-side: `woocommerce_my_account_my_orders_query` filtresiyle tek sorgu (limit -1) → dönem+arama filtresi → PHP usort sıralama → PHP sayfalama (per_page=15, `woocommerce_account_orders_per_page` filtresine bağlı); pagination linkleri GET parametrelerini taşır.
- Değiştirilen dosyalar:
  - `theme/sutre-child-v2/functions.php` — P41-A/B/C blokları: `sv41_myaccount_url()` (PHP 8.5 permalink array guard, AGENTS §5.5), `sv41_param/raw_post/notices/current_notice/redirect_notice` (PRG + beyaz-listeli notice), `sv41_handle_account_forms()` (POST işleyici: telefon/e-posta/ad-soyad/opsiyonel/tercihler), `add_rewrite_endpoint` + menü filter + `sv41_contact_prefs_content()`, `sv41_orders_filters/order_ts/orders_prepare` + `woocommerce_my_account_my_orders_query` filtresi.
  - `theme/sutre-child-v2/woocommerce/myaccount/dashboard.php` — YENİ (Woo 4.4.0 tabanlı özelleştirme).
  - `theme/sutre-child-v2/woocommerce/myaccount/orders.php` — YENİ (Woo 9.5.0 tabanlı özelleştirme).
  - `theme/sutre-child-v2/style.css` — yalnız HESABIM (P40) bölümünün devamına P41 scoped bloklar (bölüm kartları, form grid, notice, KVKK bandı, toggle switch, sipariş filtre barı; mobil ≤781px tek sütun, desktop ≥782px grid).
  - `AGENTS.md` — §7 P41 kaydı + §8 P41 dersleri.
- Veritabanı/ayar etkisi: Yeni user meta'lar yalnız kullanıcının kendi form gönderimiyle oluşur: `billing_phone`, `first_name/last_name` + `billing_first_name/billing_last_name`, `sv_gender`, `sv_birth_day/month/year`, `sv_contact_pref_email/sms/call`. Rewrite kuralı `init`'te kaydedilir; flush için kullanıcı adımı gerekli (aşağıda). Eklenti/çekirdek değişikliği YOK; DB şeması değişmedi.

## Doğrulama

- Çalıştırılan testler:
  - Sözdizimi: npm `php-parser` (gerçek PHP 8 grammar'lı tokenizer+parser) ile 3 PHP dosyası — ortamda `php` binary yok (statik PHP 8.5.8 binary sandbox'ta core dump), bu yüzden `php -l` yerine bu araç; canlı lint deploy sonrası `curl | grep Fatal` ile yapılacak (kabul ölçütü 4'ün canlı yarısı).
  - Entegrasyon çapraz kontrol: sv41_* tanım/çağrı seti (grep), nonce action adları handler↔template eşleşmesi, `sv_contact_pref_*` meta anahtarları handler↔callback↔form name tutarlılığı, GET/POST parametre adları (sv_q/sv_period/sv_sort/sv_form/sv_nonce/sv_notice) — hepsi eşleşti.
  - Woo kontrat doğrulaması: Woo 11.1.0 kaynakları GitHub raw'dan indirilip okundu (`templates/myaccount/dashboard.php`, `templates/myaccount/orders.php`, `includes/wc-template-functions.php::woocommerce_account_orders`) — template'e geçen değişkenler (`$customer_orders` paginate object, `$has_orders`, `$current_page`, `$wp_button_class`) ve `paginate=true` korunması doğrulandı.
- PASS sonuçları: 3/3 PHP dosyası syntax PASS; entegrasyon grep'leri PASS; commit'ler: `176c666` (P41a dashboard), `7b2648f` (P41b tercihler), `ebc4a2e` (P41c siparişler) — main'e pushlandı.
- FAIL / atlanan testler ve nedeni: Canlı sayfa doğrulaması (E2E: form gönderimleri, endpoint 200, arama sonuçları) staging'e deploy'dan (Hermes kanal B) SONRA yapılacak — bu turun kapsamı "push'a kadar" (pack P41). `php -l` kanıtı yukarıdaki araç kısıtıyla ikame edildi (raporda şeffaf).

## Risk ve güvenlik

- Secret veya kişisel veri etkisi: Secret YOK. Kişisel veri: kullanıcı kendi hesap bilgilerini kendisi güncelliyor; log'da telefon/e-posta/meta değeri yazılmaz (yalnız beyaz-listeli notice anahtarları URL'de taşınır: `sv_notice=phone_saved` gibi — kişisel veri değil). KVKK: opsiyonel alanlar açıkça "(opsiyonel)" etiketli, boş gönderim metayı siler; pazarlama toggle'ları varsayılan KAPALI (rıza önceden işaretlenmez — anayasa §15/§6.2); KVKK metni PLACEHOLDER, yayına hukuki onaysız çıkamaz.
- Ödeme/fiyat/stok/fatura etkisi: YOK. Ödeme akışına, fiyat/vergi/stok/fatura mantığına dokunulmadı. Sipariş araması yalnız oturum sahibinin KENDİ siparişleri üzerinde (`customer => get_current_user_id()` Woo tarafından zorlanır + filtre bunu değiştirmez); status kümesi Woo varsayılanı kalır. Form handler'ları: `is_account_page() && is_user_logged_in()` guard + form başına nonce + yetkisiz/form'suz istekte sessiz çıkış; POST→redirect→GET (PRG) ile çift gönderim engeli; notice key beyaz listesi dışı değer basılmaz (XSS yüzeyi yok, tüm çıktılar `esc_html/esc_attr/esc_url`).
- Geri dönüş adımı: Temiz tek-adım rollback: P41 commit'lerini içeren 3 commit'i `git revert 176c666 7b2648f ebc4a2e` ile geri al (revert'den sonra Ayarlar→Kalıcı Bağlantılar→Kaydet tekrar yapılmazsa eski endpoint URL'i 404 verebilir; revert sonrası da flush önerilir) VEYA tema klasörünü önceki `cp -a` yedeğine döndür. Template override'ları silindiğinde Woo çekirdek template'leri otomatik devreye girer (kırılganlık yaratmaz). Yeni user meta'lar rollback gerektirmez (izole, boş değerli olabilir).

## Açık kapılar

- NEEDS_OWNER_INPUT: KVKK bandındaki `[İŞLETME UNVANI TBD]`, `[E-POSTA TBD]`, `[TEBLİGAT ADRESİ TBD]`, `[LİNK TBD]` alanları — Blok A işletme girdileriyle doldurulacak (mevcut Blok A kapısıyla aynı girdi havuzu).
- OWNER_APPROVAL_REQUIRED: Sayfaların canlıya alınması ve görsel onay (AGENTS §0.6 — Salih'e gösterilecek; bu turda her şey staging'de kalır).
- LEGAL_REVIEW_REQUIRED: (1) İletişim Tercihleri KVKK/İYS metni ve Pano opsiyonel bölümündeki aydınlatma cümleleri PLACEHOLDER'dır — avukat onayı olmadan final sayılmaz; (2) İYS entegrasyonu ve pazarlama izinlerinin İYS ile senkronu ayrı akıştır (anayasa §7) — bu tur sadece onay KAYDINI (meta) tutar, İYS bildirimi yapmaz; canlıda İYS uyumu doğrulanmalı.
- Sonraki en küçük güvenli adım: Kullanıcı rewrite flush adımını yapar (aşağıda) → Hermes FTP deploy + LiteSpeed purge → canlı doğrulama (`/my-account/`, `/my-account/iletisim-tercihleri/`, `/my-account/orders/?sv_q=...` curl grep Fatal=0) → Salih'e görsel gösterimi.

## KULLANICI ADIMLARI (rewrite flush — TEK mini adım)

Yeni `/my-account/iletisim-tercihleri/` sayfası, WordPress'in kalıcı bağlantı kuralları yenilenmeden 404 verir. Tema aktivasyonu izlenmediği için kod içinde otomatik flush YAPILMADI (bilinçli karar — register_activation tema sürüm güncellemelerinde güvenilmez).

1. **WP Admin'e gir** (staging.sutre.store/wp-admin).
2. **Ayarlar → Kalıcı Bağlantılar** sayfasını aç.
3. Hiçbir şeyi DEĞİŞTİRMEDEN sayfanın en altındaki **Değişiklikleri Kaydet** butonuna bas. (Bu tek tık rewrite kurallarını yeniden üretir.)

Sonra: cPanel Terminal'de `git pull` + `cp -a` (deploy kanal A) VEYA Hermes FTP deploy (kanal B) → LiteSpeed Cache → **Purge All**. Doğrulama: `curl -skL "https://staging.sutre.store/my-account/iletisim-tercihleri/?v=$(date +%s)" | grep -c 'Pazarlama İletişimi'` (girişli hesapla tarayıcıdan da test edilebilir).

---

### Paket-Anayasa teknik notları (anayasa §0.10 şeffaflık kaydı)

1. **init → template_redirect:** Paket POST işleyicileri için `init` hook'u diyor; `is_account_page()` WP query'ye bağlıdır ve `init` anında güvenilir değildir. Handler'lar Woo çekirdeğinin kendi form handler pattern'i olan `template_redirect`'e alındı (nonce + guard amacı aynen korunur).
2. **Fiyat sıralaması:** `WC_Order_Query` `orderby: total`'ı desteklemez (whitelist dışı → sessiz 'date' fallback); fiyat sıralaması PHP `usort`'ta, sayfalama da filtrelenmiş sonucun DOĞRU sayfa sayısı için PHP'de yapılır (per_page=15, `woocommerce_account_orders_per_page` filtresiyle özelleştirilebilir).
3. **Telefon zorunluluğu:** Pano telefon alanı en az 10 rakam şartıyla ZORUNLU ayarlandı (kayıt formunda opsiyonel kalır — wc-rich-register dokunulmadı); boş gönderim hata notice'ı döner, veri silinmez.
