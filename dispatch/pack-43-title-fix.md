# P43 — Ürün kartı başlığı ("Jakarlı Şal") solda yapışık: KESİN teşhis + fix

**Kanal:** coder · **Tarih:** 21-09-2026 · **Repo:** `/opt/data/workspace/proje` (main)
**Öncelik:** Sahip 4 turdur bu hatayı bildiriyor; TAHMİNLE değil, KÖK NEDEN KANITIYLA çözülecek. Önce oku: `AGENTS.md` (§5 tuzaklar dahil).

## Semptom (sahibin birebir ifadesi)
Shop/ana sayfa ürün kartında beyaz bant içindeki başlık "Jakarlı Şal" **sol kenara yapışık** duruyor. Fiyat satırı konum değişikliklerine cevap veriyor, BAŞLIK hiç cevap vermiyor. Sahibin ekran görüntüleriyle 3 kez teyit edildi.

## Denenmiş ve yetersiz kalan fix'ler (bunları tekrar deneme)
- P42.2: başlık akışa alındı (`position: static`, padding 18px 20px 0)
- P42.3: padding 20px 26px 0'a çıkarıldı → fiyat hareket etti, başlık ETMEDİ
- P42.4: seçici `ul.products li.product h2.woocommerce-loop-product__title` + `position:static !important` yapıldı → **hâlâ çözülmedi (sahip onayı)**
- CSS cache-buster SUTRE_VERSION 3.2.0→3.3.2 artırıldı; canlı sayfa `?ver=3.3.2` çekiyor (curl kanıtlı) → tarayıcı cache'i KÖK NEDEN DEĞİL.

## Görev
1. **Kesin teşhis:** Canlı sayfayı `curl -sk https://staging.sutre.store/shop/` ile çek; yüklenen TÜM CSS dosyalarının listesini çıkar; her birini indir; içinde `.woocommerce-loop-product__title` VEYA başlığı etkileyen (h2, `:where()`, theme.json türevi `padding`, `margin`, `text-indent`, `padding-left: 0` vb.) kuralı **dosya+satır kanıtıyla** bul. Woo core CSS'e wp.org/GitHub raw'dan erişilebilir; block CSS paketlerini de kontrol et (`packages/woocommerce-blocks/build/`). Kazanan kuralın hangi mekanizmayla kazandığını yaz (özgülük hesabı / kaynak sırası / !important / :where sıfırlaması).
2. **Fix:** Teşhisin gösterdiği mekanizmaya karşı garantisiz çözüm YOK: kazanan mekanizma neyse onu mat et (ör. kazanansa kaynak sırası → temanın kuralını daha geç yükleyecek şekilde enqueue önceliği; kazanansa daha yüksek özgülüklü kural → onu geç; `!important` gerekiyorsa kullan). Fix sonrası **aynı teşhis yöntemiyle** doğrula: yaptığın kuralın kazanan olduğu çıktıda görünür olmalı.
3. **Görsel kanıt:** Doğrulamayı yalnız CSS grep'iyle bırakma — curl ile çekilen HTML+CSS zincirinde padding-left değerinin 26px'e ulaşmasının hesabını rapora yaz (hangi kural, hangi satır, neden kazanıyor).
4. Sadece `style.css` (ve gerekiyorsa `functions.php` enqueue sırası) — header/footer, başlığın YERİ değişmeyecek (beyaz bant düzeni sahibin onaylı), yalnız SOL BOŞLUK sorunu çözülecek.

## Kabul ölçütleri
- Canlı üretim CSS zincirinde başlığın padding-left'i %100 uygulanıyor (kazanan kural bizimki, raporda hesaplı kanıt).
- Fiyat/bant/rozet düzeni bozulmuyor; mobil ≤781px kontrolü.
- `php -l` karşılığı sözdizimi kontrolü (php binary yoksa mevcut php-parser yöntemi).
- Commit atomik + main push + AGENTS.md §5a güncelle (gerçek kök nedeni yaz) + `dispatch/out/out-43-title-fix.md` raporu (§16 formatı).

## Kapsam dışı
- Kart yerleşiminin yeniden tasarımı, rozet, hover, header/footer, diğer sayfalar.
