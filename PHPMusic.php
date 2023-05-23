<!DOCTYPE html>
<html>
<head>
    <title>PHP Music Player</title>
    <link rel="icon" href="favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 9999;
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: limegreen;
            background: linear-gradient(to top, rgba(50, 50, 50, 0), rgba(50, 50, 50, 1));
            padding: 20px;
            border-radius: 4px;
        }
        /* Style for the animated background */
        body{
            background-color: black;
            background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
        }
        .bg-move{
            /* your background image */
            background-image: url('https://camo.githubusercontent.com/596bfe4f2cd01180717a12afe3e44250dd42f2d16b8b44d1f5e45d1fa793bd11/68747470733a2f2f63646e2e646973636f72646170702e636f6d2f6174746163686d656e74732f3530393834393735343538333330323135342f3831323934323031313430303834373339312f656d6f6a695f7261696e2e676966');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .bg-move::before, .bg-move::after{
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            animation: animate 10s ease-in-out infinite alternate, color-phase 20s ease-in-out infinite;
        }
        .bg-move::after{
            filter: blur(10px);
        }
        @keyframes animate{
            0%{
                opacity: 0;
            }
            50%{
                opacity: 1;
            }
            100%{
                opacity: 0;
            }
        }
        @keyframes color-phase {
    0% {
        background: linear-gradient(-45deg, rgba(255, 255, 255, 0.75), rgba(0, 0, 0, 0.1), rgba(255, 255, 255, 0.75));
    }
    20% {
        background: linear-gradient(-45deg, rgba(179, 179, 179, 0.75), rgba(77, 77, 77, 0.1), rgba(179, 179, 179, 0.75));
    }
    40% {
        background: linear-gradient(-45deg, rgba(128, 128, 128, 0.75), rgba(51, 51, 51, 0.1), rgba(128, 128, 128, 0.75));
    }
    60% {
        background: linear-gradient(-45deg, rgba(77, 77, 77, 0.75), rgba(179, 179, 179, 0.1), rgba(77, 77, 77, 0.75));
    }
    80% {
        background: linear-gradient(-45deg, rgba(51, 51, 51, 0.75), rgba(128, 128, 128, 0.1), rgba(51, 51, 51, 0.75));
    }
    100% {
        background: linear-gradient(-45deg, rgba(255, 255, 255, 0.75), rgba(0, 0, 0, 0.1), rgba(255, 255, 255, 0.75));
    }
}
/* Audio Player */
audio {
  width: 300px;
  margin-bottom: 20px;
}

/* Progress Bar */
audio::-webkit-media-controls-progress-bar {
  background-color: limegreen;
  height: 5px;
  border-radius: 2px;
}

audio::-webkit-media-controls-current-time-display,
audio::-webkit-media-controls-time-remaining-display {
  color: limegreen;
}

audio::-webkit-media-controls-play-button,
audio::-webkit-media-controls-pause-button {
  color: limegreen;
}

audio::-webkit-media-controls-volume-slider,
audio::-webkit-media-controls-mute-button {
  color: limegreen;
}

    </style>
</head>
<body class="bg-move">
<header class="bg-gray-900 text-white py-4">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold">Music Player</h1>
    </div>
</header>

<div class="container mx-auto px-4 my-8">
    <?php
    $jsonUrl = "songs.json";
    $jsonData = file_get_contents($jsonUrl);

    $data = json_decode($jsonData, true);

    foreach ($data as $albumData) {
        $albumName = $albumData['album'];
        $tracks = $albumData['tracks'];
        $artwork = $albumData['artwork'];
        ?>

        <div class="mb-8">
            <h2 id="album-names" class="text-2xl text-white font-bold mb-4"><?php echo $albumName; ?></h2>
            
            <img id="artwork-img" class="mb-4" src="<?php echo $albumName; ?>/<?php echo $artwork; ?>" alt="<?php echo $albumName; ?> Artwork" style="max-width: 200px;">

            <div class="grid grid-cols-2 gap-4">
                <?php foreach ($tracks as $index => $track) { ?>
                    <div class="p-4 bg-gray-200 rounded shadow">
                        <p class="song-name cursor-pointer" data-src="<?php echo $albumName; ?>/<?php echo $track; ?>.mp3"><?php echo $index + 1 . '. ' . $track; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>

    <?php
    }
    ?>
</div>


<div class="overlay">
    <div class="overlay-content">
    <img id="audio-player-img" style="max-width: 64px;">
        <p id="audio-player-text" class="text-center"></p>
        <audio controls id="audio-player"></audio>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const songNames = document.querySelectorAll('.song-name');
        const overlay = document.querySelector('.overlay');
        const audioPlayer = document.querySelector('#audio-player');

        songNames.forEach(function (songName) {
            songName.addEventListener('click', function () {
                const audioSrc = this.getAttribute('data-src');
                var paragraph = document.getElementById("audio-player-text");
                var imagecontent = document.getElementById("audio-player-img");
                const image_src = document.getElementById("artwork-img");
                output_image = image_src.getAttribute('src');
                imagecontent.setAttribute('src', output_image);
                var imagelol = document.getElementById("artwork-img");
                paragraph.textContent = songName.textContent;
                audioPlayer.src = audioSrc;
                overlay.style.display = 'block';
            });
        });

        overlay.addEventListener('click', function (event) {
            if (!event.target.closest('.overlay-content')) {
                audioPlayer.pause();
                overlay.style.display = 'none';
            }
        });
    });
</script>

<footer class="bg-gray-900 text-white py-4">
    <div class="container mx-auto px-4">
        <p class="text-center">PHP Music Player</p>
    </div>
</footer>
</body>
</html>
