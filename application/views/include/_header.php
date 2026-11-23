<script>
 /* (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70828021-1', 'auto');
  ga('send', 'pageview');
*/
</script>
<div class="header">
		<div class="container">
			<div class="header-top">
				
				<div class="logo">
					<h1>
                        <a href="<?=base_url();?>" title="GRAVITY">
                             <img src="<?=base_url();?>images/logo2-light.png" alt="GRAVITY">
                    </a></h1>
				</div>
				<div class="search">
                    All you need to succeed
			</div>
				<div class="clearfix"> </div>
		</div>
			
				<div class="top-nav">
					<span class="menu" role="button" tabindex="0" aria-label="Menu"><i></i><i></i><i></i></span>
					<ul>
						<li <? if($menuactive=="home"){ ?> class="active"<? } ?>><a href="<?=base_url();?>">General Secondary </a></li>
						<li <? if($menuactive=="about"){ ?> class="active"<? } ?>><a href="<?=base_url();?>about" >About us</a></li>
                        
                        <li <? if($menuactive=="orderonline"){ ?> class="active"<? } ?>><a href="<?=base_url();?>orderonline" >Order Online</a></li>
                        <li <?
                        $material_official=$this->fct->getAll("classes","sort_order");
                        $id_ooff=1;
                        foreach($material_official as $material_off1){
                            $id_ooff=$material_off1["id_classes"];break;
                        }

                        if($menuactive=="Official Exams"){ ?> class="active"<? } ?>><a href="<?=base_url();?>officialsession<?php //echo $id_ooff;?>" >Official Exams</a></li>
                        <li <? if($menuactive=="services"){ ?> class="active"<? } ?>><a href="<?=base_url();?>services" >Services</a></li>
						<li <? if($menuactive=="news"){ ?> class="active"<? } ?>><a href="<?=base_url();?>news" >News</a></li>
						<li <? if($menuactive=="contact" || $menuactive=="contactus"){ ?> class="active"<? } ?>><a href="<?=base_url();?>contactus" >Contact us</a></li>
						<li <? if($menuactive=="tests"){ ?> class="active"<? } ?>><a href="<?=base_url();?>tests" >Practice Tests</a></li>
						<? if($this->session->userdata('member_id')){ ?>
						<li class="nav-member <? if($menuactive=="login" || $menuactive=="profile"){ ?>active<? } ?>"><a href="<?=base_url();?>profile" title="My profile (<?=htmlspecialchars($this->session->userdata('member_name'))?>)"><?=htmlspecialchars($this->session->userdata('member_name'))?></a></li>
						<? } else { ?>
						<li <? if($menuactive=="login"){ ?> class="active"<? } ?>><a href="<?=base_url();?>login" >Student Login</a></li>
						<? } ?>
					<div class="clearfix"> </div>
					</ul>	
				<script>
					$("span.menu").click(function(){
						$(this).toggleClass("open");
						$(".top-nav ul").slideToggle(300, function(){
						});
					}).keydown(function(e){
						if(e.which==13 || e.which==32){ e.preventDefault(); $(this).click(); }
					});
				</script>
					
						
			</div>
		</div>
		</div> 
