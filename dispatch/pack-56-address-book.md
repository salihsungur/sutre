# P56 — Adres Defteri sistemi (çoklu kayıtlı adres, varsayılan, sepet entegrasyonu)

**Kanal:** coder · **Tarih:** 21-09-2026 · **Repo:** `/opt/data/workspace/proje` (main)
**Önce oku:** `AGENTS.md` (§0 kurallar, §1.2 etki haritası, §5 tuzaklar — özellikle 5a-b nav key'leri), `theme/sutre-child-v2/functions.php` (P41-P55 hook deseni: sv41_notices/sv41_myaccount_url/PRG/nonce), `woocommerce/myaccount/dashboard.php` (bölüm şablonları).

## Sahibin talebi (birebir)
Mevcut adres akışı (P55 yetersiz kaldı) **tamamen baştan** yapılacak. Adres ömrü döngüsü:
- **Görme:** kayıtlı adresler kart olarak listelenir
- **Ekleme:** kullanıcı istediği kadar adres kaydeder, her birine **isim (etiket)** verir
- **Düzenleme:** mevcut adres düzenlenebilir
- **Silme:** adres silinebilir (onaylı)
- **Varsayılan:** kayıtlı adreslerden biri **varsayılan** seçilir
- **Sepet entegrasyonu:** sepette kayıtlı adreslerden **seçim** yapılır; seçilen sepete uygulanır; sepette girilen yeni adres kaydedilebilir ve kayıtlıysa **varsayılan yapılabilir**

## Adres alan şeması (SIRAYLA, sahibin tanımı)
| Alan | key | Zorunlu | Not |
| :--- | :--- | :--- | :--- |
| Adres etiketi | `label` | EVET | kullanıcı isimlendirir ("Ev", "İş" vb.) |
| İsim Soyisim | `name` | EVET | tek alan (ad soyad birlikte) |
| Cep Telefonu | `phone` | EVET | teslimat için ayrı telefon (hesap telefonundan bağımsız) |
| Adres Satırı 1 | `address_1` | EVET | |
| Adres Satırı 2 | `address_2` | opsiyonel | |
| Ülke | `country` | SABİT | **TR kilidi** — readonly "Türkiye" (dış satış yok) |
| Şehir | `city` | EVET | text input (İstanbul vb.) |
| İlçe | `district` | EVET | text input |
| Mahalle / Köy | `neighborhood` | EVET | text input |
| Posta Kodu | `postcode` | EVET | text input |
| TC Kimlik No | `tc` | opsiyonel | verildiyse 11 hane rakam zorunlu (regex `^\d{11}$`) |

## Veri modeli (tek kullanıcı meta — DB şeması YOK)
- `sv_address_book` (user meta, array): her öğe `['id' => uniqid-style string, 'label', 'name', 'phone', 'address_1', 'address_2', 'country' => 'TR', 'city', 'district', 'neighborhood', 'postcode', 'tc', 'created' => time]`
- `sv_default_address` (user meta, string = id); silinen varsayılanın yerine en eski adres otomatik varsayılan olur.

## Hesabım entegrasyonu
- Nav: **'edit-address' key'ini çıkar, yerine 'adreslerim' → "Adreslerim"** (P41-P50 filtresi aynı fonksiyonda güncellenir; nav key tuzak §5a-b).
- `/my-account/adreslerim/` endpoint (`add_rewrite_endpoint`, EP_ROOT|EP_PAGES) + content callback + `woocommerce_account_adreslerim_endpoint`. **Rewrite flush kullanıcı adımı olacak — rapora yaz.**
- Sayfa: adres kartları (etiket, isim, adres özeti, varsayılan rozet) + kart aksiyonları: **Düzenle / Varsayılan yap / Sil** (her biri nonce'lu POST, PRG + sv41_notices). Yeni/Düzenle formu aynı endpoint üzerinde `?duzenle=<id>` ile.
- **edit-address endpoint'i redirect** → adreslerim (P50'deki edit-account redirect deseniyle; save handler'ları kırma).
- Kaydetme sonrası: adres varsayılan ise `WC()->customer` shipping alanlarına da uygulanır (sepet/checkout anında doğru).

## Sepet entegrasyonu (klasik cart, P47-P55 üzerine)
- Girişli kullanıcıda `woocommerce_before_shipping_calculator` (veya hesaplayıcı üstü) konumuna **kayıtlı adres seçici**: radio listesi (etiket + kısa adres), seçim + "Uygula" POST (nonce'lu) → seçilen adres `WC()->customer` shipping alanlarına yazılır (city/district→state/postcode/address_1&2/phone) + `WC()->customer->save()`.
- "Farklı adres kullan" → mevcut Woo hesaplayıcı formu açılır (P55 kart CSS'i) + içine: "Bu adresi hesabıma kaydet" (etiket input'u ile; etiket zorunlu) + "Varsayılan adres yap" checkbox'ı → POST işleyicisi adresi `sv_address_book`'a ekler ve istenirse varsayılan yapar (P55'teki doğrudan shipping meta persist bu akışta KALDIRILIR — adres defteri tek kaynak).
- Checkout ön-doldurma: seçili/varsayılan adres checkout'ta shipping olarak dolu gelir (Woo customer üzerinden).
- Misafirlerde mevcut Woo hesaplayıcı aynen kalır.

## Doğrulama zorunlulukları
- Sözdizimi: php-parser yöntemi (php binary yok).
- Nonce + `is_user_logged_in` + capability guard her POST'ta; tüm çıktılar escape; `sv_address_book` sanitize (her alan wc_clean; tc preg).
- PHP 8.5 tuzakları, nav key tuzuakları (§5a-b), header/footer dokunulmaz, CSS yalnız style.css (P55 bloğu güncellenir).
- Canlı: guest sayfalarda Fatal 0; nav "Adreslerim" render kanıtı logged-in curl mümkün değilse kod satır kanıtıyla.

## Kapsam dışı
- Checkout bloğu değişikliği, fatura/gönderim ayrımı (tek adres tipi = teslimat), CSV export, adres doğrulama API'leri.

## Teslim formatı
- Atomik commit'ler (data-model+endpoint / sepet entegrasyonu / CSS) + main push + AGENTS.md §7 kaydı (SEN yaz) + `dispatch/out/out-56-address-book.md` (§16 formatı + KULLANICI ADIMLARI: rewrite flush).
