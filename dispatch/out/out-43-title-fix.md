# out-43 — Kart başlığı solda yapışık: kök neden + fix (tamamlanan kayıt)

Not: Coder bot (proc_093c8dfaa8f9) fix'i commit edip push'ladıktan (efc30be) sonra final rapor yazımında durduruldu; bu kayıt Hermes tarafından tamamlanır. İki bağımsız teşhis (bot + Hermes) birebir aynı sonuca ulaştı.

## Sonuç
- **Kök neden (dosya kanıtlı):** `wp-content/plugins/woocommerce/assets/css/woocommerce.css` (11.1.0):
  `.woocommerce ul.products li.product .woocommerce-loop-product__title { padding:.5em 0; margin:0; font-size:1em }`
  → özgülük **(0,4,2)** (4 class + 2 element). `padding-left:0` = başlık sol kenara yapışık.
- P42.4 tema kuralı `ul.products li.product h2.__title` = (0,3,3) → class karşılaştırması (b=4 > b=3) Woo kazanır; h2 element eklemek yetmedi. Fiyat kayıyordu çünkü Woo'nun `.price` kuralında padding yoktu — semptomun asimetrisinin açıklaması.
- **Fix (efc30be):** tema seçiciye `.woocommerce` prefix → `.woocommerce ul.products li.product h2.woocommerce-loop-product__title` = **(0,4,3)**; b eşit (4=4), c üstün (3>2) → tema GARANTİ kazanır. Ek: style.css head sırasında woocommerce.css'ten sonra yüklenir (curl kanıtlı). `position:static !important` korunur. SUTRE_VERSION 3.4.0.

## Doğrulama
- Woo CSS kuralı FTP'den indirilip satır bazında teyit edildi (tam erişim).
- Canlı: `style.css?ver=3.4.0` sunuluyor; dosya içinde kazanan seçici + `padding: 20px 26px 0` mevcut; Fatal 0.
- Deploy: style.css + functions.php → `/staging.sutre.store/wp-content/themes/sutre-child/` (SHA PASS).

## Risk ve güvenlik
- Sadece CSS seçicisi + sürüm sabiti; davranışsal değişiklik yok. Rollback: `git revert efc30be` + redeploy.

## Açık kapılar
- Kullanıcı görsel onayı BEKLİYOR.
- Bu turda FTP tam erişim açıldı → göreli yol kazası (home köküne /functions.php, /style.css çöpü) tespit edilip silindi; AGENTS.md §3'e MUTLAK yol kuralı işlendi.
