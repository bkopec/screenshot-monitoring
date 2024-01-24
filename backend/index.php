<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cerebro</title>
        <script>
            function resizeImage(event) {
                if (event.target.tagName.toLowerCase() === 'img') {
                    event.target.classList.toggle('focused');
                } 
            }
            function displayMenu(event) {
                document.getElementsByClassName('menuLinks')[0].classList.toggle('visible');
            }
        </script>
        <style>
            body {
                margin:0px;
                padding:3px;
            }
            main {
                display:flex;
                flex-direction: horizontal;
                flex-wrap:wrap;
                justify-content: center;
            }
            img {
                width: 30vw;
                height: 16.87vw;
                max-width:300px;
                max-height:168.75px;
            }
            .focused {
                width: 100vw;
                height: 56.25vw;
                max-width: 1980px;
                max-height: 1280px;
            }
            nav {
                font-size:2rem;
                margin-bottom:10px;
            }
            .menuLinks {
                display:flex;
                flex-wrap: wrap;
                flex-direction: horizontal;
                justify-content: center;
                margin:10px;
                display:none;
            }
            .visible {
                display:block;
            }
        </style>
    </head>
    <body onclick="resizeImage(event)">

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

?>

<nav onclick="displayMenu(event)">
        <div class="menuButton">Menu</div>
        <div class="menuLinks">
            <a href="index.php?key=<?php echo(htmlspecialchars($_GET['key']))?>">Last 6 hours</a>
            <a href="index.php?step=2&key=<?php echo(htmlspecialchars($_GET['key']))?>">Last 12 hours</a>
            <a href="index.php?step=5&key=<?php echo(htmlspecialchars($_GET['key']))?>">Last 30 hours</a>
            <a href="index.php?step=10&key=<?php echo(htmlspecialchars($_GET['key']))?>">Last 60 hours</a>
        </div>
</nav>
<main>
<?php

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
} else {
    echo "images directory does not exist.\n";
}

$step = 1;
if (isset($_GET['step'])) {
    $step = htmlspecialchars($_GET['step']);
 }

$x = count($files) - 1;
$y = 0;
while ($x >= 0 && $y < 360) {
    $file = $files[$x];
    $file = $directoryPath . '/' . $file;
    $file = file_get_contents($file);
    $file = base64_encode($file);
    echo("<img src='data:image/webp;base64,$file'>");
    $x -= $step;
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
</main>
    </body>