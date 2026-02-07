<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';

logoutUser();
flash('success', 'You are now logged out.');
redirect('/auth/login.php');
