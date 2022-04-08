# Implémentation du fichier ``index.php``

on récupère le fichier ``index.php`` dans le thème parent et on copie dans le thème enfant, à la racine. On récupère la balise ``<main>`` dans le dossier ``/template-parts/archive.php`` dans le thème parent.

Je construit en HTML/CSS les 4 blocs qui contiendront chacun une catégorie parmi *video*, *audio* , *jeux video* et *livre*. Chaque bloc contiendra à son tour 4 produits. On met en place un display flex dans chacun de ces blocs pour respecter l'affichage qui nous convient.

On se rend compte que le code se répète de nombreuses fois, pour chaque article et pour chaque catégorie. Nous allons essayer de *factoriser* le code pour éviter cette répétition.

Avant cela, il va aussi faloir récupérer les produits (et leurs informations) qui nous intéressent... Càd : les custom-posts de type ``produit``, qui répondent à une certaine catégorie de produit (audio ou video ou jeux video ou livre).

C'est la classe ``WP_Query`` qui va permettre cela. 

On doit lui donner en argument un tableau ``$args`` avec une architecture bien précise : chaque index de ce tableau précisera un critère de sélection des custom-posts. ``'post_type'`` précisera le type de custom-post, ``'tax_query'`` précisera à l'intérieur d'un tableau, quelle taxonomie il faudra respecter, ``posts_per_page`` permettra de ne sélectionner que 4 articles (par défaut, les 4 plus récents)
```php
$args = array(
    'post_type'=>'produit',
    'posts_per_page'=>4,
    'tax_query'=>array(
        array(
            'taxonomy'=>'categorie_produit',
            'field'=>'slug',
            'terms'=>'audio',
        ),
    ),
);
```

On va **instancier** la classe ``WP_Query`` pour créer une objet (= une *instance*) qui contiendra les produits que nous souhaitons afficher. On va nommer cet objet ``$loop``. On dit que ``$loop`` est une *instance* de ``WP_Query``. On peut maintenant appliquer les *méthodes* définies dans ``WP_Query`` sur ``$loop``, notamment : ``have_posts()``. C'est cette même méthode qui va permettre de boucler sur ``$loop`` et de traiter, dans cette boucle, chaque produit, à tour de rôle.
```php
$loop = new WP_Query($args);

if($loop->have_posts()){
    while($loop->have_posts()){
        $loop->the_post();
    }
}
```

On peut maintenant récupérer les informations sur le produit qui est traité dans la boucle. On va stocker chacune de ces informations dans une variable : ``$titre``, ``$description``, ``$image`` et ``$url`` avec les fonctions respectives ``get_the_title()``, ``get_the_excerpt()``, ``get_the_post_thumbnail()`` et ``get_the_permalink()``. On place ensuite le bloc qui sert à afficher un produit dans une catégorie, dans la boucle, et on remplace les données "en dur" par les variables en PHP que l'on vient de voir.
```php
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
					<p><?=$description?></p>
				</a>
				<?php
			}
		}
```
On peut supprimer tout le code superflu en dehors de la boucle.

On peut maintenant répéter ce code pour chaque catégorie et remplacer la ligne ``'terms'=>'audio',`` par le slug de la catégorie qui nous intéresse, ET C'EST TOUT... Donc encore une fois, on répète le code pour juste une information qui va être modifiée... D'où la nécessité de -factoriser* encore le code.

On va devoir récupérer toutes les catégories "parent" (qui ne sont pas des sous-catégories), puis boucler sur celles-ci et afficher le slug de chacune à l'endroit précisé précédemment, à la place de ``'audio'``.

Pour récupérer les catégories de produit dans un tableau ``$categories_array`` :
```php
$args = array(
    'taxonomy'=>'categorie_produit',
    'parent'=>'0',
    'orderby'=>'name',
);
$categories_array = get_terms($args);
```

Je peux maintenant boucler sur ce tableau et utiliser le bloc défini pour chaque catégorie dans cette boucle : on remplacera juste le slug de la catégorie.
```php

foreach($categories_array as $categorie){
    ?>
    <div class="categorie_produit">
    <?php
    $slug = $categorie->slug;
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
    ...
}
```
On peut supprimer tout le code superflu en dehors de la boucle.

Pour finir, il convient de placer tout ce qui concerne l'affichage dans un template dans ``/template-parts`` et d'appeler avec la fonction ``include()`` ce template.