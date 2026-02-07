<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

requireAuth();

$pageTitle = 'Recipes';
$recipes = getEnabledRecipes();
$successMessage = consumeFlash('success');
$errorMessage = consumeFlash('error');

require viewPath('layout/header.php');
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">All recipes</h1>
    <a class="btn btn-primary" href="/recipes/create.php">Add a recipe</a>
</div>

<?php if ($successMessage !== null) : ?>
    <div class="alert alert-success" role="alert"><?php echo h($successMessage); ?></div>
<?php endif; ?>

<?php if ($errorMessage !== null) : ?>
    <div class="alert alert-danger" role="alert"><?php echo h($errorMessage); ?></div>
<?php endif; ?>

<?php if ($recipes === []) : ?>
    <div class="alert alert-info" role="alert">No recipe available yet.</div>
<?php else : ?>
    <div class="row g-3">
        <?php foreach ($recipes as $recipe) : ?>
            <div class="col-12 col-md-6">
                <article class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5"><?php echo h($recipe['title']); ?></h2>
                        <p class="text-muted mb-2">By <?php echo h($recipe['author']); ?></p>
                        <p><?php echo h(truncateText((string) $recipe['recipe'])); ?></p>
                        <p class="small mb-0">
                            Rating: <?php echo h((string) $recipe['avg_rating']); ?>/5
                            | Comments: <?php echo h((string) $recipe['comment_count']); ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a class="btn btn-outline-primary btn-sm" href="/recipes/show.php?id=<?php echo (int) $recipe['recipe_id']; ?>">Read</a>
                        <a class="btn btn-outline-secondary btn-sm" href="/recipes/edit.php?id=<?php echo (int) $recipe['recipe_id']; ?>">Edit</a>
                        <a class="btn btn-outline-danger btn-sm" href="/recipes/delete.php?id=<?php echo (int) $recipe['recipe_id']; ?>">Delete</a>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require viewPath('layout/footer.php'); ?>
