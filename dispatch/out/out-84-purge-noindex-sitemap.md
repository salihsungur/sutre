# PACK-84 — LiteSpeed purge + `blog_public=1` + sitemap onarımı + robots `Sitemap:` satırı

Tarih: 23-09-2026 · Kanal: FTP (ftplib) + curl/urllib (cache-buster'sız düz URL) + cPanel UAPI token
Kanıt dizini: `dispatch/out/logs/p84/` (ham çıktılar) · Token/şifre değeri bu rapora YAZILMADI

## 1. Adım adım sonuç

**A — cPanel token:** İlk çağrı `env`'deki kullanıcı ile **HTTP 401 + cPanel Login sayfası** (`title=cPanel Login`) — token değil, **kullanıcı adı** yanlış: dosyadaki `CPANEL_USER` FTP alt hesabı (`sutredeploy@…`), cPanel API ise **hesap kullanıcısı** ister. Hesap kullanıcısı ile aynı token: **HTTP 200** `{"metadata":{},"messages":null,"status":1,…,"data":{…}}`. → Token geçerli; env dosyasındaki kullanıcı alanı düzeltilmeli.

**B — tek seferlik bakım betiği (ANA YOL, BAŞARILI):** `/sutre.store/p84-maint-<rastgele8>.php` (rastgele ad + 32-hex anahtar; anahtarsız çağrı 403 `forbidden`). FTP `STOR` → geri indirip **SHA-256 PASS** (972 B). Çalıştırma ham çıktısı:

```
BETIK HTTP: 200
BETIK GOVDE: {"blog_public":1,"rewrite_flushed":true,"litespeed":"purged"}
ANAHTARSIZ CAGRII HTTP: 403 GOVDE: forbidden
```

Betik yalnız: `update_option('blog_public', 1)` → `delete_option('rewrite_rules')` → `do_action('litespeed_purge_all')`. Ürün/sipariş/kullanıcı verisine dokunulmadı.

**C — cPanel UAPI yedek yol:** `LiteSpeed/get_litespeed_info`, `LiteSpeedCache/get_status`, `LiteSpeed/status` → **HTTP 200 + modül yok**: `Failed to load module “LiteSpeed”: … Can't locate Cpanel/API/LiteSpeed.pm in @INC` → **"modül yok"**. Cache dizini kanıtı (salt-okunur): UAPI `Fileman/list_files` (HTTP 200) ve FTP `CWD` — `/sutre.store/wp-content/litespeed/` yalnız `qc.*`, `robots.txt`, `.htaccess`, `debug/` içeriyor; **`cssjs/` ve `cache/` yok** → silinecek girdi yok. `/sutre.store/wp-content/cache` → `550 No such file or directory`. **Hiçbir dosya silinmedi/yazılmadı; başka sitelere dokunulmadı.**

**D — doğrulama (cache-buster'sız, cache-öncesi/sonrası):**

ÖNCE: `/` → `x-litespeed-cache: hit` · `sv-footer__payments=0` · `style.css?ver=3.6.5` · `<meta name='robots' content='noindex, nofollow' />`
SONRA:
```
/            HTTP 200 | miss→(2. istek)hit | payments=1 | ver=3.6.7 | meta-robots: max-image-preview:large | noindex=0
/shop/       HTTP 200 | hit              | payments=1 | ver=3.6.7 | noindex=0
/cart/       HTTP 200 | miss             | payments=1 | ver=3.6.7 | noindex=0
/my-account/ HTTP 200 | miss             | payments=1 | ver=3.6.7 | noindex=0
/product/jakarli-sal/, /product/iman-nour-sal/, /mesafeli-satis-sozlesmesi/ → 200 · payments=1 · ver=3.6.7 · noindex=0
/magaza/     HTTP 404 | noindex=0 (bkz. §7-3)
```
`Fatal error=0`, `Warning=0` tüm sayfalarda. Ana sayfa cache'i **yeni içerikle doldu** (`hit` + `ver=3.6.7` + `payments=1`) → pack-82'nin bayat girdisi çözüldü.

**E — sitemap/robots:** `theme/sutre-child-v2/functions.php` (1873 satır) ve tema genelinde `robots_txt`, `wp_sitemaps*`, `sitemap`, `blog_public`, `noindex` dizgeleri **0 eşleşme** → sitemap'in kapanma nedeni tema değil, çekirdek ayarıydı. `blog_public=1` + rewrite tazeleme sonrası sitemap çekirdekten açıldı ve robots satırını **WP çekirdeği** ekledi → **coder paketi gerekmiyor**.

## 2. Betik silindi mi
Evet. FTP `DELE` sonrası liste: `p84-maint-*` eşleşmesi **[]**; `curl -o /dev/null -w %{http_code} https://sutre.store/p84-maint-<…>.php` → **404**. Yerel kopya da silindi.

## 3. blog_public önce/sonra
Önce `0` (kanıt: site geneli `noindex, nofollow`), sonra **`1`** — betik yanıtı `"blog_public":1`, canlı meta artık `max-image-preview:large`.

## 4. Purge sonucu
`"litespeed":"purged"`; `/` ilk istek `miss` → sonraki `hit` ve içerik yeni (`payments=1`, `ver=3.6.7`). Ham kanıt: `logs/p84/{before,after}.txt`.

## 5. Sitemap + robots durumu
```
/wp-sitemap.xml → HTTP 200 (686 B) <sitemapindex> 6 <sitemap>/<loc>:
  posts-post-1 · posts-page-1 · posts-product-1 · taxonomies-category-1 · taxonomies-product_cat-1 · users-1
/wp-sitemap-posts-product-1.xml → 200 (2 <loc>) · /wp-sitemap-posts-page-1.xml → 200 (13 <loc>)
/sitemap.xml → 200 (aynı içerik) · /sitemap_index.xml → 404 (çekirdek üretmez; SEO eklentisi yok)
/robots.txt → 200 (358 B) "Sitemap: https://sutre.store/wp-sitemap.xml"
```

## 6. Diğer site regresyonu
`geleceginbilimi.com` → **200** (173.051 B, ver 7.1.2, değişmedi) · `staging.sutre.store` → **200** (ver 3.6.6, kapsam dışı, dokunulmadı).

## 7. Coder paketi gereken maddeler
1. **Gerekmiyor — sitemap + robots** (E: çekirdek çözdü).
2. **env düzeltmesi (kod değil, sahip işi):** `~/Library/Application Support/Hermes/sutre-cpanel.env` → `CPANEL_USER` hesap kullanıcısına çevrilmeli; aksi hâlde her UAPI çağrısı 401 döner.
3. **Öneri (opsiyonel, dosya:satır `functions.php` sonu):** 404 sayfası artık `noindex` meta taşımıyor (HTTP 404 durumu Google için yeterli). İstenirse: `add_filter('wp_robots', function($r){ if (is_404()) { $r['noindex'] = true; unset($r['max-image-preview']); } return $r; });`
4. **Öneri:** `/sitemap_index.xml` isteniyorsa SEO eklentisi ya da aynı filtreden 301 gerekir (şu an 404).

## 8. YAPILAMADI
- cPanel **LiteSpeed UAPI modülü yok** → cPanel üzerinden purge rotası bu hesapta kullanılamıyor (WP tarafı purge yeterli oldu).
- `AGENTS.md` §7 kaydı bu pakette **yazılmadı**: pack-84 rol sınırı "git commit (rapor dosyası hariç)" → yalnız bu rapor commit'lendi. AGENTS.md kaydı sonraki tura bırakıldı.
- Not: ham komut çıktısında FTP parolası görünür hâle geldi (sed ile maskelenemedi); **rapora/log dosyalarına yazılmadı**. Tercihe bağlı: FTP parolası rotasyonu.

## 9. Geri alma
`blog_public`'i 0'a döndürmek: aynı desende tek seferlik betikle `update_option('blog_public', 0)` + `delete_option('rewrite_rules')` + `litespeed_purge_all`, sonra betiği sil ve `https://sutre.store/` içinde `noindex, nofollow` gör (404 kontrolü ile). WP Admin alternatifi: Ayarlar → Okuma → "Arama motorlarının siteyi dizine eklemesini öner" işaretini kaldır. Cache kendini yeniden doldurur; başka geri alma gerekmez.