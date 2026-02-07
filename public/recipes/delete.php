<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

requireAuth();

$recipeId = readPositiveInt($_GET, 'id');
if ($recipeId === null) {
    flash('error', 'Recipe id is invalid.');
    redirect('/recipes/home.php');
}

$recipe = getRecipeById($recipeId);
if ($recipe === null) {
    flash('error', 'Recipe not found.');
    redirect('/recipes/home.php');
}

$pageTitle = 'Delete Recipe';

require viewPath('layout/header.php');
?>

<h1 class="h3 mb-3">Delete recipe</h1>
<div class="alert alert-warning" role="alert">
    This action is permanent. Recipe: <strong><?php echo h((string) $recipe['title']); ?></strong>
</div>

<form action="/recipes/destroy.php" method="post" class="card shadow-sm">
    <div class="card-body">
        <input type="hidden" name="id" value="<?php echo (int) $recipe['recipe_id']; ?>">
        <button class="btn btn-danger" type="submit">Delete permanently</button>
        <a class="btn btn-outline-secondary" href="/recipes/show.php?id=<?php echo (int) $recipe['recipe_id']; ?>">Cancel</a>
    </div>
</form>

<?php require viewPath('layout/footer.php'); ?>
