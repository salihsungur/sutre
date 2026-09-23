#!/usr/bin/env python3
"""SUTRE ürün görseli üretimi — Higgsfield API (xai/grok-imagine-image-2.0).

Kullanım:
  ~/.hermes/tools/higgsfield-venv/bin/python scripts/higgsfield/generate-product-images.py \
      --pack docs/visual-prompts/prompt-pack-v2-iman-nour-pamuk.md \
      --color bej --shot urun-drape \
      --ref ~/Desktop/SUTRE/"Ürün Fotoğrafları"/"İman Nour"/_referans/<dosya>.jpg \
      --out-dir ~/Desktop/SUTRE/"Ürün Fotoğrafları"/"İman Nour"/Bej

Kimlik: HF_KEY ortam değişkeni ya da
  ~/Library/Application Support/Hermes/sutre-higgsfield.env  (HF_KEY=KEY_ID:KEY_SECRET)
Kimlik ASLA ekrana basılmaz, repoya/log'a yazılmaz.

Notlar:
- Referans ZORUNLU (AGENTS §4): --ref verilmezse betik çalışmaz.
- Promptlar prompt paketinden okunur ({COLOR} yer tutucusu renkle doldurulur).
"""
from __future__ import annotations

import argparse
import json
import os
import pathlib
import re
import sys

CRED_FILE = pathlib.Path.home() / "Library/Application Support/Hermes/sutre-higgsfield.env"
MODEL_ID = "xai/grok-imagine-image-2.0"
DEFAULT_PROMPTS = pathlib.Path(__file__).resolve().parents[2] / "docs/visual-prompts/prompts-iman-nour-v3.json"

SHOTS = ("urun-drape", "makro-doku", "model-portre")


def load_prompt_spec(prompts_path: pathlib.Path) -> dict:
    spec = json.loads(prompts_path.read_text(encoding="utf-8"))
    return spec


def build_prompt(spec: dict, color_slug: str, shot: str) -> tuple[str, str, dict]:
    """(prompt, aspect_ratio, defaults) döndürür."""
    colors = {c["slug"]: c for c in spec["colors"]}
    if color_slug not in colors:
        sys.exit(f"HATA: '{color_slug}' renk tanımı yok. Seçenekler: {sorted(colors)}")
    c = colors[color_slug]
    tmpl = spec["templates"].get(shot)
    if not tmpl:
        sys.exit(f"HATA: '{shot}' şablonu yok. Seçenekler: {sorted(spec['templates'])}")
    vals = dict(c)
    vals.update(c.get("portrait", {}))
    vals["pattern_short"] = c["pattern"]
    try:
        prompt = tmpl.format(**vals)
    except KeyError as e:
        sys.exit(f"HATA: şablonda eksik alan {e} (renk: {color_slug}).")
    d = spec.get("_meta", {}).get("defaults", {})
    return prompt, d.get("aspect_ratio", "3:4"), d


def load_credentials() -> str:
    key = os.environ.get("HF_KEY") or os.environ.get("HF_CREDENTIALS")
    if not key and CRED_FILE.exists():
        vals: dict[str, str] = {}
        for line in CRED_FILE.read_text(encoding="utf-8").splitlines():
            line = line.strip()
            if not line or line.startswith("#") or "=" not in line:
                continue
            k, _, v = line.partition("=")
            vals[k.strip()] = v.strip()
        key = vals.get("HF_KEY") or vals.get("HF_CREDENTIALS")
        if not key:
            api_key = vals.get("HF_API_KEY") or vals.get("HF_API_KEY_ID")
            api_secret = vals.get("HF_API_SECRET") or vals.get("HF_KEY_SECRET")
            if api_key and api_secret:
                key = f"{api_key}:{api_secret}"
    if not key:
        sys.exit(
            f"HATA: Higgsfield kimlik bilgisi yok. {CRED_FILE} içine 'HF_KEY=KEY_ID:KEY_SECRET' yazın "
            "(ya da HF_API_KEY + HF_API_SECRET satırlarını ayrı ayrı)."
        )
    return key


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--prompts", type=pathlib.Path, default=DEFAULT_PROMPTS,
                    help=f"JSON prompt kaynağı (varsayılan: {DEFAULT_PROMPTS})")
    ap.add_argument("--color", required=True)
    ap.add_argument("--shot", required=True, choices=list(SHOTS))
    ap.add_argument("--ref", required=True, nargs="+", type=pathlib.Path, help="referans görsel(ler) — ZORUNLU")
    ap.add_argument("--out-dir", required=True, type=pathlib.Path)
    ap.add_argument("--quality", default=None, choices=["low", "medium"])
    ap.add_argument("--resolution", default=None, choices=["1k", "2k"])
    ap.add_argument("--aspect", default=None, help="geçersiz kıl (varsayılan: JSON'daki değer)")
    ap.add_argument("--dry-run", action="store_true", help="API çağrısı yapmadan prompt/parametreleri yazdır")
    args = ap.parse_args()

    for ref in args.ref:
        if not ref.exists():
            sys.exit(f"HATA: referans bulunamadı: {ref}")

    spec = load_prompt_spec(args.prompts)
    prompt, aspect_default, defaults = build_prompt(spec, args.color, args.shot)
    aspect = args.aspect or aspect_default
    resolution = args.resolution or defaults.get("resolution", "2k")
    quality = args.quality or defaults.get("quality", "medium")
    args.out_dir.mkdir(parents=True, exist_ok=True)
    out_name = f"{args.color}-pamuk-{args.shot}.png"

    if args.dry_run:
        print("DRY-RUN — API çağrısı yapılmadı")
        print(f"model      : {MODEL_ID}")
        print(f"aspect     : {aspect}  resolution: {resolution}  quality: {quality}")
        print(f"referans   : {[str(r) for r in args.ref]}")
        print(f"çıktı      : {args.out_dir / out_name}")
        print(f"prompt     :\n{prompt}")
        return 0

    key = load_credentials()
    os.environ["HF_KEY"] = key

    import higgsfield_client as hf

    urls = [hf.upload_file(str(r)) for r in args.ref]
    print(f"referans URL: {len(urls)} adet yüklendi")

    result = hf.subscribe(
        MODEL_ID,
        arguments={
            "prompt": prompt,
            "image_urls": urls,
            "quality": quality,
            "resolution": resolution,
            "aspect_ratio": aspect,
        },
    )

    status = result.get("status") if isinstance(result, dict) else None
    if status != "completed":
        print(f"HATA: beklenmeyen durum: {result}")
        return 1

    images = result.get("images") or []
    if not images:
        print("HATA: görsel dönmedi.")
        return 1

    import urllib.request

    for i, img in enumerate(images):
        url = img.get("url") if isinstance(img, dict) else None
        if not url:
            continue
        target = args.out_dir / (out_name if i == 0 else f"{args.color}-pamuk-{args.shot}-{i}.png")
        urllib.request.urlretrieve(url, target)
        print(f"indirildi: {target}  ({target.stat().st_size} B)")

    print("TAMAM")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())