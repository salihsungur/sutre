# PACK-84 — LiteSpeed purge + `blog_public=1` (noindex kaldır) + sitemap onarımı + robots Sitemap satırı

## 0. BAĞLAM / SORUNLAR (kanıtlı)
1. **Ana sayfa cache bayat:** `https://sutre.store/` (cache-buster'sız) → `x-litespeed-cache: hit`, `sv-footer__payments=0`, eski CSS `ver=3.6.5`. Diğer sayfalar (`/shop/`, `/cart/`, `/my-account/`, hukuki) yeni. Pack-82 kanıtı: cache girdisi FTP ağacında görünmüyor; `X-LiteSpeed-Purge`, `PURGE`, `?LSCWP_CTRL=purge_all`, `wp-cron.php` etkisiz.
2. **Tüm site `noindex, nofollow`** — WP `blog_public=0` (tester pack-81: `/`, `/shop/`, `/my-account/`, `/magaza/`).
3. **Sitemap yok:** `wp-sitemap.xml`, `sitemap.xml`, `sitemap_index.xml`, `product-sitemap.xml` → hepsi 404. `robots.txt` 200 ama `Sitemap:` satırı yok.
4. **Sahip kararı (23-09-2026, birebir):** *"Şimdi açalım: noindex kalksın + sitemap kurulsun"* ve *"Evet, ikisini de paketleyip gönder"*.
5. **Kimlik:** cPanel API token dosyası HAZIR: `~/Library/Application Support/Hermes/sutre-cpanel.env` (`CPANEL_HOST`, `CPANEL_PORT=2083`, `CPANEL_USER`, `CPANEL_API_TOKEN`). **Değerleri rapora, loga, komut geçmişine YAZMA.** Token'ı asla ekrana basma (`curl -v` kullanma; gerekiyorsa `-s -o /dev/null -w '%{http_code}'`).

- İlk tool çağrıların: (1) `AGENTS.md` TAMAMI, (2) `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMI, (3) `dispatch/out/out-82-litespeed-purge.md`, (4) `dispatch/out/out-81-magaza-404-audit.md`.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**

## 1. GÖREV — merdiven (ilk çalışan yeter, her adımı kanıtla)

### Adım A — cPanel token çalışıyor mu
`curl -s -H "Authorization: cpanel $CPANEL_USER:$CPANEL_API_TOKEN" "https://$CPANEL_HOST:$CPANEL_PORT/execute/Variables/get_user_information"` → JSON dönmeli. Dönmezse token/kullanıcı hatasını **tahmin etme**, ham çıktıyı raporla ve B'ye geç.

### Adım B — TEK SEFERLİK bakım betiği (önerilen ana yol)
FTP ile docroot'a kısa ömürlü bir PHP betiği koy ve çalıştır, sonra **derhal sil**:
- Dosya: `/sutre.store/p84-maint-<RASTGELE8>.php` (rastgele ad + rastgele anahtar parametresi zorunlu; anahtar uyuşmazsa betik hiçbir şey yapmaz).
- Betiğin yapacağı (yalnız bunlar):
  1. `update_option('blog_public', 1)` → noindex kalksın.
  2. Rewrite kurallarını tazele: `delete_option('rewrite_rules')` (WP bir sonraki istekte yeniden üretir) — sitemap 404'ünün en olası nedeni budur.
  3. LiteSpeed purge: sınıf/aksiyon varsa `do_action('litespeed_purge_all');` (veya `LiteSpeed\Purge::purge_all()`), yoksa `error_log` ile "litespeed yok" yaz. **Başka hiçbir eylem yok** (ürün/sipariş/kullanıcı verisine dokunma).
  4. Yanıt olarak yalnız JSON döndür: `{blog_public: <yeni>, rewrite_flushed: true, litespeed: "purged"|"yok"}`
- **Silme zorunlu ve doğrulanmalı:** iş bitince FTP `DELE`; ardından `curl -s -o /dev/null -w '%{http_code}' https://sutre.store/p84-maint-<...>.php` → **404** görmelisin. Silemezsen **raporun en üstüne** "KRİTİK: betik silinemedi" yaz.
- Betik çalışmazsa (PHP hatası) ham çıktıyı raporla ve C'ye geç.

### Adım C — cPanel UAPI yedek yolu
- LiteSpeed'e ait UAPI modülü var mı diye sına (ör. `/execute/LiteSpeed/...`, `/execute/LiteSpeedCache/...`); 404 dönerse "modül yok" yaz.
- Cache dizinini **kanıtla**: `Fileman/list_files` ile ev dizinini listele; sutre.store'a ait cache yolu bulunursa **yalnız o girdileri** sil (`Fileman/delete_files`). Başka sitelerin (`geleceginbilimi.com`, `detoneakademi_*`) verisine DOKUNMA.

### Adım D — Doğrulama (zorunlu, cache-buster'sız düz URL'ler)
```
https://sutre.store/                 → sv-footer__payments=1 · x-litespeed-cache ne olursa olsun içerik YENİ · style ver=3.6.6 · noindex/nofollow meta YOK
https://sutre.store/wp-sitemap.xml   → HTTP 200 · içerikte <urlset> ve en az 1 <loc>
https://sutre.store/robots.txt       → 200 · "Sitemap:" satırı VAR
https://sutre.store/shop/            → 200 · payments=1 · noindex YOK
https://geleceginbilimi.com/         → 200 (dokunulmadığının kanıtı)
```
- Meta kontrolü: `grep -o '<meta name="robots"[^>]*>'`. Ham çıktıları rapora yapıştır.

### Adım E — Sitemap/robots kalıcı çözüm (tema kodu gerekiyorsa UYGULAMA, raporla)
- `wp-sitemap.xml` hâlâ 404 ise: tema `functions.php`'de `wp_sitemaps_enabled`/`wp_sitemaps_*` filtresi var mı (repoda `theme/sutre-child-v2/functions.php` içinde ara). Varsa **kod düzeltmesi yapma** — "coder paketi gerekiyor" diye raporla (dosya:satır).
- `robots.txt`'e `Sitemap:` satırı: WP `robots_txt` filtresiyle eklenir (tema kodu) → `functions.php`'de zaten var mı diye **oku**; yoksa yine "coder paketi gerekiyor" diye raporla. Bu pakette tema dosyası DEĞİŞTİRME.
- **Ama:** sitemap 200 dönüyorsa ve robots'ta satır yoksa, bunun düzeltilmesi için ne gerektiğini kanıtla (satır no + önerilen filtre kodu) ve coder paketine bırak.

## 2. ROL SINIRI
- Dokunulabilir: cPanel UAPI/token çağrıları, geçici bakım betiği (oluştur → çalıştır → **sil**), LiteSpeed cache girdileri (yalnız sutre.store).
- **YASAK:** tema/plugin kodu düzenlemek, DB'de blog_public + rewrite_rules dışında bir şey değiştirmek, başka sitelerin dosya/DB'si, ürün/sipariş/kullanıcı verisi, git commit (rapor dosyası hariç).
- Provider/model override YASAK.

## 3. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-84-purge-noindex-sitemap.md` (≤800 kelime).
- Bölümler: (1) A/B/C/D/E adım adım sonuç (ham çıktılar), (2) **betik silindi mi** (404 kanıtı), (3) blog_public önce/sonra, (4) purge sonucu (ana sayfa cache-buster'sız önce/sonra), (5) sitemap + robots durumu, (6) diğer site regresyonu, (7) coder paketi gereken maddeler (dosya:satır + önerilen kod), (8) `YAPILAMADI` listesi, (9) geri alma notu (blog_public'i 0'a döndürmek gerekirse nasıl).
- Token/şifre değeri rapora ASLA yazılmaz.