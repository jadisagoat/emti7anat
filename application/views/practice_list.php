<?
// List of published practice tests (AJAX fragment and the /tests page).
// The chosen test loads into #result6 below the list (js/practice.js: ajaxpractice).
?>
<div class="pt-catalog">
<div class="title" >Practice tests</div>
<? if(!$member){ ?>
<div class="pt-msg">Please <a href="<?=base_url()?>login">log in</a> with your student account to start a practice test.</div>
<? } ?>
<? if(count($tests)){ ?>
<ul class="ulli pt-list" >
<? foreach($tests as $t){
$info=array($t['question_count'].' '.($t['question_count']==1 ? 'question' : 'questions'));
if((int)$t['time_limit']>0) $info[]=(int)$t['time_limit'].' min';
if((int)$t['max_tries']>0) $info[]=$member ? 'tries left: '.$t['tries_left'].' of '.(int)$t['max_tries'] : (int)$t['max_tries'].' tries';
?>
<li>
<span class="pt-name"><?=htmlspecialchars($t['title'])?><small><? if($t['lesson']!==''){ ?><?=htmlspecialchars($t['lesson'])?> &middot; <? } ?><?=implode(' &middot; ',$info)?></small></span>
<? if(!$member){ ?>
<a href="<?=base_url()?>login">Login to start</a>
<? } elseif($t['tries_left']===0){ ?>
<span class="pt-done">No tries left</span>
<? } else { ?>
<a href="javascript:void(0)" class="pt-start" onclick="ajaxpractice(<?=(int)$t['id_practice_test']?>)">Start test</a>
<? } ?>
</li>
<? } ?>
</ul>
<? } else { ?>
<div class="pt-msg">No practice tests are published yet.</div>
<? } ?>
</div>
<div id="result6"></div>
