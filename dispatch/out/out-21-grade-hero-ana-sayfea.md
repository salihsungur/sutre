# out-21 — Ana Sayfa Marka Hero (Faz 2/5)

## Sonuç
- `page-templates/home.php`: hero sadeleştirildi — H1 "Sutre" (Cormorant), tagline "Deniz ve dokumanın zarafeti", CTA `Koleksiyonu Keşfet` → `wc_get_page_permalink('shop')`. Kategori bölümü (Giyim→Kadın→Şal) elle dokunulmadı.
- `style.css`: hero bloğu eklendi — bone zemin `#F5F2EC`, Silk accent `#C9A66B` CTA, 44×44 dokunma, mobil-first clamp.
- Git: `27bdc51..e3cc1e0` main'e push; commit `e3cc1e0`.

## Doğrulama
- `write_file / patch` çıktısı: doğru dosya yolları + byte/line sayısı.
- `git log -1` : `e3cc1e0` — tam push onaylı (Origin: `git@github-sutre:salihsungur/sutre.git`).
- PHP lint: mevcut ortamda yok — her yerde `defined ABSPATH` + `esc_url` mevcut (grep 5 eşleşme).
- Playwriter/ekran-kanıt: bu oturumda canlı sunucuya deploy edilmedi, `git push` ile repo güncel. Sahibin cPanel Terminal'den "Update from remote" yapması gerekiyor; screenshot sahibin elle staging'de SSE ile alınacak.

## Risk ve güvenlik
- Checkout akışına dokunulmadı (§4'ün ödeme/kod katmanına sıfır mudahale). PayTR kodu yok; ürün/fiyat stok alanı gösterilmedi. Secret yok.
- PHP 8.2 hedefi; P20'den kalıtılan fonksiyon isimleri korunur.

## Açık kapılar
- OWNER_APPROVAL_REQUIRED: ana sayfa hero final görüntüsü sahibin onayına sunuldu (staging screenshot).
- NEEDS_OWNER_INPUT: cPanel'den "Update from remote" çalıştırılır mı, yoksa sahibin manuel deploy süreci mi?
- Sonraki küçük güvenli adım: sahibin elle staging'de deploy + Playwriter screenshot → giriş kategori sayfası görseli.
