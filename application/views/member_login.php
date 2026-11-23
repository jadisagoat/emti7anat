<div class="content">
<div class="container">
<div class="book">

<div class="col-md-12 content-top page-form" id="bodycontainer" >
<div class="contact">
				<div class="col-md-6 contact-top">
<? if($member){ ?>
					<h3>Welcome, <?=htmlspecialchars($member['name'])?></h3>
					<p>You are logged in. <? if(!empty($member['access_end'])){ ?>Your access is valid until <b><?=date('d/m/Y',strtotime($member['access_end']))?></b>.<? } ?></p>
					<p>Open the <b>Practice Tests</b> page and click <b>Start test</b> next to the test you want.</p>
					<a class="contactbutt pt-link-btn" href="<?=base_url()?>tests">Go to the practice tests</a>
					<a class="contactbutt pt-link-btn pt-link-secondary" href="<?=base_url()?>logout">Logout</a>
<? } elseif($blocked){ ?>
					<h3>Hello, <?=htmlspecialchars($blocked['name'])?></h3>
					<? if((int)$blocked['status']!==1){ ?>
					<p class="pt-alert">Your account is not active yet. Please contact us to activate it.</p>
					<? } elseif(!empty($blocked['access_start']) && $blocked['access_start']>date('Y-m-d')){ ?>
					<p class="pt-alert">Your access starts on <?=date('d/m/Y',strtotime($blocked['access_start']))?>.</p>
					<? } else { ?>
					<p class="pt-alert">Your access expired<? if(!empty($blocked['access_end'])){ ?> on <?=date('d/m/Y',strtotime($blocked['access_end']))?><? } ?>. Please contact us to renew it.</p>
					<? } ?>
					<a class="contactbutt pt-link-btn" href="<?=base_url()?>contactus">Contact us</a>
					<a class="contactbutt pt-link-btn pt-link-secondary" href="<?=base_url()?>logout">Logout</a>
<? } else { ?>
					<h3>Student login</h3>
<? if($this->session->userdata('member_error')){ ?>
					<p class="pt-alert"><?=htmlspecialchars($this->session->userdata('member_error'))?></p>
<? $this->session->unset_userdata('member_error'); } ?>
					<form method="post" action="<?=base_url()?>home/login_submit" class="pt-login">
						<div>
							<span>Username</span>
							<input type="text" class="input-xxlarge" name="username" id="username" autocomplete="username" required />
						</div>
						<div>
							<span>Password</span>
							<input type="password" class="input-xxlarge" name="password" id="password" autocomplete="current-password" required />
						</div>
						<input type="submit" value="LOGIN" class="contactbutt" />
					</form>
<? } ?>
				</div>
				<div class="col-md-6 contact-top">
					<h3>Practice tests</h3>
					<p>Interactive multiple-choice tests for every lesson: 4 options per question, two attempts, a hint after a wrong answer and your grade at the end.</p>
					<p>Access is given by GRAVITY for a school year. If you do not have a username and password yet, <a href="<?=base_url()?>contactus">contact us</a>.</p>
				</div>
			<div class="clearfix"> </div>
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
