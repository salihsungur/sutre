# PAKET 10 — GIT REMOTE BAĞLAMA + SSH DEPLOY KEY HAZIRLIĞI (@devops)

## Görev
`/opt/data/workspace/proje` deposunu GitHub private repo `git@github.com:salihsungur/sutre.git` bağlantısına hazırla: SSH key üret, remote ekle; PUSH YAPMA (kimlik doğrulama sahibin deploy key eklemesi sonrası ayrı pakette).

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (git remote -v / ls ~/.ssh). Plan metni yazma.

## Repo / beklenen durum
- Repo: `/opt/data/workspace/proje` — remote YOK (boş `git remote -v`), HEAD `main` @ `e83eb04`.
- `git reset --hard` / `git clean` YASAK.

## Kapsam / görev
1. Anahtar var mı kontrol: `ls ~/.ssh/` — `sutre_deploy` yoksa üret:
   `ssh-keygen -t ed25519 -C "hermes-brand sutre deploy" -f ~/.ssh/sutre_deploy -N ""`
   (hermes kullanıcısı home: `/opt/data/home`). Anahtar ismi projeye özel olsun, id_* genel ad kullanma.
2. `~/.ssh/config` dosyasına (yoksa oluştur) ekle:
   ```
   Host github-sutre
       HostName github.com
       User git
       IdentityFile ~/.ssh/sutre_deploy
       IdentitiesOnly yes
   ```
   Dosya izinleri: config 600, key 600, ~/.ssh 700 (şu an git push'tan önce kritik).
3. Remote ekle (config host alias'ı ile): `git remote add origin git@github-sutre:salihsungur/sutre.git`
4. **PUSH YAPMA.** Kimlik doğrulama henüz yok — deploy key sahibin GitHub'a eklemesi gerekiyor; başarısız push denemesi gereksiz.
5. PUBLIC anahtarın tam içeriğini (`cat ~/.ssh/sutre_deploy.pub`) rapora ve ayrıca `dispatch/out/evidence-10-ssh-pub.txt`'ye yaz. PRIVATE anahtar içeriğini ASLA rapora/log'a yazma; yalnız varlığı + izinleri + fingerprint (`ssh-keygen -lf`) raporlanır.
6. `ssh -T git@github-sutre` denemesi YAPMA (key henüz GitHub tarafında kayıtlı değil;	bufaışkanlık hata bekeceği — denemeyi atla, not düş).
7. Kanıt: `git remote -v`, `ls -la ~/.ssh/`, fingerprint çıktısı → `dispatch/out/evidence-10-ssh.txt`, `git status -sb` (temiz).

## Bağlayıcı sınırlar (out-of-scope)
- Push/push denemesi YOK. GitHub tarafında herhangi işlem YOK (key ekleme sahibin).
- Mevcut commit'lere/anaset dokunma YOK.
- Secret (private key) hiçbir rapora/log/fixture'a YAZILMAZ — §15. Sadece fingerprint + komut çıktıları.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-10-ssh-remote.md`
Anayasa §16 formatı; rapor içinde SADECE public key YER ALABİLİR (kullanıcı kopyalayıp GitHub Deploy Keys'e yapıştıracak) — private key iFreein alarak, public'i kod bloğu olarak ver. Rapor 150-300 kelime + önerilen adımlar listesi (GitHub'da Deploy key ekleme: repo → Settings → Deploy keys → Add; "Allow write access" işaretli; sonra push paketi gelecek).
