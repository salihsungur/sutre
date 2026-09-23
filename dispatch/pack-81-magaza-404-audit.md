# PACK-81 — `/magaza/` 404 denetimi + kırık iç bağlantı taraması (tester, read-only)

## 0. ÖN KOŞUL / İLK ADIMLAR
- Repo: `/Users/salihsungur/dev/sutre`. Beklenen HEAD = origin/main (paket gönderilirken orkestratörün verdiği SHA'yı `git rev-parse` ile teyit et; farklıysa raporla ama devam et).
- **Production deploy'u (pack-80) bitmiş olmalı** — bu paket ondan sonra gönderilir. Başlarken `curl -skL "https://sutre.store/?v=$(date +%s)" | grep -c 'sv-footer__payments'` → 1 görüyorsan zemin güncel demektir; görmüyorsan önce bunu raporla ve taramaya yine devam et.
- İlk tool çağrıların: (1) `AGENTS.md` TAMAMI, (2) `WF...` anayasa: `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` TAMAMI.
- **İlk yanıtın ZORUNLU bir tool çağrısı olsun; plan metni yazma.**

## 1. SORU
Tester (pack-79) bulgusu: **`https://sutre.store/magaza/` → 404**, gerçek mağaza rotası `/shop/`. Sahip onayıyla bu denetim açıldı.

Cevaplanacaklar:
1. **`/magaza/` nerede referans ediliyor?** Menülerde (header/footer/mobil), sayfa içeriklerinde, tema şablonlarında, `sitemap.xml`/`sitemap_index.xml`, `robots.txt`, canonical/hreflang, structured data (JSON-LD), e-posta şablonlarında var mı?
2. **Başka kırık iç bağlantı var mı?** (aynı tarama içinde, sınırlı ve raporlanabilir ölçekte)
3. **Kullanıcı etkisi:** `/magaza/` ile karşılaşan bir ziyaretçi ne görüyor (404 sayfası içeriği, geri dönüş yolu var mı)? Google'a açık mı (indexlenebilir 404)?
4. **Öneri (uygulama YOK, yalnız öneri):** 301 yönlendirme mi, menü düzeltmesi mi, yoksa dokunmamak mı — gerekçesiyle.

## 2. YÖNTEM (kanıt zorunlu)
- **Rota keşfi:** tema şablonlarında ve canlı HTML'de `magaza` dizgesi ara (`theme/sutre-child-v2/**` repoda; canlı HTML'de curl). WordPress menüleri DB'de tutulur → **render edilmiş HTML üzerinden** kanıtla (menü çıktısı), DB'ye yazma erişimin yok.
- **Tarama yüzeyi (sınırlı):** `/` · `/shop/` · bir ürün sayfası · `/cart/` · `/checkout/` · `/my-account/` · hukuki sayfalar · varsa blog/iletişim. Her sayfada: tüm `<a href>` topla, aynı origin'e gidenleri (tekilleştir) HTTP HEAD/GET ile sına; **4xx/5xx dönenleri** listele (sayfa → hedef → durum).
- **sitemap/robots:** `sitemap_index.xml`, `sitemap.xml`, `robots.txt`, `product-sitemap.xml` vb. indir; `/magaza/` geçiyor mu bak.
- **404 sayfasının kendisi:** `/magaza/` yanıtını (`HTTP kodu`, `Location` başlığı var mı, `X-Robots-Tag`, sayfa içeriği) kayıt altına al.
- Her iddiaya dosya:satır veya ham curl çıktısı ekle. Üst sınır: tarama **≤60 iç bağlantı**; daha fazlası varsa tekilleştirme kuralını yaz ve ilk 60'ı raporla + toplam sayıyı belirt.

## 3. ROL SINIRI (read-only — bağlayıcı)
- Repoda **hiçbir dosyayı değiştirme**; commit/push YOK; FTP upload YOK; **production'da hiçbir şeyi değiştirme** (menü, sayfa, yönlendirme dahil).
- Yalnız yazabilirsin: `dispatch/out/out-81-magaza-404-audit.md` + `dispatch/out/evidence/out-81-*.png` (gerekirse).
- Provider/model override YASAK.

## 4. RAPOR
- Dosya: `/Users/salihsungur/dev/sutre/dispatch/out/out-81-magaza-404-audit.md` (≤800 kelime).
- Bölümler: (1) özet bulgu (kırık bağlantı sayısı, en kritik 3), (2) `/magaza/` referans envanteri (yer → dosya:satır / HTML kanıtı), (3) tam kırık bağlantı tablosu (kaynak sayfa → hedef → HTTP kodu), (4) sitemap/robots bulgusu, (5) 404 sayfası davranışı, (6) **öneri + gerekçe** (uygulama yapılmadı), (7) `git status -sb` (repoda değişikliğin olmamalı), (8) açık noktalar.
- Her madde için sonuç etiketi: **PASS / FAIL / BULGU / DOĞRULANAMADI** (kanıtsız hüküm yasak). Geçici dosya bırakma.