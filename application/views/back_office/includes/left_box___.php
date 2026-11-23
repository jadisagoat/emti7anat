<?
$cond=array('position'=>2);
$menu_left=$this->fct->getAll_cond('content_type','sort_order',$cond);
$menu_left_seg1=$this->uri->segment(2);
$menu_left_seg2=$this->uri->segment(3);
?>
<style>
.accordion-heading{ text-transform:capitalize}</style>
<div class="accordion" id="accordion2">
<?
$user_id=$this->session->userdata('user_id');
$i=0;
foreach($menu_left as $val){ $i++;
if($this->session->userdata('level') == 0){
$cond=array('id_user'=>$user_id,'id_content'=>$val["id_content"]);
$read_priv=$this->fct->getonecell('priviledge','read_p',$cond);
} else { $read_priv = 1; }
if($read_priv == 1){
	
?>
<div class="accordion-group">
<?
if("orderonline_us"== str_replace(" ","_",$val["name"])){ ?>
    <div class="accordion-heading">
        <a class="accordion-toggle"   href="<?= base_url(); ?>back_office/orderonline_us">
            Order Online
        </a>
    </div>
<? }else
if("services"== str_replace(" ","_",$val["name"])||"contact_us"== str_replace(" ","_",$val["name"])||"about_us"== str_replace(" ","_",$val["name"])||("general_secondary"== str_replace(" ","_",$val["name"]))){ ?>
<div class="accordion-heading">
<a class="accordion-toggle"   href="<?= base_url(); ?>back_office/<?= str_replace(" ","_",$val["name"]); ?>
 ">
<? 
echo $val["name"];

?>
</a>
</div>
<? }else
 {  ?>
<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?=$i;?>">
<? 

if($val["name"]=="questions from exams")echo "supplementary problems";
else
if($val["name"]=="classes")echo "class";
else
if($val["name"]=="official exams")echo "session problems ";
else echo $val["name"];

?>
</a>
</div>


<? 
if("contributors"== str_replace(" ","_",$val["name"]) 
|| "material"== str_replace(" ","_",$val["name"]) 
|| "classes"== str_replace(" ","_",$val["name"]) )
{ ?>
<div id="collapse_<?=$i;?>" class="accordion-body collapse 
<?  
echo $menu_left_seg1 ." ";
echo $val["name"]." ";
if(("contributors"== $menu_left_seg1) || 
("material"==$menu_left_seg1) ||
($menu_left_seg1 == str_replace(" ","_",$val["name"]))) 
echo "in"; ?>
" >
<div class="accordion-inner">
<ul class="nav nav-list">
<li class="<? if(($menu_left_seg1 == "material" || $menu_left_seg1 == "classes" )&& $menu_left_seg2 !="add") echo "active"; ?>"><a href="<?= base_url(); ?>back_office/material">List Class</a></li>
<li class="<? if(($menu_left_seg1 == "material" || $menu_left_seg1 == "classes" )&& $menu_left_seg2 =="add") echo "active"; ?>"><a href="<?= base_url(); ?>back_office/material/add">New class</a></li>
</ul>
</div>
</div>

<? } else { ?>
<div id="collapse_<?=$i;?>" class="accordion-body collapse <? if($menu_left_seg1 == str_replace(" ","_",$val["name"])) echo "in";?>">
<div class="accordion-inner">
<ul class="nav nav-list">
<li class="<? if($menu_left_seg1 == str_replace(" ","_",$val["name"]) && $menu_left_seg2 !="add") echo "active"; ?>"><a href="<?= base_url(); ?>back_office/<?= str_replace(" ","_",$val["name"]); ?>">List 
<? if($val["name"]=="questions from exams")echo "solution supplement";
else
if($val["name"]=="official exams")echo "session problem ";
else echo $val["name"]; ?></a></li>
<li class="<? if($menu_left_seg1 == str_replace(" ","_",$val["name"]) && $menu_left_seg2 =="add") echo "active"; ?>"><a href="<?= base_url(); ?>back_office/<?= str_replace(" ","_",$val["name"]); ?>/add">New <? if($val["name"]=="questions from exams")echo "solution supplement";
else if($val["name"]=="official exams")echo "session problem ";
else echo $val["name"]; ?></a></li>
</ul>
</div>
</div>
<? }} ?>
</div>

<? } } ?>
</div>