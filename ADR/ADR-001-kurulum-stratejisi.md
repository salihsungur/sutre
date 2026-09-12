# ADR-001 — Faz 1 Kurulum Stratejisi ve Ortam Mimarisi (TASLAK)

- Durum: TASLAK — SAHİP ONAYI BEKLENİYOR (OWNER_APPROVAL_REQUIRED)
- Tarih: 2026-09-12. Sahip: @architect.
- Bu bir KARAR DEĞİLDİR. Hosting/domain/bütçe girdileri gelmeden (PROJECT_INPUTS.md §Domain ve altyapı tümü NEEDS_OWNER_INPUT) kurulum yöntemi taahhüdü verilemez (anayasa §0.2).
- Sürüm notu: Sürüm kilidi P6'dan geldi: `docs/architecture/version-lock.md` (WP 7.1 / WC 11.1.0 / PHP 8.4, 2026-09-12 doğrulama). Kurulum anında version-lock §6 tekrar doğrulama kapısı geçerli.

## Bağlam
Faz 1 (anayasa §13) gereği: Git + local/staging/production ayrımı, kararlı sürümler, SSL, e-posta teslimatı, sistem cron, cache, yedek, restore testi. Hosting gereksinimleri P3'te `docs/architecture/hosting-requirements.md` olarak kilitlendi. Salih'in hosting/domain/bütçe girdisi yok. Hedef: AI ajanının tekrarlanabilir, geri alınabilir iş yapması (§0.6 akışı) ve sürüm kilidi/yedek/staging izolasyonunun §8.1–8.3 ile uyumu.

## Seçenekler

### A) Yönetilen WP hosting + SSH/wp-cli
Staging = aynı hesapta ayrı docroot/DB (host yeteneklerine bağlı).
- Artı: §8.1 "yönetilen, izole, güncel, staging/otomatik yedek destekli hosting tercih edilmeli" maddesine birebir oturur. Operasyon yükü düşük; SSL/yedek host'ta; wp-cli + Git ile tek doğruluk kaynağı kurulabilir.
- Eksi: Ortam ayrımı (§3.3) ve canlı veri staging'e girme yasağı (§7) host panelinin yeteneğine muhtaç. Ortam Git'ten replike edilemediği için AI işleri reproducible değil; restore testi host aracına bağlı; sağlayıcıya kilitlenme §18 ilkesiyle gerilimli.

### B) Docker Compose self-host (staging = compose overlay)
- Artı: Local = staging = production aynı stack. Ortama özel değerler env/secret ara katmanıyla koddan ayrılır (§3.3 mekanik garanti). Rollback = önceki imaj/tag; restore testi §8.3'te en doğrulanabilir. Sistem cron, mail sandbox, izleme kolay eşlenir. Yayın anında sürüm kilidi (§3.1) imaj tag'iyle sağılanır.
- Eksi: Sunucu bakım yükü (güvenlik sertleştirme, güncelleme, reverse proxy/TLS) sürekli iş; §18'e göre "geçiş engellemeyen" yükmün kim taşıyacağı tek kişilik işletmede kritik soru. PayTR webhook için TLS/proxy bakımı gerekir.

### C) Bedava tier VPS + WordPress docker stack
- Artı: Maliyet ~0; ilk denemeler hızlı.
- Eksi: Bedava tier'da kaynak kısıtı/burst limitleri/IP ve disk kalıcılığı sorunları callback gecikmesi ve staging izolasyonu bozar; otomatik, şifreli, ayrı konumlu yedek (§8.3) ve restore testi çoğu tier'da sağlanamaz. §8.1 "yönetilen/güncel" hedefiyle çelişir; öncelik sırası (ödeme bütünlüğü > maliyet) gereği üretim için uygun değil. Sandbox/geçici local test dışında önerilmez.

## Kısıt uyum matrisi
| Kısıt (kaynak) | A | B | C |
|---|---|---|---|
| HTTPS + otomatik sertifika (§8.1) | Host ile PASS | Proxy (Caddy/Traefik) ile PASS | Tier'e bağlı |
| 3 ortam + değerlerin koddan ayrımı (§3.3) | Host'a bağlı | PASS | Kısmi |
| Canlı veri staging'e yasak (§7) | Süreçle | Süreç + izolasyon kolay | Riskli |
| Sistem cron + günlük uzlaştırma (§3.3, §4.5) | Host cron PASS | Container/systemd cron PASS | Tier kısıtlı |
| Mail SPF/DKIM/DMARC + sandbox (§14, §3.3) | Harici SMTP | Harici SMTP | Harici SMTP |
| Otomatik şifreli ayrı konum yedek + restore testi (§8.3) | Host yedeği + test kanıtı | Volume dump + off-site, test edilebilir | Genelde ZAYIF |
| Sürüm kilidi (§3.1, hosting-req §1) | Host politikasına bağlı | PASS (imaj tag) | PASS (ama kaynak dar) |
| AI için reproducible/rollback (§0.6, §2.2) | Zayıf | PASS | Orta |
| KVKK veri konumu (hosting-req §8) | Değerlendirme yolu aynı | Türkiye VPS kolaylaştırır | Tier lokasyonu belirsiz |

## Tavsiye (karar değil)
Anayasa kısıtlarıyla en uyumlu olan **B: "yönetilen kaynaklar üzerine Docker Compose self-host"** — en yönetilebilir VPS/cloud üzerinde, AI operasyonla desteklenerek. Gerekçe: (1) §0.6 dal→local/staging→test→yedek→onay→prod akışı B'de en reproducible; (2) §3.3 ortam değeri ayrımı ve §4.2 secret manager→env akışı B'de yapısal; (3) §8.3 restore/rollback testi tek komutla kanıtlanabilir.

Koşullu ikinci seçenek **A**: Salih "yönetilen hosting kiralamak istiyorum" derse geçerli; bu durumda staging izolasyonunun (ayrı subdomain + ayrı DB + host yedek restore testi) hosting-req §5 kanıtlarıyla ispatı şart olur.

**C üretim için reddedilir** (özet gerekçe yukarıda); yalnız local/geçici sandbox olarak kalabilir.

## Açık kapılar
- NEEDS_OWNER_INPUT: domain; hosting tipi tercihi (yönetilen vs VPS); aylık hosting bütçesi; sunucu bakımının kim yapacağı/onayı.
- OWNER_APPROVAL_REQUIRED: bu ADR'nin onayı; RPO/RTO hedefleri (PROJECT_INPUTS §Hosting).
- LEGAL_REVIEW_REQUIRED: hosting/CDN/e-posta/yedek sağlayıcılarının KVKK veri aktarım etkisi; gerekirse yurtdışı aktarım mekanizması (hosting-req §8).

## Sonraki en küçük güvenli adım
Salih'ten hosting tipi + bütçe + domain girdisi alındıktan sonra bu taslak OWNER_APPROVAL'da kapatılır; karar sonrası version-lock (P6) ADR'ye bağlanır ve local stack için compose iskeleti Faz 1 işi olarak yazılır.
