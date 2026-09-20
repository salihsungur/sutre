#!/usr/bin/env python3
"""
P31 doğrulama testleri: block template kapatma + klasik Woo şablon override'ları.

Zero-dependency (PHP runtime yok; statik yapı + bütünlük doğrulaması):
    python3 tests/test_p31_classic_templates.py

Çıkış kodu: 0 = tüm G testleri yeşil, 1 = en az bir kırmızı.
INFO satırları sonucu etkilemez.
"""
import hashlib
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "theme" / "sutre-child-v2"
SRC = ROOT / ".woo-templates-src" / "woocommerce" / "templates"

results = []


def check(name, cond, detail=""):
    results.append((name, bool(cond), detail))


def md5(p: Path) -> str:
    return hashlib.md5(p.read_bytes()).hexdigest()


func = (THEME / "functions.php").read_text(encoding="utf-8")
css = (THEME / "style.css").read_text(encoding="utf-8")

# ── G1a: after_setup_theme (priority >= 20) içinde remove_theme_support('block-templates')
g1_ok, g1_detail = False, "after_setup_theme callback bulunamadı"
for m in re.finditer(
    r"add_action\(\s*'after_setup_theme'\s*,\s*function\s*\([^)]*\)\s*\{(?P<body>.*?)\}\s*,\s*(?P<prio>\d+)\s*\);",
    func,
    re.S,
):
    if "remove_theme_support( 'block-templates' )" in m.group("body") and int(m.group("prio")) >= 20:
        g1_ok, g1_detail = True, f"priority={m.group('prio')}"
check("G1a block-templates desteği kaldırılıyor (after_setup_theme, prio>=20)", g1_ok, g1_detail)

# ── G1b: eski işlevsiz filtre ÇAĞRISI kaldırılmış olmalı (WP locate_block_template bu filtreyi bilmiyor)
check("G1b add_filter('woocommerce_has_block_template') çağrısı kaldırıldı",
      re.search(r"add_filter\(\s*'woocommerce_has_block_template'", func) is None)

# ── G2a: üç klasik şablon child woocommerce/ altında mevcut
for f in ("archive-product.php", "single-product.php", "content-single-product.php"):
    check(f"G2a woocommerce/{f} mevcut", (THEME / "woocommerce" / f).is_file())

# ── G2b: kopyalar resmî Woo 11.1.0 kaynağıyla birebir (md5 — değişiklik yok, §3.1)
for f in ("archive-product.php", "single-product.php", "content-single-product.php"):
    dst, src = THEME / "woocommerce" / f, SRC / f
    ok = dst.is_file() and src.is_file() and md5(dst) == md5(src)
    check(f"G2b woocommerce/{f} == Woo 11.1.0 kaynağı (md5 birebir)", ok)

# ── G2c: klasik şablonlar get_header/get_footer çağırır (header.php/footer.php zinciri)
for f in ("archive-product.php", "single-product.php"):
    p = THEME / "woocommerce" / f
    if p.is_file():
        t = p.read_text(encoding="utf-8")
        check(f"G2c {f} get_header( 'shop' )", "get_header( 'shop' )" in t)
        check(f"G2c {f} get_footer( 'shop' )", "get_footer( 'shop' )" in t)

# ── G3: sidebar kaldırma (sahibin önceki kararı; şablonlar verbatim kalır)
check("G3 remove_action woocommerce_sidebar/woocommerce_get_sidebar/10",
      re.search(r"remove_action\(\s*'woocommerce_sidebar'\s*,\s*'woocommerce_get_sidebar'\s*,\s*10\s*\)",
                func) is not None)

# ── G4a: shop banner woocommerce_before_main_content, priority 5 (wrapper'dan ÖNCE → full-bleed)
g4_ok, g4_detail = False, "banner callback bulunamadı"
for m in re.finditer(
    r"add_action\(\s*'woocommerce_before_main_content'\s*,\s*function\s*\([^)]*\)\s*\{(?P<body>.*?)\}\s*,\s*(?P<prio>\d+)\s*\);",
    func,
    re.S,
):
    if "sv-shop-banner" in m.group("body"):
        g4_ok = int(m.group("prio")) == 5
        g4_detail = f"priority={m.group('prio')} (beklenen 5)"
check("G4a shop banner priority 5", g4_ok, g4_detail)

# ── G4b: Woo sayfa başlığı gizli (banner h1 kullanılıyor)
check("G4b woocommerce_show_page_title __return_false",
      re.search(r"add_filter\(\s*'woocommerce_show_page_title'\s*,\s*'__return_false'", func) is not None)

# ── G4c: add_theme_support('woocommerce') — klasik 'desteklenen tema' yolu garanti
check("G4c add_theme_support('woocommerce') mevcut",
      re.search(r"add_theme_support\(\s*'woocommerce'", func) is not None)

# ── G4d: Woo default wrapper override → .woocommerce container (G5 padding ön koşulu)
check("G4d default wrapper remove + .woocommerce container",
      re.search(r"remove_action\(\s*'woocommerce_before_main_content'\s*,\s*'woocommerce_output_content_wrapper'\s*,\s*10\s*\)",
                func) is not None
      and re.search(r"echo '<div class=\"woocommerce\">'", func) is not None)

# ── G5a: breadcrumb stili (küçük, whisper)
g5a = ".woocommerce-breadcrumb" in css
if g5a:
    tail = css.split(".woocommerce-breadcrumb", 1)[-1][:500]
    g5a = "whisper" in tail
check("G5a .woocommerce-breadcrumb stili (whisper)", g5a)

# ── G5b: grid container padding section ölçeğiyle tutarlı (mobil 64px 20px / desktop 96px 40px)
bm = re.search(r"#sutre-content \.woocommerce \{(?P<body>.*?)\}", css, re.S)
check("G5b mobil padding 64px 20px (section ile tutarlı)",
      bm is not None and "64px 20px" in bm.group("body"))
bd = re.search(r"@media \(min-width: 782px\) \{.*?#sutre-content \.woocommerce \{(?P<body>.*?)\}", css, re.S)
check("G5b desktop padding 96px 40px (section ile tutarlı)",
      bd is not None and "96px 40px" in bd.group("body"))

# ── INFO: banner görseli (deploy notu — sonucu etkilemez)
banner_img = THEME / "assets" / "img" / "site" / "shop-banner-sutre.png"
check("INFO banner görseli repoda mevcut", True,
      "VAR" if banner_img.is_file() else "YOK — canlıda var mı bakılmalı (deploy notu)")

failed = [r for r in results if not r[1] and r[0].startswith("G")]
for name, ok, detail in results:
    print(("PASS " if ok else "FAIL ") + name + (f"  [{detail}]" if detail else ""))
print()
print(f"TOPLAM: {len(results)} — PASS: {len(results) - len(failed) - (0 if not failed else 0)} — FAIL: {len(failed)} (G testleri)")
sys.exit(1 if failed else 0)
