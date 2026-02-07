<?php
session_start();

require_once(__DIR__ . '/../variables/variables.php');

$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo('La recette n\'existe pas');
    return;
}

$recipeId = (int) $getData['id'];

$textsql = $mysqlClient->prepare(
    "SELECT r.id AS id_recette,r.type AS type_recette, r.nom AS nom_recette, r.temps_preparation AS prep_recette, r.temps_cuisson AS cook_recette, r.instruction_cuisson AS cook_instruction, i.id AS id_ingredient, i.nom AS nom_ingredient, i.origine AS origine_ingredient, i.quantite AS quantite_ingredient
     FROM recette r
     INNER JOIN ingredients i ON r.id = i.id_recette
     WHERE r.id = :idd"
);
$textsql->execute([
    'idd' => $recipeId,
]);
$result = $textsql->fetchAll(PDO::FETCH_ASSOC);

if ($result === []) {
    echo('La recette n\'existe pas');
    return;
}

$recipe = $result[0];

$recipeImage = null;
foreach ($recetteImages as $recetteImage) {
    if ((int) $recetteImage['id_recette'] === $recipeId) {
        $recipeImage = $recetteImage;
        break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- favicon -->
    <link rel="shortcut icon" href="/final/assets/favicon.ico" type="image/x-icon" />
    <!-- normalize -->
    <link rel="stylesheet" href="/final/css/normalize.css" />
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <!-- main css -->
    <link rel="stylesheet" href="/final/css/main.css" />
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <?php require_once(__DIR__ . '/../base/header.php'); ?>
        <main class="page">
            <div class="recipe-page">
                <section class="recipe-hero">
                    <?php if ($recipeImage !== null) : ?>
                        <img
                            src="/<?php echo $recipeImage['lien_image']; ?>/<?php echo htmlspecialchars($recipeImage['nom_image']); ?>.jpeg"
                            class="img recipe-hero-img"
                            alt="<?php echo htmlspecialchars($recipeImage['nom_recette']); ?>"
                        />
                    <?php endif; ?>
                    <article class="recipe-info">
                        <h2><?php echo htmlspecialchars($recipe['nom_recette']); ?></h2>
                        <p>
                            Cette recette est de type : <span><?php echo htmlspecialchars($recipe['type_recette']); ?></span>
                        </p>
                        <div class="recipe-icons">
                            <article>
                                <i class="fas fa-clock"></i>
                                <h5>Prep time</h5>
                                <p><?php echo (int) $recipe['prep_recette']; ?> min</p>
                            </article>
                            <article>
                                <i class="far fa-clock"></i>
                                <h5>cook time</h5>
                                <p><?php echo (int) $recipe['cook_recette']; ?> min</p>
                            </article>
                        </div>
                    </article>
                </section>

                <section class="recipe-content">
                    <article>
                        <h4>instructions</h4>
                        <div class="single-instruction">
                            <p><?php echo htmlspecialchars($recipe['cook_instruction']); ?></p>
                        </div>
                    </article>
                    <article class="second-column">
                        <div>
                            <h4>Ingredients</h4>
                            <?php foreach ($result as $row) : ?>
                                <p class="single-ingredient"><?php echo htmlspecialchars($row['quantite_ingredient'] . ' ' . $row['nom_ingredient']); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </article>
                </section>
            </div>
        </main>
        <?php require_once(__DIR__ . '/../base/footer.php'); ?>
    </div>
</body>
</html>

