<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

if (requestMethod() !== 'POST') {
    redirect('/contact/index.php');
}

$input = validateContactInput($_POST);
rememberOld($_POST, ['email', 'message']);

if ($input['errors'] !== []) {
    flash('error', implode(' ', $input['errors']));
    redirect('/contact/index.php');
}

$uploadResult = saveUploadedScreenshot(isset($_FILES['screenshot']) ? (array) $_FILES['screenshot'] : null);
if ($uploadResult['error'] !== null) {
    flash('error', (string) $uploadResult['error']);
    redirect('/contact/index.php');
}

$pageTitle = 'Contact confirmation';
require viewPath('layout/header.php');
?>

<h1 class="h3 mb-3">Message received</h1>
<div class="card shadow-sm">
    <div class="card-body">
        <p><strong>Email:</strong> <?php echo h($input['email']); ?></p>
        <p><strong>Message:</strong><br><?php echo nl2br(h($input['message'])); ?></p>
        <?php if ($uploadResult['uploaded']) : ?>
            <div class="alert alert-success mb-0" role="alert">
                Screenshot uploaded to storage as <?php echo h((string) $uploadResult['filename']); ?>.
            </div>
        <?php else : ?>
            <div class="alert alert-secondary mb-0" role="alert">
                No screenshot uploaded.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require viewPath('layout/footer.php'); ?>
