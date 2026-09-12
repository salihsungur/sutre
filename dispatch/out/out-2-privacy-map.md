# OUT-2 — Privacy-Data-Map Skeleton + Hukuki Sayfa Envanteri (@legal)

## Sonuç
- Tamamlanan hedef: Anayasa §13 Faz 0 "tehdit modeli ve veri haritası" kaleminin veri haritası kısmı — §7 tablo şablonu + §6.1 sayfa envanteri.
- Değiştirilen dosyalar:
  - `/opt/data/workspace/proje/PRIVACY-DATA-MAP.md` — 9 sütunlu veri haritası tablosu; 8 satır kategorisi + kart verisi özel satırı (3a).
  - `/opt/data/workspace/proje/docs/legal-placeholders/README.md` — §6.1'in 10 zorunlu sayfasının envanteri; içerik YOK, yalnız envanter + onay akışı + onay kayıt tablosu.
- Veritabanı/ayar etkisi: yok (yalnız dokümantasyon; git repo yok, commit/push yapılmadı).

## Doğrulama
- Çalıştırılan testler: uygulanamaz (metin varlığı doğrulaması yapıldı).
- PASS sonuçları: her iki `write_file` çağrısı `verified: true` döndü; dosyalar diskte. Tablo §7'nin 9 alanını sütun olarak içeriyor; envanter 10 sayfayı listeliyor.
- FAIL / atlanan: yok. Anayasa TAMAMI okundu (745 satır) — §6, §7, §13 Faz 0, §15, §16 çıktıya yansıtıldı.

## Risk ve güvenlik
- Secret veya kişisel veri etkisi: yok — tüm değerler TBD/placeholder; gerçek veri, örnek kişi bilgisi veya uydurulan mevzuat rakamı girilmedi.
- Ödeme/fiyat/stok/fatura etkisi: yok.
- Geri dönüş adımı: dosyaları silmek yeterli (iki yeni dosya; başka dosyaya dokunulmadı).

## Açık kapılar
- NEEDS_OWNER_INPUT: satıcı bilgileri sayfası için işletme unvanı, vergi no, tebligat adresi, iletişim kanalları; RPO/RTO.
- OWNER_APPROVAL_REQUIRED: yok (bu paket kapsamında).
- LEGAL_REVIEW_REQUIRED: her iki dosyanın TAMAMI. Veri haritasında 9 TBD sütun × 8 kategori; envanterde 10/10 sayfa placeholder. Kart/CVV satırı (3a) "saklanmaz — MUST NOT" olarak kilitli; bu satır review kapsamına girmez, kural §4.1/§15'ten gelir.
- Mevzuat güncellik notu: İYS bildirim süreleri, cayma/gönderim/iade süreleri bu dosyalara sabit değer olarak YAZILMADI — canlıya çıkmadan önce güncel resmî kaynak (GİB, Ticaret Bakanlığı, İYS) + @researcher taramasıyla doğrulanacak; 11 Eylül 2026 rapor tespitleri varsayım olarak kullanılacak.
- Sonraki en küçük güvenli adım: Faz 0'ın tehdit modeli yarısı + PROJECT_INPUTS.md girdilerinin toplanması; uzman atanması (avukat/mali müşavir) Salih'in dış doğrulama kararıdır.
