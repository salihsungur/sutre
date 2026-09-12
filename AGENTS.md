# AGENTS.md — WooCommerce + PayTR Marka Operasyonu (Hermes-Brand)

## 1. Proje Kimliği
- Stack: self-hosted WordPress + WooCommerce + PayTR (birincil ödeme, değiştirilebilir adapter).
- Pazar: Türkiye. TRY, Europe/Istanbul, tr_TR.
- Teknik sözleşme: `WOOCOMMERCE_PAYTR_AI_PROJE_ANAYASASI.md` (bu repo kökünde) — HER ajan işe başlamadan önce TAMAMINI okur.
- Öncelik sırası: Hukuki güvenlik > ödeme ve veri bütünlüğü > güvenlik > sürdürülebilirlik > performans > özellik sayısı.

## 2. Çalışma Modeli
- Hermes = orkestratör; üretim YAPMAZ. Tüm iş kalıcı Bot profillerine `hermes -p <bot> chat -Q --yolo --query-file <mutlak-yol>` ile gider.
- Dispatch paketleri bu workspace'te `dispatch/pack-N-<slug>.md`; bot çıktıları `dispatch/out/` altına.
- Her paket anayasa §16 AI ÇIKTI SÖZLEŞMESİ formatını zorunlu kılar.
- Provider/model override yasak — Salih'in manuel yetkisi.
- Bot profilleri: opencode-go / glm-5.3-flash, reasoning_effort=high (2026-09-12, Salih'in tek seferlik yetkisiyle yapılandırıldı).

## 3. Profil Haritası
- Yazılım: product, architect, researcher, coder, debugger, data, devops, designer, docs, reviewer, tester.
- İşletme: legal (hukuk/uyum — hukuki danışman değil), finance (mali model — mali müşavir değil), marketing (kanal/büyüme — izinsiz pazarlama yasak), ops (sipariş/stok/iade operasyonu).
- Açık kapı etiketleri: NEEDS_OWNER_INPUT / OWNER_APPROVAL_REQUIRED / LEGAL_REVIEW_REQUIRED.

## 4. Faz Planı (anayasa §13)
Faz 0 keşif/girdiler → Faz 1 temel altyapı → Faz 2 mağaza çekirdeği → Faz 3 PayTR → Faz 4 hukuk/mali → Faz 5 sosyal/SEO/analitik → Faz 6 canlıya geçiş → Faz 7 büyüme.
Durum: Faz 0 dokümantasyon kapalı (sahip girdileri hariç) — Faz 1 başladı (Git repo).
Her fazın kabul kapısı anayasa §14'tür; açık güvenlik/ödeme/hukuk kapısı varsa canlıya geçiş DURUR.

## 5. Ortam Durumu (2026-09-12 itibarıyla)
- Faz 0 teslimleri (tamamı anayasa §16 formatlı raporlarla `dispatch/out/` altında):
  - `PROJECT_INPUTS.md` — BOŞ ŞABLON: 23 NEEDS_OWNER_INPUT, 8 OWNER_APPROVAL_REQUIRED, 12 LEGAL_REVIEW_REQUIRED, 2 SECRET_REFERENCE_ONLY.
  - `PRIVACY-DATA-MAP.md` — KVKK veri haritası skeleton (tüm hücreler TBD — LEGAL_REVIEW_REQUIRED).
  - `SECURITY.md` — STRIDE tehdit modeli (8 tehdit) + anayasa §8 kontrol listesi (tümü PLANLANDI).
  - `docs/legal-placeholders/README.md` — §6.1'in 10 zorunlu sayfa envanteri.
  - `docs/architecture/hosting-requirements.md` — hosting/SSL/HPOS/yedek gereksinim listesi (satın alma yok).
  - `docs/integrations/paytr-readiness.md` — PayTR readiness checklist (Faz 3 öncesi kanıt kapıları).
- Bekleyen kapılar: Salih'in PROJECT_INPUTS.md doldurması; PayTR üyelik/sandbox durumu; hosting/domain seçimi.
- Repo/çalışma dizini: `/opt/data/workspace/proje/` — Faz 1'de Git deposu kurulacak.
- PayTR hesabı/sandbox: duruma göre.
- Hosting/domain: henüz yok.
- API key'ler: kurulum wizard'ı ile girilecek.

## 6. Bağımsız Doğrulama
Specialist raporu kabul kanıtı değildir; Hermes kanıtı diskten doğrular (dosya, test çıktısı, log). Testin bilinen defekte RED üretebildiği aranır.
