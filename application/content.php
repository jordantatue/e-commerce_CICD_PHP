<?php
// Cette page est principalement incluse depuis /home.php.
// On charge les données uniquement si elles ne sont pas déjà présentes.
if (!isset($types, $recetteImages)) {
    require_once(__DIR__ . '/../variables/variables.php');
}
?>

<main class="page">
    <header class="hero">
        <div class="hero-container">
            <div class="hero-text">
                <h1>Recettes simples</h1>
                <h4>Pas de fioritures, juste des recettes</h4>
            </div>
        </div>
    </header>

    <section class="recipes-container">
        <div class="tags-container">
            <h4>Types de recettes</h4>
            <div class="tags-list">
                <?php foreach ($types as $type) : ?>
                    <p><?php echo htmlspecialchars($type['type']); ?> (<?php echo (int) $type['count']; ?>)</p>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="recipes-list">
            <?php foreach ($recetteImages as $recetteImage) : ?>
                <a href="/application/content_unique.php?id=<?php echo (int) $recetteImage['id_recette']; ?>" class="recipe">
                    <img
                        src="/<?php echo $recetteImage['lien_image']; ?>/<?php echo htmlspecialchars($recetteImage['nom_image']); ?>.jpeg"
                        class="img recipe-img"
                        alt="<?php echo htmlspecialchars($recetteImage['nom_recette']); ?>"
                    />
                    <h5><?php echo htmlspecialchars($recetteImage['nom_recette']); ?></h5>
                    <p>Prep: <?php echo (int) $recetteImage['prep_recette']; ?> min | Cook: <?php echo (int) $recetteImage['cook_recette']; ?> min</p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
</main>

