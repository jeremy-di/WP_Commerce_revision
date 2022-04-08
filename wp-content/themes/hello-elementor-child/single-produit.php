<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php
$post = get_post();
$prix_vente = get_field( 'prix_vente' );
$code_ean = get_field( 'code_ean' );
$reduction = get_field( 'reduction' );
$prix_reduit = $prix_vente * (100-$reduction) / 100;
$tva_id = get_field( 'taux_de_tva' );
$tva = get_term_meta($tva_id);
$taux_tva = $tva['taux'][0];
$montant_tva = $prix_reduit * $taux_tva / 100;
$image = get_the_post_thumbnail();
$categories_id = get_field( 'categories_du_produit');

$categories_array = [];
foreach($categories_id as $id){
	$categorie = get_term_by('id',$id,'categorie_produit');
	$categorie_name = $categorie->name;
	$url = get_term_link($id,'categorie_produit');
	$url2 = '<a href="'.$url.'">'.$categorie_name.'</a>';
	array_push($categories_array,$url2);
}
$categories_str = implode(' - ',$categories_array);


get_header();
while ( have_posts() ) :
	the_post();
	?>
<main id="content" <?php post_class( 'site-main' ); ?> role="main">
	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<header class="page-header">
			<?=$image?>
			<div>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<p>Catégorie(s) : <?=$categories_str?></p>
			</div>
		</header>
	<?php endif; ?>
	<div class="page-content">
		<?php the_content(); ?>
		<div class="produit-info">
			<p>Prix : <?=number_format($prix_vente,2,',',' ')?> €</p>
			<?php if($reduction>0): ?>
			<p>Bon de réduction : <?=$reduction?>%</p>
			<p>Prix avec la promotion : <?=number_format($prix_reduit,2,',',' ')?> €</p>
			<?php else : ?>
			<p>Pas de promotion en cours</p>
			<?php endif; ?>
			<p>Montant TVA : <?=number_format($montant_tva,2,',',' ')?> € (<?=$taux_tva?>%)</p>
			<p>Code EAN : <?=number_format($code_ean,0,'',' ')?></p>


		</div>
		<div class="post-tags">
			<?php the_tags( '<span class="tag-links">' . __( 'Tagged ', 'hello-elementor' ), null, '</span>' ); ?>
		</div>
		<?php wp_link_pages(); ?>
	</div>

	<?php comments_template(); ?>
</main>

	<?php
endwhile;
get_footer();
