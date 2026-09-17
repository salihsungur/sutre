# PAKET 24 — Ana Sayfa %100 Satış Odaklı Refactor (AI ÇIKTI SÖZLEŞMESİ §16)

## Sonuç
home.php P23'ten kısmen geri alındı: `sutre-story` ("Hikâyemiz") ve `sutre-values`
("Neden Sutre?") bölümleri bütünüyle silindi. Ana sayfa artık hero → tek satır
tanıtım cümlesi → koleksiyon kartları → WooCommerce `[products limit="2" columns="2"
visibility="featured"]` section'ından oluşuyor. Featured başlığı satıcı dili
içermeyen "Koleksiyon"a sabitlendi. Hero (wordmark + tagline + "Koleksiyonu Keşfet"
CTA) ve header/footer dokunulmadı. Koleksiyon kartları premium görünümüyle korundu;
mikro-CTA/promosyon dili eklenmedi. override.css'ten story/values CSS blokları
silindi; collection/featured stilleri ve 44px dokunma hedefleri korundu; yeni
`.sutre-home__intro` tek satır tanıtım (slogan değil, seri tanımı).

## Doğrulama
- `grep -c "sutre-story|sutre-values" home.php` → 0 (kanıt: terminal çıktısı, exit 1 = eşleşme yok)
- home.php'de yalnız sutre-hero / sutre-collection / sutre-featured section'ları kaldı.
- override.css'ten `sutre-story`/`sutre-values` kuralları temizlendi (grep → boş).
- Yerel `php -l` çalıştırılamaz (bu ortamda PHP yok) — sahibin cPanel adımına bırakıldı, dokümante edildi: docs/operations/p24-owner-manual-steps.md.
- Shas: feat d1ae48a, docs (aşağıda ikinci commit SHA).

## Risk ve güvenlik
- WooCommerce Products kısa kodu resmi hook; çekirdek dokunuş yok (anayasa §3.1).
- Header/footer block part'ları değiştirilmedi; footer fix bozulmadı.
- Risk düşük: yalnızca ana sayfa şablonu + tek CSS; rollback = `git revert <SHA>`.

## Açık kapılar
- OWNER_ACTION: cPanel'de `php -l` + staging görüntü kontrolü (adımlar owner-manual dosyasında).
- Sonraki en küçük güvenli adım: "Hikâyemiz" içeriğinin /biz-kimiz sayfasına taşınması (ayrı paket).
