# PAKET — PLAYWRITER SERVES HOST'TA PORT 19988 (Hermes-Parça Görevi)

## Görev
Salih'in tarayıcısındaki Playwriter eklentisi (`chrome://extensions` kurulu) bağlanamıyor çünkü relay/host servisi local Hermes (Windows/macOS) üzerinde ÇALIŞMIYOR. Sen (Hermes-x) HOST'ta doğrudan çalışıyorsun — bu görevde sen local terminal kullan.');

## İlk(tuple) adım — Playwriter daemon'ı başta relay servis host:
```bash
npx -y playwriter@latest serve --host 127.0.0.1
```
Komut **yanıt dönmeden koşar** — daemon ayağa kalkar. Eğer bunu çalıştırıyorsan — "bash from terminal": ctrl-c yok, aynı zaman başka bir pencere terminal aç.

**Kontrol 1:** daemon bağlanma mı?
```bash
ss -tln | grep 19988
# beklenen: LISTEN 0 128 127.0.0.1:19988 (veya benzer)
```

**2.) Tarayıcı eklentiyle bağlantı kur:**
- Salih'in tarayıcıda Playwriter ikonunu click şarkı (normal bir web tab) — **"Click the Playwriter extension icon"** demesi öneririm.
- İkon YEŞİL oldğunda, extension connected — uniq ikon prompt: dakika click.

**3. Test görevi (Salih_in istediği):**
`playwriter -s 1 -e "page.goto('https://staging.sutre.store/shop/')" → screenshot / console.log page title` olarak şunu, sonra SS:
- shop sayfası açılmaya ve ürünler görüntülenmeli (ürün-1 + ₺100,00)

## Kanıt / kapılar:
- Salih'in tarayıcında ikon yeşile döner — yeşil ise USER'S tarayıcıdan komut çalışја ─
  - `playwriter session new` ID döner — "extension connected" ile bittiğinde relay'da id çıkıyor.
- Report: `playwriter -s 1 -e "await page.screenshot({path: './shop.png'})"` → Yue ekranda görüntü resmi çıkan uygun bar你不 like pearl图片.
- Salih'in tarayıcıda yeşil kanıt — lev'mdiag.

## OUT (out-of-scope):
- Server erişim değiştirmez; cPanel / staging web portal ile DAIMA aynı usul.—
- "**Eğer diğer bir hermes agent bunu yapamıyorsa**" — bunu Salih'e söyleyen besj срок: "host npx playwriter serve salanna hook host kendisi çalışır local host olabilir; connect over host unıvers network."
