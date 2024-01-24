<html>
    <head>
        <title>Cerebro</title>
        <style>
            body {
                display:flex;
                flex-direction: horizontal;
                flex-wrap:wrap;
                justify-content: center;
            }
            img {
                width: 25vw;
                height: 14.81vw;
                max-width:300px;
                max-height:168.75px;
            }
        </style>
    </head>
    <body>
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

$step = "";
if (isset($_GET['step'])) {
    $frame = htmlspecialchars($_GET['step']);
 }

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_file_uploads', '250');
error_reporting(E_ALL);

// todo list all images by days as base64
$directoryPath = "images";
$files = [];

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

$x = count($files) - 1;
$y = 0;
$step = $step == "" ? 1 : 10;
while ($x > 0 && $y < 360) {
    $file = $files[$x];
    $file = $directoryPath . '/' . $file;
    $file = file_get_contents($file);
    $file = base64_encode($file);
    echo("<img src='data:image/webp;base64,$file'>");
    $x -= step;
    $y++;
}

// unlink the first n files above 2400 count 
$nbFiles = count($files);

if ($nbFiles > 3600) {
    $toDelete = $nbFiles - 3600 > 500 ? 500 : $nbFiles - 3600;
    for ($i = 0; $i < $toDelete; $i++) {
        $file = $directoryPath . '/' . $files[$i];
        unlink($file);
    }
}

//$klTimeZone = new DateTimeZone('Asia/Kuala_Lumpur');
?>
    </body>