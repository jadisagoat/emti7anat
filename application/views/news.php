<div class="content">
<div class="container">
<div class="book">

<? // $this->load->view('include/menuleft');  ?>
<div class="col-md-12 content-top" id="bodycontainer" >
<? foreach($info as $info1){ ?>
<div class="infocont">
<h4><?=$info1['title']?></h4>
<p><?=$info1['description']?></p>
</div>
<? } ?>         	
                    
</div>
<div class="clearfix"></div>
</div>
</div>
</div>