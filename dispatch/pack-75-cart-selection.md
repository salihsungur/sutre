# P75 — Sepet sayfası baştan tasarım: kart görünümü + ürün seçimi + bekleyen ürünler

**Kanal:** coder · **Tarih:** 23-09-2026 · **Repo:** `/opt/data/workspace/proje` (main)
**Önce oku:** `AGENTS.md` (§5 tuzaklar, §7 P47/P48/P56-P58 kayıtları — klasik sepet + adres defteri bağlamı), `theme/sutre-child-v2/functions.php` (P56-P68 hook desenleri), `style.css` (P74/Tur3 sepet bloğu).

## Sahibin birebir talebi
1. **Ürün fotoğrafı mobil görünümde görünmüyor** (P74 stack'te thumbnail kayboluyor) — fix.
2. **Ürünler liste değil KART kart görünsün** (mobil + masaüstü) ve her üründe **seçme hakkı** olsun: müşteri sepette 5 ürün varken 3'ünü seçip **yalnız seçilenlerle ödeme** yapabilsin.
3. **"Kupon kullan" ve "Ödeme sayfasına git" butonları mobilde sola yapışık** → ortalama veya tam genişlik.
4. **Sepet sayfası genel anlamda güncellensin** (yalnız responsive değil — tasarım + işlev birlikte; görsel onay sahibin).

## Özellik spesifikasyonu: "Seç ve Öde" (Bekleyen Ürünler sistemi)
- Her sepet kartında **checkbox** (varsayılan İŞARETLİ): "Ödemeye dahil et".
- **"Seçilenlerle Ödemeye Geç"** butonu → seçili olmayan ürünler sepatten çıkar ve **"Bekleyen Ürünler"** listesine taşınır → checkout'a yönlendirilir.
- **Bekleyen Ürünler** sepette ayrı bölüm: ürün kartı + **"Sepete Geri Al"** butonu (ürün sepete geri eklenir, bekleyen listeden çıkar).
- Tüm ürünler bekleyenlere taşınırsa: klasik boş sepet mesajı + bekleyen ürünler bölümü gösterilir.
- **Veri modeli:** bekleyen ürünler `WC()->session` içinde (`sv_cart_parked`: product_id, variation_id, variation attrs, quantity). Hem misafir hem üye çalışır (Woo session cookie). Login sonrası sepet birleşimi Woo'ya aittir — dokunulmaz.
- **Akış:** JS `fetch` → `admin-ajax.php` `sv_park_unselected` action (nonce `sv_cart_selection` + seçili item key listesi POST) → sunucu: seçilmeyenleri park et → JSON `{redirect: checkout_url}` → JS `window.location`. JS'siz fallback: buton normal form submit davranışında kalır (tüm ürünler checkout'a — bilinçli graceful degradation).
- **Geri alma:** her bekleyen kartta form POST (`sv_restore_parked`, nonce) → `WC()->cart->add_to_cart(product_id, qty, variation_id, attrs)` + park listesinden çıkar → PRG redirect.

## Sepet sayfası yeniden tasarım (P75 CSS — style.css P74 bloğunun yerine P75 bloğu)
- **Desktop:** 2 kolon — sol: ürün kartları (dikey liste), sağ: Sepet Özeti paneli (subtotal, kupon, toplamlar, "Seçilenlerle Ödemeye Geç" + "Sepeti Güncelle"). `cart-collaterals` bu panele dönüşür.
- **Ürün kartı:** thumbnail (140px) solda / üstte mobil, ürün adı, birim fiyat, adet seçici (Mobil: tam genişlik — P47 dili), ara toplam, × kaldır sağ üst, **seçim checkbox'ı sol üst**. Kart: beyaz, hairline çerçeve.
- **Mobil (≤781px):** tek kolon, kartlar alt alta; kupon + butonlar tam genişlik ortalı; P74'ün data-title stack hack'i KALDIRILIR (kart tasarımı stack'i gereksiz kılar).
- **Görsel düzeltme:** thumbnail mobilde görünmez sorunuyu kökten çözer (kart düzeni).
- Kupon: "Kupon kodunuz var mı?" açılırDetay + input + "Uygula" — desktop sağ panelde.
- Mevcut Tur3 responsive kuralları kart düzenine göre revize edilir; eski data-title stack kuralları silinir.

## Teknik gereklilikler
- Kart item key'leri (`$cart_item_key`) seçim ID'si olarak kullanılır; park/restore işlemlerinde `WC()->cart->get_cart_item($key)` üzerinden veri okunur.
- Tüm POST'lar: nonce (`sv_cart_selection` / `sv_restore_parked`) + `WC()->cart` varlık kontrolü + sanitize.
- JS: vanilla, inline (P67 çerez bandı deseni); `fetch` + JSON; hata halinde sayfa yenileme fallback.
- `wp_mail`/e-posta yok. PayTR/checkout akışına kod DOKUNMAZ (yalnız sepetteki ürün kümesi değişir).
- Misafir akışı: seçim/park session ile çalışır; checkout yine üyelik zorunlu (login'e yönlenir — mevcut davranış).

## Kabul ölçütleri
1. Sepet kart görünümü (desktop 2 kolon / mobil tek kolon), thumbnail HER yerde görünür.
2. Checkbox seçimi → "Seçilenlerle Ödemeye Geç" → yalnız seçilenler checkout'ta; diğerleri Bekleyen Ürünler'de.
3. Bekleyen üründen "Sepete Geri Al" → sepete döner.
4. Kupon + butonlar mobilde tam genişlik/ortalı.
5. php-parser PASS; guest curl E2E: add_to_cart → park → cart HTML kontrolü (seçilen kalır, park listesi doğru) → restore → sepete döner. (curl ile session cookie jar kullan — bot yapabilir.)
6. Header/footer teması dokunulmaz; CSS yalnız style.css P75 bloğu.

## Kapsam dışı
- Bekleyen ürünlerin kalıcılığı login sonrası (session bazlı; kalıcı kaydetme ileride), e-bülten, İYS.

## Teslim
- Atomik commit'ler + push + AGENTS.md §7 kaydı (SEN yaz) + `dispatch/out/out-75-cart-selection.md` + KULLANICI ADIMLARI (LiteSpeed Purge All).
