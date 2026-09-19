# Çeviri / Yerelleştirme Stratejisi — staging görünür metinler

- Belge türü: durum + strateji (P19, Faz 2/3). Tarih: 2026-09-15. Yazar: @coder (bot).
- Bağlam: P17/P18 kanıtı — WP core, WooCommerce ve tema çevirileri Türkçe (wp-admin → Updates / "Çevirileriniz güncel"). Görünen İngilizce metinler öncelikle **Twenty Twenty-Five blok tema şablonlarında ve page editor'e elle eklenmiş blok içeriklerinde hard-coded** string'lerden gelir; plugin/core çevirisiyle ilgisi yoktur.
- İlkeler: anayasa §3.1 (core değişikliği YOK, child/blok theme katmanı), §3.2 (küçük iş için eklenti YOK), §15 (dil/UYARI yasak listesi). STAGING'de metin kanıtı SS kanıtı + bu belge.

## Durum dökümü (2026-09-15)

| Yerleşim | Dil | Kaynak | Düzeltme kim/yol |
|---|---|---|---|
| WP-admin | tr_TR ✓ | Core çeviri | — |
| WooCommerce front-end | tr_TR ✓ | Core çeviri (akış tamam; eşik istisnada içinde blok string olabilir) | — |
| Checkout/Login/Register block string'leri | kısmen tr | WC block çevirisi | bot edit yapmaz — editor (sahip) |
| My Account kayıt formu (mu-plugin) | tr | P19 snippet | bu paket |
| Blok şablonları / Twenty Twenty-Five | hard-coded yazılmış | tema php + block markup | sahibin Faz 2/4 FULL REDESIGN |

## Strateji (sahibin seçime sunulan; bot sunucu edit'i YAPMAZ — erişim yok)

1. **WP-admin → Settings → General → Site Language + Users → Profile → Locale**: zaten tr_TR; kanıt SS-0 P17. `Updates` ekranında "Çevirileriniz güncel" göründüğü doğrulanmıştır (P13/P17).
2. **Gettext filtresi (isteğe bağlı küçük mu-plugin)**: core/plugin çeviri katmanından geçen ama eksik kalan string'ler `gettext` filtresiyle (mu-plugin pattern) tek tek Türkçe'ye çevrilebilir; küçük sayıda eksik string için, aynı rollback mantığı (dosya sil).
3. **Tema/blok şablonları (yol A)**: `wp-content/themes/...` altındaki .php ve blok şablonlarındaki İngilizce string'leri sahibin elle Türkçe yapar — bot FTP/cPanel erişim yok; büyük edit'te child theme directory önerisi (Faz 2/4 redesign ile birleştirilmesi düşünülür).
4. **Page editor blok içerikleri (Sayfalar)**: Checkout / My Account / Privacy vs. page'lerde editor'e elle eklenen blok metinleri **WP-admin page editor'de sahibin kendisi Türkçe yazılır** — bot içerik UYDURAMAZ (§15). Login/Register Button/Yöntem B'de yazılan "Hesabınız yok mu?" gibi metinler bu kapsamdadır.

## Karar kaydı (buradan çıkan iş)

- Şimdi (P19/Faz 2/3): yalnız kayıt formu görünen metinleri Türkçe (mu-plugin snippet, bu paket).
- Faz 2/4 (sahibin takvimi): **tema FULL REDESIGN paketi** — başlık/görsel / blok şablon/visual identity tam Türkçe + SUTRE marka ile yeniden inşa; şimdiye dek görünen İngilizce string'ler **GEÇİCİ kabul edilir** ve TODO kayda bağlanır.
- P18 notu: checkout Login block string'leri page editor'de sahibin elle çevirebilir; sadece elle müdahale gereken blokların listesi aşağıdaki sayfada.

## Red / geri dönüş

- Her değişiklik blok/mu-plugin düzeyinde; page revizyon veya snippet silme ile geri alınır; DB yazımı yok (kullanıcı meta hariç, staging).
