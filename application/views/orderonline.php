<div class="content">
<div class="container">
<div class="book">

<?php  //$this->load->view('include/menuleft');
?>

<script src="<?=base_url(); ?>csssite/select2.js"></script>
<link href="<?=base_url(); ?>csssite/select2.min.css" rel="stylesheet" type="text/css" media="all" />
<script>
    /*
function ajaxlangauge_onlineorder(id_language){
    $('#result1_online').html('');
    $.post("<?=base_url();?>home/books",{id_language:id_language},function(data){
        $('#result1_online').html(data);
         $('.js-example-basic-multiple').select2();
    });
    $('#infobooks').html('');
}
*/

    function ajaxlangauge_onlineorder(id_language){
        $('#id_books').html('');
        $.post("<?=base_url();?>home/books",{id_language:id_language},function(data){
            $('#id_books').html(data);
           // $('.js-example-basic-multiple').select2();
        });
        $('#infobooks').html('');
    }
function infobooks(id_books){
 
var id_books = $(".js-example-basic-multiple").val();
$('#infobooks').html('');
    $.post("<?=base_url();?>home/infobooks",{id_books:id_books},function(data){
        $('#infobooks').html(data);
    });
}


/*
$(function(){
    $('.js-example-basic-multiple').select2({
        placeholder: "Select a state"
    }).on('change', function(e) {
        var theSelection = $(".js-example-basic-multiple").val();
    });
});*/
</script>
<div class="col-md-12 content-top page-form" id="bodycontainer" >
<div class="contact">
	 <!--<div class="contact-md">
			 <h3>Contact</h3>
			 <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great</p>
		 </div>-->
<?
$info["name"] = "";
$info["subject"] = "";
$info["email"] = "";
$info["phone"] = "";
$info["comments"] = "";
$info["address"] = "";

?>
				<div class="col-md-6 contact-top">
					<h3>Order Online</h3>
                    <style>
                        .styled-select2 select{  margin-top:-3px;line-height:2 !important;width:100% !important;
                            height:auto !important;  }
                        .contact-top div{ padding:0 !important; }
                        .styled-select{
                            background:none !important;
                            width:100%;overflow: hidden;
                            background: url(<?php echo base_url();?>images/arr12.png) no-repeat right #ffffff;
                            border: 1px solid #cacfd5;float:left;cursor: pointer;
                            background-position: 95% 55%;margin-top:3px;
                            min-height:34px;
                        }
                        .styled-select select{  margin-top:5px;line-height:2 !important;width:100% !important;
                            height:auto !important; }
                        .styled-select option{
                            padding:5px !important;
                        }

                    </style>
<?php
$language=$this->fct->getAll('language',"title");
?>

                    <div>
                    <span>Language </span>
<div class="styled-select2" >
<select name="id_language" id="id_language" onchange="ajaxlangauge_onlineorder(this.value)" >
    <option value="">Select a language</option>
    <?php
    foreach($language as $language1)
     {
      echo '<option value="'.$language1['id_language'].'">'.$language1['title'].'</option>';
     }
      echo '</select>';
    ?>
</div>
</div>
<div>
  <span>Book (Ctrl+Enter) to choose more than one book  </span>
  <div class="styled-select" >
  <div id="result1_online">
      <select class="js-example-basic-multiple" name="id_books[]" id="id_books"
              onchange="infobooks(this.value)" multiple >
      </select>
<?php
     /* $books=$this->fct->getAll('books',"sort_order");
      echo '<select class="js-example-basic-multiple" name="id_books" multiple="multiple"
            id="id_books" onchange="infobooks(this.value)">
          <option value="">Select a Book</option>';
          foreach($books as $books1){ echo '<option value="'.$books1['id_books'].'">'.$books1['title'].'</option>';}
          echo '</select>';
     */ ?>
  </div>
  </div>
</div>

<div>
<span>Your Name </span>
<input type="text" class="input-xxlarge" id="name" value="<?= set_value("name",$info["name"]); ?>" />
<?= form_error("name","<span class='text-error'>","</span>"); ?>						</div>
						<div>
							<span>Your Email </span>	
<input type="text" class="input-xxlarge" id="email" value="<?= set_value("email",$info["email"]); ?>" />
<?= form_error("email","<span class='text-error'>","</span>"); ?>
  						</div>

<div>
    <span>Phone</span>
    <input type="text" class="input-xxlarge" id="phone" value="<?= set_value("phone",$info["phone"]); ?>" />
    <?= form_error("phone","<span class='text-error'>","</span>"); ?>
</div>

<div>
    <span>Address</span>
    <textarea class="ckeditor" id="address" rows="2" cols="100" name="address" ><?= set_value("address",$info["address"]); ?></textarea>
    <?= form_error("address","<span class='text-error'>","</span>"); ?>
</div>
      
      
						<div>
							<span>Message</span>
							<textarea class="ckeditor" id="comments" rows="3" cols="100" name="comments" ><?= set_value("comments",$info["comments"]); ?></textarea>
<?= form_error("comments","<span class='text-error'>","</span>"); ?>
						</div>
                          
       <input type="button"  value="SEND" onClick="insert()"  class="contactbutt" /> 
<script>
function insert(){
var url =  "<?php echo base_url()?>home/orderonlineinfo";

var id_language=$('#id_language').val();
var id_books = $(".js-example-basic-multiple").val();
    if (id_books == null){
        id_books='';
    }
var name=$('#name').val();
var subject=$('#subject').val();

var phone=$('#phone').val();
var email=$('#email').val();
var address=$('#address').val();
var comments=$('#comments').val();

var emailfff = document.getElementById('email');
var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    $("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
    $("#id_language").css({'border':'1px solid #bebdbd','color':'#132c57'});
    $("#result1_online span.select2").css({'border':'1px solid #bebdbd','color':'#132c57'});
    $("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
    $("#email").css({'border':'1px solid #bebdbd','color':'#132c57'});
    $("#comments").css({'border':'1px solid #bebdbd','color':'#132c57'});
    $("#address").css({'border':'1px solid #bebdbd','color':'#132c57'});
    $("#phone").css({'border':'1px solid #bebdbd','color':'#132c57'});
    
var boolean=0;
    if(id_language==''){
        $("#id_language").css({'border':'1px solid #ff0000','color':'#ff0000'});
        boolean=1;
    }else
    if(id_books==''){
        $("#result1_online span.select2").css({'border':'1px solid #ff0000','color':'#ff0000'});
      
        boolean=1;
    }else
 		   if(name==''){
				$("#name").css({'border':'1px solid #ff0000','color':'#ff0000'});
                  boolean=1;
            }else
		    if(email==''){
  				$("#email").css({'border':'1px solid #ff0000','color':'#ff0000'});
				$("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
                boolean=1;
            }
            else
            if(phone==''){
                $("#phone").css({'border':'1px solid #ff0000','color':'#ff0000'});
                $("#email").css({'border':'1px solid #bebdbd','color':'#132c57'});
                boolean=1;
            }
            else
                if (!filter.test(emailfff.value)) {
			    $("#email").css({'border':'1px solid #ff0000','color':'#ff0000'});
				$("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
                boolean=1;

	}
	else
    if(address ==''){
        $("#address").css({'border':'1px solid #ff0000','color':'#ff0000'});
        boolean=1;
    }
     else
		    if(comments==''){
                document.getElementById('allinfocontact').innerHTML='';
            }
 	 
           if(boolean==0)
            {
$.post(url,{'phone':phone,'name':name,'id_language':id_language,'id_books':id_books,address:address,'email':email,'comments':comments},function(data){
 
    if(data=="Error")
                    {
$('.allinfocontact').html(data);
					}
					else
					 {
                         $("#phone").css({'border':'1px solid #bebdbd','color':'#132c57'});
                         $("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
                         $("#id_language").css({'border':'1px solid #bebdbd','color':'#132c57'});
                         $("#result1_online span.select2").css({'border':'1px solid #bebdbd','color':'#132c57'});
                         $("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
                         $("#email").css({'border':'1px solid #bebdbd','color':'#132c57'});
                         $("#comments").css({'border':'1px solid #bebdbd','color':'#132c57'});
                         $("#address").css({'border':'1px solid #bebdbd','color':'#132c57'});

                         
                         $('#phone').val('');
                         $('#infobooks').html('');
                         $('#name').val('');
                         $('#id_books').val('');
                         $('#result1_online').html('');
                          $('#id_language option').prop('selected', function() {
                             return this.defaultSelected;
                         });
                         
                         $('#email').val('');
                         $('#address').val('');
                         $('#comments').val('');
                         
				 	 $('.allinfocontact').html(data);
 					 }
			 

                }); 
            }
        }

</script>                  
                         
				</div>
				<div class="col-md-6 contact-top">
					<h3><?=$info['title']?></h3>
		          	<p>
                    <div id="infobooks"></div>
                        <?=$info['description']?>
                    </p>
             
				</div>
			<div class="clearfix"> </div>



</div>
 		          	
		         	
 <div class="col-md-9">
 
 <div class="allinfocontact" id="allinfocontact" > </div>
 </div>                   
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
