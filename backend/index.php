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
                margin-bottom:10px;
            }
            .menuButton {
                font-size:3rem;
                text-align:center;
                cursor:pointer;
                padding:35px;
            }
            .menuLinks {
                font-size:2rem;
                display:flex;
                flex-direction: column;
                align-items: center;
                padding:15px;
                gap:10px;
                display:none;
            }
            .visible {
                display:flex;
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

<nav>
        <div class="menuButton" onclick="displayMenu(event)">Click here for the Menu</div>
        <div class="menuLinks">
            <a href="index.php?key=<?php echo(htmlspecialchars($_GET['key']))?>">See the last 6 hours</a>
            <a href="index.php?step=2&key=<?php echo(htmlspecialchars($_GET['key']))?>">See the last 12 hours</a>
            <a href="index.php?step=5&key=<?php echo(htmlspecialchars($_GET['key']))?>">See the last 30 hours</a>
            <a href="index.php?step=10&key=<?php echo(htmlspecialchars($_GET['key']))?>">See the last 60 hours</a>
        </div>
</nav>

<main>
<?php

$directoryPath = "images";
$files = [];

if (is_dir($directoryPath)) {
    $filesAndDirs = scandir($directoryPath);

    $files = array_filter($filesAndDirs, function ($item) use ($directoryPath) {
        return is_file($directoryPath . '/' . $item);
    });
} else {
    echo "images/ directory does not exist.\n";
}

$step = 1;
if (isset($_GET['step'])) {
    $step = htmlspecialchars($_GET['step']);
 }

$x = count($files) - 1;
$y = 0;
while ($x >= 0 && $y < 360) {
    $file = $files[$x];
    $timestamp = strstr($file, '.', true);
    $file = $directoryPath . '/' . $file;
    $file = file_get_contents($file);
    $file = base64_encode($file);
    echo("<img title="$timestamp" src='data:image/webp;base64,$file'>");
    $x -= $step;
    $y++;
}

$nbFiles = count($files);

if ($nbFiles > 3600) {
    $toDelete = $nbFiles - 3600 > 500 ? 500 : $nbFiles - 3600;
    for ($i = 0; $i < $toDelete; $i++) {
        $file = $directoryPath . '/' . $files[$i];
        unlink($file);
    }
}
?>

</main>
</body>