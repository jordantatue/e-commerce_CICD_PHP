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

$pageTitle = 'Edit Recipe';
$errorMessage = consumeFlash('error');

require viewPath('layout/header.php');
?>

<h1 class="h3 mb-3">Edit recipe</h1>

<?php if ($errorMessage !== null) : ?>
    <div class="alert alert-danger" role="alert"><?php echo h($errorMessage); ?></div>
<?php endif; ?>

<form action="/recipes/update.php" method="post" class="card shadow-sm">
    <div class="card-body">
        <input type="hidden" name="id" value="<?php echo (int) $recipe['recipe_id']; ?>">

        <div class="mb-3">
            <label class="form-label" for="title">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?php echo h(old('title', (string) $recipe['title'])); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="recipe">Recipe</label>
            <textarea class="form-control" id="recipe" name="recipe" rows="8" required><?php echo h(old('recipe', (string) $recipe['recipe'])); ?></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Update recipe</button>
    </div>
</form>

<?php require viewPath('layout/footer.php'); ?>
