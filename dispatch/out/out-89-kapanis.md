# PACK-89 — Kapanış (kısa) raporu

Tarih: 23-09-2026 · Kapsam: ftp-tool `mv` + kalıntı log commit + AGENTS §9 kaydı. FTP'de hiçbir şey taşınmadı/silinmedi; tema/DB/canlı site dokunulmadı.

## (A) ftp-tool.py — `mv` alt komutu

`~/.hermes/tools/ftp-tool.py` — `rm` dalının yanına eklendi (docstring kullanım listesine de işlendi), parola yine `creds()` üzerinden okunur, hiçbir yere yazılmaz:

```
elif cmd == "mv":
    try:
        ftp.rename(sys.argv[2], sys.argv[3])
    except Exception as e:
        sys.exit(f"HATA: yeniden adlandırılamadı: {sys.argv[2]} -> {sys.argv[3]} ({e})")
    print(f"OK: {sys.argv[2]} -> {sys.argv[3]}")
```

`ftplib.FTP.rename` = RNFR + RNTO. Test (gerçek taşıma YOK, hedef yok):

```
$ python3 ~/.hermes/tools/ftp-tool.py mv /sutre-yok-boyle-yol-89-deneme /tmp/x
HATA: yeniden adlandırılamadı: /sutre-yok-boyle-yol-89-deneme -> /tmp/x (550 Sorry, but that file doesn't exist)
exit code: 1
```

Kablolama doğru: hata yolu `HATA:` + exit 1 verdi (uzak dizin açılmadı, hiçbir dosya/dizin taşınmadı).

## (B) Commit'ler

- `865a8c0` — `chore(dispatch): run-pack-88 log kalinti kapatildi` (dispatch/out/logs/run-pack-88.log, 29 satır)
- `HEAD` = `origin/main` (eşitlik `git rev-parse` ile teyit edildi, push başarılı: 96e0701..865a8c0)

## (C) AGENTS.md §9

Satır 261'teki dispatch arşivi kaydı güncellendi (aynı konu iki satır olarak çiftlenmesin diye §0.5 tek-kaynak kuralı gereği değiştirme yapıldı):

```
- **Dispatch arşivi (23-09-2026):** pack-76→88 paketleri + tüm koşu logları + kanıt görselleri repoda kalıcıdır (tam çıktı; `tail` yok).
```

## (D) `git status --porcelain`

Aşağıda ham çıktı — beklenen kalıntı deseni: bu paketin KENDİ logu/paket dosyası koşu sürerken untracked kalır (pack-88 logu da aynı şekilde bir sonraki pakette kapatılmıştı).

```
?? dispatch/out/logs/run-pack-89.log
?? dispatch/pack-89-kapanis-kisa.md
```

## (E) YAPILAMADI

Yok. Notlar: (1) FTP oturumu yalnız `mv` hata yolunu denemek için açıldı; başka komut çalıştırılmadı. (2) `--help` ayrı komut olarak yok; kullanım metni argümansız çağrıda (`__doc__`) basılır ve editoryal kontrol edildi. (3) Kullanıcı adımı yok (LiteSpeed purge gerekmiyor — site dokunulmadı).