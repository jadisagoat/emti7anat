<style type="text/css" id="page-css">
.scroll-pane{overflow: auto;width:1020px;height:590px; }
</style>
<link rel="stylesheet" media="all" type="text/css" href="<?=base_url();?>css/style-demo.css">
<script src="<?=base_url();?>js/jquery.easing-1.3.js"></script>
<script src="<?=base_url();?>js/jquery.jcarousellite.js"></script>  
<script>
$(document).ready(function() {
	
	//as
var heighxxdt=$('.as').height();
$('#alllas').height(heighxxdt);	
	
$('.scroll-pane').jScrollPane();
$('.searchmystafff').keyup(function(){
		var val = $(this).val();
		var val = val.toLowerCase();
 		var i=0;
		$('.namenews').each(function(index, element) {
        var html = $(this).html();
		var parent1 = $(this).parent().parent().attr('id');
		$('#'+parent1).removeClass('mystaff-box2');
		var item_html = html.replace(/<[^>]+>/gi,''); 
		var item_html = item_html.toLowerCase();
		
		if(item_html.indexOf(val)!=-1){
			i++;
			if(i%4==0){
			$('#'+parent1).addClass('mystaff-box2');	
				}
		$('#'+parent1).css({'display':'block'});	
		}else{
		$('#'+parent1).css({'display':'none'});	
		}
        });
		$('.scroll-pane').jScrollPane();
		});
});
	
</script>     

    
<div class="page_about" >
<div class="search  ">
<input type="text" onblur="if(this.value == ''){ this.value = 'Search'}"
onfocus="if(this.value == 'Search'){ this.value = ''}" 
value="Search" class="searchmystafff" style=" height:20px; margin-top:4 ">
<div class="ico-search"><img src="<?=base_url();?>images/search1.png"></div>
<br clear="all" />
</div>

<div class="scroll-pane">
<div id="alllas" style=" width:1010px;height:565px;overflow:hidden; position:relative; padding-bottom:15px;"> 
<div class="as">
<?  $ksearch=0;
$olddate='';
$m=0; 
 
foreach($events_and_activities as $events_and_activities1){ 

$date=explode('-',$events_and_activities1['date']);
$newyears=$date['0'];
if($newyears!=$olddate){ $olddate=$newyears;
  if($m!=0){ ?>
      </ul>
        </div> 
        <? if( $nextt>5){ ?>
        <a href="#" class="next">&rsaquo;</a>
        <?  } ?>
        <div class="clear"></div>
    </div>
<? } $nextt=0; ?>
<div class="titleevents">
<?=$newyears?>
</div>
<div class="custom-container slower<?=$m?>">
        <a href="#" class="prev">&lsaquo;</a>
        <div class="carousel">
            <ul>

<?  $m++; } $nextt++; ?>
<? if(is_file("uploads/events_and_activities/".$events_and_activities1['image']))
{  ?><li class="cur" id="mystaff-box_<?=$ksearch?>" onclick="eventsadetails(<?=$events_and_activities1['id_events_and_activities']?>)" >
<div class="itemjcarev">
<div class="imgab_events">
<img src="<?= base_url() ?>uploads/events_and_activities/175x105/<?=$events_and_activities1['image']?>"   />
</div>
<div class="title_events namenews"><?=character_limiter($events_and_activities1['title'], 42);?></div>
<div class="description_events">
<?
$date = new DateTime($events_and_activities1['date']);
$day = $date->format('d');
$month = $date->format('m');
$year = $date->format('Y');
echo date("F j S,Y",mktime('0','0','0',$month,$day,$year));
?> - <?= character_limiter($events_and_activities1['location'],25);?></div>
</div></li>  
<?  $ksearch++; } ?>          
 
<? } 
if($m!=0){ ?>
</ul>
</div>
<a href="#" class="next">&rsaquo;</a>
<div class="clear"></div>
</div>
<? }  ?>
<br clear="all" />
</div>
</div>
 </div>
<script type="text/javascript">
$(function() {
<? for($k=$m;$k>=0;$k--){ ?>
$(".slower<?=$k?> .carousel").jCarouselLite({
btnNext: ".slower<?=$k?> .next",
btnPrev: ".slower<?=$k?> .prev",
speed: 800,
visible:5,
circular: false
});
<? } ?>
});
</script> 
