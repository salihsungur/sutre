# PAKET 1 — PROJECT_INPUTS.md OLUŞTURMA (@docs)

## Görev
Anayasa §13 Faz 0'ın ilk teslimi: `/opt/data/workspace/proje/PROJECT_INPUTS.md` dosyasını oluştur. Bu dosya, bilinmeyen TÜM işletme verisini sahipten talep edilebilir tek envanterdir.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile anayasa'yı oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` (TAMAMI — §17 şablon, §0.2 otonomi sınırı, §13 Faz 0)
2. `/opt/data/workspace/proje/AGENTS.md`

## Kapsam / görev
1. `PROJECT_INPUTS.md`'i anayasa §17 şablonunu temel alarak yaz; şablonun TÜM bölümlerini koru (İşletme, Domain ve altyapı, Katalog, Ödeme, Mali ve hukuk).
2. Her satırı şu etiketlerden biriyle işaretle: `NEEDS_OWNER_INPUT` / `OWNER_APPROVAL_REQUIRED` / `LEGAL_REVIEW_REQUIRED` / `SECRET_REFERENCE_ONLY`. Hiçbir değeri TAHMİN ETME, uydurma.
3. §13 Faz 0'ın istediği ek girdi alanlarını şablona EKLE (şablonu genişlet, eksiltme):
   - Marka adı, ürün tipi/kategorisi, hedef müşteri
   - SKU sayısı/tipi, fiyat aralığı, kargo politikası, iade politikası, garanti/servis
   - Hosting/DNS/e-posta gereksinim onayları
   - PayTR üyelik/sandbox durumu (merchant bilgileri ASLA bu dosyaya yazılmaz — `SECRET_REFERENCE_ONLY`)
   - RPO/RTO (OWNER_APPROVAL_REQUIRED)
4. Dosyanın başına "durum: BOŞ ŞABLON — hiçbir alan doğrulanmadı" notu koy.
5. Dosyayı `write_file` ile `/opt/data/workspace/proje/PROJECT_INPUTS.md` yoluna yaz. Büyük tek yazım riskine karşı dosyayı tek seferde yazabilirsin (tek dosya, ~4-6KB bekleniyor).

## Bağlayıcı sınırlar (out-of-scope)
- SADECE bu tek dosyayı oluştur. Başka dosya oluştur/düzenle YOK.
- Git repo henüz yok — commit/push YAPMA.
- Hukuki metin, KDV oranı, garanti şartı, vergi/e-Fatura yükümlülüğü UYDURMA — bunlar LEGAL_REVIEW_REQUIRED etiketi alır.
- PayTR merchant ID/key/salt gibi değerler bu dosyaya ASLA yazılmaz.
- Provider/model override YOK; yapılandırmana dokunma.

## Kanıt / kapılar
- Kanıt: `/opt/data/workspace/proje/PROJECT_INPUTS.md` diskte mevcut, §17'nin 5 bölümünü + Faz 0 ek alanlarını içeriyor.
- Kapı: TÜM bilinmeyenler açık kapı etiketli olmalı; tek bir "tahmin edilmiş" değer olmamalı.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-1-project-inputs.md`
Anayasa §16 AI ÇIKTI SÖZLEŞMESİ formatını aynen kullan (Sonuç / Doğrulama / Risk ve güvenlik / Açık kapılar). İddia yalnız kanıtla; `Verified` / `Unverified` ayrımı zorunlu. Rapor 200-400 kelime.
