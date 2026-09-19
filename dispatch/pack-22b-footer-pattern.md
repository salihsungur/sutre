# PAKET 22b — KÖK TABANLI DÜZELTME: TWENTY TWENTY-FIVE FOOTER PATTERN OVERRIDE (@coder)

## Görev
P22'de child `parts/footer.html` yüklenmedi — kök-neden KEŞFEDİLDİ:
Parent TT5'in `parts/footer.html` SADECE `<!-- wp:pattern {"slug":"twentytwentyfive/footer"} /-->` çağırır.
Footer içeriği `twentytwentyfive/footer` PATTERN'inde (parent patterns/footer.php) — "WordPress ile gururla sunuluyor" orada.
child parts override bu pattern'a ULAŞMAZ.

## İlk yanıt kuralı
İlk yanıt ZORUNLU tool çağrısı: read_file /opt/data/design-system.md + git status -sb.

## Kesin çözüm (anayasa §3.1 — hook-only, çekirdek yok)
Child theme'de (repo: `theme/sutre-child/`) şunu üret:

1. **`patterns/footer.php`** — WP pattern header:
```php
<?php
/**
 * Title: sutre-footer
 * Slug: sutre-child/sutre-footer
 * Categories: sutre
 */
?>
<!-- P22'deki footer.html içeriği AYNEN — wp:group tagName footer sutre-footer ... -->
```
İç:minik marka satırı (site-title) + "Hukuki:" + 5 placeholder link + "© Sutre — Tüm hakları saklıdır".
"WordPress ile gururla..." YASAK (anayasa §16 — sahibin açık talimatı).

2. **`templates/index.html`** — child bunu yazınca parent template override edilir:
   başında `<!-- wp:pattern {"slug":"sutre-child/footer-parts"..."` yerine DOĞRUDAN
   `<!-- wp:group {"tagName":"footer", ... -->` sutre-footer content blocks (footer.html'den içerikle).
   Basit tut: header/template/part isimleri parent'la aynı: header part ref'i parent'a bırak
   (`<!-- wp:template-part {"slug":"header","area":"header"} /-->`) — SADECE footer düzeltilmeli.

3. **`functions.php`ye dokunma.**override.css zaten enqueue'lu.

## Kabul kriterleri
- Çıktıda kalan: staging.sutre.store ana sayfa HTML'ında "gururla" KELİMESİ GEÇMESİN davranış —
  coder kendisi curl ile test EDEMİYOR (WP yok) — bunu owner'a bırak.
- REPONUN web temp klasörü değişmiyor; yalnız child theme klasörü.
- 5 hukuki taslak DOSYA İÇERİĞİ DEĞİSMEZ.

## Commit/push
Anlamlı commit; push deploy-key ile. Rapor:
`dispatch/out/out-22b-tt5-footer-pattern.md` (§16; output doğrulama: dosya bayt + byte-lenk listesi).

## Out-of-scope
- Ana sayfa/hero işlevleri YOK (P22 spesifik footer/pattern sorunudur)
- PayTR, e-mail, KDV, legal metin düzenlemesi YOK
- public_html YASAK; secret yok
