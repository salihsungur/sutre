# PAKET 11 — REMOTE PUSH (@devops)

## Görev
Deploy key sahibin GitHub'a ekledi (OWNER_APPROVAL kapısı kapandı). `main`, `staging`, `dev` dallarını origin'e push et ve kanıtla.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (git status -sb / git remote -v). Plan metni yazma.

## Repo / beklenen durum
- Repo: `/opt/data/workspace/proje`
- Remote: `origin → git@github-sutre:salihsungur/sutre.git` ( possessed, fetch+push)
- HEAD: `main @ e83eb04` (+ önceki commit'ler). Tree: working-tree'de untracked dispatch dosyaları olabilir — DOKUNMA, preserve et.
- `git reset --hard` / `git clean` YASAK.

## Kapsam / görev
1. Bağlantı testi: `ssh -T git@github-sutre` — çıktıya "Hi salihsungur!" + "does not provide shell access" bekleniyor; başarısızsa dur, rapora hata yaz (raporu eksik bırakmadan).
2. Push sırası:
   - `git push -u origin main`
   - `git push -u origin staging`
   - `git push -u origin dev`
3. Kanıt (rapora yedekle + `dispatch/out/evidence-11-push.txt`):
   - `git rev-parse HEAD` == `git rev-parse origin/main`
   - `git ls-remote origin main staging dev` üç dalın SHA'sı görünsün (main SHA'sı HEAD'e eşit; staging/dev ilk commit başlangıç olabilir — origin'de zaten varsa force-push YOK, error raporla ve dur).
   - `git log --oneline -5`
4. Push reddedilirse (permission denied / key rejected): rapora tam hata, dur. Tekrar deploy key eklemeyi sahibe söyle, pack yeniden çalıştırılabilir; remote/branch değişikliği yapma.

## Bağlayıcı sınırlar (out-of-scope)
- Force push YOK. Remote değişikliği YOK. Mevcut commit'e müdahale YOK.
- `ssh -T` dışında GitHub'a başka işlem YOK.
- Private key'i hiçbir çıktıya yazma.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-11-push.md`
Anayasa §16 formatı. Rapor 100-200 kelime + kanıt blokları.
