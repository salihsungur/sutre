# Checkout Login Formuna "Hesap Oluştur (Kayıt)" Eklenmesi — Sahip Talimatı

- Belge türü: Elle uygulama talimatı (sahibin WP-admin erişimi var; bot erişim yok).
- Tarih: 2026-09-15 (P18, Faz 2/2). İlgili kanıt: `dispatch/out/out-17-woo-install.md` maddeleri 7-9.
- İlke: Kod/plugin YOK (anayasa §3.2); yalnız WP-admin editor işlemleri. Her adımdan sonra ekran görüntüsü (SS) alın; kanıt dosyasına kaydedin.

## 0. Ön kontrol (2 dakika)

1. WP-admin → Settings → General.
2. "Membership" bölümünde **"Anyone can register"** checkbox durumuna bakın.
   - ON ise kayıt açıktır — bu bilinçli tercih mi? (P17'de guest checkout OFF ile birlikte tutarlıdır; değilse kapatın.)
   - OFF ise "Hesap oluştur" kayıt formu checkout'ta çalışmaz; bu durumda aşağıdaki blok ekleme YAPILMAZ, önce karar verin (NEEDS_OWNER_INPUT).
3. SS-0: bu ayar sayfasının görüntüsünü alın.

## 1. Checkout page'i editor'de açma

1. WP-admin → Pages → **Checkout** → Edit.
2. Editörün block (Gutenberg) görünümünde olduğunu doğrulayın; "Checkout" WooCommerce block'unun (geniş bin blok) sayfada durduğunu görün.
3. SS-1 alın.

## 2. Login block'un konumu

P17'de Login formu checkout'un üstünde görünüyor (Login block "Display as form" modunda). Editor'de bu Login block'u bulun:

1. Checkout block içindeki yapılara bakın; "Log in" başlıklı küçük blok üst kısımda.
2. Bloğa tıklayın; sağ panelde (Settings sidebar) "Display style" seçeneklerini görün: "Compact / Form" vb. P17'de Form seçilmiş.
3. SS-2 alın (Login block + sidebar).

## 3. Kayıt formu bloğunun eklenmesi

Login block yalnız login formu render eder (P17 gözlemi: "Kayıt ol" görünmüyor). Çözüm: Yanına ayrıştırılmış kayıt/action satırı eklemek.

Yöntem A (önerilen, WooCommerce 11.1):
1. Login block'un HEMEN altında blok sınırını seçin (+ işareti görünene kadar).
2. **"My Account" / "Account" block grubu** içinde WooCommerce'in sağladığı login/register davranışını içeren blok varsa onu ekleyin. (WooCommerce Block ürün ailesinde "Account" blokları login+register kombinasyonunu sayfa yerleşimine göre render eder.)
3. Blok ayarlarında "Allow customers to create an account" benzeri toggle AÇIK olmalı — yalnız adım 0'daki "Anyone can register" ON ise etkilidir.
4. SS-3 alın.

Yöntem A çalışmazsa Yöntem B (paragraf + kısa kod değil; ancak bloklar):
1. Login block'un altına bir **Paragraph** veya **Buttons** block ekleyin.
2. Metin: "Hesabınız yok mu? [Hesap oluştur]" — link hedefi `/my-account/` (My Account page'de register formu varsa "registration complete" akışı orada tamamlanır).
3. Serbest bağlantı ../shop/.. gibi GEREKSİZ hedeflere vermeyin; yalnız My Account page'i.
4. SS-4 alın.

## 4. Doğrulama (front-end)

1. Gizli sekmede staging checkout'u açın (giriş YAPMADAN).
2. Login formunun altında eklediğiniz "Hesap oluştur" görünmeli; tıklanınca kayıt akışına gitmeli.
3. "Pont ol / footer" erişelim — kayıt formunu doldurup testte bir test hesabı oluşturmayı deneyin (staging'de güvenli).
4. Test hesabıyla tekrar checkout'a girin; login formunun artık "Hello, <kullanıcı>" yerine checkout'u sürdürdüğünü doğrulayın.
5. SS-5..SS-7 alın; her SS dosya adının `p18-login-<adım>.png` formatında kaydına kanıt klasörüne koyun.

## 5. Red/geri dönüş

- Her adım blok tabanlıdır; silme = state geri alma. Page'i revizyonlara geri döndürme: Edit ekranı → Revision. Kod değişikliği olmadığından rollback dosya düzeyinde gerekmez.

## 6. İlgili kapılar

- NEEDS_OWNER_INPUT: "Anyone can register" tercih kararı; register linkinin My Account'a yöneltilmesi Yöntem B'de seçilirse.
- Uygulanamayan durum: checkbox OFF + sahibin kaydı istememesi — o zaman hiçbir şey eklenmez, checkout "during checkout account creation" akışı yeterlidir (P17 kararı).
