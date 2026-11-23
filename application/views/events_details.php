


<style type="text/css" id="page-css">
.scroll-pane{overflow: auto;width:1020px;height:590px; }
</style>
<link rel="stylesheet" media="all" type="text/css" href="<?=base_url();?>css/style-demo2.css">
<script src="<?=base_url();?>js/jquery.easing-1.3.js"></script>
<script src="<?=base_url();?>js/jquery.jcarousellite.js"></script> 
<style type="text/css" id="page-css">
.scroll-pane{overflow: auto;width:540px;height:530px; }
.scroll-pane2{overflow: auto;width:460px;height:530px; }
 
</style> 





<script type="text/javascript">
$(document).ready(function() {
var heighxxdt=$('.as').height();
$('#alllas').height(heighxxdt);

var heighxxdt2=$('.as2').height();
$('#alllas2').height(heighxxdt2);	
		
$('.scroll-pane').jScrollPane();
$('.scroll-pane2').jScrollPane();
 
 
$(".slower0 .carousel").jCarouselLite({
btnNext: ".slower0 .next",
btnPrev: ".slower0 .prev",
speed: 800,
visible:5,
circular: false
});

});
 

 
</script> 

 
 
<div class="page_about" > 
 
<div class="title_pevents">
<?=$events['title']?>
<br />
<span class="cur" onclick="load_page('events')"><font style="color:#B2191F; font-family:Arial, Helvetica, sans-serif;" >&lt;</font> Back to previous page</span>
</div>

 
<div class="leftact" >
<div class="leftcont">
<div class="scroll-pane">
<div id="alllas" style=" width:480px;height:465px;overflow:hidden;
position:relative; padding:20px 0 20px 20px;"> 
<div class="as descevent">
 <?=$events['description']?> 
<br clear="all" />
</div> 
</div>
</div>
 
</div>
</div>


<div class="rightact" >
 
<div class="scroll-pane2">
<div id="alllas2" style=" width:420px;height:465px;overflow:hidden;
position:relative; padding:20px 0 20px 20px;"> 
<div class="as2" >

<? if(is_file("uploads/events_and_activities/".$events['pdf']))
{   ?>
<a href="<?=base_url();?>uploads/events_and_activities/<?=$events['pdf']?>" target="_blank">
<div class="pdfev"><div class="pdfevin">PDF</div></div>
<div class="rightpdf">
<div class="title">
Agenda
</div>
<div class="title_peventscc ">
<?=$events['title']?>
<br />
<div class="description_eventscc">
<?
$date = new DateTime($events['date']);
$day = $date->format('d');
$month = $date->format('m');
$year = $date->format('Y');
echo date("F j S,Y",mktime('0','0','0',$month,$day,$year));
?> - <?= $events['location'] ;?></div>

</div>
</div></a>
<br clear="all" /><? } 
$speeches=$this->fct->getAll_cond('speeches','id_speeches',array('id_events_and_activities'=>$events['id_events_and_activities']));
if(count($speeches)){ ?>
<div class="titlerightact">
Speeches
</div>
<div class="descacrt">
<?
foreach($speeches as $speeches1){
  if(is_file("uploads/speeches/".$speeches1['pdf']))
{   ?>
<a href="<?=base_url();?>uploads/speeches/<?=$speeches1['pdf']?>"  target="_blank">	
<?=$speeches1['title']?>&nbsp;&nbsp;
</a>	
<? } }
?>
</div>
<? } ?>

<!--<div class="titlerightact">
Media Coverage
</div>
<div class="descacrt">
 aaaa
</div>-->
<div class="titlerightact">
Pictures
</div>
<div class="galleryacrt">

<div class="custom-container slower0 ">
        <a href="#" class="prev">&lsaquo;</a>
        <div style="width:385px; margin-left:5px; margin-right:10px; float:left; overflow:hidden">
        <div class="carousel" >
            <ul>
<? 
$i=0;
foreach($gallery as $gallery1){
if(is_file("uploads/events_and_activities/gallery/".$gallery1['image']))
{  if($i%6==0){
	if($i!=0){?></div></li> <? } ?>
<li class="cur"  style="width:400px"><div>
<? } ?>

 <img src="<?= base_url() ?>uploads/events_and_activities/gallery/122x81/<?=$gallery1['image']?>"   />
  
<? } 
$i++; }
if(count($gallery))echo "</li>"; ?> 
<? 
$i=0;
foreach($gallery as $gallery1){
if(is_file("uploads/events_and_activities/gallery/".$gallery1['image']))
{  if($i%6==0){
	if($i!=0){?></div></li> <? } ?>
<li class="cur"  style="width:400px"><div>
<? } ?>

 <img src="<?= base_url() ?>uploads/events_and_activities/gallery/122x81/<?=$gallery1['image']?>"   />
  
<? } 
$i++; }
if(count($gallery))echo "</li>"; ?>  <? 
$i=0;
foreach($gallery as $gallery1){
if(is_file("uploads/events_and_activities/gallery/".$gallery1['image']))
{  if($i%6==0){
	if($i!=0){?></div></li> <? } ?>
<li class="cur"  style="width:380px"><div>
<? } ?>

 <img src="<?= base_url() ?>uploads/events_and_activities/gallery/122x81/<?=$gallery1['image']?>"   />
  
<? } 
$i++; }
if(count($gallery))echo "</li>"; ?> 
</ul>
</div></div>
<a href="#" class="next">&rsaquo;</a>
<div class="clear"></div>

</div>






<!--<div class="custom-container slower0 ">
        <a href="#" class="prev">&lsaquo;</a>
        <div class="carousel">
            <ul>
<? 
/*$i=0;
foreach($gallery as $gallery1){
if(is_file("uploads/events_and_activities/gallery/".$gallery1['image']))
{  if($i%6==0){
	if($i!=0){?></li> <? } ?>
<li class="cur"  style="width:400px">
<? } ?>

 <img src="<?= base_url() ?>uploads/events_and_activities/gallery/122x81/<?=$gallery1['image']?>"   />
  
<? } 
$i++; }
if(count($gallery))echo "</li>";*/
 ?> 

<br clear="all" />
</ul>
</div>
<a href="#" class="next">&rsaquo;</a>
<div class="clear"></div>
</div>-->

 
 

</div>
<br clear="all" />
</div> 
</div>
</div>
 
 
</div>


</div>  