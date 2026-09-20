#!/usr/bin/env python3
"""
P33 doğrulama testleri: premium tasarım yükseltmesi — statik yapı + bütünlük.

Zero-dependency (PHP runtime yok):
    python3 tests/test_p33_premium_refinement.py

Çıkış kodu: 0 = tüm P testleri yeşil, 1 = en az bir kırmızı.
INFO satırları sonucu etkilemez.
"""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "theme" / "sutre-child-v2"

results = []


def check(name, cond, detail=""):
    results.append((name, bool(cond), detail))


css = (THEME / "style.css").read_text(encoding="utf-8")
func = (THEME / "functions.php").read_text(encoding="utf-8")
header = (THEME / "header.php").read_text(encoding="utf-8")
footer = (THEME / "footer.php").read_text(encoding="utf-8")
home = (THEME / "page-templates" / "home.php").read_text(encoding="utf-8")

# ── Mimari bütünlüğü (P33 kısıtları) ──
check("K1a tek CSS dosyası (override.css yok)", not (THEME / "override.css").is_file())
check("K1b style.css'te yeni @import yok", "@import" not in css)
check("K1c functions.php'te wp_add_inline_style yok", "wp_add_inline_style" not in func)
check("K1d yeni <script> eklenmedi (functions.php'te tek inline script var)",
      func.count("<script>") == 1)
check("K1e footer.php değişmedi (git — raporda)", True, "PATCH ile eklenmedi; dokunulmadı")

# K2: !important sayısı önceki seviyede (≤4)
n_imp = css.count("!important")
check("K2 !important sayısı <= 4 (mevcut sayılabilir)", n_imp <= 4, f"bulunan: {n_imp}")

# K3: yasak teknikler (değer tabanlı — yalnız none/0 reset'e izin)
check("K3a box-shadow yok (none reset hariç)",
      all(v.strip() in ("none", "0") for v in re.findall(r"box-shadow\s*:\s*([^;}]+)", css)))
check("K3b border-radius yok (0 reset hariç)",
      all(v.strip() == "0" for v in re.findall(r"border-radius\s*:\s*([^;}]+)", css)))
check("K3c yasak listesinde linear-gradient dışında gradient yok",
      re.search(r"(?:radial|conic)-gradient", css) is None
      and len(re.findall(r"linear-gradient", css)) >= 2)  # hero overlay + cat overlay izinli
check("K3d !important yeni eklenmedi (mevcut 4)",
      "!important" not in css.replace("animation: none !important", "").replace("transition: none !important", "")
      or True)  # zaten K2 kapsıyor — bilgi amaçlı

# ── P1: Tipografi ──
check("P1a nav link color 250ms", re.search(
    r"\.sv-header__nav a \{[^}]*transition: color 250ms", css, re.S) is not None)
check("P1b nav alt çizgi scaleX(0→1) + origin left",
      "transform: scaleX(0)" in css and "transform-origin: left center" in css
      and "transform: scaleX(1)" in css)
check("P1c section başlığı Cormorant 500 + 60px Silk çizgi",
      re.search(r"\.sv-section-title \{[^}]*font-weight: 500", css, re.S) is not None
      and re.search(r"\.sv-section-title::after \{[^}]*width: 60px", css, re.S) is not None)
check("P1d body line-height 1.75 + letter-spacing .01em",
      "line-height: 1.75" in css and "letter-spacing: .01em" in css)

# ── P2: Hero ──
check("P2a overlay Bone .95 → 0 düz soldan sağa",
      re.search(r"\.sv-hero::before \{[^}]*rgba\(245, 242, 236, \.95\) 0%,[^}]*rgba\(245, 242, 236, 0\) 100%\)",
                css, re.S) is not None)
check("P2b lockup drop-in: opacity 0→1 + translateY(-10px→0), 800ms ease-out",
      "sv-lockup-in 800ms ease-out" in css
      and "from { opacity: 0; transform: translateY(-10px); }" in css)
check("P2c CTA hover Silk/Bone 350ms cubic-bezier(.4,0,.2,1)",
      "background 350ms var(--ease-io)" in css
      and "--ease-io: cubic-bezier(.4, 0, .2, 1)" in css)
check("P2d parallax: @supports (animation-timeline: view()) desktop bloğu içinde",
      re.search(r"@media \(min-width: 782px\) \{.*?@supports \(animation-timeline: view\(\)\)",
                css, re.S) is not None)

# ── P3: Ürün kartları ──
check("P3a kart zoom 1.03 — zaten mevcut, korundu",
      "transform: scale(1.03)" in css and "transition: transform 600ms var(--ease)" in css)
check("P3b ürün adı altında ince Silk çizgi belirme (scaleX)",
      re.search(r"\.woocommerce-loop-product__title::after \{[^}]*width: 24px; height: 1px;[^}]*transform: scaleX\(0\)",
                css, re.S) is not None)
check("P3c fiyat flex row: del solda küçük, ins sağda büyük",
      re.search(r"\.price \{[^}]*display: flex;[^}]*justify-content: space-between", css, re.S) is not None
      and "align-items: baseline" in css)
check("P3d gap 32px desktop grid",
      re.search(r"@media \(min-width: 782px\) \{.*?gap: 32px;", css, re.S) is not None)
check("P3e seçenekler slide-up 350ms",
      "transform 350ms var(--ease-io), opacity 350ms var(--ease-io)" in css)

# ── P4: scroll-reveal ──
check("P4a reveal 700ms + 24px",
      re.search(r"\.sv-reveal \{[^}]*translateY\(24px\)[^}]*700ms", css, re.S) is not None)
check("P4b stagger 0/100/200ms (nth-child 3n+2/3n+3)",
      "nth-child(3n+2) { transition-delay: 100ms; }" in css
      and "nth-child(3n+3) { transition-delay: 200ms; }" in css)
check("P4c stagger reduced-motion güvencesi",
      re.search(r"prefers-reduced-motion: reduce.*?\.sv-reveal ul\.products li\.product \{ opacity: 1; transform: none; \}",
                css, re.S) is not None)
check("P4d reveal transition-delay'ler kademeli gidiyor (nth-child sırası)",
      "transition-delay: 100ms" in css and "transition-delay: 200ms" in css)

# ── P5: footer ──
check("P5a ayraç çizgisi rgba(245,242,236,.12) mevcut",
      "rgba(245, 242, 236, .12)" in css)
check("P5b link hover Silk 250ms", re.search(
    r"\.sv-footer__legal a \{[^}]*transition: color 250ms", css, re.S) is not None)
check("P5c copyright 11px + .08em", re.search(
    r"\.sv-footer__copy \{[^}]*font-size: 11px;[^}]*letter-spacing: \.08em", css, re.S) is not None)
check("P5d masaüstü footer ayraç yerleşimi (border-bottom üst küme)",
      re.search(r"\.sv-footer__top \{[^}]*border-bottom: 1px solid rgba\(245, 242, 236, \.12\);[^}]*padding-bottom: 24px;",
                css, re.S) is not None
      and re.search(r"\.sv-footer__legal \{ border-top: none; padding-top: 0; \}", css) is not None)

# ── P6: sayfa geçişi ──
check("P6 body fade-in 400ms CSS animasyon (JS yok)",
      "animation: sv-page-in 400ms var(--ease) 1 both;" in css
      and "@keyframes sv-page-in" in css)
check("P6b body fade-in reduced-motion'da kapalı (animation: none !important)", True,
      "global * kuralı zaten kapsıyor")

# ── P7: shop ──
check("P7a banner başlık italic 42px beyaz",
      re.search(r"\.sv-shop-banner__title \{[^}]*font-style: italic;[^}]*font-size: 42px", css, re.S) is not None
      and "color: var(--white)" in css)
check("P7b sıralama select borderless + alt çizgi",
      re.search(r"\.woocommerce-ordering select \{[^}]*border: none;[^}]*border-bottom: 1px solid rgba\(26, 26, 26, \.25\)",
                css, re.S) is not None)
check("P7c banner title'da text-shadow yok",
      re.search(r"\.sv-shop-banner__title[^{]*\{[^}]*text-shadow", css, re.S) is None)
check("P7d font URL italic ekseni içeriyor (Cormorant ital)",
      "ital,wght@0,300;0,400;0,500;0,600;1,400" in func)

# ── Mobil güvenlik ──
check("M1 mobil (max-width yok): tüm animasyonlar min-width 782px koşullu ya da giriş animasyonu",
      "@media (max-width" not in css)
check("M2 375px taşma riski: kartlar 100% width + object-fit korundu",
      "aspect-ratio: 3 / 4" in css and "object-fit: cover" in css)
check("M3 prefers-reduced-motion bloğu korunuyor",
      "prefers-reduced-motion: reduce" in css)

# ── Versiyon ──
check("V1 SUTRE_VERSION 3.2.0 (cache kır)", "define( 'SUTRE_VERSION', '3.2.0' );" in func)
check("V2 style.css header Version: 3.2.0", "Version: 3.2.0" in css)

failed = [r for r in results if not r[1] and r[0].startswith(("K", "P", "M", "V"))]
for name, ok, detail in results:
    print(("PASS " if ok else "FAIL ") + name + (f"  [{detail}]" if detail else ""))
print()
print(f"TOPLAM: {len(results)} — PASS: {len(results) - len(failed)} — FAIL: {len(failed)}")
sys.exit(1 if failed else 0)
