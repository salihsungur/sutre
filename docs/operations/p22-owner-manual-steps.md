# HUKUKİ TASLAK METİNLER — SAHİBİN ELLE ADIMLARI (PAKET 22, 2026-09-17)

> Bot sunucuya erişemiyor; aşağıdaki adımlar SAHİBİN cPanel + WP-admin üzerinde ELLE yapması içindir.
> Her adım tek elle işlem; kod tarafı (tema footer/header + taslak metinler) repo'dan gelir.

## 0. Repo güncelleme (İLK ADIM — diğer tüm adımların ön şartı)
1. cPanel → Files / Git™ Version Control → `sutre` reposu → **Update from Remote** butonuna bas (deploy key üzerinden GitHub main çeker).
2. Çalışma kopyası `/home/<kullanıcı>/.git/sutre-repo` ise deploy hook/klasör kopyalama mevcut P13 script akışına göre yapılır (sahip bilinen akış).

## 1. LiteSpeed Cache purge (cache busters)
1. WP-admin → LiteSpeed Cache → Toolbox → **Purge All** (veya toolbar'daki LiteSpeed ikonu → Purge All).
2. cPanel → Select PHP Version / MultiPHP değişikliği yapılmadıysa ek işlem gerekmez.
3. Tarayıcıdan siteyi **gizli pencere (Ctrl+Shift+N)** aç — eski CSS/JS cache'sini görmemek için zorunlu.

## 2. Site adı: "Sutre Staging" → "Sutre"
1. WP-admin → Settings → General → **Site Title**: `Sutre` (elle aynen yaz — tırnak/boşluk yok).
2. Kaydet. Tema ayrıca ön yüzde "Staging" sonekini temizleyen filtre içerir (sahibin düzeltmesi tamamlanana kadar ÇİFT emniyet; sonradan kaldırılacak).

## 3. Header/footer tekilleştirmenin sahibin tarafı
Tema dosyaları repo'dan otomatik gelir (`theme/sutre-child/parts/header.html`, `footer.html`, `override.css`). Elle yapılacak tek şey:
1. WP-admin → Görünüm → Editör → Template Parts listesinde eski (override edilmiş) header/footer part'ları hâlâ görünüyorsa, `sutre-header--unified` sınıflı yeni kayıtların kullanımını görsel onayla.
2. **Sakın:** WP-admin ekranından footer metnini elle yazma — repo ile ÇAKIŞIR; footer metni tek kaynak: `parts/footer.html`.

## 4. PayTR başvuru ön-görüntü uyarısı (elle oku)
- Başvuru sırasında "siteyi güzelleştir / sadeleştir" gibi otomatik öneri yoktur; PayTR inceleme ekibi siteyi **canlı hukuki sayfalarla birlikte** görür.
- Hukuki sayfalar şu an DRAFT; PayTR başvurusu öncesi avukat onayı tamamlanmış versiyonların sitede YAYINDA olması gerekir (ekip %100 beklenen görsel değil, HUKUKİ BÜTÜNLÜK bakar).
- `docs/legal-placeholders/*` dosyalarındaki DRAFT uyarıları kaldırmadan siteye PYASAMAYA koyma.

## 5. Hukuki taslak onay akışı
1. Her dosya avukata iletilir (`LEGAL_REVIEW_REQUIRED`).
2. Onaylı versiyon WP sayfalarına elle işlenir (Gizlilik, Mesafeli Satış, İade/Cayma, Ön Bilgilendirme, Çerez).
3. Footer'daki `Hukuki:` linkleri (Gizlilik | Mesafeli Satış | İade) bu sayfalara bağlanır; sayfa slug'ları metin başlıklarıyla eşleştirilir (placeholding `/gizlilik-politikasi/` vb.).

## 6. Sır yok
Tüm taslak metinlerde secret/anahtar yok; PayTR secret'ları hiçbir belgeye yazılmaz (anayasa §4.2).
