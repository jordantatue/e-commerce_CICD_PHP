<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

if (isAuthenticated()) {
    redirect('/recipes/home.php');
}

$errorMessage = consumeFlash('error');
$successMessage = consumeFlash('success');
$pageTitle = 'Login';

require viewPath('layout/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h3 mb-4">Sign in</h1>

                <?php if ($errorMessage !== null) : ?>
                    <div class="alert alert-danger" role="alert"><?php echo h($errorMessage); ?></div>
                <?php endif; ?>

                <?php if ($successMessage !== null) : ?>
                    <div class="alert alert-success" role="alert"><?php echo h($successMessage); ?></div>
                <?php endif; ?>

                <form action="/auth/login_submit.php" method="post" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo h(old('email')); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require viewPath('layout/footer.php'); ?>
