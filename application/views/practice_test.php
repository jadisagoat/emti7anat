<?
// Practice test shell (AJAX fragment). Contains only stems and option texts:
// the correct options, hints and explanations stay on the server (home/practice_answer, home/practice_reveal).
// display_mode 'all' = every question on the page with a Finish button; 'one' = one question at a time.
$N=count($questions);
$letters=array(1=>'A',2=>'B',3=>'C',4=>'D');
$max=max(1,(int)$test['max_attempts']);
$limit=max(0,(int)$test['time_limit'])*60;
$mode=(isset($test['display_mode']) && $test['display_mode']==='one') ? 'one' : 'all';
?>
<div id="pt-root" class="pt pt-mode-<?=$mode?>" data-mode="<?=$mode?>" data-test="<?=(int)$test['id_practice_test']?>" data-total="<?=$N?>" data-url="<?=base_url()?>home/" data-attempts="<?=$max?>" data-reveal="<?=htmlspecialchars($test['reveal_mode'])?>" data-limit="<?=$limit?>">
<? if($limit>0){ ?>
	<div class="pt-timer" role="timer" aria-label="Time left">
		<span class="pt-timer-icon" aria-hidden="true"></span>
		<span class="pt-timer-label">Time left</span>
		<b><?=sprintf('%02d:%02d',floor($limit/60),$limit%60)?></b>
		<span class="pt-timer-track" aria-hidden="true"><i></i></span>
	</div>
	<div class="pt-sr" aria-live="assertive"></div>
<? } ?>
	<div class="pt-head">
		<div class="pt-title"><?=htmlspecialchars($test['title'])?></div>
		<div class="pt-meta">
			<span><?=$N?> question<?= $N==1 ? '' : 's' ?> &middot; answered <b class="pt-done-count">0</b> of <?=$N?></span>
		</div>
		<div class="pt-bar"><i style="width:0%"></i></div>
	</div>
<? if(trim(strip_tags((string)$test['description']))!==''){ ?>
	<div class="pt-intro"><?=$test['description']?></div>
<? } ?>
<? $i=0; foreach($questions as $q){ $i++; ?>
	<section class="pt-q" data-q="<?=(int)$q['id_practice_question']?>" data-n="<?=$i?>"<?= $mode==='one' ? ' hidden' : '' ?>>
		<div class="pt-qhead">Question <?=$i?> of <?=$N?> <span>&middot; Attempt <b class="pt-att">1</b> of <?=$max?></span></div>
		<div class="pt-stem">
<? if(trim(strip_tags((string)$q['question_text'],'<img>'))!==''){ ?>
			<div class="pt-text"><?=$q['question_text']?></div>
<? } ?>
<? if(!empty($q['image']) && is_file('uploads/practice_question/'.$q['image'])){ ?>
			<img src="<?=base_url()?>uploads/practice_question/<?=$q['image']?>" alt="Question <?=$i?>" loading="lazy" />
<? } ?>
		</div>
		<div class="pt-opts">
<? for($k=1;$k<=4;$k++){ ?>
			<button type="button" class="pt-opt" data-opt="<?=$k?>"><span class="pt-letter"><?=$letters[$k]?></span><span class="pt-opt-text"><?=$q['option_'.$k]?></span></button>
<? } ?>
		</div>
		<div class="pt-feedback" aria-live="polite"></div>
		<div class="pt-actions">
			<button type="button" class="pt-btn pt-btn-secondary pt-reveal" hidden>See answer</button>
<? if($mode==='one'){ ?>
			<button type="button" class="pt-btn pt-next" hidden><?= $i<$N ? 'Next question' : 'Finish' ?></button>
<? } ?>
		</div>
	</section>
<? } ?>
<? if($mode==='all'){ ?>
	<div class="pt-finish-bar">
		<button type="button" class="pt-btn pt-finish">Finish test</button>
		<span class="pt-finish-note">Answer all the questions, then click Finish. Questions left unanswered count as not answered.</span>
	</div>
<? } ?>
	<div class="pt-summary" hidden></div>
	<div class="pt-overlay" hidden>
		<div class="pt-modal" role="dialog" aria-modal="true" aria-labelledby="pt-modal-title">
			<div class="pt-modal-head" id="pt-modal-title">Hint</div>
			<div class="pt-modal-body"></div>
			<button type="button" class="pt-btn pt-modal-close">Try again</button>
		</div>
	</div>
</div>
