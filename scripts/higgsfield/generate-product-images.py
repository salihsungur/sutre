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
import os
import pathlib
import re
import sys

CRED_FILE = pathlib.Path.home() / "Library/Application Support/Hermes/sutre-higgsfield.env"
MODEL_ID = "xai/grok-imagine-image-2.0"

COLOR_PROMPT = {
    "karamel": "rich caramel",
    "gul-kurusu": "dusty rose-brown (gul kurusu)",
    "gri-bej": "soft greige (grey-beige)",
    "lacivert": "deep navy blue",
    "visne-curugu": "deep cherry red (visne curugu)",
}

SHOTS = {
    "urun-drape": {"aspect_ratio": "2:3", "resolution": "2k", "heading": "GÖRSEL 1 — ÜRÜN DRAPE"},
    "makro-doku": {"aspect_ratio": "1:1", "resolution": "2k", "heading": "GÖRSEL 2 — MAKRO DOKU"},
    "model-portre": {"aspect_ratio": "2:3", "resolution": "2k", "heading": "GÖRSEL 3 — MODEL PORTRE"},
}


def load_credentials() -> str:
    key = os.environ.get("HF_KEY") or os.environ.get("HF_CREDENTIALS")
    if not key and CRED_FILE.exists():
        for line in CRED_FILE.read_text(encoding="utf-8").splitlines():
            line = line.strip()
            if line.startswith("HF_KEY=") and line.partition("=")[2].strip():
                key = line.partition("=")[2].strip()
                break
    if not key:
        sys.exit(
            f"HATA: HF kimlik bilgisi yok. {CRED_FILE} içine 'HF_KEY=KEY_ID:KEY_SECRET' yazın "
            "(veya HF_KEY ortam değişkenini ayarlayın)."
        )
    return key


def load_prompt(pack_path: pathlib.Path, shot: str, color: str) -> str:
    text = pack_path.read_text(encoding="utf-8")
    heading = SHOTS[shot]["heading"]
    pattern = re.compile(rf"^###\s+{re.escape(heading)}.*?$", re.MULTILINE)
    m = pattern.search(text)
    if not m:
        sys.exit(f"HATA: prompt paketinde '{heading}' başlığı bulunamadı ({pack_path}).")
    rest = text[m.end():]
    nxt = re.search(r"^###\s+", rest, re.MULTILINE)
    block = rest[: nxt.start()] if nxt else rest
    # başlık altındaki parantez notunu at, ilk paragrafı al
    lines = [ln.strip() for ln in block.splitlines() if ln.strip() and not ln.strip().startswith("(")]
    if not lines:
        sys.exit(f"HATA: '{heading}' altında prompt metni yok.")
    prompt = lines[0]
    return prompt.replace("{COLOR}", COLOR_PROMPT[color])


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--pack", required=True, type=pathlib.Path)
    ap.add_argument("--color", required=True, choices=sorted(COLOR_PROMPT))
    ap.add_argument("--shot", required=True, choices=sorted(SHOTS))
    ap.add_argument("--ref", required=True, nargs="+", type=pathlib.Path, help="referans görsel(ler) — ZORUNLU")
    ap.add_argument("--out-dir", required=True, type=pathlib.Path)
    ap.add_argument("--quality", default="medium", choices=["low", "medium"])
    ap.add_argument("--dry-run", action="store_true", help="API çağrısı yapmadan prompt/parametreleri yazdır")
    args = ap.parse_args()

    for ref in args.ref:
        if not ref.exists():
            sys.exit(f"HATA: referans bulunamadı: {ref}")

    prompt = load_prompt(args.pack, args.shot, args.color)
    shot = SHOTS[args.shot]
    args.out_dir.mkdir(parents=True, exist_ok=True)
    out_name = f"{args.color}-pamuk-{args.shot}.png"

    if args.dry_run:
        print("DRY-RUN — API çağrısı yapılmadı")
        print(f"model      : {MODEL_ID}")
        print(f"aspect     : {shot['aspect_ratio']}  resolution: {shot['resolution']}  quality: {args.quality}")
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
            "quality": args.quality,
            "resolution": shot["resolution"],
            "aspect_ratio": shot["aspect_ratio"],
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