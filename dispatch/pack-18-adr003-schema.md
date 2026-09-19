# PAKET 18 — FAZ 2/2: ADR-003 MAĞAZA ŞEMA PLANI + CHECKOUT LOGIN STYLING(@coder)

## Görev
Faz 2/1 kanıtlı kapandı (P17: WC 11.1.0 + HPOS + Türkiye/TRY + Giyim + checkout login form — sahibin manuel block + display-as-form). Şimdi planlama: (a) **ADR-003 mağaza veri şeması** taslağı (ürün/kategori/kargo modeli), (b) checkout'ta Login formuna "KAYIT OLUŞTUR" satırını eklemek için talimat (sahibin www-admin elle güzeli; bot erişimi yok).

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file version-lock + P17 raporu). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/dispatch/out/out-17-woo-install.md` (P17 kanıt)
2. `/opt/data/workspace/proje/ADR/ADR-002-paylasimli-hosting-deploy.md`
3. `/opt/data/workspace/proje/docs/architecture/version-lock.md`

## Kapsam / görev
1. `/opt/data/workspace/proje/ADR/ADR-003-magaza-sema.md` yaz (TASLAK — SAHİP ONAYI):
   - **Kategori ağacı taslağı:** `Giyim` root; alt kategoriler öneri (kadın/erkek; tişört/kapşonlu/aksesuar) + iskelet SKU şeması (`SUTRE-SKU-<kategori kodu>-<numara>`) — sahibin onayına tabi (NEEDS_OWNER_INPUT: gerçek ürün listesi/kVS; sahibin belirleyeceği ürün sayısı).
   - **Kargo şema:** tek standart (Free) — değişken maliyet yapısı: her 1-5 kg / farklı bölge/South spor kargo taşıyıcı; BENZER fake numbers yasa (LEGAL_REVIEW_REQUIRED kargo masrafı sahibin onu belirlemek — anayasa §5.1)
   - **Veri şeması**: her SKU için anayasa §5'tekptue alan sabit: barcode, weight, stock, KDV LEGAL_REVIEW_REQUIRED, garanti/cayma istisna — *tabelle templateolmaktır; sahibin doldurası*
   - **Checkout login/register normalization:** login form altında "Kayıt ol" zaten görünmez gördük — WP 'Disable registration' kontrol (Settings → General → membership) ya da checkout login prompt'ta "Hesap oluştur" eklenmesi yöntemleri.
2. `docs/operations/checkout-login-enhancement.md`:
   - Sahibin WP-admin'de Checkout page → Login block altına: block ekleme methodu (Login/Register block + "Register" kısmı) adım adım.
   - Küçük plugin yok; page'ede yapı Weinstein направления; her adım SS.
3. `AGENTS.md` Faz 2 satırı güncelleme (bot admin özel eski Faz 2 satır "Durum: Faz 0 dokümantasyon kapalı" — Faz 1/2 durumunu vurgula):
   - "Faz 1 staging tamam (P15-P17) — Faz 2 continuing"
   Akşama iyi bir şey — AGENTS korumalı; yalnız bot erişmek onay.Clerk → docs ayrı belge: `docs/FAZ-DURUM.md` oluştur: faz kapılarının tablosu.

## Bağlılık sınırları (out-of-scope)
- PayTR integration YOK (Faz 3)
- Fiili ürün/kategori bilgisi sahibin onayına Beast (NEEDS_OWNER_INPUT)
- Kargo taşıyıcı seçimi/fiyat — LEGAL_REVIEW (sahip karar)
- staging→production migration YOK
- Secret yazma YOK.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-18-adr003-schema.md`
§16 format; 200-350 kelime.
