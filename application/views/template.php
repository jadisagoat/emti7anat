<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $generalinfo['website_title'] ?></title>
<? $this->load->view('include/_head'); ?>
</head>
<body >
<? $data['menuactive']=$menuactive;$this->load->view('include/_header',$data);  ?>

<?
// Title band for the inner pages (home and official exams show the banner carousel instead)
$page_heads = array('about'=>'About us','orderonline'=>'Order Online','services'=>'Services','news'=>'News','contactus'=>'Contact us','login'=>'Student Login','tests'=>'Practice Tests','profile'=>'My Profile');
if(isset($page_heads[$menuactive])){ ?>
<div class="page-hero">
	<div class="container">
		<div class="crumbs"><a href="<?=base_url();?>">Home</a><span>/</span><?=$page_heads[$menuactive]?></div>
		<h2><?=$page_heads[$menuactive]?></h2>
	</div>
</div>
<? } ?>

<? $this->load->view($content); ?>

<? $this->load->view('include/_footer');  ?>

</body>
</html>
