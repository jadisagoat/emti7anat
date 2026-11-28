<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
/* Link preview (WhatsApp, Facebook, X, Telegram...). Image 1200x630, centre square readable for small thumbnails. */
$og_base  = base_url();
$og_title = 'GRAVITY Guides | Physics for Secondary Classes';
$og_desc  = 'Physics guides for Grades 10, 11 and 12: summaries, solved problems, official exams and practice tests.';
$og_image = $og_base . 'images/og-share.jpg?v=1';
?>
<meta name="description" content="<?= $og_desc ?>" />
<meta name="keywords" content="GRAVITY, physics, Hassan Kamar, Grade 10, Grade 11, Grade 12, official exams, practice tests" />
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<link rel="canonical" href="<?= $og_base ?>" />

<meta property="og:type" content="website" />
<meta property="og:site_name" content="GRAVITY Guides" />
<meta property="og:locale" content="en_US" />
<meta property="og:url" content="<?= $og_base ?>" />
<meta property="og:title" content="<?= $og_title ?>" />
<meta property="og:description" content="<?= $og_desc ?>" />
<meta property="og:image" content="<?= $og_image ?>" />
<meta property="og:image:secure_url" content="<?= $og_image ?>" />
<meta property="og:image:type" content="image/jpeg" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:alt" content="GRAVITY physics guides for secondary classes" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?= $og_title ?>" />
<meta name="twitter:description" content="<?= $og_desc ?>" />
<meta name="twitter:image" content="<?= $og_image ?>" />
<meta itemprop="name" content="<?= $og_title ?>" />
<meta itemprop="description" content="<?= $og_desc ?>" />
<meta itemprop="image" content="<?= $og_image ?>" />

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<link rel="shortcut icon" href="<?php echo base_url();?>images/logo.png" type="image/x-icon">
<link rel="icon" type="image/x-icon" href="<?php echo base_url();?>images/logo.png">

<meta name="theme-color" content="#07090f">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="<?=base_url()?>csssite/css.css" media="screen" />
<!--<script type="text/javascript"  src="<?=base_url()?>scripts/myscript.js"></script>-->
<link href="<?=base_url()?>csssite/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?=base_url()?>js/jquery.min.js"></script>
<script src="<?=base_url()?>menu/script.js"></script>
<!-- Custom Theme files -->
<!--theme-style: gravity.css replaces menu/styles.css + csssite/style1.css (old files kept on disk)-->
<link href="<?=base_url()?>csssite/gravity.css?v=5" rel="stylesheet" type="text/css" media="all" />
<!--//theme-style-->
<!-- practice tests (interactive MCQ) -->
<script>window.GRAVITY_BASE = "<?=base_url()?>";</script>
<script src="<?=base_url()?>js/practice.js?v=2"></script>
<script type="application/x-javascript">
addEventListener("load",function(){ setTimeout(hideURLbar, 0);},false);
function hideURLbar(){ window.scrollTo(0,1);}
</script>
