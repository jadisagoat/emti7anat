<div class="pt-result">
	<h4><?=htmlspecialchars($test['title'])?>: your result</h4>
<? if((int)$test['show_total']===1){ ?>
	<div class="pt-score"><b><?=(float)$score?></b> / <?=(float)$max?> <span>(<?=$percent?>%)</span>
		<span class="pt-verdict <?= $passed ? 'is-pass' : 'is-fail' ?>"><?= $passed ? 'Passed' : 'Not passed' ?></span>
		<small>pass mark <?=(float)$pass_percent?>%</small>
	</div>
<? } else { ?>
	<div class="pt-score"><span class="pt-verdict <?= $passed ? 'is-pass' : 'is-fail' ?>"><?= $passed ? 'Passed' : 'Not passed' ?></span></div>
<? } ?>
<? if($timed_out){ ?>
	<div class="pt-msg pt-msg-warn"><b>Time is up.</b> The test ended automatically; the questions you did not reach count as not answered.</div>
<? } ?>
	<div class="pt-time">Time used: <b><?=sprintf('%02d:%02d',floor($duration/60),$duration%60)?></b><? if($limit>0){ ?> of <?=sprintf('%02d:%02d',floor($limit/60),$limit%60)?><? } ?></div>
	<div class="pt-dist">
		<span class="pt-grade pt-grade-1"><?=$c1?> &times; <?=htmlspecialchars($test['label_first'])?></span>
		<span class="pt-grade pt-grade-2"><?=$c2?> &times; <?=htmlspecialchars($test['label_second'])?></span>
		<span class="pt-grade pt-grade-0"><?=$c0?> &times; <?=htmlspecialchars($test['label_fail'])?></span>
<? if($unanswered>0){ ?>
		<span class="pt-grade pt-grade-x"><?=$unanswered?> unanswered</span>
<? } ?>
	</div>
	<link rel="stylesheet" href="<?=base_url()?>csssite/profile.css?v=1" />
	<? $this->load->view('member_answers', array('det'=>$rows)); ?>
	<div class="pt-actions">
		<button type="button" class="pt-btn" onclick="ajaxpractice(<?=(int)$test['id_practice_test']?>)">Try again</button>
		<button type="button" class="pt-btn pt-btn-secondary" onclick="ptBack(<?=(int)$test['id_section']?>)"><?= (int)$test['id_section'] ? 'Back to the lesson' : 'Back to the tests' ?></button>
	</div>
<? if($saved){ ?>
	<div class="pt-note">Result saved to <a href="<?=base_url()?>profile">your profile</a> (<?=htmlspecialchars($member['name'])?>).</div>
<? } ?>
</div>
