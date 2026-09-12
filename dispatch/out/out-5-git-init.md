# PAKET 5 RAPORU — Faz 1/1: Git Deposu Kurulumu (@devops)

## Sonuç
- Tamamlanan hedef: `/opt/data/workspace/proje/` altında Git deposu kuruldu (`git init -b main`, init öncesi `git status` → "not a git repository" ile doğrulandı). İki commit tamamlandı: `01c0e6b docs: proje anayasası ve Faz 0 girdi/envanter dosyaları` (8 dosya) ve `10f261e chore: dispatch altyapısı ve git kuralları` (10 dosya). Dallanma düzeni: `main` (kalıcı, aktif) + `staging` + `dev` branch'leri oluşturuldu.
- Değiştirilen dosyalar: `.gitignore` (yeni); `.gitignore` anayasa §2.2'ye göre yazıldı — `wp-config.php`, `wp-content/uploads/`, cache dizinleri, yedek/DB döküm dosyaları (`*.sql`, `*.sql.gz`, `*.dump`), secret dosyaları (`.env*`, `!.env.example` hariç tutularak), `node_modules/`, `vendor/`, `dispatch/run-*.log`, `.hermes/dispatch/tmp*` ignore edildi; `dispatch/pack-*.md` ve `dispatch/out/out-*.md` kanıt olarak takip ediliyor. Mevcut doküman içerikleri değiştirilmedi.
- Veritabanı/ayar etkisi: Yok — sadece depo/dosya kurulumu.

## Doğrulama
- Çalıştırılan testler: `git status` (init öncesi/sonrası), `git log --oneline`, `git branch -a`, `git status --ignored --short`.
- PASS sonuçları: Çalışma ağacı temiz (`nothing to commit, working tree clean`); 2 commit mevcut; 3 branch mevcut (`dev`, `main*`, `staging`); 5 `run-*.log` dosyası ignore edildiği doğrulandı (`!!` işaretli). Kanıt dosyası: `/opt/data/workspace/proje/dispatch/out/evidence-5-git-init.txt`.
- FAIL / atlanan testler ve nedeni: Yok.

## Risk ve güvenlik
- Secret veya kişisel veri etkisi: Yok — hiçbir secret yazılmadı; `.gitignore` gelecekteki sızıntıları (wp-config, .env, DB dökümü) §2.2/§15'e uygun engelliyor.
- Ödeme/fiyat/stok/fatura etkisi: Yok.
- Geri dönüş adımı: Depoyu silmek yeterli — `rm -rf /opt/data/workspace/proje/.git` (doküman dosyaları zarar görmez).

## Açık kapılar
- NEEDS_OWNER_INPUT: Yok.
- OWNER_APPROVAL_REQUIRED: Remote ekleme/push (remote seçimi Salih'e sorulacak).
- LEGAL_REVIEW_REQUIRED: Yok.
- Sonraki en küçük güvenli adım: Faz 1'in sıradaki kalemi (WordPress/WooCommerce kurulum planı, hosting gereksinimlerine göre).
