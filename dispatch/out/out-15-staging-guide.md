# PAKET 15 Raporu — Staging Kurulum Talimat Seti + DB Kurulumu (@devops)

## 1. Görev sonucu
`docs/operations/staging-install-guide.md` oluşturuldu (268 satır, 9 adım + kanıt listesi + yasaklar özeti).
Sahibin cPanel Terminal'de sırayla yapıştıracağı komutlar; her adımda "KANIT BEKLENEN ÇIKTI" bloğu var.

## 2. WP 7.1 / PHP sürüm araştırması (KESİN SONUÇ)
- Kaynak: https://make.wordpress.org/hosting/handbook/compatibility/ — resmî uyum tablosu:
  **WordPress 7.1: PHP 8.2–8.5** (MariaDB 10.11/11.4/11.8/12.3) — yayın 2026-08-19.
- Sonuç: WP 7.1'in PHP 8.5 resmî **test beyanı VAR** (12.09'da yok sanılan bilgi güncel tablodan düştü).
- Frontend/panel önerisi "PHP 8.3+" (wordpress.org/about/requirements/) minimum çerçeve; 8.5 bu öneriyle çelişmez.
- DECISION: kural çerçevesi "beyan varsa hedef 8.5" → **hedef PHP 8.5** (Salih'in 8.5 seçimi tutuldu,
  ADR-002 §3'teki 8.4 hedefi bu satırda 8.5'e YÜKSELTTİK — version-lock §6 tekrar doğrulama kapısı işletildi:
  8.4 seçimi (31.12.2026 aktif destek sonu) yerine 8.5 dalı (31.12.2029 güvenlik sonu) daha uzun ömürlü).
- Fallback kapı kanıtta: panel dropdown'da 8.5 yoksa 8.4 seçilir + kanıta not düşülür (version-lock seçimi korunur).

## 3. Kanıt
- Guide: `/opt/data/workspace/proje/docs/operations/staging-install-guide.md` (yukarıdaki denetim: 13 adımda
  "KANIT BEKLENEN ÇIKTI" bloğu, `utf8mb4`, `sutr_` prefix, `DISABLE_WP_CRON`, `sutre-admin`, checksumlar,
  public_html uyaran hepsi yerinde).
- Checksum (wp.org tar.gz, 2026-09-15 çekildi, benim ortamda indirilerek hesaplandı):
  MD5 `51db0c3bd4f2332b25f9c23e17983047` / SHA256 `05a5f89138f632b7329f1202f2a0553c5f7fe4daf8e4b9ca7ebae9b9466b9e86`
  (35.356.041 byte, last-modified 2026-08-19 — WP 7.1 yayın tarihiyle tutarlı).
- API teyidi: WP core version-check 1.7 → current 7.1 ✓ (sürüm kilidi süper edilmiş durumda).
- DNS: orkestratör paketinde NOERROR 89.252.180.243 (benim yeniden DNS çözümü bu pakette YAPILMADI —
  sahibin tarayıcı testi Adım 6'da; izolasyon: her ek DNS kontrolü panel/gitmeli kanıtla).

## 4. ADR-002 uyum kontrolü
| ADR-002 talebi | Guide karşılığı |
|---|---|
| Terminal elle kurulum (wp-cli yok) | Adım 0–7 tamamen Terminal/tarayıcı; wp-cli çağrısı YOK |
| Softaculous YASAK | Guide Yasaklar özeti + kurulum arşiv çekmeli |
| public_html'e yazma YASAK (§0.6) | Adım 0'da açık uyarı |
| DISABLE_WP_CRON (§4 panel cron) | Adım 4: satır elle ekleniyor + Adım 5: panel cron satırı (15dk, log dosyasına yaz) |
| Ayrı DB (§2) | Adım 2: `spokenla_staging` + `spokenla_stuser`, ALL PRIVILEGES |
| Git dışı geçer (wp-config) | Guide yasaklar bölümü — wp-config Git'i dışı |
| PHP sürüm (8.4 hedefti) | 8.5 hedef (§3'teki araştırma kapısının kapanışı) + 8.4 fallback dropdown'da 8.5 yoksa |

## 5. Değişiklik / sapma notu
- Sürüm planındaki YÜKSELTME (8.4 → 8.5): version-lock.md.anayasa §3.1 gereği kurulum anı yeniden doğrulama
  bu paketin resmi "teyit" adımıdır — bu değişikliği @architect'e loggable not olarak gönderdim; version-lock.md
  dosyasını ben düzenlemedim (doküman sahibi @researcher, versiyon değişikliği ondan).
- Kalan açık kapı: staging DB adı/user panel önek ekleyebilir (Adım 2 notu var); sahibin kanıt dosyasında
  oluşan TAM adı gösterecek, varsa wp-config'e o raporlanmış ad yazılabilir.
- AGENTS.md'ye dost link ekli değil (istek böyle); `docs/operations` dizini BU PAKETLE açıldı:
  `/opt/data/workspace/proje/docs/operations/`.
- Salih unutmasın: parolaları yalnız kendi parola kasende; herhangi bir dosyaya/rapora yazma YASAK.

## 6. Sınırlar (out-of-scope tarafım)
- WordPress çekirdeğine ben dokunmadım (yalnız talimat metni).
- cPanel API çağrısı yok (sahibin token'ı bu pakette kullanılmıyor; terminalde elle kurulum kararı ADR-002 §2'den).
- Production domain (sutre.store) docroot işlemleri BU PAKETTE YOK.
- Secret/DB parola BU rapora ve guide'a yazılmadı.
- Production deploy adımı OWNER_APPROVAL_REQUIRED — bu paket yalnız staging.

— Her komut çıktısı sahibin kanıt dosyasına; @devops (ben) Adım 0–9 kanıtları geldikten sonra
dosya izinleri + cache-kapalı + cron çalışma kanıtı (Paket 16) kontrolüne geçecek.
