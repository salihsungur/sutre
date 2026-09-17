<?php
/**
 * Title: Sutre footer
 * Slug: sutre-child/sutre-footer
 * Categories: footer
 * Description: Sutre footer — marka + hukuki bağlantılar + telif. "%s gururla ..." satırı YASAKTIR (anayasa §16).
 *
 * PAKET 22b. Bu pattern yalnızca inserter/pattern listesi içindir; canlı footer
 * block-templates/index.html içinde DOĞRUDAN aynı blok markup'la gömülüdür.
 * Block Types alanı YEDEK bilinçli olarak YOKTUR: "Block Types: core/template-part/footer"
 * yazılsaydı, Block Hooks bu pattern'i her footer part'ına otomatik eklerdi (P22'nin
 * yaşadığı kök nedenin aynısı).
 *
 * @package Sutre_Child
 */

?>
<!-- wp:group {"tagName":"footer","className":"sutre-footer","layout":{"type":"constrained"}} -->
<footer class="wp-block-group sutre-footer">

	<!-- wp:group {"className":"sutre-footer__brand","layout":{"type":"constrained"}} -->
	<div class="wp-block-group sutre-footer__brand">
		<!-- wp:site-title {"level":0,"className":"sutre-footer__title"} / -->
	</div>
	<!-- /wp:group -->

	<!-- wp:paragraph {"className":"sutre-footer__legal-label"} -->
	<p class="sutre-footer__legal-label">Hukuki:</p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"className":"sutre-footer__legal-links","layout":{"type":"flex","flexWrap":"wrap"}} -->
	<div class="wp-block-group sutre-footer__legal-links">
		<!-- wp:paragraph -->
		<p><a href="/gizlilik-politikasi/">Gizlilik</a></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><a href="/mesafeli-satis/">Mesafeli Satış</a></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><a href="/iade-ve-cayma/">İade</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:paragraph {"className":"sutre-footer__copyright"} -->
	<p class="sutre-footer__copyright">© 2026 Sutre — Tüm hakları saklıdır</p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"className":"sutre-footer__draft-note"} -->
	<p class="sutre-footer__draft-note">Hukuki sayfalar taslak aşamasındadır (LEGAL_REVIEW_REQUIRED); yayından önce avukat onayı gereklidir.</p>
	<!-- /wp:paragraph -->

</footer>
<!-- /wp:group -->
