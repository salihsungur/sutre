# PAKET 12 — HOSTING KEŞİF + STAGING / SUTRE.STORE Tiles KURULUM (@devops)

## Görev
Host hesabına SSH erişimi keşfet, ortamı belgele, staging subdomain + WP/Nginx bilgisi topla, "sutre.store" domain'ine cPanel DNS' girişine bağlı yönlendyi hazırla. BU PAKET TEXT-KEŞFET; gerçek WP install (P14 /salı) ayrı paket — tarifeye çıkarma.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file anayasa/AGENTS oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — §3.1, §3.3, §8.1, §13 Faz 1
2. `/opt/data/workspace/proje/docs/architecture/hosting-requirements.md`
3. `/opt/data/workspace/proje/docs/architecture/environment-plan.md`
4. `/opt/data/workspace/proje/docs/architecture/version-lock.md`

## Kapsam / görev
1. **SSH bağlantı keşfi:** Sahibin key'i cPanel'de authorize edilmiş (`sutre-hosting` RSA key, fingerprint SHA256:zXTK/FMajUoIwQqce4FyIoxKIv8SVCGoSma9WyYUQbU — `/opt/data/attachments/sutre.pub`). Bağlantı şablonu:
   - `ssh -i <host-key-path> -o StrictHostKeyChecking=accept-new <user>@<host>`
   - AMA: host/user/path SAHİBİN İLETMESİ GEREKİYOR — bu paket BU BİLGİLERİ BEKLEMEK ZORUNDA; not olarak rapora ve NEEDS_OWNER_INPUT'a kaydol.
   - Key'in private hali makinede YOK (sahibin cPanel'de oluşturdu, private şu an host tarafında) — eğer private key bu makinede yoksa SSH bulunamaz; key file'ın diskte olup olmadığını ara ara: `ls -la /opt/data/attachments/ | grep -i key`, `grep -rl "sutre-hosting" ~/.ssh/` — yoksa raporda SAHİBE ÇAĞRIDA BULUN ("private key .pub-pair'ini makinede bırakı veya cPanel'den 'Download key private' — dosyayı güvenli /opt/data/.ssh altına koyup haber ver").
2. cPanel'de vardır varsayı seemed hosting; şablonlara göre (boşru rather) planlandı NE YAPSLAK:
   - hostname + port (genelde 22, bazen özel) + cPanel kullanıcı adı (bana iletsen yeter)
   - `sutre.store` DNS NS'lerinin host'al izek tatıminated et  (public DNS query yoksa yönlending rapora)
   - staging subdomain: `staging.sutre.store` cPanel'de ayrı docroot/container — deployable yapısında hold:  yapılabilir>
   - production doc root: `public_html` (cPanel default)
3. **Staging kurulumunu (P10 local compose) hosting'e taşılması PLANDIR; staging İKİLİ İŞ BU PAKETTE YOK.**

## Bağlayıcı sınırlar (out-of-scope)
- WordPress install / contributingerto YOK.
- Both: canlı/production domain'e doğrudan site kurma — staging subdomain ayrı docroot; production domain domain yönlendği bu pakette tek DNS belgelendir.
- cPanel herhangi.account/panu herhangi bi changeset.nodevu bişey YOK (eğer eriene růkı yok) — pasif keşif + plana sahibi bilgi.
- Secret/host kaynaklı hassas bilgileri raporlara ASLA KOMPLE yazma — host adı/ıyı maskeliNot (ör. hostname'in "ilk karakter.*         * });".partition 4 char maskeli; kullanıcı adı masked-half) — anayasa §15. SADECE sahibin bana diğer sesamanı vermesi gerekiyor; bunu sen raporda sıkılaştır.

## Kanıt / kapılar
- Kanıt: `ssh -V`, key fingerprint, cPanel girisne-Bilgi BEKLENİYOR notu; `stat` dosya izni 600; dns lookup'a gerek yok (buyuk ihtimalle ageve ucuzuz).

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-11b-hosting-discovery.md`
Anayasa §16. Sahibin vereceği eksik bilgi listesi bölümü dolsun; rapor 150-300 kelime.
