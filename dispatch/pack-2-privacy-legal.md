# PAKET 2 — PRIVACY-DATA-MAP SKELETON + HUKUKİ SAYFA ENVANTERİ (@legal)

## Görev
Anayasa §13 Faz 0'ın "tehdit modeli ve veri haritası" kaleminin veri haritası kısmı: `/opt/data/workspace/proje/PRIVACY-DATA-MAP.md` skeleton'u + `/opt/data/workspace/proje/docs/legal-placeholders/` altında §6.1 sayfa envanteri.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile anayasa'yı oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` (TAMAMI — özellikle §6, §7, §13 Faz 0, §15)
2. `/opt/data/workspace/proje/AGENTS.md`

## Kapsam / görev
1. `PRIVACY-DATA-MAP.md` yaz: §7'deki 9 alan (veri kategorisi, toplama amacı, işleme şartı, kaynak, erişen roller, üçüncü taraf alıcı, yurtdışı aktarım, saklama süresi/silme, veri sahibi talebi) sütun olan bir tablo şablonu. Satır olarak şu beklenen kategorileri placeholder olarak aç (değerler `TBD — LEGAL_REVIEW_REQUIRED`):
   - hesap/kullanıcı kaydı; sipariş ve teslimat verisi; ödeme callback kayıtları (kart verisi ASLA — satır olarak "kart/CVV: saklanmaz, MUST NOT" notuyla koy); çerez/analitik; pazarlama izni (İYS); sunucu/audit logları; destek iletişimi (e-posta/WhatsApp); yedekler.
2. `docs/legal-placeholders/README.md` yaz: anayasa §6.1'deki 10 zorunlu sayfanın envanteri — her biri için: sayfa adı, checkout/footer erişim gereksinimi, durum `PLACEHOLDER — YAYINA HAZIR DEĞİL`, onay gereksinimi `LEGAL_REVIEW_REQUIRED` + dış uzman (avukat/mali müşavir) notu. Sayfa İÇERİĞİNİ yazma — yalnız envanter.
3. Her iki dosyayı ayrı `write_file` çağrılarıyla yaz (truncation riskine karşı tek tek).

## Bağlayıcı sınırlar (out-of-scope)
- Yalnız bu 2 dosyayı oluştur; başka dosya YOK.
- Hukuki metin taslağı doldurma — envanter + iskelet yeterli (tam metinler Faz 4'te).
- "Onaylıymış gibi" sunma: her dosyada yayın engeli açık belirtilmeli (anayasa §15).
- Git repo yok — commit/push YAPMA.
- Güncel mevzuat rakamı (İYS süreleri, cayma süreleri vb.) sabit doğru olarak kodlama — "güncel resmî kaynaktan doğrulanacak" notu düş.

## Kanıt / kapılar
- Kanıt: iki dosya diskte, §7 tablosu 9 sütunlu, envanter 10 sayfayı listeliyor.
- Kapı: tüm hukuki içerikler `LEGAL_REVIEW_REQUIRED` etiketli.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-2-privacy-map.md`
Anayasa §16 formatı (Sonuç / Doğrulama / Risk ve güvenlik / Açık kapılar). Rapor 200-400 kelime.
