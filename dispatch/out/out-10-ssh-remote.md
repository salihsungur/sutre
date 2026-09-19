# PAKET 10 — Git Remote Bağlama + SSH Deploy Key Hazırlığı

**Bot:** @devops · **Tarih:** 2026-09-12 · **Durum:** TAMAM (push hariç — bkz. Bekleyen Kapı)

## Yürütülen İşlemler
1. `ls ~/.ssh/` → `/opt/data/home/.ssh/` mevcut DEĞİLDİ; `sutre_deploy` anahtarı yoktu.
2. Anahtar üretildi: `ssh-keygen -t ed25519 -C "hermes-brand sutre deploy" -f ~/.ssh/sutre_deploy -N ""` → ED25519, passphrase'siz (deploy key standardı).
3. `~/.ssh/config` oluşturuldu ve `github-sutre` host alias'ı eklendi (HostName github.com, User git, IdentityFile sutre_deploy, IdentitiesOnly yes).
4. İzinler ayarlandı: `~/.ssh` = 700, `config` = 600, `sutre_deploy` = 600, `sutre_deploy.pub` = 600 (kanıt: evidence-10-ssh.txt).
5. Remote eklendi: `git remote add origin git@github-sutre:salihsungur/sutre.git` (config alias'ı üzerinden).
6. PUSH YAPILMADI ve `ssh -T git@github-sutre` denemesi ATLANDI — key GitHub tarafında henüz deploy key olarak kayıtlı değil; başarısız deneme gereksizdi (paket talimatı md.4 ve md.6 uyarınca).

## Kanıt
- `git remote -v`: origin fetch/push → `git@github-sutre:salihsungur/sutre.git` ✓
- Fingerprint: `SHA256:Q6ypqsEs2K3r2iVYqJlwgoBdU0LVZwv/N3K/bMG5Q/8` (ED25519, 256-bit) ✓
- İzinler: 700/600/600 ✓ — `ls -la ~/.ssh/` çıktısı evidence-10-ssh.txt içinde.
- HEAD: `main @ e83eb04` değişmedi; commit'lere dokunulmadı; `git reset --hard`/`git clean` kullanılmadı.
- Private key içeriği HİÇBİR rapora/log'a yazılmadı (§15); yalnız varlık + izin + fingerprint raporlandı.

## Sahibin Deploy Key'i Olarak Eklenecek PUBLIC Key
`evidence-10-ssh-pub.txt` dosyasından da kopyalanabilir:

```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIN43egjUw/pJkV4CbxnDzG18FY042nCUKesybgxE1762 hermes-brand sutre deploy
```

## Önerilen Adımlar (Sahip)
1. GitHub → `salihsungur/sutre` repo'sunu aç (private repo henüz yoksa oluşturup hiçbir dosya init'leme — mevcut history push edilecek).
2. Settings → Deploy keys → Add deploy key.
3. Title: `hermes-brand sutre deploy`; Key: yukarıdaki public key'i yapıştır.
4. **"Allow write access" kutusunu İŞARETLE** (push yetkisi gerekli).
5. Add key. (Deploy key repo-bazlıdır; kullanıcı hesabına eklenmez.)
6. Ardından push paketi: ilk push `git push -u origin main` şeklinde alias üzerinden yapılacak.

## Bekleyen Kapı
- OWNER_APPROVAL_REQUIRED: Deploy key'in GitHub'a eklenmesi + private repo'nun varlığı sahip onayına/eylemine bağlı. Push ayrı paket.
