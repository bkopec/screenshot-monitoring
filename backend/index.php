<?php

if (isset($_GET['key'])) {
    $requestKey = htmlspecialchars($_GET['key']);
    $actualKey = rtrim(file_get_contents("key"));
    if ($requestKey !== $actualKey) {
        http_response_code(401);
        exit("Invalid key.");
    }
} else {
    http_response_code(400);
    exit("Missing key.");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_file_uploads', '250');
error_reporting(E_ALL);

// todo list all images by days as base64
$directoryPath = "images";

if (is_dir($directoryPath)) {
    $filesAndDirs = scandir($directoryPath);

    $files = array_filter($filesAndDirs, function ($item) use ($directoryPath) {
        return is_file($directoryPath . '/' . $item);
    });

    if (!empty($files)) {
        echo "Files in $directoryPath:\n";
        foreach ($files as $file) {
            echo $file . "\n";
        }
    } else {
        echo "No files found in $directoryPath.\n";
    }
} else {
    echo "images directory does not exist.\n";
}

?>