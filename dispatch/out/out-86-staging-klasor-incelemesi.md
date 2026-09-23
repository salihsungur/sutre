# out-86 — Üretim temasındaki `staging.sutre.store` girdisi (read-only inceleme)

devops · 23-09-2026 · Yalnız `ls/get/sha` + HTTP GET; silme/taşıma/yükleme YOK.

## 1. ÖZET HÜKÜM

- **Ne:** `/sutre.store/wp-content/themes/sutre-child/staging.sutre.store/` bir **dizindir**; zinciri `wp-content/themes/sutre-child/` → **2 dosya**: `functions.php` 22888 B, `style.css` 36915 B (toplam **59803 B**). `.sql`/`.zip`/`.env`/`wp-config*`/`index.php`/gizli dosya **YOK**.
- **Ne olduğu:** P43 deploy'unun yan ürünü — yüklediği iki dosyanın, tema dizinine göre çözülen **hatalı yola** düşmüş kopyası; commit **`efc30be`** ile **SHA-256 özdeş**, `SUTRE_VERSION = '3.4.0'`.
- **Ne zaman:** **21-09-2026 13:46:36 (+03)** (`Last-Modified … 10:46:36 GMT`), commit'ten 2 dk 21 sn sonra.
- **Risk DÜŞÜK–ORTA:** PHP bu yolda **çalışıyor** (§4); içerik zararsız, listeleme kapalı, sızıntı/secret yok.
- **Aktif mi: HAYIR — ÖLÜ ARTIK** (repo + canlı HTML prod/staging + sitemap: **0** referans, §7).

## 2. Ham `ls`

```
--- .../themes/sutre-child/  (üst dizin)
drwxr-xr-x 3 spokenla spokenla 24 Sep 21 13:46 staging.sutre.store
--- .../staging.sutre.store/wp-content/ -> themes/ (25 B) -> sutre-child/ (44 B)
-rw-r--r-- 1 spokenla spokenla 22888 Sep 21 13:46 functions.php
-rw-r--r-- 1 spokenla spokenla 36915 Sep 21 13:46 style.css
```
Dizinler 755, dosyalar 644; ara dizinlerin tümü 21-09 13:46 damgalı. Aynı zincir **staging temasında da** var (§6).

## 3. İçerik envanteri

3 iç dizin + 2 dosya (1 php, 1 css); görsel/arşiv/sql/env **0**. İkisi indirilip bakıldı (scratch; repoya konmadı): gerçek tema kodu (ABSPATH guard, enqueue, P43 kazanan CSS seçicisi); `grep -iE "password|secret|api_key|token|DB_PASS"` → **0**. **Hüküm: P43 çağının donmuş tema kopyası**, ne yedek ne çöp; ikizi git'te `efc30be`'de.

## 4. Web erişimi + PHP çalışıyor mu

Prod sonuçları (staging'de birebir aynı): nested dizin → **403** (autoindex kapalı, `index.php` yok); nested `style.css` → **200** `text/css` 36915 B, `last-modified: 21 Sep 2026 10:46:36 GMT`, `max-age=604800`; nested `functions.php` → **200** `text/html` **0 bayt**, `_lscache_priv` cookie, `x-litespeed-cache: miss`; `does-not-exist.txt` → 404.

- **PHP ÇALIŞIYOR (kanıt):** `functions.php` 22888 B kaynak yerine **0 bayt** döndü ve LSWS/PHP katmanından geçti; statik sunulsaydı `content-length: 22888` + `<?php` gelirdi. Kaynak sızıntısı **yok**, PHP yürütücüsü **aktif**.

## 5. Köken analizi

1. `efc30be` — 21-09 **13:44 +03** (P43; style.css + functions.php).
2. Oluşum **13:46:36** (HTTP başlığı) = deploy'un hemen ardı.
3. `out-43-title-fix.md:15`: o koşuda *"style.css + functions.php → /staging.sutre.store/wp-content/themes/sutre-child/ (SHA PASS)"*.
4. `out-43:22`: aynı koşuda *"göreli yol kazası (home köküne /functions.php, /style.css çöpü)"* — **aynı iki dosya**; temizlik yalnız home köküne bakmış.
5. AGENTS §3: chroot 21-09'da kaldırıldı; öncesinde FTP kökü `sutre-child`.

**Mekanizma:** oturum `sutre-child`'e kilitliyken `/staging.sutre.store/...` yolu **tema dizinine göre** çözüldü → `<tema>/staging.sutre.store/...` (eşdeğer varyant: cwd=tema dizini + göreli yol); **tam komut kurtarılamadı** (§10).
**Çürütüldü:** pack-77/78/80/83 üretmiş olamaz — hepsi **23-09** ve **mutlak** yol (`out-80-ftp-deploy.txt`: `cwd=… (ABSOLUTE)`); dosya damgası **21-09**.

## 6. staging ile ilişki (SHA)

- `style.css` 36915 B: prod = staging = git `efc30be` = `cd6e23e3…a24a86e1`
- `functions.php` 22888 B: prod = staging = git `efc30be` = `87619be3…1afaf01`

Staging'in **güncel** teması `b43e024b…`/`4fbb134e…` → bu kopya güncel staging teması **değil**. **Prod'a geçiş (çıkarsama):** 21-09'da prod tema deploy'u yok (migration 22-09, P62 `cp -a`); prod'da 19–21-09 mtime'lı dosyalar timestamp-koruyan kopyayı gösterir → yapı staging'de doğdu, kopyayla prod'a geldi.

## 7. Referans taraması

Repo teması, prod ve staging ana sayfa HTML, `wp-sitemap.xml` → **0** referans (staging'deki `staging.sutre.store/wp-content/...` izleri sitenin kendi asset URL'leri). Hiçbir mekanizma bu dosyaları okumaz: **ölü artık**.

## 8. Risk

PHP yürütme **var** (kanıtlı) ama içerik ABSPATH guard'lı eski tema dosyası. Sızıntı/secret/gizli dosya **yok**; listeleme kapalı; Woo/WP çekirdeği değil → CVE yok. Kalan maliyet 59803 B × 2 ortam + denetim sorusu. **Genel: düşük–orta, acil değil.**

## 9. SEÇENEKLER + ÖNERİ (uygulama YOK)

- **(a) Dokunma:** etki sıfır; PHP yüzeyi + karışıklık sürer.
- **(b) Arşivle → sil (ÖNERİLEN):** indir → `dispatch/out/backups/p86-nested-geri-alma/` + `SHA256SUMS`; `ftp-tool.py rmrf` ile iki ortamda içteki ağacı sil; 403/404 + `/`, `/shop/`, `/cart/`, `/product/*` Fatal 0 regresyon. Risk çok düşük (içerik git'te); geri alma blob'dan tek komut.
- **(c) Doğrudan sil:** aynı etki/risk, arşiv izi yok; git ikizi sayesinde güvenli, ama denetim izi bırakmaz.

**Önerim (b), staging + production:** kayıp riski yok, "referanssız + PHP çalışan dizin" kapanır, geri dönüş yolu belgelenir. Cache purge gerekmez.

## 10. YAPILAMADI / DOĞRULANAMADI

- P43'ün birebir FTP komutu: **DOĞRULANAMADI** (Docker çağı; örnek 24-09'da silindi; kayıt yok) — koşu/zaman/mekanizma kanıtlı, komut değil.
- 21-09 chroot'unun doğrudan ispatı: **DOĞRULANAMADI** (bugünkü oturum home kökünden açılıyor).
- Prod'a geçişin P62 `cp -a` ile olduğu: **DOĞRULANAMADI** (mtime/SHA korelasyonu; log yok).
- `ftp_deploy_staging.py` **BULUNAMADI**. Kapsam dışı: `themes/.test-write`, `sutre-child.bak`.