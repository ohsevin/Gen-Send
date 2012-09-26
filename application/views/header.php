<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <base href="<?php echo site_url();?>" />
    <title><?php echo (isset($meta['title']) && trim($meta['title']) != '') ? $meta['title'] . META_TITLE_SEPARATOR . META_TITLE_APPEND : META_TITLE_DEFAULT; ?></title>
    <meta name="keywords" content="<?php echo (isset($meta['keywords']) && trim($meta['keywords']) != '') ? $meta['keywords'] . META_KEYWORDS_APPEND : META_KEYWORDS_DEFAULT; ?>" />
    <meta name="description" content="<?php echo (isset($meta['description']) && trim($meta['description']) != '') ? $meta['description'] . META_DESCRIPTION_APPEND : META_DESCRIPTION_DEFAULT; ?>" />
    
    <link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
      
    <script src="js/libs/modernizr-2.5.3.min.js"></script>
</head>


<body>
    <header>
        <div class="wrapper">
            <h1><a href="<?php echo site_url();?>">Gen&amp;Send</a></h1>
            <h3>A Password Creation &amp; Push Application</h3>
        </div>
    </header>
    <div id="notices"></div>
    
    <div role="main" id="main">
    <!-- All hail the glorious source code! Well, okay, it's not *that* glorious, but still, nice one for checking. +1 Internets for you. -->
