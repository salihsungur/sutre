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
- Git: feat SHA'ları `632c764` (P25 feat) + `5181508` (visibility fix);
  docs SHA `6431078` (out-25 raporu). `git push` kanıtı: `42d70c2..632c764`,
  `6431078..5181508` (staging repo'ya push edildi).
- Staging'den bağımsız doğrulama (P25 deploy ÖNCESİ): WooCommerce Store API
  iki ürünü 200 ile verir (id 22 İman Nour Şal: 6 Renk varyantı, regular
  ₺499,90 çizgili + sale ₺449,90; id 29 Jakarlı Şal: 10 Renk varyantı) —
  `[products]` shortcode'u deploy sonrası bunları grid'e render edecektir.
- Tarayıcı (Hermes browser) ile staging açıldı: P24 hâli hâlâ yayında
  (deploy sahibin elle işi); P24'te `visibility="featured"` shortcode'unun
  BOŞ render ettiği tespit edildi — 5181508'te bu param kaldırıldı.

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
