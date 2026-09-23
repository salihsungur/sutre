# SUTRE — ÜRÜN FOTOĞRAFI KURALLARI + PROMPT PAKETİ v3 (İman Nour, 5 renk × 3 görsel)

> **Kaynak:** Sahibin docker session'da hazırladığı 9 detaylı örnek prompt (v1/v2 paketlerinden belirgin şekilde daha detaylı) — 24-09-2026'da alındı ve bu dokümana kalıcı kural olarak işlendi.
> **Makine kaynağı:** `docs/visual-prompts/prompts-iman-nour-v3.json` (15 prompt bu dosyadan üretilir — doküman ile kod arasında drift olmaz).
> **Üretici:** `scripts/higgsfield/generate-product-images.py` · model `xai/grok-imagine-image-2.0`

---

## A. ZORUNLU TEKNİK PARAMETRELER
| Alan | Değer |
|---|---|
| Model | `xai/grok-imagine-image-2.0` (Higgsfield API) |
| Oran | **3:4 (dikey)** — üç görsel tipinde de. Sahibin kuralı; 2:3 DEĞİL |
| Çözünürlük / kalite | `2k` / `quality=medium` (API'de üst sınır) |
| Tuval (post) | `--upscale-to 2480x3312` → **jakarlı setiyle birebir aynı tuval** (Lanczos) |
| Keskinleştirme (post) | `--sharpen`: **makro 160 · ürün 110 · portre 80** (unsharp mask; jakarlı setinin keskinlik seviyesi) |
| Referans | **ZORUNLU** (`image_urls`) — ürünün gerçek fotoğrafı; referanssız üretim yasak |
| Ürün ölçüsü | **190cm × 70cm** her prompt'ta yazılır |
| Çıktı adı | `<slug>-pamuk-<tip>.png` · tipler: `urun-drape`, `makro-doku`, `model-portre` |
| Arşiv | denemeler `_deneme/<Renk>*/`, onaylılar `<Renk>/` |

### A2. KESKİNLİK / DOKU NETLİĞİ KURALI (24-09-2026 dersi — sahip onayı)
Sahip dersi: *"gölge kısımlarda motif tamamen kaybolmuş; motifin silikleştiği makro kabul edilemez. Görseller çok yumuşak/plastik olmasın, motiften detay kaybetmesin."*
Hedef referans: **jakarlı setinin makroları** (2480×3312, tack-sharp, raking ışık, gölgede motif okunur, yüksek mikro-kontrast).
- **Makro promptta zorunlu:** `tack-sharp, high micro-contrast, raking oblique light from the upper left, strong specular highlights on raised satin threads vs matte ground weave, motif clearly readable even inside the shadowed folds, not soft, not plastic, no smoothing, no haze, no bokeh over the fabric surface`.
- **Ürün ve portrede zorunlu:** kumaş motifi kıvrımlarda **keskin ve okunur** kalır (yumuşaklaşma/plastikleşme yok).
- Yasak kelimeler makroda: yalnız `soft/gentle` nitelendirmeleriyle bırakılan kıvrım-gölge anlatımı (gölge "motif yutan" hale gelmemeli).

---

## B0. REFERANS KİLİDİ — EN ÖNEMLİ KURAL (24-09-2026 dersi, kanıtlı)
**Prompt'ta desen/motif METİNLE TARİF EDİLMEZ.** İlk denemede prompt'a "large-scale baroque damask floral jacquard" yazıldı; model referansı bırakıp **kendi desenini uydurdu** (referans ise küçük ölçekli soyut geometrik ton-sur-ton bir dokumaydı) → ürün fotoğrafı ürünle alakasız çıktı. Kök neden: metin tarifi, görsel referanstan üstün tutuluyor.
**Zorunlu kalıp (her promptun başında):**
> `The shawl is the EXACT same physical product as in the provided reference photo. Preserve its woven jacquard pattern with maximum fidelity: the same fine all-over tone-on-tone woven motif, the SAME motif scale as in the reference (do not enlarge, shrink or restyle the motif), the same repeat rhythm and the same {renk} color. Do NOT invent, redraw or substitute a different pattern — never turn it into a large-scale floral, damask or baroque design.`
- Tarif edilebilir: **renk, sahne, dekor, ışık, döküm biçimi, bağlama stili, kadraj**.
- Tarif **YASAK**: desen tipi, motif adı, motif ölçeği (referans neyse o).
- Referans görsel her üretimde `upload_file` ile yüklenir; `image_urls` zorunlu.

## B. ÜRÜN TANIMI KURALI (her promptta aynı iskelet)
`190cm x 70cm rectangular cotton-polyester shawl with a silky satin-finish jacquard weave, hemmed edges without fringe`

- **KUMAŞ (sahip onayı):** malzeme **pamuk-polyester**, dokuma **ipek/saten hissi** verir → ipeksi görünüm korunur, yanlış "silk (saf ipek)" iddiası kullanılmaz.
- **DESEN:** metinle yazılmaz — bkz. §B0 referans kilidi.
- **Kenar:** hemli, **püskülsüz** (without fringe).

---

## C. GÖRSEL 1 — ÜRÜN ÇEKİMİ (drape) — "tam açılmış ürün" YASAK
1. Açılış: `Professional luxury product photography of a 190cm x 70cm rectangular ...`
2. **Kritik döküm kuralı:** ürün TAMAMEN açılmış/yayılmış gösterilmez — **bir bölümü katlı ve toplanmış** kalır, kalanı açık akar; desenin sürekliliği görünür: *"NOT fully spread out — one section remains elegantly folded and gathered while the rest flows open, showing the continuous pattern across the whole piece."*
3. **Yüzey (surface):** ev içi bir mobilya (krem keten günlük yatak, keten çalışma masası, ceviz raf, koyu ahşap konsol vb.) — bir ucu **uzun akıcı kuyruk** halinde aşağı sarkar.
4. **Sahne (scene) ZORUNLU ve renk ailesine göre farklı:** duvar/oda tonu + 2-4 obje (mat seramik sürahi, okaliptüs, tahta makara, pirinç tepsi, cam sürahi, kurutulmuş botanik) + **ışık tanımı** (sabah pencere ışığı / loş akşam ortam ışığı / abajur vurgusu).
5. **Sahne kuralları:** yalnız **İÇ MEKÂN** — paketleme fotoğrafı ve açık alan (dış mekân) fotoğrafı İPTAL (§4). İnsan yok.
6. Kapanış: `... Cozy refined luxury aesthetic, ultra sharp fabric detail. No people, no text, no watermark.`

## D. GÖRSEL 2 — MAKRO DOKU
1. Açılış: `Extreme macro photography of a {FABRIC} silk fabric with a {PATTERN} jacquard weave in tone-on-tone ...`
2. Zorunlu öğeler: görünür ince dokuma iplikleri · sheen'in **yönlü stüdyo ışığını** yakalaması · motiflerin bir tonda açık, bir tonda koyu dokunması → **hafif 3D rölyef etkisi** · kare boyunca **dikey inen yumuşak kıvrımlar** (gölge + derinlik).
3. Palet cümlesi: `{Palette} palette with {undertone} undertones.`
4. Kapanış: `Ultra sharp premium luxury textile catalog quality, {atmosphere}. The entire frame is filled with the fabric, vertical composition. No text, no watermark.` (kadrajın TAMAMI kumaş — arka plan/obje yok)

## E. GÖRSEL 3 — MODEL PORTRE (en çok kurallı olan)
1. Açılış: `High-end studio fashion photography of an elegant woman wearing a 190cm x 70cm rectangular ... as an elegant modest headscarf.`
2. **Başörtüsü yerleşimi (sabit):** *"placed high on the crown like a smooth bonnet, **fully covering all her hair with no hair visible at all**"* — saç kuralı pazarlıksız.
3. **Bağlama stili ADLANDIRILIR ve tanımlanır** (set içinde çeşitlilik için 5 farklı stil):
   | # | Stil | Tanım |
   |---|---|---|
   | 1 | CROSSOVER WRAP | İki uç önden çaprazlanır, terzi işi kruvaze ceket gibi göğüste kavuşur, uçlar zıt yanlardan akar |
   | 2 | SOFT SIDE-CASCADE | Tek uç taçtan çapraz olarak SOL omuza tek uzun kaskad; diğer uç karşı omzun arkasında düz |
   | 3 | SHOULDER-CAPE | Pelerin gibi İKİ omuzdan geniş akar; bir taraf belirgin uzun (asimetri), tepede dikişsiz bağlanır |
   | 4 | HALF-FALL DRAPED | Yalnız BİR uç başın yanından dolanıp SAĞ omuz/göğüste katlı dökülür; diğeri tamamen arkada |
   | 5 | FRONT-SWEEP DOUBLE LAYER | Önden bir sarım + göğüste ikinci çapraz sarım → iki katmanlı ön kıvrım, kalan uzunluk SOL omuzda |
4. **Yasak:** çene altında sıkı düğüm (`No knot under the chin`), görünür bağlantı/sıkıştırma. Her stile özel ek cümle yazılır.
5. **Bakış:** `The woman looks directly into the camera lens with a {MOOD} expression — strong portrait eye contact.` (mood her renkte farklı: serene/gentle/calm/deep/rich).
6. Makyaj tonu + **renkle uyumlu bluz** (yüksek yaka, ince örgü) — şal daima odak.
7. Arka plan: renkle uyumlu **düz stüdyo fonu** + softbox + **rim light rengi** + `premium modest-fashion editorial campaign style`.
8. Kapanış: `Rich {tones} with visible {weave} detail. Waist-up framing with generous negative space on left and right sides. Vertical composition. No text, no watermark.` (**bel üstü kadraj, iki yanda boşluk** — yazı/overlay için)

---

## F. ÇEŞİTLİLİK KURALI (set tekdüze görünmesin)
Her renk kendi **sahnesini, ışığını, yüzeyini, bağlama stilini, fonunu ve bluzunu** alır; ortak olan YALNIZ yapı iskeleti ve kalite kapanışlarıdır.

| Renk | Kumaş/Desen | Sahne teması | Bağlama stili |
|---|---|---|---|
| **Karamel** `#815F49` | barok damask floral, karamel + gül-bej | sıcak sakin yatak odası: krem keten günlük yatak, meşe tabure, mat sürahi + okaliptüs, sabah pencere ışığı | CROSSOVER WRAP |
| **Gül Kurusu** `#A66B4F–B88370` | gül madalyon damask, toz gül + sıcak kum | ışık dolu tekstil atölyesi: keten çalışma masası, ahşap makaralar, kurutulmuş güller, tül arkasından yayılmış gün ışığı | SOFT SIDE-CASCADE |
| **Gri Bej** `#BEBDB7` | ince tonal geometrik, greige + sıcak fildişi | modern okuma köşesi: antrasit beton duvar, bukle koltuk, mermer sehpa, kurutulmuş pampas, abajur ışığı | SHOULDER-CAPE |
| **Lacivert** `#26344B` | klasik kaşmir (boteh), lacivert + petrol | akşam çalışma odası: lacivert duvar, koyu ceviz masa, pirinç okuma lambası, deri ciltli kitaplar, loş ışık | HALF-FALL DRAPED |
| **Vişne Çürüğü** `#AC213C` | floral madalyon, vişne + bordo | loş yemek köşesi: bordo duvar, koyu ahşap konsol, pirinç tepsi + cam sürahi + kristal kadeh, mum ışığı | FRONT-SWEEP DOUBLE LAYER |

---

## G. KUMAŞ KARAKTERİ — ÇÖZÜLDÜ (24-09-2026, sahip onayı)
Ürün malzemesi **pamuk-polyester**; dokuma şekli **ipek/saten hissi** veriyor. Ürün açıklamasında da böyle yazılıyor ("malzeme pamuk, polyester dokuma şekline ipek").
→ Promptlar şu kalıbı kullanır: **`cotton-polyester shawl with a silky satin-finish {desen} jacquard weave ... {sheen}`**
- Parlak/ipeksi dokuma görünümü **korunur** (ürünün görsel gerçeği bu).
- Yanlış **"silk" (saf ipek) iddiası kullanılmaz** — müşteriye hatalı malzeme izlenimi verilmez.
- Mat pamuk kuralı (eski plan notu) **geçersiz**.

---

## H. ÜRETİM AKIŞI
1. Her renk için referans: `_referans/ref-<slug>.jpeg` → `upload_file` ile API'ye yüklenir (public URL gerekmez).
2. `scripts/higgsfield/generate-product-images.py --color <slug> --shot <tip> --ref <referans> --out-dir <hedef>`
3. Sonuçlar `_deneme/<Renk>/` altına iner → **sahibe gösterilir** (§0.6: görsel değişiklik sahibin onayına tabi).
4. Onay sonrası `<Renk>/` klasörüne taşınır, AGENTS.md §7 işlenir, siteye yerleştirme ayrı tur.
5. Kimlik bilgisi (`HF_KEY`) repoya/log'a/rapora yazılmaz.