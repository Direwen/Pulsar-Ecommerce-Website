<!DOCTYPE html>
<html class="scroll-smooth" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ffffff',
                        secondary: '#f5f4f6',
                        accent: '#1878b8',
                        dark: '#24292c',
                        "light-dark": '#898888',
                        "light-gray": '#e5e6ea'
                    },
                }
            }
        }
    </script>
    <title><?php echo $website_title; ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $root_directory ?>assets/pulsar_icon.webp">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <link rel="stylesheet" href="<?= $root_directory ?>css/common.css" />
    <link rel="stylesheet" href="<?= $root_directory ?>css/header.css" />
    <link rel="stylesheet" href="<?= $root_directory ?>css/interactive_components.css" />
</head>

<body>