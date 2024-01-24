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

$uploadDirectory = './images/';
if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0775, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['files'])) {
    $files = $_FILES['files'];

    for ($i = 0; $i < count($files['name']); $i++) {
        $filename = $files['name'][$i];
        $tmpFilePath = $files['tmp_name'][$i];
        $fileType = $files['type'][$i];
        if ($fileType === 'image/webp') {
            $destination = $uploadDirectory . $filename;

            if (move_uploaded_file($tmpFilePath, $destination)) {
                echo 'File successfully uploaded: ' . $filename . ' to ' . $destination . '<br>';
            } else {
                echo 'Error uploading file: ' . $filename . '<br>';
            }
        } else {
            echo 'Invalid file type. Only WebP images are allowed.<br>';
        }
    }
}
else {
    echo("test");
    //http_response_code(400);
}
?>
  