<?php
$name = $categorie->name;

?>

	<div class="categorie_produit">
		<h2><?=$name?></h2>
		<?php
		$slug = $categorie->slug;
		// on récupère les produits voulu (catégorie audio)
		$args = array(
			'post_type'=>'produit',
			'posts_per_page'=>4,
			'tax_query'=>array(
				array(
					'taxonomy'=>'categorie_produit',
					'field'=>'slug',
					'terms'=>$slug,
				),
			),
		);

		$loop = new WP_Query($args);
		if($loop->have_posts()){
			while($loop->have_posts()){
				$loop->the_post();
				$titre = get_the_title();
				$description = get_the_excerpt();
				$image = get_the_post_thumbnail();
				$url = get_the_permalink();
				?>
				<a class="categorie_produit_item" href="<?=$url?>">
					<?=$image?>
					<h3><?=$titre?></h3>
					<!--<p><?=$description?></p>-->
				</a>
				<?php
			}
		}


		?>
	</div>
	
