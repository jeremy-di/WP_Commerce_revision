<?php
/**
 * The site's entry point.
 *
 * Loads the relevant template part,
 * the loop is executed (when needed) by the relevant template part.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();


?>
<?=do_shortcode( ' [ivory-search id="94" title="Default Search Form"] ' )?>;
<main id="content" class="site-main accueil" role="main">
	<?php
		$args = array(
			'taxonomy'=>'categorie_produit',
			'parent'=>'0',
			'orderby'=>'name',
		);
		$categories_array = get_terms($args);
		foreach($categories_array as $categorie):
			include 'template-parts/categorie_produit.php';
		endforeach; ?>
</main>
<?php

get_footer();

