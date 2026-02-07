<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

$pageTitle = 'Contact';
$errorMessage = consumeFlash('error');
$successMessage = consumeFlash('success');

require viewPath('layout/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <h1 class="h3 mb-3">Contact us</h1>

        <?php if ($errorMessage !== null) : ?>
            <div class="alert alert-danger" role="alert"><?php echo h($errorMessage); ?></div>
        <?php endif; ?>

        <?php if ($successMessage !== null) : ?>
            <div class="alert alert-success" role="alert"><?php echo h($successMessage); ?></div>
        <?php endif; ?>

        <form action="/contact/submit.php" method="post" enctype="multipart/form-data" class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" type="email" id="email" name="email" value="<?php echo h(old('email')); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="6" required><?php echo h(old('message')); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="screenshot">Screenshot (optional)</label>
                    <input class="form-control" type="file" id="screenshot" name="screenshot" accept=".jpg,.jpeg,.png,.gif">
                </div>

                <button class="btn btn-primary" type="submit">Send message</button>
            </div>
        </form>
    </div>
</div>

<?php require viewPath('layout/footer.php'); ?>
