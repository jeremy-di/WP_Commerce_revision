<?php
get_header();
?>

<main id="content" class="site-main accueil" role="main">
    <div id="archive_content">

<?php


if(have_posts()){
    while(have_posts()){
        the_post();
        $titre = get_the_title();
        $description = get_the_excerpt();
        $image = get_the_post_thumbnail();
        $url = get_the_permalink();
        ?>
        <a class="archive_item" href="<?=$url?>">
            <?=$image?>
            <div>
                <h3><?=$titre?></h3>
                <p><?=$description?></p>
            </div>
        </a>
        <?php
    }
}
?>
</main>
    </div>
<?php
get_footer();
?>