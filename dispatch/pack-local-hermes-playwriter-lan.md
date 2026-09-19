# GÖREV: Playwriter relay'ını LAN'a aç (host'ta 0.0.0.0) + Docker Hermes'e erişim izni tesis et

## BAĞLAM
Salih'in Docker'daki Hermes agent'ı, Salih'in Brave tarayıcısındaki Playwriter eklentisine bağlanmaya çalışıyor.
Docker'dan `host.docker.internal:19988`'e bağlanınca `Forbidden - Invalid Host header` hatası döndü (host header reddi).
Sen HOST'ta doğrudan çalışıyorsun; bu görevi sen yapacaksın. Salih Docker'ı komutla yayınlanmaya hazırlamanı bekliyor.

## YAPILACAKLAR

### 1) Relay'i LAN'a (0.0.0.0) başlat
Önce çalışmakta olan daemon'ı durdur (Ctrl+C veya `pkill -f playwriter`), sonra:
```bash
npx -y playwriter@latest serve --host 0.0.0.0
```

### 2) Dinleme doğrulaması
```bash
ss -tln | grep 19988
```
Beklenen: `0.0.0.0:19988` LISTEN satırı.

### 3) Windows Firewall izni (Salih Windows'taysa; PowerShell'i yönetici olarak çalıştırın)
```powershell
netsh advfirewall firewall add rule name="Playwriter 19988" dir=in action=allow protocol=TCP localport=19988
```

### 4) Docker içinden erişim testi
Docker agent'ına (Salih'in kendi kontrolü) şunu koştur — ya da Terminal'den LOCAL TEST et:
```bash
curl -s --max-time 5 http://host.docker.internal:19988
```
- Beklenen: `OK` ya da HTTP 200 - Forbidden ÇIKMAMALI
- Forbidden persist ederse:
  - Windows hosts dosyasına (`C:\Windows\System32\drivers\etc\hosts`) ekle:
    ```
    host.docker.internal host-gateway
    ```
  - Docker CLI'de `docker run --add-host=host.docker.internal:host-gateway` (Docker Desktop'ta genelde default).

### 5) Son doğrulama: Brave üzerinden gerçek test
```bash
npx playwriter session new
npx playwriter -s 1 -e "await page.goto('https://staging.sutre.store/shop/'); await page.screenshot({ path: 'shop-2.png' }); console.log(await page.title());"
```
Beklenen: `Shop – Sutre Staging` başlığı + `shop-2.png` dosyası + "Ok".

### KAPANIŞ / RAPOR
- Salih'e net bilgi ver:
  - Yeşil ikon + session ID + shop-2.png dosyası ✓ → "Remote reliable, Docker agent bağlantısı tamam" («дальше bırak»).
  - Hata varsa: hata metnini aynen yaz → Salih'e iletilecek; alternatif Yol B "traforo tunnel" (remote-access) bağlayacak.
- out-of-scope: herhangi bir staging/cPanel/DB code dokunma (spin-up YOK).

# NOTE: Test STARTtelleştirme defense — Selih'in Brave tarayıcında shop sayfası açılıp ürünlerin görüntüsü doğrulamanız kanıtı sayılır.
