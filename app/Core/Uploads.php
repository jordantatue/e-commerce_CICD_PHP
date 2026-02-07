<?php

declare(strict_types=1);

function saveUploadedScreenshot(?array $file): array
{
    if ($file === null || !isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [
            'uploaded' => false,
            'filename' => null,
            'error' => null,
        ];
    }

    if ((int) $file['error'] !== UPLOAD_ERR_OK) {
        return [
            'uploaded' => false,
            'filename' => null,
            'error' => 'Upload failed. Please try again.',
        ];
    }

    if ((int) $file['size'] > 5 * 1024 * 1024) {
        return [
            'uploaded' => false,
            'filename' => null,
            'error' => 'File is too large (max 5MB).',
        ];
    }

    $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extension, $allowedExtensions, true)) {
        return [
            'uploaded' => false,
            'filename' => null,
            'error' => 'File type is not allowed.',
        ];
    }

    $targetDirectory = rootPath('storage/uploads');
    if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0775, true) && !is_dir($targetDirectory)) {
        return [
            'uploaded' => false,
            'filename' => null,
            'error' => 'Upload directory is not available.',
        ];
    }

    $filename = uniqid('screenshot_', true) . '.' . $extension;
    $targetPath = $targetDirectory . '/' . $filename;

    if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
        return [
            'uploaded' => false,
            'filename' => null,
            'error' => 'Unable to save uploaded file.',
        ];
    }

    return [
        'uploaded' => true,
        'filename' => $filename,
        'error' => null,
    ];
}
