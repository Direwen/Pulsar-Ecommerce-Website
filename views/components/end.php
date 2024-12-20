<?php

require("./views/components/header.php");
require("./views/components/overlay.php");

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $state = $_SESSION['message-state'];

    // Define a class based on the state
    $messageClass = '';
    if ($state === 'success') {
        $messageClass = 'bg-green-500 text-primary border border-light-gray'; // Subtle green for success
    } elseif ($state === 'error') {
        $messageClass = 'bg-danger text-light-gray border border-dark'; // Bold red for error
    } elseif ($state === 'info') {
        $messageClass = 'bg-yellow-500 text-secondary border border-light-gray'; // Muted yellow for info
    }
    
    


    require("./views/components/message.php");

    // Clear the session variable for subsequent requests
    unset($_SESSION['message']);
    unset($_SESSION['message-state']);
}

require("./views/components/footer.php");


?>

<span class="hidden" root-directory="<?= $root_directory; ?>" id="root-directory"></span>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.7/axios.min.js" integrity="sha512-DdX/YwF5e41Ok+AI81HI8f5/5UsoxCVT9GKYZRIzpLxb8Twz4ZwPPX+jQMwMhNQ9b5+zDEefc+dcvQoPWGNZ3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<?php
$jsDirectoryPath = "./js";
$files = scandir($jsDirectoryPath);

foreach ($files as $file)
    echo "<script src=\"" . $root_directory . "js/" . $file . "\"></script>";
?>

</html>
