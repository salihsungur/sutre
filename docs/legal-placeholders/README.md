# Hukuki Sayfa Envanteri — LEGAL PLACEHOLDERS (SKELETON)

> **PAKET 22 GÜNCELLEME (2026-09-17):** Aşağıdaki 5 sayfa için Türkçe DRAFT taslak metinleri üretildi (`LEGAL_REVIEW_REQUIRED` kapısı aynen geçerli, taslak avukat dışı KULLANILAMAZ):
> - `gizlilik-politikasi.md` · `mesafeli-satis-sozlesmesi.md` · `iade-ve-cayma.md` · `on-bilgilendirme-formu.md` · `cerez-politikasi.md`
> Sahibin elle adım listesi: `docs/operations/p22-owner-manual-steps.md`.
>
> **Durum: ENVANTER / PLACEHOLDER — YAYINA HAZIR DEĞİL.**
> Anayasa §6.1 gereği yayınlanması zorunlu 10 sayfanın envanteridir. **Sayfa içerikleri bu dosyada YOK** — tam metinler Faz 4'te (anayasa §13) dış uzman onayıyla üretilecektir. §6.1: "Şablonlar placeholder olarak üretilebilir; şirket ve ürün bilgileri doğrulanmadan yayına alınamaz."
>
> Anayasa §15: hukuki metni onaylıymış gibi yayımlamak YASAK. Aşağıdaki tüm sayfalar `LEGAL_REVIEW_REQUIRED` kapısıyla açıktır; bu etiketin kaldırılması ancak kayıtlı mali müşavir/avukat doğrulaması ile mümkündür.
> Mevzuat rakamları (İYS süreleri, cayma süreleri, gönderim süreleri vb.) bu envanterde **sabit doğru olarak kodlanmamıştır**; canlıya çıkmadan önce güncel resmî kaynaktan doğrulanacaktır.

## Envanter

| # | Sayfa | Checkout / footer erişim gereksinimi (§6.1) | Durum | Onay gereksinimi | Dış uzman |
|---|---|---|---|---|---|
| 1 | Satıcı / işletme bilgileri ve iletişim | Footer + checkout erişilebilir | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat (+ işletme sahibi girdisi: unvan, vergi no, tebligat adresi — NEEDS_OWNER_INPUT) |
| 2 | Gizlilik ve KVKK aydınlatma metni | Footer + checkout erişilebilir | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat / KVKK uzmanı |
| 3 | Çerez politikası ve tercih yönetimi | Footer + checkout erişilebilir | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat / KVKK uzmanı |
| 4 | Ön bilgilendirme formu | Checkout akışında satın alma ÖNCESİ açık gösterim + footer | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat |
| 5 | Mesafeli satış sözleşmesi | Checkout kabulü ayrık checkbox + footer | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat |
| 6 | Teslimat / kargo politikası | Footer + checkout erişilebilir | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat (+ ops girdisi: kargo firması, süre, masraf — TBD) |
| 7 | İptal, cayma ve iade politikası | Footer + checkout erişilebilir | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat (cayma istisnaları yalnız doğrulanmış kural olarak uygulanır — §6.3) |
| 8 | Garanti ve satış sonrası hizmet bilgisi | Footer + ürün sayfası (ürün sınıfına göre) | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat (Garanti Belgesi Yönetmeliği / satış sonrası hizmet düzenlemeleri — §6.6) |
| 9 | Ticari elektronik ileti / İYS bilgilendirmesi | Footer + checkout ayrık pazarlama rızası | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat (+ İYS kayıt süreci işletme adına yapılır — §0.2 dış kapı) |
| 10 | Açık destek ve uyuşmazlık / başvuru kanalları | Footer erişilebilir | PLACEHOLDER — YAYINA HAZIR DEĞİL | LEGAL_REVIEW_REQUIRED | Avukat (tüketici başvuru kanalları güncel kaynaktan doğrulanacak) |

## Checkout rıza ayrımı notu (§6.2 — envanterle bağlantılı)

Bu envanterdeki 2, 4, 5 ve 9 numaralı sayfalar checkout rızalarına kaynaklık eder. Rızalar tek checkbox altında birleştirilemez:

- ön bilgilendirme + mesafeli satış sözleşmesi kabulü (ayrık);
- KVKK aydınlatması sunulduğunun kaydı (ayrık);
- gerekliyse belirli veri işleme/aktarım açık rızası (ayrık);
- pazarlama izni (ayrık, varsayılan işaretli olamaz, siparişin zorunlu şartı olamaz).

Kabul kayıtlarında belge sürümü/hash'i, zaman, sipariş/kullanıcı ilişkisi ve ispat için gerekli teknik bağlam saklanır — bu kayıt düzeni Faz 4'te teknik olarak uygulanacaktır.

## Onay akışı

1. Her sayfa için placeholder iskelet Faz 4'te üretilir — içerik bu dosyada tutulmaz, sayfa bazlı ayrı dosyalara yazılır.
2. Tüm metinler dış uzmana (avukat / mali müşavir gerekliyse) sunulur; onay kaydı tarih + uzman + sürüm ile buraya işlenir.
3. Onaysız hiçbir sayfa canlıya çıkarılamaz; §14 kabul kriterleri "Hukuki sayfalar güncel ve onaylı" şartını taşır.
4. Mevzuat güncellik taraması @researcher ile resmî kaynaklardan yapılır; doğrulama kaynağı ve tarihi her sayfa için kayda geçer.

## Onay kaydı

| Sayfa | Onay veren uzman | Tarih | Sürüm/hash | Kaynak doğrulaması |
|---|---|---|---|---|
| Tümü | — | — | — | BEKLEMEDE — LEGAL_REVIEW_REQUIRED |

Son güncelleme: 2026-09-12 — envanter oluşturuldu (@legal).
