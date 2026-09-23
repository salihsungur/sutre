# PACK-81 — `/magaza/` 404 denetimi + kırık iç bağlantı taraması

- **Rol:** tester (read-only) · **Tarih:** 23-09-2026
- **Repo HEAD:** `5cd1c935c36d06dc970e867f9d735f2ddf074dcd` = `origin/main` (beklenen ile aynı) — **PASS**
- **Ön koşul (pack-80 zemini):** `curl https://sutre.store/?v=…` → `sv-footer__payments`=1 → production deploy canlı — **PASS**
- **Kapsam:** 9 kaynak sayfa, **30 tekilleştirilmiş iç bağlantı** (≤60 üst sınırının altında; budama gerekmedi). Tekilleştirme: şema+host+path üzerinden, fragment atılır, harici origin hariç.

---

## 1. Özet bulgu

- **Kullanıcıya görünen kırık iç bağlantı: 0.** 30 iç bağlantıdan 29'u `200`. Tek non-200 `/imunify-bot-check` → `403`; ancak bu bağlantı kaynak kodda YOK, hosting güvenlik katmanının enjekte ettiği **gizli** öğedir (`display:none!important`, `aria-hidden="true"`, `tabindex="-1"`, `rel="nofollow"`) → kullanıcıya görünmez, `robots.txt` ile de kapalı — **BULGU (defekt değil)**, AGENTS §1.1'deki "sistem davranışı" kaydıyla uyumlu.
- **`/magaza/` hiçbir yerde bağlantı olarak referans edilmiyor.** Rota yok (`404`), yönlendirme yok. Bu, pack-79'un "kırık link" varsayımını çürütür: hata bir **eksik rota**, kırık bağlantı değil.
- **En kritik 3:**
  1. **404 sayfasının içerik alanı TAMAMEN BOŞ** — tema'da `404.php` yok, `index.php` `have_posts()` false iken hiçbir şey basmıyor → `<div id="sutre-content"></div>` boş. Ziyaretçi ne hata mesajı, ne arama, ne "ana sayfaya dön" görüyor.
  2. **Tüm site `noindex, nofollow`** (`/`, `/shop/`, `/my-account/`, `/magaza/` + staging — 3 taze istekte doğrulandı, WP çekirdeği `blog_public=0` konumu).
  3. **Sitemap yok** — 6 aday yol da `404`, `robots.txt`'te `Sitemap:` satırı yok.

## 2. `/magaza/` referans envanteri

| Yer | Kanıt | Sonuç |
| :--- | :--- | :--- |
| Tema şablonları | `theme/sutre-child-v2/**` içinde `magaza` dizgesi 0 (yalnız yorumlarda "mağaza" kelimesi) | **PASS** (referans yok) |
| Header/mobil menü etiketi | `header.php:36` + `header.php:60`: `<a href="…wc_get_page_permalink('shop')…">Mağaza</a>` → **`/shop/`** | **BULGU** (etiket TR, rota `/shop/`) |
| Footer | `footer.php:15`: aynı çağrı, etiket "Tüm Koleksiyon" → `/shop/` | **PASS** |
| Render edilmiş HTML (9 sayfa) | Yalnız `>Mağaza</a>` metni; bağlantı hedefi `https://sutre.store/shop/` | **PASS** |
| Canonical / hreflang / og:url | `/magaza/` geçmiyor (404 sayfasında canonical bile yok) | **PASS** |
| Structured data (JSON-LD) | `/magaza/` geçmiyor | **PASS** |
| `sitemap.xml` / `robots.txt` | geçmiyor (sitemap yok) | **PASS** |
| E-posta şablonları | Repoda override edilmiş mail şablonu yok (`woocommerce/emails` dizini yok) | **DOĞRULANAMADI** (DB'ye erişim yok; tema katmanında kanıt yok) |

## 3. Tam kırık bağlantı tablosu

| Kaynak sayfa | Hedef | HTTP | Not |
| :--- | :--- | ---: | :--- |
| 9 sayfanın tamamı | `/imunify-bot-check` | **403** | Host enjeksiyonu; gizli + nofollow → kullanıcıya görünmez (defekt değil) |

Diğer 29 iç bağlantı → **200**. Kullanıcıya görünen kırık bağlantı **yok**.

## 4. Sitemap / robots bulgusu

- `/robots.txt` → **200** (313B). İçinde `Sitemap:` yönergesi **YOK**. `/imunify-bot-check` Disallow'lu.
- `/sitemap_index.xml · /sitemap.xml · /wp-sitemap.xml · /wp-sitemap-posts-post-1.xml · /sitemap-index.xml · /product-sitemap.xml` → **hepsi 404**.
- Tema `sitemap`/`robots`/`blog_public` dizgelerine hiç dokunmuyor → sitemap çekirdek/eklenti düzeyinde kapalı. **BULGU (FAIL):** sitede yayınlanmış sitemap yok.

## 5. 404 sayfası davranışı

- `/magaza/` → **HTTP 404**, `Location` başlığı **yok** (yönlendirme yok), `X-Robots-Tag` **yok**.
- `<body class="error404 …">`, `meta robots = noindex, nofollow` → **indexlenebilir 404 DEĞİL** (doğru davranış) — **PASS**.
- İçerik: `<div id="sutre-content"></div>` **boş**; `<main>` region accessibility ağacında hiç yok. Ekran görüntüsü: `dispatch/out/evidence/out-81-magaza-404-desktop.png` — header ile footer arası **tamamen boş**, hata metni/CTA yok.
- Geri dönüş yolu: header nav + footer bağlantılarının tamamı çalışıyor (kurtarma linkleri var ama **sayfa içi yönlendirme/uyarı yok**) — **BULGU (FAIL kullanıcı deneyimi)**.

## 6. Öneri (UYGULAMA YAPILMADI — yalnız öneri)

1. **Menü düzeltmesi: GEREKSİZ.** Menü zaten doğru (`/shop/`) ve tek kaynaktan (`wc_get_page_permalink('shop')`) üretiliyor — dokunulması risk yaratır. Menü etiketini `/magaza/`'ya çevirmek YANLIŞ olur (rota yok).
2. **404 şablonu ekle (en yüksek kullanıcı etkisi):** `theme/sutre-child-v2/404.php` — "Sayfa bulunamadı" + arama kutusu + "Koleksiyona dön" CTA. Tek CSS/header/footer kuralına uyar.
3. **`/magaza/` → `/shop/` 301 önerilir (düşük maliyet):** Türkçe URL bekleyen ziyaretçi/backlink için anlamlı. Ancak **mevcut `noindex` durumu çözülmeden SEO değeri yoktur** — önce madde 4.
4. **`noindex` + sitemap (kapsam dışı ama maddi):** WP "arama motorlarını engelle" ayarı kapatılmalı ve sitemap yayınlanmalı; aksi hâlde hiçbir sayfa Google'a giremez. `OWNER_APPROVAL_REQUIRED`.
5. **Dokunmamak:** `/imunify-bot-check` (host davranışı, gizli, nofollow).

## 7. `git status -sb`

`## main...origin/main` — takip edilen dosyada değişiklik **YOK**; HEAD `5cd1c93` sabit. Yalnız izinli 2 çıktı eklendi: `dispatch/out/out-81-magaza-404-audit.md`, `dispatch/out/evidence/out-81-magaza-404-desktop.png`. Geçici dosya bırakılmadı. — **PASS**

## 8. Açık noktalar

- **NEEDS_OWNER_INPUT:** `/magaza/` 301 eklenecek mi; 404 şablonu onayı (görsel değişiklik → sahip onayı zorunlu); site geneli `noindex` bilinçli mi.
- **DOĞRULANAMADI:** Menü kayıtları ve e-posta şablonları DB'de — yazma/okuma erişimi yok, kanıt render edilmiş HTML ile sınırlı.
- **Kapsam dışı gözlem:** `/shop/` sayfasında canonical eksik (404'te olmaması doğru; shop'ta beklenirdi).