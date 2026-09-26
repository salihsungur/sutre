# PACK-99 — SOP VERBATIM kayıt + `07-operasyon` kategorisi (26-09-2026)

Kaynak: `~/.hermes/composer-pastes/pasted_content_2026-09-26_11-15-13-760_f06a97.txt`
Kanonik: `01-repo/docs/operations/SOP-siparis-yasal-dongu.md` · Masaüstü: `~/Desktop/SUTRE/07-operasyon/` (symlink)

## 1. VERBATIMLİK KANITI (ham; yöntem `cp`)

```
=== SHA-256 KAYNAK ===
86420ca737c66aa2b87211e37a041340e59b83b37c603dc410578ebbbe93903b  ...f06a97.txt
=== SHA-256 HEDEF ===
86420ca737c66aa2b87211e37a041340e59b83b37c603dc410578ebbbe93903b  .../docs/operations/SOP-siparis-yasal-dongu.md
=== wc KAYNAK (l/w/c) ===    87  663  5231
=== wc HEDEF   (l/w/c) ===    87  663  5231
=== git diff --no-index --stat (boş olmalı) ===
diff-exit: 0      (çıktı YOK → fark yok)
```
Üçü de eşleşti: SHA `86420ca7…`, sayımlar **87·663·5231**, diff boş.

## 2. SYMLINK KANITI

```
lrwxr-xr-x@ 1 salihsungur staff 83 ... SOP-siparis-yasal-dongu.md ->
  /Users/salihsungur/Desktop/SUTRE/01-repo/docs/operations/SOP-siparis-yasal-dongu.md

cat (symlink üzerinden ilk satır):
# Standart Operasyon Prosedürü (SOP): E-Ticaret Sipariş & Yasal İşlem Döngüsü

shasum -a 256 (symlink üzerinden): 86420ca737c66aa2b87211e37a041340e59b83b37c603dc410578ebbbe93903b
```
Kopya değil bağ: `07-operasyon/` boyutu 0B → drift yok.

## 3. README + AGENTS

- `README.md`: kategori tablosuna `07-operasyon/` satırı ("operasyon prosedürleri (SOP); kanonik dosyalar `01-repo/docs/` altında sürümlenir"), tam ağaca symlink notu, boyut tablosuna `07-operasyon/ | symlink (0B)`.
- `AGENTS.md`: §7'ye açık kapı notu (KDV oranı girdi; `LEGAL_REVIEW_REQUIRED`; GİB e-Arşiv + Shipink hesabı sahipte) + kayıt defterine SOP maddesi.
- **SAPMA:** İstenen "§9 kayıt defteri" YOK; AGENTS.md bölümleri 0–8, "kayıt defteri" = **§8**. Madde §8'e işlendi (yalnız numarada sapma, içerikte değil).

## 4. COMMIT / PUSH

```
commit 7a83b5a  docs(operations): satis & yasal islem dongusu SOP'u verbatim eklendi
commit 27d51a6  docs(dispatch): out-99 raporu kanitlarla tamamlandi
push : 7a83b5a..27d51a6  main -> main
HEAD == origin/main : 27d51a68cab0bc820d5a46799b9250c5a2e11f8c
git show HEAD:…SOP…md | shasum -a 256 = 86420ca7…     (commit içi blob birebir)
```
Son `git status --porcelain`: bu paketin ürettiği dosyalar commit'li → **kapsam temiz**. Kalan izler başka paketlerin (`pack-100`, koşu logları) ve orkestratör paket dosyasının — rol sınırı gereği DOKUNULMADI.

## 5. DURDURULDU / YAPILAMADI

Yok. Kaynak composer-pastes dosyasına dokunulmadı; FTP/canlı site kullanılmadı; belge içeriği değiştirilmedi.