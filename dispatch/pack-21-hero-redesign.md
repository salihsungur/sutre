# PAKET 21 — FAZ 2/5: ANA SAYFA MARKA HERO + SUTRE ÜRÜN SAYFA SIRALAMASI (@coder)

## Görev
Sutre staging WP'de:
1. **Ana Sayfa** — WordPress blog sayfasını kaldır, "Marka Hero" ana sayfasına çevir:
   - Cormorant Garamond H1: "Sutre"
   - Tagline Türkçe: "Deniz ve dokumanın zarafeti" (marka kit design-system §5'ten)
   - Hero altında: "Koleksiyonu keşfet" butonu → `/shop/` yolu — Silk accent(#C9A66B) vurgu
   - Ana sayfa demo bot'a "Blog" değil marka sayfası — bloklar Türkçe'de & mobil-first
2. **Products sayfası: "Sutre Şal" kategorisi mevcut "Giyim → Kadın → Şal" ADR-003 onaylı, bot elle dokunmaz**
3. **Checkout Login blok feminine gerektirmedi — sahibin P17/P18 Kararları:**
   Guest checkout OFF; During-checkout creation ON; "sonra checkout login form" sahibin elle checkout page'de editordue eklediği login block bu style'da hâlâ var.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file tasarım kitini oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/design-system.md` (Cormorant/Jost/fonts + Türkçe özet, palet, 44×44px dokunma hedefi)
2. `/opt/data/workspace/proje/ADR/ADR-003-magaza-sema.md` (Şal kategori onay)
3. `/opt/data/workspace/proje/theme/sutre-child/style.css` (P20 şablon düşük derece — styles var)
4. `/opt/data/workspace/proje/theme/sutre-child/page-templates/home.php` (P20 yazılmış mevcut home template)

## Kapsam / görev
1. **Ana sayfa hero template** düzeltmeleri — local repo dosyaya yaz:
   - `theme/sutre-child/page-templates/home.php` — yeni hero markup (Cormorant + Türkçe tagline + CTA)
   - `theme/sutre-child/style.css` (CSS: hero padding mobile, 44x44 cta, serif glifler)
   - Playwriter test: /admin host'ta validasyon (her sen resim)
2. **Git push ile sunucuya** — cPanel Terminal'den sahibin elle **"Update from remote"** alternatif — bot `git push` sonrası sahibin elle update yapması gerekiyor; bot bu yöntemi kullanacak
3. Git push GH PAT kullanma yok — resmi deploy key var (sahibin Git GH key paylaşıldı).
4. **SMTP dokumana onay:** `docs/operations/smtp-setup.md` zaten mevcut; P20'de; bu pakette kaynakta sorun yok.

## Kanıt / kapılar
- Commit + push
- Playwriter screenshot: staging.sutre.store — Hero + Tagline + CTA görüntü (sahibin elle doğrulama)
- Copper home page BG: bone(yakın: #F5F2EC)

## Bağlılık sınırları (out-of-scope)
- PayTR YOK; Ürün Seçimleri (Faz 2/6'da sahibin elle)
- public_html touch YASAK
- KDV/vergi rakamı YASAK (§5.1)
- State ENGINE No secret — SADECE CODE

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-21-grade-hero-ana-sayfea.md`
§16 format; 200-350 karakter.
