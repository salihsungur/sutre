# PAKET 25 — Ana Sayfa Butik Giyim Marketi Satış Sayfası (AI ÇIKTI SÖZLEŞMESİ §16)

## Sonuç
Ana sayfa sahibin 2026-09-17 bağlayıcı kararıyla butik giyim marketi satış
sahfesine dönüştürüldü. Sıra: satış bandı (Marine/Ink renk şerit — içerik/rakam
YOK, §0.7 tahmin yasağına uyum) → hero (Sutre wordmark + tagline + CTA,
korunur) → **ÜRÜNLER** ana ürün grid'i (WooCommerce resmi `[products limit="10"
columns="1"]` shortcode'u; mobil 1 kolon, ≥782px 2 kolon; "Tümünü Gör →"
/shop/'e bağlanır) → **KATEGORİLER** (İpek / Pamuk / Bambu; P23 kart tasarımı
korundu, içerik dilinde yalnız ürün bilgisi + "Keşfet" mikro-link; hype/ikna
metni YOK). "Hikâyemiz"/"Neden Sutre?" (P24 kararı) geri GELMEDİ. Header/footer
block part'ları (tek kaynak) dokunulmadı. P24 hâlindeki bozuk `<li>` markup ve
duplicate "Koleksiyon" başlığı aynı feat'te düzeltildi. Placeholder görsel
WooCommerce çekirdeğinden (`woocommerce-placeholder.webp` 200 OK kanıtılan)
kendiliğinden render olur; del (çizgili regular) + ins (bold Silk sale) fiyat
biçimi WooCommerce core markup'ını override.css stilize eder (çekirdek kod
dokunuşu yok, anayasa §4/§3.1).

## Doğrulama
- PHP syntax: php-parser (npm) ile home.php → "PHP SYNTAX OK" (lokal PHP ikiliği yok; kanıt /tmp npm php-parser çalıştırma çıktısı).
- `grep -c "sutre-story|sutre-values"` → home.php 0, override.css 0.
- override.css'te hardcoded hex yok — yalnız footer'daki #FFFFFF (P22'den korunur).
- Git: feat SHA `632c764` (main), `git push` kanıtı: `42d70c2..632c764 main -> main` (staging repo'ya; "Everything up-to-date" ikinci çağrı).
- WP pointer: page 18 `sutre-anasayfa` template `page-templates/home.php`; staging API 200 (curl).
- WP debug fatal/warning kanıtı: sahibin cPanel/WP-admin'elle (deploy sonrası) — bot FTP yapmaz (paket kararı).

## Risk ve güvenlik
- Risk: DÜŞÜK. Yalnız ana sayfa şablonu + tek CSS blok; çekirdek/plugin/checkout dokunuşu YOK.
- Fake/target veri (kargo süresi, %KDV, hype metni) yazılmadı — §0.7 ve §15 bütünüyle uyumlu.
- Renk şeridi içeriksizdir: sahibin onayı olmadan hiçbir vaat/rakam içermez.
- Depolama: local repo'da feat SHA hazır; push yapıldı; deploy sahibin elle
  cPanel/FTP işi — paket kuralına göre bot DEPLOY YAPMAZ (§16 beyanı).

## Açık kapılar
- OWNER_ACTION: cPanel FTP ile `theme/sutre-child/page-templates/home.php` +
  `theme/sutre-child/parts/override.css` staging'e elle upload et (iki dosya);
  sonrasında ana sayfayı elle doğrula (kabul kriteri 1–5).
- NEEDS_OWNER_INPUT: kartlarda "renk varyant sayısı" yazısı — WooCommerce
  varyasyon metası hiç yok; sahibin sahte rakam girmeden elle doldurması için
  ürün editörüne kısa açıklama eklemesi gerekir (anayasa §5 / §0.7; rakam YOK).
- Sonraki en küçük güvenli adım: deploy sonrası mobil 375px + desktop wide
  elle kontrol; geribildirim gelirse tek line yönelik mikro CSS ince ayar paketi.
