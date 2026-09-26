# PACK-99 — SOP belgesini VERBATIM kaydet (sipariş & yasal döngü) + `07-operasyon` kategorisi

Tarih: 26-09-2026 · Kaynak: `~/.hermes/composer-pastes/pasted_content_2026-09-26_11-15-13-760_f06a97.txt`
Kanonik hedef: `01-repo/docs/operations/SOP-siparis-yasal-dongu.md` · Masaüstü görünümü: `~/Desktop/SUTRE/07-operasyon/` (symlink)

## 1. VERBATIMLİK KANITI (ham çıktı)

Yöntem: `cp` (yeniden üretim/elle yazma YOK). Üç bağımsız kontrol:

```
=== SHA-256 KAYNAK ===
86420ca737c66aa2b87211e37a041340e59b83b37c603dc410578ebbbe93903b  ...f06a97.txt
=== SHA-256 HEDEF ===
86420ca737c66aa2b87211e37a041340e59b83b37c603dc410578ebbbe93903b  .../docs/operations/SOP-siparis-yasal-dongu.md

=== wc KAYNAK (l/w/c) ===    87  663  5231
=== wc HEDEF   (l/w/c) ===    87  663  5231

=== git diff --no-index --stat (boş olmalı) ===
diff-exit: 0   (çıktı YOK → fark yok)
```

Üç kontrolün üçü de beklenen değerle eşleşti: SHA `86420ca7…`, sayımlar **87·663·5231**, diff boş.

## 2. SYMLINK KANITI

```
lrwxr-xr-x@ 1 salihsungur staff 83 Sep 26 14:17 SOP-siparis-yasal-dongu.md ->
  /Users/salihsungur/Desktop/SUTRE/01-repo/docs/operations/SOP-siparis-yasal-dongu.md

cat (symlink üzerinden ilk satır):
# Standart Operasyon Prosedürü (SOP): E-Ticaret Sipariş & Yasal İşlem Döngüsü

shasum -a 256 (symlink üzerinden): 86420ca737c66aa2b87211e37a041340e59b83b37c603dc410578ebbbe93903b
```

Kopya değil bağ: `07-operasyon/` boyutu 0B; drift yok.

## 3. README + AGENTS DEĞİŞİKLİKLERİ

- `~/Desktop/SUTRE/README.md`: kategori tablosuna `07-operasyon/` satırı (açıklama: "operasyon prosedürleri (SOP); kanonik dosyalar `01-repo/docs/` altında sürümlenir"), tam ağaca `07-operasyon/` + symlink notu, boyut tablosuna `07-operasyon/ | symlink (0B)` eklendi.
- `AGENTS.md`: §7'ye açık kapı notu (KDV oranı girdi; `LEGAL_REVIEW_REQUIRED`; GİB e-Arşiv + Shipink hesabı sahipte) ve kayıt defterine SOP maddesi eklendi.
- **NOT (sapma):** İstenen "§9 kayıt defteri" AGENTS.md'de YOK — dosyada bölümler 0–8 arası; "kayıt defteri" = **§8 Tarihsel Kayıt Defteri**. Madde §8'e işlendi (§0 kuralının atfı §9'a işaret ediyor ama başlık yok; sapma yalnız numarada, içerikte değil).

## 4. COMMIT / PUSH

```
staged: AGENTS.md · dispatch/out/out-99-sop-kaydi.md · docs/operations/SOP-siparis-yasal-dongu.md
commit 7a83b5a  docs(operations): satis & yasal islem dongusu SOP'u verbatim eklendi
push : 773cfe6..7a83b5a  main -> main   (origin: github-sutre)

git rev-parse HEAD origin/main:
7a83b5aa0aa9b11bda2613064e25782db7c1801c   HEAD
7a83b5aa0aa9b11bda2613064e25782db7c1801c   origin/main     → EŞİT
```

Commit içi blob doğrulaması: `git show HEAD:docs/operations/SOP-siparis-yasal-dongu.md | shasum -a 256` = `86420ca7…` (kaynakla birebir).

Son `git status --porcelain` (benim kapsamım TEMİZ):
```
 M dispatch/out/logs/run-pack-98.log      (başka paket — kapsam dışı)
?? dispatch/out/logs/run-pack-100.log     (başka paket — kapsam dışı)
?? dispatch/out/logs/run-pack-99.log      (koşu logu — kapsam dışı)
?? dispatch/pack-100-instagram-bio.md     (başka paket — kapsam dışı)
?? dispatch/pack-99-sop-kaydi.md          (orkestratör paket dosyası — rol sınırı dışı)
```
Kalan izler başka paketlerin/orkestratörün dosyalarıdır; rol sınırı gereği DOKUNULMADI. Bu paketin ürettiği üç dosyanın tümü commit'lendi → kapsam temiz.

## 5. DURDURULDU / YAPILAMADI

Yok — üç verbatimlik kontrolü de geçti; hiçbir adımda DURDURULMADI.
Kaynak composer-pastes dosyasına dokunulmadı; FTP/canlı site kullanılmadı; belge içeriği değiştirilmedi.