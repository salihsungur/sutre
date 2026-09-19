# PAKET 13 — Hosting SSH Keşif ve Ortam Envanteri (@devops)

Tarih: 2026-09-12 · Mod: YALNIZ KEŞİF (salt-okuma) · Rapor formatı: anayasa §16

## Sonuç
- Tamamlanan hedef: KISMİ — SSH anahtarı ve hedef host doğrulandı; ancak SSH oturumu HİÇBİR portta kurulamadı. Uzak ortam envanteri (whoami/docroot/PHP/MariaDB/cron/SSL/WordPress varlığı) ve uzak taraf DNS kanıtı TOPLANAMADI.
- Değiştirilen dosyalar: `dispatch/out/evidence-13-ssh.txt`, `evidence-13-dns.txt`, `dispatch/out/out-13-ssh-discovery.md` (bu rapor). Uzak sistemde HİÇBİR değişiklik yok.
- Veritabanı/ayar etkisi: YOK (uzak bağlantı kurulamadı; kurulum/DB oluşturma zaten kapsam dışıydı).

## Doğrulama
- Çalıştırılan testler ve kanıtlar:
  - Anahtar: `ssh-keygen -lf` → `256 SHA256:wYI8pi23RvK961zOFjOH2N9/rM0ma1fn+X4kjqxA3UU hermes-hosting sutre cpanel (ED25519)` — sahibin verdiği parmak iziyle BİREBİR EŞLEŞTİ (evidence-13-ssh.txt).
  - Host çözümleme: `mt-charon.guzelhosting.com → 89.252.180.242` (getent + Python socket, iki bağımsız yöntem) — host AYAKTA ve isim çözülüyor.
  - SSH port 22: `Connection refused` (sunucu erişilebilir ama 22'de dinleyici yok — ağ bloğu değil, sunucu tarafı reddi).
  - SSH port 2222: `Connection timed out` (filtered).
  - Port taraması (bash /dev/tcp, 3 koşu): tutarlı açık portlar yalnız `2083, 2087, 443` — bunlar cPanel/HTTPS servisleri; SSH handshake denemeleri `Connection closed` döndü (SSH banner yok). Diğer aday portlar (23, 2200, 2100, 2020, 2223, 10000, 21098) kapalı.
  - DNS (LOKAL fallback — uzak SSH kapalı olduğundan remote `getent` yapılamadı): `sutre.store` ve `staging.sutre.store` → `Name or service not known` (getent + Python socket, iki yöntemle teyit). NS/SOA ayrımı bu makinede dig/nslookup bulunmadığından yapılamadı; orkestratörün önceki REFUSED bulgusuyla uyumlu: zone sorgulanamıyor / A kaydı yok.
- PASS sonuçları: anahtar parmak izi eşleşmesi; host DNS çözümlemesi; port keşif tutarlılığı (3 koşu); lokal DNS negatif teyidi.
- FAIL / atlanan testler ve nedeni: Uzak envanter komutlarının TAMAMI atlandı — SSH oturumu kurulamadığı için yürütülemediler (evidence-13-env.txt dosyası bu yüzden OLUŞTURULMADI; oluşturulması sahte kanıt olurdu). Remote DNS kanıtı lokal kontrolle değiştirildi.

## Risk ve güvenlik
- Secret veya kişisel veri etkisi: YOK. Anahtar private bileşeni hiçbir çıktıya yazılmadı; yalnız public parmak izi kaydedildi.
- Ödeme/fiyat/stok/fatura etkisi: YOK.
- Geri dönüş adımı: Gerekmez — uzak sistemde okuma/yazma yapılmadı (bağlantı kurulamadı).

## Açık kapılar
- NEEDS_OWNER_INPUT: cPanel → "SSH Access" sayfasından (a) SSH servisinin Deactivate/Activate durumu, (b) kullanılan dinleme portu. Port 22'de sunucu tarafı RED var: servis ya kapalı ya özel bir portta dinliyor.
- OWNER_APPROVAL_REQUIRED: SSH erişimi aktif edildikten ve port bildirildikten sonra bu paketin uzak envanter kısmı yeniden koşulur. Ayrıca anayasa §8.1 uyarınca AI'ya kalıcı production SSH yetkisi verilmez; keşif için geçici erişim sahibin onayıyla sınırlandırılmalıdır.
- LEGAL_REVIEW_REQUIRED: YOK (bu paket kapsamında).
- Not — envanter boşluğu: PHP sürümü (version-lock.md'deki PHP 8.4 hedefiyle uyumluluk), MariaDB sürümü, docroot yapısı (staging/production ayrımı), cron durumu, wp-cli varlığı, disk/kota ve WordPress öncülü kontrolü kanıtsız kalmıştır. Bunlar §3.1/§13 Faz 1 kapıları için zorunlu kanıttır; SSH erişimi açılana kadar hiçbiri varsayılamaz.
- Sonraki en küçük güvenli adım: Sahibin cPanel "SSH Access" sayfasından SSH'ı aktive edip port numarasını bildirmesi; ardından bu paketin yalnız envanter bölümü (salt-okuma) tek oturumla tekrar koşulur.
