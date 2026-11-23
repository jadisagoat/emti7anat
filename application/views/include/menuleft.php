<script>
function ajaxtest(){
	$('#headerhomen').css({'display':'none'});
	$("html, body").animate({ scrollTop: 0 }, "slow");
 	$('#bodycontainer').html('<img src="<?=base_url();?>images/loader.gif" >');
	$.post("<?=base_url();?>home/testsimple",{},function(data){
		 $('#bodycontainer').html(data);
		}); 		
}

function ajaxlangauge(id_language){
   $('#result6').html('');
	$.post("<?=base_url();?>home/classs",{},function(data){
		 $('#id_class').html(data);
		});		
$('#id_material').html('<select name="id_material" id="id_material" onchange="ajaxsubject_class_data(this.value)" ><option value="all">Subject</option></select>');	
 }


function ajaxunit(id_classes,id_material){
	$('#headerhomen').css({'display':'none'});
	$("html, body").animate({ scrollTop: 0 }, "slow");
 	$('#bodycontainer').html('<img src="<?=base_url();?>images/loader.gif" >');
	$.post("<?=base_url();?>home/unit",{id_classes:id_classes,id_material:id_material},function(data){
		 $('#bodycontainer').html(data);
		}); 		
}	
function ajaxsubject_class(id_classes){
	$('#result6').html('');
	 
	$('#headerhomen').css({'display':'none'});
	$("html, body").animate({ scrollTop: 0 }, "slow");
 	$('#id_material').html('<img src="<?=base_url();?>images/loader.gif" >');
	$.post("<?=base_url();?>home/ajaxsubject_class",{id_classes:id_classes},function(data){
		 $('#id_material').html(data);
		}); 		
}
 
function ajaxsubject_class_data(id_material){
var id_class =$('#id_class').val();
var id_language =$('#id_language').val();
$('#result6').html('<img src="<?=base_url();?>images/loader.gif" >');
	$.post("<?=base_url();?>home/datasubjectclass",{id_language:id_language,id_class:id_class,id_material:id_material},function(data){
	$('#result6').html(data);
		}); 		
}
 




function ajaxsection(id_unit){
var id_classes =$('#id_classes').val();
var id_material =$('#id_material').val();
  	$('#id_section').html('<img src="<?=base_url();?>images/loader.gif" >');
	$.post("<?=base_url();?>home/section",{id_classes:id_classes,id_material:id_material,id_unit:id_unit},function(data){
	$('#id_section').html(data);
		}); 		
}

function ajaxdata(id_section){
var id_classes =$('#id_classes').val();
var id_material =$('#id_material').val();
var id_unit =$('#id_unit').val();
var id_language =$('#id_language').val();
   	$('#result6').html('<img src="<?=base_url();?>images/loader.gif" >');
	$.post("<?=base_url();?>home/data",{id_language:id_language,id_classes:id_classes,id_material:id_material,id_unit:id_unit,id_section:id_section},function(data){
	$('#result6').html(data);
		}); 		
}









function ajaxsubject_class(id_expectations_class){
    $('#result6').html('');
    $('#headerhomen').css({'display':'none'});
    $("html, body").animate({ scrollTop: 0 }, "slow");
    $('#result6').html('<img src="<?=base_url();?>images/loader.gif" >');
    $.post("<?=base_url();?>home/ajaxsubject_class",{id_expectations_class:id_expectations_class},function(data){
        $('#result6').html(data);
    });
}


function ajaxexpectations(){
    $('#headerhomen').css({'display':'none'});
    $("html, body").animate({ scrollTop: 0 }, "slow");
    $('#bodycontainer').html('<img src="<?=base_url();?>images/loader.gif" >');
    $.post("<?=base_url();?>home/testexpectations",{},function(data){
        $('#bodycontainer').html(data);
    });
}
function ajaxexpectations_class(id_classes){
    $('#result6').html('');
    $("html, body").animate({ scrollTop: 0 }, "slow");
    $('#result6').html('<img src="<?=base_url();?>images/loader.gif" >');
    $.post("<?=base_url();?>home/ajaxexpectations_class",{id_classes:id_classes},function(data){
        $('#result6').html(data);
    });
}


/*
    function findTotal(){
        var arr = document.getElementsByName('expgrade');
        var tot=0;
        for(var i=0;i<arr.length;i++){
            if(parseInt(arr[i].value))
                tot += parseInt(arr[i].value);
        }
        document.getElementById('sumtotal').value = tot;
    }
*/
</script>

<div class="col-md-3 leftsside">
<div id='cssmenu'>
<div class="side-title">Guides &amp; resources</div>
<ul>
 
<?  /* foreach($class as $class1){
$cond=array('id_classes'=>$class1['id_classes']);
$material=$this->fct->getAll_cond ("material","sort_order",$cond);
?>
<li <? if(count($material)){ ?>class='has-sub' <? } ?>>
<a><span><?=$class1['title']?></span></a>
<? if(count($material)){ ?>
<ul>
<? foreach($material as $material1){ ?>
<li><a onclick="ajaxunit('<?=$class1['id_classes']?>','<?=$material1['id_material']?>')" ><span><?=$material1['title']?></span></a></li>
<? } ?>
</ul>
<? } ?>
</li>
<?   }*/

$material=$this->fct->getAll("material","sort_order");
foreach($material as $material1){ ?>
    <li><a onclick="ajaxunit('<?=$material1['id_material']?>','<?=$material1['id_material']?>')" ><span><?=$material1['title']?></span></a></li>
<?php }
?>


<li >
<a onclick="ajaxtest()"><span>Test sample</span></a></li>

    <li>
        <a onclick="ajaxexpectations()"><span>Expectations </span></a></li>

    <li>
        <a onclick="ajaxpracticelist()"><span>Practice tests</span></a></li>
</ul>
</div>
</div>