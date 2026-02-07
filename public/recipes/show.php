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

$comments = getCommentsByRecipeId($recipeId);
$pageTitle = $recipe['title'];
$errorMessage = consumeFlash('error');
$successMessage = consumeFlash('success');

require viewPath('layout/header.php');
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0"><?php echo h($recipe['title']); ?></h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="/recipes/edit.php?id=<?php echo (int) $recipe['recipe_id']; ?>">Edit</a>
        <a class="btn btn-outline-danger btn-sm" href="/recipes/delete.php?id=<?php echo (int) $recipe['recipe_id']; ?>">Delete</a>
    </div>
</div>

<p class="text-muted">By <?php echo h($recipe['author']); ?> | Rating <?php echo h((string) $recipe['avg_rating']); ?>/5</p>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <p class="mb-0"><?php echo nl2br(h((string) $recipe['recipe'])); ?></p>
    </div>
</div>

<?php if ($errorMessage !== null) : ?>
    <div class="alert alert-danger" role="alert"><?php echo h($errorMessage); ?></div>
<?php endif; ?>

<?php if ($successMessage !== null) : ?>
    <div class="alert alert-success" role="alert"><?php echo h($successMessage); ?></div>
<?php endif; ?>

<section class="mb-4">
    <h2 class="h4">Comments</h2>

    <?php if ($comments === []) : ?>
        <div class="alert alert-light border" role="alert">No comments yet.</div>
    <?php else : ?>
        <div class="vstack gap-3">
            <?php foreach ($comments as $comment) : ?>
                <article class="card">
                    <div class="card-body">
                        <p class="mb-2"><?php echo nl2br(h((string) $comment['comment'])); ?></p>
                        <p class="small text-muted mb-0">
                            <?php echo h((string) $comment['full_name']); ?>
                            | <?php echo h((string) $comment['created_at']); ?>
                            | <?php echo h((string) $comment['review']); ?>/5
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="card shadow-sm">
    <div class="card-body">
        <h2 class="h5">Add a comment</h2>
        <form action="/comments/store.php" method="post">
            <input type="hidden" name="recipe_id" value="<?php echo (int) $recipe['recipe_id']; ?>">

            <div class="mb-3">
                <label for="review" class="form-label">Review (1 to 5)</label>
                <input class="form-control" type="number" id="review" name="review" min="1" max="5" step="1" required>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label">Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
            </div>

            <button class="btn btn-primary" type="submit">Post comment</button>
        </form>
    </div>
</section>

<?php require viewPath('layout/footer.php'); ?>
