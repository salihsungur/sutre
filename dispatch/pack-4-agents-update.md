# PAKET 4 — AGENTS.md FAZ 0 DURUM GÜNCELLEMESİ (@docs)

## Görev
`/opt/data/workspace/proje/AGENTS.md` dosyasındaki "## 5. Ortam Durumu" ve "## 4. Faz Planı" bölümlerini Faz 0'ın tamamlanan durumuyla güncelle. Bu, işin teslim edilmiş sayılması için zorunlu context-doc adımıdır.

## İlk yanıt kuralı
İlk yanıtın ZORUNLU bir tool çağrısı olsun (read_file ile AGENTS.md'i oku). Plan metni yazma.

## Okuma (sırayla)
1. `/opt/data/workspace/proje/AGENTS.md`
2. `/opt/data/workspace/proje/dispatch/out/out-1-project-inputs.md`, `out-2-privacy-map.md`, `out-3-threat-hosting.md` (özet için — tam yeniden anlatma)

## Kapsam / görev
Yalnız AGENTS.md'de şu değişiklikleri yap (patch/düzenleme; diğer bölümlere dokunma):
1. "## 5. Ortam Durumu" bölümünü güncelle:
   - Faz 0 teslimleri: `PROJECT_INPUTS.md` (BOŞ ŞABLON, 23 NEEDS_OWNER_INPUT + 8 OWNER_APPROVAL_REQUIRED + 12 LEGAL_REVIEW_REQUIRED + 2 SECRET_REFERENCE_ONLY), `PRIVACY-DATA-MAP.md` (skeleton, TBD), `SECURITY.md` (tehdit modeli + §8 kontrol listesi, tümü PLANLANDI), `docs/legal-placeholders/README.md` (10 sayfa envanteri), `docs/architecture/hosting-requirements.md`, `docs/integrations/paytr-readiness.md`.
   - Tarih: 2026-09-12. Hepsi anayasa §16 formatlı raporlarla `/opt/data/workspace/proje/dispatch/out/` altında.
   - Bekleyen kapılar: sahibin PROJECT_INPUTS doldurması; PayTR üyelik/sandbox durumu; hosting/domain seçimi.
2. "## 4. Faz Planı" altına tek satır durum notu ekle: `Durum: Faz 0 dokümantasyon kapalı (sahip girdileri hariç) — Faz 1 başladı (Git repo).`
3. Bot profil config notu ekle (§2 veya §5'e bir satır): `Bot profilleri: opencode-go / glm-5.3-flash, reasoning_effort=high (2026-09-12 Salih'in tek seferlik yetkisiyle yapılandırıldı).`

## Bağlayıcı sınırlar (out-of-scope)
- Yalnız AGENTS.md; başka dosya YOK.
- Yeni iddia/istatistik uydurma; yalnız yukarıda verilen doğrulanmış bilgileri yaz.
- Git repo henüz yok — commit YAPMA (bir sonraki paket repo kuracak).

## Kanıt / kapılar
- Kanıt: `read_file` geri okuma — güncellenmiş bölümler diskte.

## Rapor
`/opt/data/workspace/proje/dispatch/out/out-4-agents-update.md`
Anayasa §16 formatı. Rapor 150-300 kelime.
