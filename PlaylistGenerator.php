<!DOCTYPE html>
<html>
<head>
    <title>Album Track Lister</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $folderPath = $_POST["folderPath"];
        
        // Define the safe root directory
        $safeRootDirectory = "/var/www/html"; // Change this to the appropriate directory on Windows
        
        // Check if the requested folder is within the safe root directory
        if (!isInsideDirectory($folderPath, $safeRootDirectory)) {
            echo "Invalid folder path.";
            return;
        }
        
        // Get all subdirectories in the chosen folder
        $subDirectories = array_filter(glob($folderPath . '/*'), 'is_dir');
        
        // Generate an array where the subfolders are the album names and the tracks are the mp3 files within
        $albums = array_map(function ($subDir) {
            $albumName = basename($subDir);
            $mp3Files = array_map('pathinfo', glob($subDir . '/*.mp3'));
            $trackNames = array_map(function ($file) {
                return $file['filename'];
            }, $mp3Files);
            
            return [
                "album" => $albumName,
                "artwork" => "cover.jpg",
                "tracks" => $trackNames
            ];
        }, $subDirectories);
        
        // Convert the albums array to JSON
        $json = json_encode($albums, JSON_PRETTY_PRINT);
        
        // Output the JSON
        echo "<pre>" . $json . "</pre>";
    }
    
    /**
     * Checks if a directory is inside another directory.
     *
     * @param string $directory The directory to check.
     * @param string $rootDirectory The root directory.
     * @return bool True if the directory is inside the root directory, false otherwise.
     */
    function isInsideDirectory($directory, $rootDirectory) {
        $realDirectory = realpath($directory);
        $realRootDirectory = realpath($rootDirectory);
        
        if ($realDirectory === false || $realRootDirectory === false) {
            return false;
        }
        
        return strpos($realDirectory, $realRootDirectory) === 0;
    }
    ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="folderPath">Please choose a folder:</label>
        <input type="text" name="folderPath" id="folderPath">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
