<?php
	set_time_limit(240);

    // Include the UberGallery class
    include('photobrowser/resources/UberGallery.php');

    // Initialize the UberGallery object
    $gallery = new UberGallery();

    // Initialize the gallery array
    //$galleryArray = $gallery->readImageDirectory($folder);
	$galleryArray = $gallery->readImageData($files,$directory);

    // Define theme path
    if (!defined('THEMEPATH')) {
        define('THEMEPATH', $gallery->getThemePath());
    }
    // Set path to theme index
    $themeIndex = $gallery->getThemePath(true) . '/index.php';

    // Initialize the theme
    if (file_exists($themeIndex)) {
        include($themeIndex);
    } else {
        die('ERROR: Failed to initialize theme');
    }
?>