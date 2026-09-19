# Faz Durumu — Kapı Tablosu (canlı kaynak)

> Belge türü: durum tablosu. Kaynak: anayasa §13/§14, AGENTS.md §4, dispatch/out/ raporları. Güncelleme: 2026-09-15 (P18; P19 eklendi).

| Faz | Kapsam | Durum | Kanıt | Açık kapılar |
|---|---|---|---|---|
| 0 — Keşif/girdiler | PROJECT_INPUTS, tehdit modeli, veri haritası, hukuki envanter | Dokümantasyon TAMAM (sahip girdileri hariç) | `dispatch/out/*` P0-P5 | NEEDS_OWNER_INPUT: PROJECT_INPUTS.md sahibin doldurması |
| 1 — Temel altyapı | Git repo, hosting keşfi, WP+Woo sürüm kilit | TAMAM (P6-P14) | version-lock.md, ADR-001/002, out-13/13b | OWNER_APPROVAL_REQUIRED: RPO/RTO; LEGAL_REVIEW_REQUIRED: JetBackup uzak konum |
| 2 — Mağaza çekirdeği | WooCommerce kurulum, katalog/şema, checkout, kargo | SÜRÜYOR (Faz 2/1 TAMAM — P17; Faz 2/2 taslak — P18) | out-17-woo-install.md, ADR-003 TASLAK | ADR-003 OWNER_APPROVAL_REQUIRED; ürün listesi NEEDS_OWNER_INPUT; kargo/KDV LEGAL_REVIEW_REQUIRED |
| 3 — PayTR | Sandbox, callback, reconciliation | BAŞLAMADI (Faz 2 kapandıktan sonra) | — | PayTR üyelik/sandbox durumu NEEDS_OWNER_INPUT |
| 4 — Hukuk/mali | ETBİS, e-Arşiv, KVKK, hukuki sayfalar | BEKLEMEDE | docs/legal-placeholders/README.md | 12 LEGAL_REVIEW_REQUIRED (mali müşavir/avukat) |
| 5 — Sosyal/SEO/analitik | Katalog, analytics, schema | BAŞLAMADI | — | — |
| 6 — Canlıya geçiş | §14 kabul, production, izleme | BAŞLAMADI | — | §14 tüm kanıtlar; staging→production planı OWNER_APPROVAL_REQUIRED |
| 7 — Büyüme | Pazaryeri adapter'ları | BAŞLAMADI | — | — |

## Faz 2 alt adımları (detay)

| Alt adım | Durum | Kanıt |
|---|---|---|
| 2/1 — WC 11.1.0 + HPOS + Türkiye/TRY + Giyim + checkout login form | TAMAM | out-17-woo-install.md (10 kanıt maddesi) |
| 2/2 — ADR-003 mağaza şema taslağı + checkout login enhancement talimatı | TASLAK — sahibin onayı bekliyor | ADR-003-magaza-sema.md, docs/operations/checkout-login-enhancement.md (bu paket) |
| 2/3 — Kayıt formu zenginleştirme (mu-plugin snippet — sahibin cPanel'e elle kopyalar; bot erişim yok) + çeviri stratejisi | SNIPPET HAZIR — sahibin kurulumu bekliyor | docs/snippets/wc-rich-register.php, docs/operations/translation-strategy.md |
| 2/3b — İlk gerçek ürün/kategori girişi (sahip elle) | BEKLEMEDE (NEEDS_OWNER_INPUT) | — |


## Faz 2/3 — Kayıt formu zenginleştirme + çeviri stratejisi (P19, sahibin elle yapacakları)

1. **mu-plugins snippet kurulumu (cPanel File Manager):**
   0. (cPanel Terminal varsa) `php -l wp-content/mu-plugins/wc-rich-register.php` → 'No syntax errors' beklenir.
   1. cPanel → File Manager → `sutre.store` docroot altındaki `wp-content/` klasörüne gir.
   2. `mu-plugins` dizini yoksa oluştur (dizin izni 755).
   3. `docs/snippets/wc-rich-register.php` içeriğini herhangi bir editörde aç, tamamen kopyala.
   4. File Manager → +File → isim `wc-rich-register.php` (izin 644) → düzenle → içeriği yapıştır → kaydet.
   5. mu-plugins içindeki her .php dosyası otomatik yüklenir; eklenti listesi/Dashboard'da görünmez (mu-plugin olduğu için) — My Account kayıt formuna alanlar düşer.
   6. Rollback: dosyayı silmek yeterli (DB yazımı yok; mevcut staging kullanıcı meta'larda kalır, sorun değil).
2. **Settings → General → "Membership": "Anyone can register" ON olsun; New User Default Role = Customer** (confirmation — sahibin zaten kararı; ekran SS al).
3. **Front-end kontrol:** gizli sekmede `…/my-account/` aç; kayıt formunda Ad* / Soyad* / Telefon (opsiyonel, placeholder 05XX XXX XX XX) / E-posta / Parola görünecek; boş gönderek zorunlu alan hatalarını; boş telefonlu kaydı telefonla pas geçmeyi test et.
4. **Çeviri stratejisi:** `docs/operations/translation-strategy.md` oku; görünen İngilizce string'ler Faz 2/4 tema FULL REDESIGN'e kadar GEÇİCİ; page editor'deki blok metinlerini istediğin zaman elle Türkçe yazabilirsin (kod yok).

## Canlıya geçiş DURUR, eğer:

§14 kanıtlarından herhangi biri yok; açık güvenlik/ödeme/hukuk kapısı var; ADR-003 onaylanmadan gerçek ürün girilirse (fiili şema) veya Settings→Tax LEGAL_REVIEW_REQUIRED olmadan doldurulursa.

## Faz 2 alt adımları — GÜNCELLEME (2026-09-15 sonrası, P19 kapanış)

| Alt adım | Durum | Kanıt |
|---|---|---|
| 2/3 — Kayıt formu zenginleştirme + parola politika + mail-fatal workaround | **TAMAM (P19 PASS)** | out-19-register-enhancements.md (kapanış kanıtlar şarkıda) |

### Faz 2/4 (tema FULL REDESIGN) — BAŞLAMADI'AÇIK KAPILARI:
- BACKLOG: Hz-Part's temel kategori ağacı + theme layout + göınaq — Salih'ın istegne göre FULL PALETTE — tasarım brief beklıyor (sahibin yazılı talep) — bunu Apr Ze planın gözden geçirme ile′ text-file'ı hazırl yar say.
- **smtp CHECKLIST (Faz 2/4):** SMTP plugin/config öncesi bu mu-plugins filtre Kaldırı set = wp_new_user_notif Email + WC_Emails "New account" enable → testim safiri PRODDB'ye taşıyan.
