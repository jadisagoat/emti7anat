
<?php
$content_name="practice result";
$id_content=$this->fct->getonecell("content_type","id_content",array("name"=>"practice result"));
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$read_priv=$this->fct->getonecell("priviledge","read_p",$cond);
$delete_priv=$this->fct->getonecell("priviledge","delete_p",$cond);
} else {
$read_priv = 1;
$delete_priv = 1;
}
if($read_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }

if(!function_exists('pt_mmss')){ function pt_mmss($s){ $s=(int)$s; return sprintf('%02d:%02d',floor($s/60),$s%60); } }

// ---- summaries (computed on the filtered rows). status 1 = finished, 0 = started but not finished ----
$total=count($rows); $finished=0; $passed=0; $sum=0; $students=array(); $by_student=array(); $by_test=array(); $json=array();
foreach($rows as $r){
$fin=((int)$r["status"]===1); $ok=($fin && $r["passed"]);
if($fin){ $finished++; $sum+=(float)$r["percent"]; }
if($ok) $passed++;
$students[$r["id_members"]]=1;
$name=$r["member_name"]!==NULL?$r["member_name"]:"(".bo_t('pt_deleted_student').")";
$ttitle=$r["test_title"]!==NULL?$r["test_title"]:"(".bo_t('pt_deleted_test').")";
$k=$r["id_members"];
if(!isset($by_student[$k])) $by_student[$k]=array("name"=>$name,"username"=>$r["member_username"],"n"=>0,"fin"=>0,"passed"=>0,"best"=>0,"sum"=>0,"last"=>"");
$by_student[$k]["n"]++; if($fin){ $by_student[$k]["fin"]++; $by_student[$k]["sum"]+=(float)$r["percent"]; if((float)$r["percent"]>$by_student[$k]["best"]) $by_student[$k]["best"]=(float)$r["percent"]; }
$by_student[$k]["passed"]+=$ok?1:0;
if($r["created_date"]>$by_student[$k]["last"]) $by_student[$k]["last"]=$r["created_date"];
$k=$r["id_practice_test"];
if(!isset($by_test[$k])) $by_test[$k]=array("title"=>$ttitle,"lesson"=>$r["section_title"],"n"=>0,"fin"=>0,"passed"=>0,"sum"=>0,"students"=>array());
$by_test[$k]["n"]++; if($fin){ $by_test[$k]["fin"]++; $by_test[$k]["sum"]+=(float)$r["percent"]; } $by_test[$k]["passed"]+=$ok?1:0; $by_test[$k]["students"][$r["id_members"]]=1;
// compact data for the popups (incl. what the student answered per question)
$det=json_decode((string)$r["details"],TRUE);
$json[]=array("id"=>(int)$r["id_practice_result"],"mid"=>(int)$r["id_members"],"date"=>$r["created_date"],"name"=>$name,"user"=>(string)$r["member_username"],
"test"=>$ttitle,"score"=>(float)$r["score"],"max"=>(float)$r["max_score"],"pct"=>(float)$r["percent"],"fin"=>$fin?1:0,"passed"=>$ok?1:0,"time"=>pt_mmss($r["duration_sec"]),"to"=>(int)$r["timed_out"],
"det"=>is_array($det) ? $det : array());
}
// Answers of one result as a table: one column per attempt (✗ wrong in red, ✓ right in green),
// then the correct answer and the grade. Same markup as answers_html() in the popup below.
if(!function_exists('pt_answers_table')){
function pt_answers_table($det){
$cols=1;
foreach($det as $d){
  $n=(isset($d["chosen_text"]) && is_array($d["chosen_text"])) ? count($d["chosen_text"]) : 0;
  if($n>$cols) $cols=$n;
}
$h='<table class="pt-ans-table"><thead><tr><th>#</th><th>'.bo_t('pt_question').'</th>';
for($a=1;$a<=$cols;$a++) $h.='<th>'.sprintf(bo_t('pt_attempt_n'),$a).'</th>';
$h.='<th>'.bo_t('pt_correct_answer').'</th><th>'.bo_t('pt_grade').'</th></tr></thead><tbody>';
foreach($det as $d){
  $texts=(isset($d["chosen_text"]) && is_array($d["chosen_text"])) ? $d["chosen_text"] : array();
  $nums=(isset($d["chosen"]) && is_array($d["chosen"])) ? $d["chosen"] : array();
  $correct=isset($d["correct"]) ? (int)$d["correct"] : 0;
  $code=isset($d["code"]) ? (int)$d["code"] : -1;
  $h.='<tr><td>'.(int)$d["n"].'</td><td class="pt-ans-q">'.htmlspecialchars((string)$d["title"]).'</td>';
  if(!count($texts)){
    $none=array_key_exists("chosen_text",$d) ? bo_t('pt_not_answered') : bo_t('pt_not_recorded');
    $h.='<td colspan="'.$cols.'"><span class="pt-ans-none">'.$none.'</span></td>';
  } else {
    for($a=0;$a<$cols;$a++){
      if(!isset($texts[$a])){ $h.='<td><span class="pt-ans-none">&ndash;</span></td>'; continue; }
      $known=isset($nums[$a]) && $correct>0;
      $ok=$known && (int)$nums[$a]===$correct;
      $cls=$known ? ($ok ? 'is-right' : 'is-wrong') : '';
      $mark=$known ? ($ok ? '&#10003;' : '&#10007;') : '';
      $tip=$known ? ($ok ? bo_t('pt_ans_right') : bo_t('pt_ans_wrong')) : '';
      $h.='<td><span class="pt-ans '.$cls.'" title="'.htmlspecialchars($tip).'">'.($mark!=='' ? '<i>'.$mark.'</i>' : '').'<bdi>'.htmlspecialchars((string)$texts[$a]).'</bdi></span></td>';
    }
  }
  $h.='<td>'.(!empty($d["correct_text"]) ? '<span class="pt-ans is-key"><bdi>'.htmlspecialchars((string)$d["correct_text"]).'</bdi></span>' : '<span class="pt-ans-none">&ndash;</span>').'</td>';
  $gcls=$code===1 ? 'g1' : ($code===2 ? 'g2' : ($code===0 ? 'g0' : 'gx'));
  $h.='<td><span class="pt-ans-grade '.$gcls.'">'.htmlspecialchars((string)$d["grade"]).'</span> <small class="muted">'.sprintf(bo_t('pt_points_unit'),(float)$d["points"]).'</small></td></tr>';
}
return $h.'</tbody></table>';
}
}
$unfinished=$total-$finished;
$qs=http_build_query(array_filter(array("test"=>$f["test"],"member"=>$f["member"],"from"=>$f["from"],"to"=>$f["to"],"status"=>$f["status"])));
?>
<link rel="stylesheet" href="<?= base_url(); ?>css/practice_admin.css" />
<script>
var PT_ROWS=<?= json_encode($json, JSON_UNESCAPED_UNICODE); ?>;
function pt_t(k, d){ return (window.BO_I18N && BO_I18N[k] !== undefined) ? BO_I18N[k] : (d === undefined ? k : d); }
function toggle_details(id){ $("#details_"+id).toggle(); return false; }
function delete_row(id){ window.location="<?= base_url(); ?>back_office/practice_result/delete/"+id+"<?= $qs ? '?'.$qs : ''; ?>"; return false; }
function esc(s){ return $("<div>").text(s==null?"":String(s)).html(); }
function badge(r){ if(!r.fin) return '<span class="label label-warning">'+esc(pt_t('pt_unfinished'))+'</span>'; return r.passed ? '<span class="label label-success">'+esc(pt_t('pt_passed'))+'</span>' : '<span class="label label-important">'+esc(pt_t('pt_failed'))+'</span>'; }
// per-question answers of one result (popup): same table as pt_answers_table() in PHP
function answers_html(r){
  if(!r.det || !r.det.length) return '<small class="muted">'+esc(pt_t('pt_no_details'))+'</small>';
  var cols=1;
  $.each(r.det,function(i,d){ if(d.chosen_text && d.chosen_text.length>cols) cols=d.chosen_text.length; });
  var h='<table class="pt-ans-table"><thead><tr><th>#</th><th>'+esc(pt_t('pt_question'))+'</th>';
  for(var a=1;a<=cols;a++) h+='<th>'+esc(pt_t('pt_attempt_n','Attempt %d').replace('%d',a))+'</th>';
  h+='<th>'+esc(pt_t('pt_correct_answer'))+'</th><th>'+esc(pt_t('pt_grade'))+'</th></tr></thead><tbody>';
  $.each(r.det,function(i,d){
    var texts=d.chosen_text||[], nums=d.chosen||[], correct=parseInt(d.correct,10)||0, code=(d.code===undefined?-1:parseInt(d.code,10));
    h+='<tr><td>'+esc(d.n)+'</td><td class="pt-ans-q">'+esc(d.title)+'</td>';
    if(!texts.length){
      h+='<td colspan="'+cols+'"><span class="pt-ans-none">'+esc(d.chosen_text===undefined ? pt_t('pt_not_recorded') : pt_t('pt_not_answered'))+'</span></td>';
    } else {
      for(var k=0;k<cols;k++){
        if(texts[k]===undefined){ h+='<td><span class="pt-ans-none">&ndash;</span></td>'; continue; }
        var known=(nums[k]!==undefined && correct>0), ok=known && parseInt(nums[k],10)===correct;
        h+='<td><span class="pt-ans '+(known?(ok?'is-right':'is-wrong'):'')+'" title="'+esc(known?(ok?pt_t('pt_ans_right'):pt_t('pt_ans_wrong')):'')+'">'+(known?'<i>'+(ok?'&#10003;':'&#10007;')+'</i>':'')+'<bdi>'+esc(texts[k])+'</bdi></span></td>';
      }
    }
    h+='<td>'+(d.correct_text ? '<span class="pt-ans is-key"><bdi>'+esc(d.correct_text)+'</bdi></span>' : '<span class="pt-ans-none">&ndash;</span>')+'</td>';
    var g=code===1?'g1':(code===2?'g2':(code===0?'g0':'gx'));
    h+='<td><span class="pt-ans-grade '+g+'">'+esc(d.grade)+'</span> <small class="muted">'+esc(pt_t('pt_points_unit','%s pt').replace('%s',d.points))+'</small></td></tr>';
  });
  return h+'</tbody></table>';
}
function toggle_popup_answers(id){ $("#pa_"+id).toggle(); return false; }
// Popup for the summary numbers: who passed / failed / did not finish, all attempts, or the list of students.
function pt_popup(kind){
  var title="", html="", rows=PT_ROWS;
  if(kind==="passed"){ title=pt_t('pt_popup_passed'); rows=$.grep(rows,function(r){ return r.fin && r.passed; }); }
  else if(kind==="failed"){ title=pt_t('pt_popup_failed'); rows=$.grep(rows,function(r){ return r.fin && !r.passed; }); }
  else if(kind==="unfinished"){ title=pt_t('pt_popup_unfinished'); rows=$.grep(rows,function(r){ return !r.fin; }); }
  else if(kind==="attempts"){ title=pt_t('pt_popup_attempts'); }
  var count=rows.length;
  if(kind==="students"){
    title=pt_t('pt_students');
    var by={}, order=[];
    $.each(PT_ROWS,function(i,r){ if(!by[r.mid]){ by[r.mid]={name:r.name,user:r.user,n:0,passed:0,failed:0,unf:0,best:0,last:""}; order.push(r.mid); }
      var s=by[r.mid]; s.n++; if(!r.fin) s.unf++; else if(r.passed) s.passed++; else s.failed++; if(r.fin && r.pct>s.best) s.best=r.pct; if(r.date>s.last) s.last=r.date; });
    count=order.length;
    html='<table class="table table-condensed table-striped"><thead><tr><th>'+esc(pt_t('pt_student'))+'</th><th>'+esc(pt_t('pt_attempts'))+'</th><th>'+esc(pt_t('pt_passed'))+'</th><th>'+esc(pt_t('pt_failed'))+'</th><th>'+esc(pt_t('pt_unfinished'))+'</th><th>'+esc(pt_t('pt_best'))+'</th><th>'+esc(pt_t('pt_last_attempt'))+'</th></tr></thead><tbody>';
    $.each(order,function(i,mid){ var s=by[mid]; html+='<tr><td><a href="<?= base_url(); ?>back_office/practice_result?member='+mid+'">'+esc(s.name)+'</a><br><small class="muted">'+esc(s.user)+'</small></td><td>'+s.n+'</td><td class="text-success">'+s.passed+'</td><td class="text-error">'+s.failed+'</td><td>'+s.unf+'</td><td>'+s.best+'%</td><td><small>'+esc(s.last)+'</small></td></tr>'; });
    html+='</tbody></table>';
  } else {
    html='<table class="table table-condensed table-striped"><thead><tr><th>'+esc(pt_t('pt_date'))+'</th><th>'+esc(pt_t('pt_student'))+'</th><th>'+esc(pt_t('pt_test'))+'</th><th>'+esc(pt_t('pt_grade'))+'</th><th>'+esc(pt_t('pt_time'))+'</th><th>'+esc(pt_t('pt_result'))+'</th><th></th></tr></thead><tbody>';
    if(!rows.length) html+='<tr><td colspan="7" style="text-align:center">'+esc(pt_t('pt_nobody'))+'</td></tr>';
    $.each(rows,function(i,r){
      html+='<tr><td><small>'+esc(r.date)+'</small></td><td>'+esc(r.name)+'<br><small class="muted">'+esc(r.user)+'</small></td><td>'+esc(r.test)+'</td><td>'+(r.fin ? r.score+' / '+r.max+' ('+r.pct+'%)' : '-')+'</td><td>'+(r.fin ? r.time+(r.to?' <small class="text-error">'+esc(pt_t('pt_timed_out'))+'</small>':'') : '-')+'</td><td>'+badge(r)+'</td>';
      html+='<td>'+(r.det && r.det.length ? '<a class="cur" onclick="return toggle_popup_answers('+r.id+')">'+esc(pt_t('pt_answers'))+'</a>' : '')+'</td></tr>';
      if(r.det && r.det.length) html+='<tr id="pa_'+r.id+'" style="display:none"><td colspan="7" style="background:#fafafa">'+answers_html(r)+'</td></tr>';
    });
    html+='</tbody></table>';
  }
  $("#pt-popup-title").text(title+" ("+count+")");
  $("#pt-popup-body").html(html);
  if($.fn.modal){ $("#pt-popup").modal("show"); } else { $("#pt-popup").show(); }
  return false;
}
function pt_popup_close(){ if($.fn.modal){ $("#pt-popup").modal("hide"); } else { $("#pt-popup").hide(); } return false; }
$(function(){ var m=/popup=([a-z]+)/.exec(window.location.hash||""); if(m) pt_popup(m[1]); });
</script>
<style>
.pt-stats{ margin:0 0 15px; }
.pt-filter label{ display:inline-block; margin:0 10px 8px 0; vertical-align:top; }
.pt-filter label span{ display:block; font-size:11px; color:#777; }
.pt-filter select, .pt-filter input[type=text]{ margin:0; }
.pt-filter .pt-date{ width:95px; }
.pt-details > td{ background:#f7f8fb; padding:10px 14px !important; }
.pt-ans-table{ width:100%; border-collapse:collapse; background:#fff; border:1px solid #e3e7ef; border-radius:8px; font-size:13.5px; }
.pt-ans-table th{ background:#eef2f7; color:#566a7f; font-weight:600; padding:7px 10px; text-align:start; border-bottom:1px solid #e3e7ef; white-space:nowrap; }
.pt-ans-table td{ padding:7px 10px; border-bottom:1px solid #f0f2f6; vertical-align:middle; text-align:start; }
.pt-ans-table tr:last-child td{ border-bottom:0; }
.pt-ans-table .pt-ans-q{ color:#384551; min-width:180px; }
.pt-ans{ display:inline-flex; align-items:center; gap:6px; padding:3px 9px; border-radius:6px; background:#f1f3f7; color:#384551; white-space:nowrap; }
.pt-ans i{ font-style:normal; font-weight:700; }
.pt-ans.is-right{ background:#e8f7f0; color:#12805c; }
.pt-ans.is-wrong{ background:#fdebee; color:#c8102e; }
.pt-ans.is-wrong bdi{ text-decoration:line-through; text-decoration-thickness:1px; }
.pt-ans.is-key{ background:#e7f2f8; color:#0b648f; font-weight:600; }
.pt-ans-none{ color:#a8b1bb; font-style:italic; }
.pt-ans-grade{ display:inline-block; min-width:26px; padding:2px 8px; border-radius:6px; font-weight:700; text-align:center; }
.pt-ans-grade.g1{ background:#e8f7f0; color:#12805c; }
.pt-ans-grade.g2{ background:#e7f2f8; color:#0b648f; }
.pt-ans-grade.g0{ background:#fdebee; color:#c8102e; }
.pt-ans-grade.gx{ background:#f1f3f7; color:#8592a3; }
#pt-popup .pt-ans-table{ margin:4px 0; }
/* "By student" / "By test": each half scrolls instead of running under the other one */
.bo-practice .row-fluid > .span6{ min-width:0; max-width:50%; overflow-x:auto; padding:0 8px; }
.bo-practice .row-fluid > .span6 table{ margin-bottom:0; }
.bo-practice .row-fluid > .span6 td small, .bo-practice .row-fluid > .span6 td{ white-space:nowrap; }
@media (max-width: 1599.98px){ .bo-practice .row-fluid > .span6{ max-width:100%; flex:0 0 100%; margin-bottom:12px; } }
#pt-popup .modal-body{ max-height:460px; overflow-y:auto; }
</style>
<div class="container-fluid">
<div class="row-fluid">
<div class="span2">
<?php $this->load->view("back_office/includes/left_box"); ?>
</div>
<div class="span10 bo-practice <?= bo_dir_class(); ?>">
<div class="span10-fluid" >
<ul class="breadcrumb">
<li class="active"><?= $title; ?> <small class="muted"><?= bo_t('pt_report_subtitle'); ?></small></li>
<li class="pull-right"><a href="<?= base_url(); ?>back_office/practice_result/export<?= $qs ? '?'.$qs : ''; ?>" id="topLink" title="<?= bo_t('pt_export_csv_title'); ?>"><?= bo_t('pt_export_csv'); ?></a></li>
</ul>
</div>
<div class="hundred pull-left">
<?php if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success"><?= $this->session->userdata("success_message"); ?></div>
<?php } ?>

<form method="get" action="<?= base_url(); ?>back_office/practice_result" class="pt-filter well well-small">
<label><span><?= bo_t('pt_test'); ?></span>
<select name="test" class="input-large">
<option value="0"><?= bo_t('pt_all_tests'); ?></option>
<?php foreach($tests as $t){ ?><option value="<?= $t["id_practice_test"]; ?>" <?php if($f["test"]==$t["id_practice_test"]) echo 'selected="selected"'; ?>><?= htmlspecialchars($t["title"]); ?></option><?php } ?>
</select></label>
<label><span><?= bo_t('pt_student'); ?></span>
<select name="member" class="input-large">
<option value="0"><?= bo_t('pt_all_students'); ?></option>
<?php foreach($members as $m){ ?><option value="<?= $m["id_members"]; ?>" <?php if($f["member"]==$m["id_members"]) echo 'selected="selected"'; ?>><?= htmlspecialchars($m["name"]); ?> (<?= htmlspecialchars($m["username"]); ?>)</option><?php } ?>
</select></label>
<label><span><?= bo_t('pt_from_date'); ?></span>
<div class="input-append date" data-date-format="mm/dd/yyyy"><input class="pt-date" name="from" type="text" value="<?= htmlspecialchars($f["from"]); ?>"><span class="add-on"><i class="icon-th"></i></span></div></label>
<label><span><?= bo_t('pt_to_date'); ?></span>
<div class="input-append date" data-date-format="mm/dd/yyyy"><input class="pt-date" name="to" type="text" value="<?= htmlspecialchars($f["to"]); ?>"><span class="add-on"><i class="icon-th"></i></span></div></label>
<label><span><?= bo_t('pt_result'); ?></span>
<select name="status" class="input-medium">
<option value=""><?= bo_t('pt_all'); ?></option>
<option value="passed" <?php if($f["status"]=="passed") echo 'selected="selected"'; ?>><?= bo_t('pt_passed_only'); ?></option>
<option value="failed" <?php if($f["status"]=="failed") echo 'selected="selected"'; ?>><?= bo_t('pt_failed_only'); ?></option>
<option value="unfinished" <?php if($f["status"]=="unfinished") echo 'selected="selected"'; ?>><?= bo_t('pt_unfinished_only'); ?></option>
</select></label>
<label><span>&nbsp;</span>
<button type="submit" class="btn btn-primary"><?= bo_t('pt_filter'); ?></button>
<a class="btn btn-outline-secondary" href="<?= base_url(); ?>back_office/practice_result"><?= bo_t('pt_reset'); ?></a></label>
</form>

<div class="pt-stats">
<a onclick="return pt_popup('attempts')" title="<?= bo_t('pt_tip_attempts'); ?>"><b><?= $total; ?></b><small><?= bo_t('pt_stat_attempts'); ?></small></a>
<a onclick="return pt_popup('students')" title="<?= bo_t('pt_tip_students'); ?>"><b><?= count($students); ?></b><small><?= bo_t('pt_stat_students'); ?></small></a>
<a onclick="return pt_popup('passed')" title="<?= bo_t('pt_tip_passed'); ?>"><b class="text-success"><?= $passed; ?></b><small><?= bo_t('pt_passed'); ?></small></a>
<a onclick="return pt_popup('failed')" title="<?= bo_t('pt_tip_failed'); ?>"><b class="text-error"><?= $finished-$passed; ?></b><small><?= bo_t('pt_failed'); ?></small></a>
<a onclick="return pt_popup('unfinished')" title="<?= bo_t('pt_tip_unfinished'); ?>"><b class="text-warning"><?= $unfinished; ?></b><small><?= bo_t('pt_unfinished'); ?></small></a>
<a onclick="return pt_popup('passed')"><b><?= $finished ? round($passed/$finished*100) : 0; ?>%</b><small><?= bo_t('pt_stat_pass_rate'); ?></small></a>
<a onclick="return pt_popup('attempts')"><b><?= $finished ? round($sum/$finished) : 0; ?>%</b><small><?= bo_t('pt_stat_avg'); ?></small></a>
</div>

<div class="row-fluid">
<div class="span6">
<h4><?= bo_t('pt_by_student'); ?></h4>
<table class="table table-condensed table-striped">
<thead><tr><th><?= bo_t('pt_student'); ?></th><th><?= bo_t('pt_attempts'); ?></th><th><?= bo_t('pt_passed'); ?></th><th><?= bo_t('pt_best'); ?></th><th><?= bo_t('pt_average'); ?></th><th><?= bo_t('pt_last_attempt'); ?></th></tr></thead>
<tbody>
<?php if(count($by_student)){ foreach($by_student as $id=>$s){ ?>
<tr>
<td><a href="<?= base_url(); ?>back_office/practice_result?member=<?= (int)$id; ?>"><?= htmlspecialchars($s["name"]); ?></a><br><small class="muted"><?= htmlspecialchars((string)$s["username"]); ?></small></td>
<td><?= $s["n"]; ?><?php if($s["n"]>$s["fin"]){ ?> <small class="muted"><?= sprintf(bo_t('pt_unfinished_count'), $s["n"]-$s["fin"]); ?></small><?php } ?></td>
<td><?= $s["passed"]; ?> / <?= $s["fin"]; ?></td>
<td><?= $s["fin"] ? $s["best"]."%" : "-"; ?></td>
<td><?= $s["fin"] ? round($s["sum"]/$s["fin"])."%" : "-"; ?></td>
<td><small><?= $s["last"]; ?></small></td>
</tr>
<?php } } else { ?><tr><td colspan="6" style="text-align:center"><?= bo_t('pt_no_attempts_filter'); ?></td></tr><?php } ?>
</tbody>
</table>
</div>
<div class="span6">
<h4><?= bo_t('pt_by_test'); ?></h4>
<table class="table table-condensed table-striped">
<thead><tr><th><?= bo_t('pt_test'); ?></th><th><?= bo_t('pt_students'); ?></th><th><?= bo_t('pt_attempts'); ?></th><th><?= bo_t('pt_passed'); ?></th><th><?= bo_t('pt_stat_pass_rate'); ?></th><th><?= bo_t('pt_average'); ?></th></tr></thead>
<tbody>
<?php if(count($by_test)){ foreach($by_test as $id=>$t){ ?>
<tr>
<td><a href="<?= base_url(); ?>back_office/practice_result?test=<?= (int)$id; ?>"><?= htmlspecialchars($t["title"]); ?></a><?php if($t["lesson"]){ ?><br><small class="muted"><?= htmlspecialchars($t["lesson"]); ?></small><?php } ?></td>
<td><?= count($t["students"]); ?></td>
<td><?= $t["n"]; ?><?php if($t["n"]>$t["fin"]){ ?> <small class="muted"><?= sprintf(bo_t('pt_unfinished_count'), $t["n"]-$t["fin"]); ?></small><?php } ?></td>
<td><?= $t["passed"]; ?></td>
<td><?= $t["fin"] ? round($t["passed"]/$t["fin"]*100)."%" : "-"; ?></td>
<td><?= $t["fin"] ? round($t["sum"]/$t["fin"])."%" : "-"; ?></td>
</tr>
<?php } } else { ?><tr><td colspan="6" style="text-align:center"><?= bo_t('pt_no_attempts_filter'); ?></td></tr><?php } ?>
</tbody>
</table>
</div>
</div>

<h4><?= bo_t('pt_popup_attempts'); ?> <small class="muted"><?= bo_t('pt_newest_first'); ?></small></h4>
<table class="table table-striped" id="table-1">
<thead>
<tr><th><?= bo_t('pt_date'); ?></th><th><?= bo_t('pt_student'); ?></th><th><?= bo_t('pt_test'); ?></th><th><?= bo_t('pt_grade'); ?></th><th><?= bo_t('pt_percent'); ?></th><th>A / B / F</th><th><?= bo_t('pt_time'); ?></th><th><?= bo_t('pt_result'); ?></th><th class="pt-actions-cell"><?= bo_t('pt_actions'); ?></th></tr>
</thead>
<tbody>
<?php if(count($rows)){ foreach($rows as $r){ $det=json_decode((string)$r["details"],TRUE); $fin=((int)$r["status"]===1); ?>
<tr>
<td><small><?= $r["created_date"]; ?></small></td>
<td><?= $r["member_name"]!==NULL ? htmlspecialchars($r["member_name"]) : "<small>".bo_t('pt_deleted_student')."</small>"; ?><br><small class="muted"><?= htmlspecialchars((string)$r["member_username"]); ?></small></td>
<td><?= $r["test_title"]!==NULL ? htmlspecialchars($r["test_title"]) : "<small>".bo_t('pt_deleted_test')."</small>"; ?><?php if($r["section_title"]){ ?><br><small class="muted"><?= htmlspecialchars($r["section_title"]); ?></small><?php } ?></td>
<?php if($fin){ ?>
<td><?= (float)$r["score"]; ?> / <?= (float)$r["max_score"]; ?></td>
<td><?= (float)$r["percent"]; ?>% <small class="muted"><?= sprintf(bo_t('pt_pass_mark_small'), (float)$r["pass_percent"]); ?></small></td>
<td><?= (int)$r["first_try"]; ?> / <?= (int)$r["second_try"]; ?> / <?= (int)$r["failed"]; ?></td>
<td><?= pt_mmss($r["duration_sec"]); ?><?php if($r["timed_out"]){ ?> <small class="text-error"><?= bo_t('pt_timed_out'); ?></small><?php } ?></td>
<td><?php if($r["passed"]){ ?><span class="label label-success"><?= bo_t('pt_passed'); ?></span><?php } else { ?><span class="label label-important"><?= bo_t('pt_failed'); ?></span><?php } ?></td>
<?php } else { ?>
<td colspan="4"><small class="muted"><?= sprintf(bo_t('pt_started_unfinished'), (int)$r["total_questions"]); ?></small></td>
<td><span class="label label-warning"><?= bo_t('pt_unfinished'); ?></span></td>
<?php } ?>
<td class="pt-actions-cell">
<?php if($fin && is_array($det) && count($det)){ ?><a class="table-edit-link cur" onclick="return toggle_details(<?= (int)$r["id_practice_result"]; ?>)"><i class="icon-list"></i> <?= bo_t('pt_details'); ?></a> <span class="hidden"> | </span><?php } ?>
<?php if($delete_priv == 1){ ?>
<a onclick="if(confirm('<?= addslashes(bo_t('pt_confirm_delete_result')); ?>')){ delete_row('<?= (int)$r["id_practice_result"]; ?>'); }" class="table-delete-link cur" title="<?= bo_t('pt_delete'); ?>"><i class="icon-remove-sign"></i> <?= bo_t('pt_delete'); ?></a>
<?php } ?>
</td>
</tr>
<?php if($fin && is_array($det) && count($det)){ ?>
<tr id="details_<?= (int)$r["id_practice_result"]; ?>" class="pt-details" style="display:none">
<td colspan="9">
<?= pt_answers_table($det); ?>
</td>
</tr>
<?php } ?>
<?php } } else { ?>
<tr class='odd'><td colspan="9" style='text-align:center;'><?= bo_t('pt_no_results_filter'); ?></td></tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>

<!-- popup for the summary numbers (legacy modal markup; js/admin_legacy.js wraps it for Bootstrap 5) -->
<div id="pt-popup" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-header">
<h3 id="pt-popup-title" class="modal-title"><?= bo_t('pt_details'); ?></h3>
<button type="button" class="close" onclick="return pt_popup_close()" aria-hidden="true">&times;</button>
</div>
<div class="modal-body" id="pt-popup-body"></div>
<div class="modal-footer"><a class="btn btn-outline-secondary" onclick="return pt_popup_close()"><?= bo_t('pt_close'); ?></a></div>
</div>
<?php $this->session->unset_userdata("success_message"); ?>
<?php $this->session->unset_userdata("error_message"); ?>
