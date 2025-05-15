<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TEBS - Settings</title>
    <?php include "php/header.php" ?>
</head>

<body class="bg-light" data-bs-theme="light">

    <?php include "php/navbar.php" ?>

    <div class="col-md-10">
        <div class="container py-5">

            <h1 class="text-center">Installation Guide</h1>

            <hr>

            <h3>Pre-Requisites</h3>
            <p>Before use, you will need to install the following software:</p>
            <ul>
                <li><a href="https://obsproject.com/">Open Broadcaster Software</a></li>
                <li><a href="https://store.steampowered.com/app/359550/Tom_Clancys_Rainbow_Six_Siege/">Tom Clancy's Rainbow Six Siege</a></li>
            </ul>
            <p>Additionally, you will need the following hardware:</p>
            <ul>
                <li>A high-specification PC that meets the requirements to run Siege</li>
                <li>Webcamera</li>
                <li>Microphone/Headset for audio input</li>
            </ul>

            <hr>

            <h3>1. Configuring the Game</h3>
            <p>Before we can start to configure OBS, the game should be setup correctly to ensure it looks as best as possible on broadcast.</p>
            <ul>
                <li>To avoid excessive system strain, it is reccomended to cap the ingame FPS at 60 (Higher outputs will not be reflected on the broadcast anyways)</li>
                <li>Game must be running in Windowed or Borderless display mode</li>
                <li>Run in a 1920x1080 resolution with 16:9 aspect ratio on the highest graphic preset your system can comfortably run</li>
                <li>Set the ingame HUD boundaries to maximum value</li>
            </ul>

            <hr>

            <h3>2. Installing the OBS Configuration</h3>
            <p>Before you start this step, please download and extract this <a href='https://broadcast.travisbrown.co.uk/installs/tebs_streampackage.zip'>.zip file</a> somewhere accessible on your PC</p>
            <ul>
                <li>At the top of the OBS window, select the "Scene Collection" dropdown window, and choose the "Add" option</li>
                <li>Inside of the window that opens up, within the "Collection Path" box, open and select the JSON file contained within the ZIP you just extracted.</li>
                <li>Select the "Import" option to import the file</li>
                <li>OBS will prompt that there are missing files. Search the directories and find the associated file inside of the extracted folder.</li>
                <li>If selected correctly, OBS will pop up a message that is has found other files, select that "Yes" to automatically make the file associations.</li>
                <li>Select apply and close the window.</li>
            </ul>

            <hr>

            <h3>2. Configuring the Package to your hardware</h3>
            <p>To modify a source, right click on it in the source list and select "Properties".</p>
            <ul>
                <li>
                    Within the Analyst Desk Scene:
                    <ul>
                        <li>CORE_Microphone - Select your microphone (This will automatically apply to the microphone source across all scenes it is present)</li>
                        <li>ANL_Camera - Select your camera and a 1920x1080 resolution</li>
                    </ul>
                </li>
                <li>
                    Within the Ingame Scene:
                    <ul>
                        <li>IG_GameFeed - Select your Rainbow Six Siege EXE (Game will need to be open for this to work)</li>
                        <li>IG_GameSound - Select your Rainbow Six Siege EXE (Game will need to be open for this to work)</li>
                    </ul>
                </li>
            </ul>
            <p>You will also need to adjust the volume of all audio sources. Reccomended levels:</p>
            <ul>
                <li>INT_Music: -25db</li>
                <li>ANL_Music: -40db</li>
                <li>CORE_Microphone: -20db</li>
                <li>IG_GameSound: -30db</li>
            </ul>

            <hr>

            <h3>Optional Improvements</h3>
            <p>Congratulations, TEBS is ready for use! While you are now broadcast-ready, this section contains some additional improvements which could make your stream even better!</p>
            <ul>
                <li>Installing <a href="https://www.videolan.org/vlc/download-windows.html">VLC</a> will allow you to have playlists of music during Intermission/Analyst scenes. <a href="https://ncs.io/">NCS</a> is a great source for intermission music, and the Invitational POSTMATCH album works well for Analyst desk music.</li>
                <li>If you have a co-caster adding an additional Audio Window Capture source which captures your Discord/TeamSpeak call can be used to bring their audio onto the stream</li>
                <li>A <a href="https://www.elgato.com/ww/en/p/stream-deck-mk2-black">Stream Deck</a> is great for quickly changing sources and improving the flow of a broadcast, I'd highly reccomend one if you are doing this frequently</li>
                <li>Duplicate the intermission scene and replace the background video with content such as trailers and higlights to make the intermissions more engaging for viewers</li>
                <li>Replace the default transition with a custom stinger. <a href="https://www.own3d.tv/en/">Own3d</a> have a selection of free options, or you can use something like this <a href="https://www.lordtocs.net/stingers">Stinger Maker</a> to create custom ones.</li>
                <li>
                    Additional Notes:
                    <ul>
                        <li>Any sources being accessed locally are colour coded red, anything accessed from TEBS is blue.</li>
                        <li>Sources will only refresh and update data upon loading it, you can click on the source to force it to refresh it</li>
                        <li>The current intermission B-roll is freecamera footage captured by myself. Drone footage, replays and content are all good alternatives</li>
                        <li>The included music tracks are <a href="https://ncs.io/T_Alive">"Alive" by Tamlin</a> and <a href="https://www.youtube.com/watch?v=cBBGN6IUO14">"Invitation to Dream" by Kill Miami and Ubisoft</a>.
                    </ul>
                </li>
            </ul>
        </div>

        <script src="js/manager.js"></script>
</body>

</html>