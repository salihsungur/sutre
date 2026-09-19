# PAKET 11 — REMOTE PUSH Raporu (@devops)

- **Tarih:** 2026-09-12 18:16 UTC
- **Bot:** @devops (opencode-go / glm-5.3-flash)
- **Durum:** ✅ BAŞARILI — 3 dal origin'e push edildi, kanıt alındı.

## Yapılan İşler

### 1. Bağlantı Testi (`ssh -T git@github-sutre`)

İlk denemede `ssh -T git@github-sutre` → `Could not resolve hostname github-sutre`
(exit=255). Sebep: `~/.ssh/config` dosyasında `github-sutre` Host alias'ı tanımlı
olması gereken ortamda `ssh` bu alias'ı çözemiyor — `ssh` ikili dosyası
`/opt/data/home/.ssh/config`'i okumuyor (muhtemelen HOME/ssh sürüm/strict-mode
farkı). `ssh -F` ile config dosyasını açıkça vermek de host-key doğrulamasında
takıldı; `ssh-keyscan` ile github.com known_hosts'a eklendikten sonra doğrudan
`git@github.com` + deploy key ile test başarılı oldu.

```
ssh -i /opt/data/home/.ssh/sutre_deploy -o IdentitiesOnly=yes \
    -o UserKnownHostsFile=/opt/data/home/.ssh/known_hosts \
    -o BatchMode=yes -T git@github.com
→ Hi salihsungur/sutre! You've successfully authenticated, but GitHub does not provide shell access.
```

### 2. Remote Alias Notu

`origin` remote URL'i `git@github-sutre:salihsungur/sutre.git` olarak tanımlı.
Bu alias ssh/git tarafında çözülemediği için push'ı gerçekleştirmek amacıyla
**geçici olarak** `git remote set-url origin git@github.com:salihsungur/sutre.git`
yapıldı, push'lar tamamlandı, ardından **orijinal değer geri yazıldı**
(`git remote set-url origin git@github-sutre:salihsungur/sutre.git`).
Şu an `git remote -v` çıktısı orijinal alias'ı gösteriyor:

```
origin  git@github-sutre:salihsungur/sutre.git (fetch)
origin  git@github-sutre:salihsungur/sutre.git (push)
```

Bu alias'ın ssh config'iyle kalıcı çözülmesi (örn. `/opt/data/home/.ssh/config`
içindeki Host bloğunun ssh tarafından okunması) ayrı bir altyapı düzeltmesi
gerektirir — bu paketin kapsamı dışında bırakıldı.

### 3. Push Sonuçları

| Dal      | Durum       | SHA                                       |
|----------|-------------|-------------------------------------------|
| main     | new branch  | e83eb04be48db8a5f6b1e841848d80cdc4048686  |
| staging  | new branch  | 10f261ef3c5f0771db560286bfd5d54649308003  |
| dev      | new branch  | 10f261ef3c5f0771db560286bfd5d54649308003  |

Üç push da `exit=0`, `* [new branch]` mesajlarıyla tamamlandı. Force-push
kullanılmadı, remote URL değişikliği geçiciydi ve geri alındı.

## Kanıt

```
--- git rev-parse HEAD ---
e83eb04be48db8a5f6b1e841848d80cdc4048686
--- git rev-parse origin/main ---
e83eb04be48db8a5f6b1e841848d80cdc4048686
```
→ HEAD == origin/main ✅

```
--- git ls-remote origin main staging dev ---
10f261ef3c5f0771db560286bfd5d54649308003  refs/heads/dev
e83eb04be48db8a5f6b1e841848d80cdc4048686  refs/heads/main
10f261ef3c5f0771db560286bfd5d54649308003  refs/heads/staging
```
→ main SHA'sı HEAD'e eşit; staging/dev ilk commit başlangıcı (10f261e)
  beklendiği gibi origin'de görünüyor. ✅

```
--- git branch -vv ---
  dev     10f261e [origin/dev] chore: dispatch altyapısı ve git kuralları
* main    e83eb04 [origin/main] chore: paket 9 rapor ve kanıt dosyaları (compose iskeleti)
  staging 10f261e [origin/staging] chore: dispatch altyapısı ve git kuralları
```

```
--- git log --oneline -5 (main) ---
e83eb04 chore: paket 9 rapor ve kanıt dosyaları (compose iskeleti)
e655732 feat(local): Faz 1 compose iskeleti (local-only, ADR-001 B kapsamında şablon)
3a88d70 docs: ADR'ye sürüm kilidi bağlantısı (tutarlılık)
6d24a9a docs(adr): kurulum stratejisi taslağı ve ortam planı (P7, @architect)
2c0d9bc docs(faz1): sürüm kilit belgesi (P6, @researcher)
```

```
--- git status -sb ---
## main...origin/main
?? dispatch/out/evidence-10-ssh-pub.txt
?? dispatch/out/evidence-10-ssh.txt
?? dispatch/out/evidence-11-push.txt
?? dispatch/out/evidence-8-commits.txt
?? dispatch/out/out-10-ssh-remote.md
?? dispatch/out/out-8-docs-commit.md
?? dispatch/pack-10-ssh-remote.md
?? dispatch/pack-11-push.md
?? dispatch/pack-8-docs-commit.md
```
→ Working-tree'deki untracked dispatch dosyalarına dokunulmadı, korundu. ✅

## Sonuç

`main`, `staging`, `dev` dalları origin'e push edildi. HEAD==origin/main eşitliği
ve `git ls-remote` çıktısıyla doğrulandı. Untracked dispatch dosyaları
korundu. Force-push, remote kalıcı değişiklik, mevcut commit'e müdahale yapılmadı.

## Kalan Açık Konu

`github-sutre` ssh Host alias'ı ortamın `ssh` ikili dosyası tarafından çözülemiyor
— sonraki paketlerde push/pull için ya bu alias'ın ssh config'inde kalıcı olarak
çözülmesi ya da doğrudan `git@github.com` kullanımı tercih edilmeli.
