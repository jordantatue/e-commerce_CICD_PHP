<?php
$user = currentUser();
$pageTitle = $pageTitle ?? 'Recipe App';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/recipes/home.php">Simple Recipes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/recipes/home.php">Recipes</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact/index.php">Contact</a></li>
                <?php if ($user !== null) : ?>
                    <li class="nav-item"><a class="nav-link" href="/recipes/create.php">Add Recipe</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if ($user === null) : ?>
                    <li class="nav-item"><a class="nav-link" href="/auth/login.php">Login</a></li>
                <?php else : ?>
                    <li class="nav-item"><span class="navbar-text me-3">Logged in as <?php echo h($user['email']); ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container pb-5">
