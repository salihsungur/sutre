# PAKET 7 — FAZ 1/3: KURULUM STRATEJİSİ VE ADR TASLAĞI (@architect)

## Görev
Faz 1 kalansetin mimari kararı: WordPress+WooCommerce kurulum yöntemi ve staging/production ayrımı mimarisi. Salih'in altyapı girdileri HENÜZ YOK (hosting/domain NEEDS_OWNER_INPUT), bu yüzden çıktı KARAR DEĞİL, KARAR TASLAĞIDIR.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile anayasa oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` — §2, §3.3, §8.1, §13 Faz 1
2. `/opt/data/workspace/proje/docs/architecture/hosting-requirements.md` (P3 çıktısı)
3. `/opt/data/workspace/proje/docs/architecture/version-lock.md` (varsa, P6 çıktısı; yoksa bu paket sürüm bağımsız yazılır ve "sürüm P6'dan gelir" notu düş)

## Kapsam / görev
1. `/opt/data/workspace/proje/ADR/ADR-001-kurulum-stratejisi.md` yaz:
   - 3 kurulum seçeneği karşılaştırması: (a) yönetilen WP hosting + FTP/SSH + wp-cli, (b) Docker Compose tabanlı self-host (staging=compose overlay), (c) bedava tier VPS + WordPress docker stack.
   - Anayasa kısıtlarıyla karşılaştırma: §8.1 hosting gereksinimleri, §3.3 ortam ayrımı, §8.3 yedek, staging izolasyonu, sistem cron, e-posta teslimatı.
   - Tavsiye: seçeneklerden hangisinin anayasa kısıtlarıyla en uyumlu olduğu + gerekçe. SON KARAR HAYIR — "Sahip onayı bekleniyor" bandına yaz; production taahhüdü YOK.
   - Hangi veri Salih'ten bekliyor (hosting seçimi, domain, bütçe) — açık kapılar bölümü.
2. `/opt/data/workspace/proje/docs/architecture/environment-plan.md` yaz: local/staging/production ayrımının netleşmiş şeması (domain/subdomain önerileri, staging'e canlı veri girişi YASAK notu, secret akışı secret manager→env ara katmanı, WP-Cron yerine sistem cron, mail test/sandbox ayrımı).
3. `AGENTS.md` formatına ek Osman notu YOK — AGENTS.md dışında başka dosya yazma.

## Bağlayıcı sınırlar (out-of-scope)
- Yalnız 2 dosya yaz (ADR-001 + environment-plan.md); ADR dizini yoksa oluştur.
- Satın alma kararı, domain/hosting seçim taahhüdü YOK.
- Provider/model override YOK; secret yazma YOK.

## Kanıt / kapılar
- Kanıt: iki dosya diskte; ADR karar taslağı + açık kapı sceni BELİRGİN.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-7-adr-env.md`
Anayasa §16 formatı. Rapor 150-350 kelime.
