# PAKET 17 RAPORU — WooCommerce 11.1.0 Staging Kurulumu (Faz 2/1) — TAMAM

## Sonuç
- Tamamlanan hedef: staging WP 7.1 içine WooCommerce 11.1.0 kuruldu ve produce kanıtıyla kapandı (sahibin cPanel Terminal + wp-admin yürütümü; bot erişim yok).
- Değiştirilen dosyalar: `docs/operations/woocommerce-install-guide.md` (yeni talimat seti), rapor; sahibin elle checkout page'e Login block ekleme (senin checkout'da register görünmüyor — kayıt ol kısmı yok P18 kapsamında), front-end ürünü görünür.
- Veritabanı etkisi: WooCommerce tabloları + HPOS aktif; dummy ürün; kargo bölgesi Türkiye.

## Doğrulama (ham kanıtlar, sahibin çıktıları)
1. Zip hash birebir (MD5 c31b1d9... / SHA256 6bae9bf7...) ✓
2. plugins/woocommerce açıldı ✓
3. Activate + setup wizard Türkiye/TRY/Fashion ✓ (tax BOŞ — LEGAL_REVIEW_REQUIRED)
4. HPOS **Enabled** + OrdersTableDataStore ✓ (anayasa §3.1) + WC 11.1.0 ✓
5. Dummy ürün `ürün-1` / `SUTRE-SKU-B0001` / kategori `Giyim` (fiyat 100 dummy) ✓
6. Kargo bölgesi Türkiye + Flat rate ₺0,00 ✓
7. Checkout davranışı (sahibin kararı uygulanmış): guest checkout OFF (checkbox kaldırıldı) + During checkout creation ON ✓ — coming-soon KAPALI (bu ayar WP 7.1'de WooCommerce 11.1'de otomatik 'Yakında' kapatıldı) ✓
8. Permalinks post-name ✓ Shop 200 ✓
9. Checkout sayfası: Login form görünür (gizli sekme tetsing — login/registration form validation) ✓
10. "There are no payment methods available" - beklenen placeholder (PayTR Faz 3)

## Risk ve güvenlik
- PayTR kurulumu YAPILMADI (Faz 3); KDV/vergi hiçbir varsayılan yazılmadı (anayasa §5.1 LEGAL_REVIEW_REQUIRED açık kapı); public_html'e dokunulmadı; no PayTR secret; Softaculous kullanım yok.
- Guest checkout OFF; sifre kasada; secret yok.

## Açık kapılar
- LEGAL_REVIEW_REQUIRED: KDV oranı/vergi modeli (Settings→Tax boş bırakıldı; onay Faz 4)
- OWNER_APPROVAL_REQUIRED: Faz 6 canlıya çıkışta production taşınmadan staging→prod transfer planı.
- NEEDS_OWNER_INPUT: yok — test kategorisi (Giyim), checkout davranışı (user karar: login form checkout üstünde) onaylandı.
- Sonraki paket: P18 (Faz 2/2) codigo: login/logout styling + ürün/kategori şeması planı (ADR-003, sahibin onayı).
