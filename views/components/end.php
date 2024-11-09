<?php

require("./views/components/header.php");
require("./views/components/navbar.php");
require("./views/components/admin_easytouch_overlay.php");

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $state = $_SESSION['message-state'];

    // Define a class based on the state
    $messageClass = '';
    if ($state === 'success') {
        $messageClass = 'bg-green-500 text-white'; // Green background for success
    } elseif ($state === 'error') {
        $messageClass = 'bg-red-500 text-white'; // Red background for error
    } elseif ($state === 'info') {
        $messageClass = 'bg-blue-500 text-white'; // Blue background for info
    }

    require("./views/components/message.php");
}

require("./views/components/footer.php");


?>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.7/axios.min.js" integrity="sha512-DdX/YwF5e41Ok+AI81HI8f5/5UsoxCVT9GKYZRIzpLxb8Twz4ZwPPX+jQMwMhNQ9b5+zDEefc+dcvQoPWGNZ3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>


<?php
$jsDirectoryPath = "./js";
$files = scandir($jsDirectoryPath);

foreach ($files as $file)
    echo "<script src=\"" . $root_directory . "js/" . $file . "\"></script>";
?>

</html>
