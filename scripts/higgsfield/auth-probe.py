#!/usr/bin/env python3
"""Higgsfield kimlik doğrulama probu — KREDI HARCAMAZ.

Kredi dosyasındaki değerin hangi biçimde çalıştığını bulur:
  1) KEY_ID:KEY_SECRET  → Authorization: Key <id>:<secret>
  2) tek parça anahtar  → Authorization: Key <key> / Bearer <key> / hf-api-key <key>
Ücretsiz bir uç noktaya (var olmayan bir isteğin durumu) istek atar:
  401 → kimlik GEÇERSİZ
  404 (veya başka 2xx/4xx-iş) → kimlik GEÇERLİ (sadece kayıt yok)

Değerler asla ekrana basılmaz; yalnız HTTP kodları ve kullanılan şema yazılır.
"""
from __future__ import annotations

import json
import os
import pathlib
import sys
import urllib.error
import urllib.request

CRED_FILE = pathlib.Path.home() / "Library/Application Support/Hermes/sutre-higgsfield.env"
BASE = "https://api.higgsfield.ai"
PROBE = f"{BASE}/requests/00000000-0000-0000-0000-000000000000/status"


def load_value() -> str:
    val = os.environ.get("HF_KEY") or os.environ.get("HF_CREDENTIALS")
    if not val and CRED_FILE.exists():
        vals: dict[str, str] = {}
        for line in CRED_FILE.read_text(encoding="utf-8").splitlines():
            line = line.strip()
            if not line or line.startswith("#") or "=" not in line:
                continue
            k, _, v = line.partition("=")
            vals[k.strip()] = v.strip()
        val = vals.get("HF_KEY") or vals.get("HF_CREDENTIALS")
        if not val:
            k = vals.get("HF_API_KEY") or vals.get("HF_API_KEY_ID")
            s = vals.get("HF_API_SECRET") or vals.get("HF_KEY_SECRET")
            if k and s:
                val = f"{k}:{s}"
    if not val:
        sys.exit("HATA: kredi dosyasında değer bulunamadı.")
    return val


def call(headers: dict[str, str]) -> tuple[int | None, str]:
    req = urllib.request.Request(PROBE, headers=headers, method="GET")
    try:
        with urllib.request.urlopen(req, timeout=30) as r:
            return r.status, r.read(200).decode("utf-8", "replace")
    except urllib.error.HTTPError as e:
        return e.code, e.read(200).decode("utf-8", "replace")
    except Exception as e:  # ağ hatası
        return None, f"{type(e).__name__}: {e}"


def main() -> int:
    val = load_value()
    has_pair = ":" in val
    print(f"kredi: {'KEY_ID+SECRET çifti' if has_pair else 'tek parça anahtar'} "
          f"(uzunluk {len(val)}, iki nokta {'var' if has_pair else 'yok'})")
    print(f"prob uc noktasi: {PROBE}\n")

    attempts: list[tuple[str, dict[str, str]]] = []
    if has_pair:
        kid, _, sec = val.partition(":")
        attempts += [
            ("Authorization: Key <id>:<secret>", {"Authorization": f"Key {val}"}),
            ("hf-api-key + hf-secret", {"hf-api-key": kid, "hf-secret": sec}),
            ("Authorization: Bearer <id>:<secret>", {"Authorization": f"Bearer {val}"}),
        ]
    else:
        attempts += [
            ("Authorization: Key <key>", {"Authorization": f"Key {val}"}),
            ("Authorization: Bearer <key>", {"Authorization": f"Bearer {val}"}),
            ("hf-api-key: <key>", {"hf-api-key": val}),
            ("x-api-key: <key>", {"x-api-key": val}),
        ]

    ok = False
    for label, headers in attempts:
        code, body = call(headers)
        note = ""
        if code == 401:
            note = "→ kimlik REDDEDİLDİ"
        elif code is not None and code != 401:
            note = "→ kimlik KABUL (uç nokta kaydı yok)"
            ok = True
        else:
            note = f"→ ağ hatası ({body[:80]})"
        print(f"  {label:38s} HTTP {code}  {note}")
        if code is not None and code not in (401, 403):
            try:
                print(f"     yanıt: {json.dumps(json.loads(body))[:120]}")
            except Exception:
                pass

    print("\nSONUÇ:", "çalışan bir şema bulundu ✓" if ok else "hiçbir şema kabul edilmedi ✗")
    return 0 if ok else 1


if __name__ == "__main__":
    raise SystemExit(main())