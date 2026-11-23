<div class="content">
<div class="container">
<div class="book">

<?  //$this->load->view('include/menuleft');  ?>
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
$info["comments"] = "";
?>
				<div class="col-md-6 contact-top">
					<h3>Want to contact with me?</h3>
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
							<span>Subject</span>		
							<input type="text" class="input-xxlarge" id="subject" value="<?= set_value("subject",$info["subject"]); ?>" />
<?= form_error("subject","<span class='text-error'>","</span>"); ?>	
						</div>
						<div>
							<span>Your Message</span>		
							<textarea class="ckeditor" id="comments" rows="15" cols="100" name="comments" >
<?= set_value("comments",$info["comments"]); ?>
</textarea>
<?= form_error("comments","<span class='text-error'>","</span>"); ?>
						</div>
                          
       <input type="button"  value="SEND" onClick="insert()"  class="contactbutt" /> 
<script>
function insert(){
var url =  "<?php echo base_url()?>home/contactinfo";
 
var name=$('#name').val();
var subject=$('#subject').val();
var email=$('#email').val();
var comments=$('#comments').val();

var emailfff = document.getElementById('email');
var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

var boolean=0;
 		   if(name==''){
				$("#name").css({'border':'1px solid #ff0000','color':'#ff0000'});
                 boolean=1;
            }else
		    if(email==''){
  				$("#email").css({'border':'1px solid #ff0000','color':'#ff0000'});
				$("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
                boolean=1;
            } 

    else if (!filter.test(emailfff.value)) {

			    $("#email").css({'border':'1px solid #ff0000','color':'#ff0000'});
				$("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
                boolean=1;

	}

  
			else
		    if(subject==''){
  				$("#subject").css({'border':'1px solid #ff0000','color':'#ff0000'});
				$("#email").css({'border':'1px solid #bebdbd','color':'#132c57'});
                boolean=1;
            }
			
			else
		    if(comments==''){
  				$("#comments").css({'border':'1px solid #ff0000','color':'#ff0000'});
				$("#email").css({'border':'1px solid #bebdbd','color':'#132c57'});
                boolean=1;
            }
            else{
                document.getElementById('allinfocontact').innerHTML='';
				boolean=0;
            }
 	 
           if(boolean==0)
            {
$("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});				
$("#subject").css({'border':'1px solid #bebdbd','color':'#132c57'});			
$("#name").css({'border':'1px solid #bebdbd','color':'#132c57'});
$("#email").css({'border':'1px solid #bebdbd','color':'#132c57'});
$("#comments").css({'border':'1px solid #bebdbd','color':'#132c57'});
							
$('#name').val('');
$('#subject').val('');
$('#email').val('');
$('#comments').val('');

$.post(url,{'name':name,'subject':subject,'email':email,'comments':comments},function(data){  if(data=="No Success"){ 
$('.allinfocontact').html(data);
					}
					else
					 {
				 	 $('.allinfocontact').html(data);
 					 }
					
                }); 
            }
        }

</script>                  
                         
				</div>
				<div class="col-md-6 contact-top">
					<h3><?=$info['title']?></h3>
		          	<p><?=$info['description']?></p>
                    
                    <!--<div class="map">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d37494223.23909492!2d103!3d55!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x453c569a896724fb%3A0x1409fdf86611f613!2sRussia!5e0!3m2!1sen!2sin!4v1415776049771"></iframe>
					</div>-->
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