# P24 — Sahibin Elle Adımları (cPanel / hosting)

Bu ortamda yerel PHP linter yok; sözdizimi kontrolü ve görüntü kontrolü sende.

1. Pull:
   git pull origin main
   (beklenen SHA'lar: feat d1ae48a + docs commit; kanıtı raporda gör)

2. PHP sözdizimi kontrolü (cPanel Terminal veya SSH):
   php -l ~/public_html/wp-content/themes/sutre-child/page-templates/home.php
   Beklenen: "No syntax errors detected"

3. CSS purge (LiteSpeed Cache varsa):
   cPanel → LiteSpeed Cache Manager → Purge All.
   Alternatif: WP Yönetim → LiteSpeed Cache → Cache ClearAll.

4. Görüntü kontrolü (masaüstü + 375px mobil, gizli pencere):
   - Ana sayfa sırası: Hero (Sutre + tagline + Koleksiyonu Keşfet) →
     tanıtım cümlesi → 3 koleksiyon kartı → ürün grid'i.
   - "Hikâyemiz" ve "Neden Sutre?" YOK.
   - Öne çıkan ürünler bölüm başlığı "Koleksiyon".
   - 375px: featured grid tek kolon, dokunma hedefleri ≥44px.

5. Sorun varsa rollback:
   git revert d1ae48a   # feat commit — story/values geri gelir
   git push origin main
   (docs commit'i revert gerekmez)
