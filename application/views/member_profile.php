<?php
/**
 * Student profile (route /profile): account, summary counters, one row per test and the full attempt history
 * with the answers of every finished attempt. Data prepared by home::profile().
 */
$mp_base = base_url();
$mp_mmss = function ($s) { $s = (int) $s; return sprintf('%02d:%02d', floor($s / 60), $s % 60); };
$mp_date = function ($d) { return $d ? date('d/m/Y H:i', strtotime($d)) : '-'; };
$mp_initial = function_exists('mb_substr') ? mb_substr(trim((string) $member['name']), 0, 1, 'UTF-8') : substr(trim((string) $member['name']), 0, 1);
?>
<link rel="stylesheet" href="<?= $mp_base ?>csssite/profile.css?v=1" />
<div class="content">
<div class="container">
<div class="book">
<div class="col-md-12 content-top page-form" id="bodycontainer">
<div class="mp">

<section class="mp-card mp-head">
	<div class="mp-avatar" aria-hidden="true"><?= htmlspecialchars($mp_initial) ?></div>
	<div class="mp-who">
		<h3><?= htmlspecialchars($member['name']) ?></h3>
		<div class="mp-meta">
			<span>Username: <b><bdi><?= htmlspecialchars($member['username']) ?></bdi></b></span>
<?php if (!empty($member['access_end'])) { ?>
			<span>Access valid until <b><?= date('d/m/Y', strtotime($member['access_end'])) ?></b></span>
<?php } ?>
<?php if (!empty($member['last_login'])) { ?>
			<span>Last login <b><?= $mp_date($member['last_login']) ?></b></span>
<?php } ?>
		</div>
	</div>
	<div class="mp-actions">
		<a class="contactbutt pt-link-btn" href="<?= $mp_base ?>tests">Go to the practice tests</a>
		<a class="contactbutt pt-link-btn pt-link-secondary" href="<?= $mp_base ?>logout">Logout</a>
	</div>
</section>

<section class="mp-stats" aria-label="Summary">
	<div class="mp-stat"><b><?= $stats['attempts'] ?></b><span>Attempts</span></div>
	<div class="mp-stat is-pass"><b><?= $stats['passed'] ?></b><span>Passed</span></div>
	<div class="mp-stat is-fail"><b><?= $stats['failed'] ?></b><span>Not passed</span></div>
	<div class="mp-stat is-open"><b><?= $stats['unfinished'] ?></b><span>Not finished</span></div>
	<div class="mp-stat"><b><?= $stats['finished'] ? $stats['best'] . '%' : '-' ?></b><span>Best score</span></div>
	<div class="mp-stat"><b><?= $stats['finished'] ? $stats['average'] . '%' : '-' ?></b><span>Average score</span></div>
</section>

<?php if (!count($attempts)) { ?>
<section class="mp-card">
	<h3>My results</h3>
	<p class="mp-empty">You have not taken any practice test yet. Open the <a href="<?= $mp_base ?>tests">Practice Tests</a> page and click <b>Start test</b>.</p>
</section>
<?php } else { ?>

<section class="mp-card">
	<h3>My tests</h3>
	<div class="mp-scroll">
	<table class="mp-table">
		<thead><tr><th>Test</th><th>Attempts</th><th>Best score</th><th>Last result</th><th>Status</th><th></th></tr></thead>
		<tbody>
<?php foreach ($tests as $t) { ?>
			<tr>
				<td><b><?= htmlspecialchars($t['title']) ?></b><?php if ($t['lesson'] !== '') { ?><small><?= htmlspecialchars($t['lesson']) ?></small><?php } ?></td>
				<td><?php if ($t['max_tries'] > 0 && $t['used'] <= $t['max_tries']) { ?><?= $t['used'] ?> of <?= $t['max_tries'] ?><small><?= $t['tries_left'] ?> left</small><?php } elseif ($t['max_tries'] > 0) { ?><?= $t['used'] ?><small>limit <?= $t['max_tries'] ?>, none left</small><?php } else { ?><?= $t['used'] ?><small>no limit</small><?php } ?></td>
				<td><?= $t['best'] === null ? '-' : $t['best'] . '%' ?></td>
				<td><?php if ($t['last'] === null) { ?><span class="mp-badge is-open">Not finished</span><?php } else { ?><?= $t['last']['percent'] ?>% <span class="mp-badge <?= $t['last']['passed'] ? 'is-pass' : 'is-fail' ?>"><?= $t['last']['passed'] ? 'Passed' : 'Not passed' ?></span><?php } ?><small><?= $mp_date($t['last_date']) ?></small></td>
				<td><?php if ($t['ever_passed']) { ?><span class="mp-badge is-pass">Passed</span><?php } elseif ($t['best'] === null) { ?><span class="mp-badge is-open">Not finished</span><?php } else { ?><span class="mp-badge is-fail">Not passed yet</span><?php } ?></td>
				<td class="mp-go"><?php if ($t['available']) { ?><a href="<?= $mp_base ?>tests">Take again</a><?php } elseif ($t['tries_left'] === 0) { ?><small>No tries left</small><?php } ?></td>
			</tr>
<?php } ?>
		</tbody>
	</table>
	</div>
</section>

<section class="mp-card">
	<h3>Attempt history</h3>
	<div class="mp-scroll">
	<table class="mp-table mp-history">
		<thead><tr><th>Date</th><th>Test</th><th>Score</th><th>Result</th><th>1st / 2nd / wrong</th><th>Time</th><th></th></tr></thead>
		<tbody>
<?php foreach ($attempts as $r) { $fin = ((int) $r['status'] === 1); $det = json_decode((string) $r['details'], TRUE); ?>
			<tr>
				<td><?= $mp_date($r['created_date']) ?></td>
				<td><?= htmlspecialchars($r['test_title'] !== null ? $r['test_title'] : '(test removed)') ?><?php if (!empty($r['section_title'])) { ?><small><?= htmlspecialchars($r['section_title']) ?></small><?php } ?></td>
<?php if ($fin) { ?>
				<td><b><?= (float) $r['score'] ?></b> / <?= (float) $r['max_score'] ?> <small><?= (float) $r['percent'] ?>% &middot; pass mark <?= (float) $r['pass_percent'] ?>%</small></td>
				<td><span class="mp-badge <?= $r['passed'] ? 'is-pass' : 'is-fail' ?>"><?= $r['passed'] ? 'Passed' : 'Not passed' ?></span></td>
				<td><span class="pt-grade pt-grade-1"><?= (int) $r['first_try'] ?></span> <span class="pt-grade pt-grade-2"><?= (int) $r['second_try'] ?></span> <span class="pt-grade pt-grade-0"><?= (int) $r['failed'] ?></span></td>
				<td><?= $mp_mmss($r['duration_sec']) ?><?php if ($r['timed_out']) { ?><small class="mp-warn">time was up</small><?php } ?></td>
				<td class="mp-go"><?php if (is_array($det) && count($det)) { ?><button type="button" class="mp-toggle" data-target="mpa_<?= (int) $r['id_practice_result'] ?>" aria-expanded="false">Show answers</button><?php } ?></td>
<?php } else { ?>
				<td colspan="4"><span class="mp-badge is-open">Not finished</span> <small>started, not submitted (<?= (int) $r['total_questions'] ?> <?= (int) $r['total_questions'] === 1 ? 'question' : 'questions' ?>)</small></td>
				<td></td>
<?php } ?>
			</tr>
<?php if ($fin && is_array($det) && count($det)) { ?>
			<tr class="mp-answers" id="mpa_<?= (int) $r['id_practice_result'] ?>" hidden>
				<td colspan="7"><?php $this->load->view('member_answers', array('det' => $det)); ?></td>
			</tr>
<?php } ?>
<?php } ?>
		</tbody>
	</table>
	</div>
	<p class="mp-legend"><span class="pt-grade pt-grade-1">n</span> right on the 1st attempt &nbsp; <span class="pt-grade pt-grade-2">n</span> right on the 2nd attempt &nbsp; <span class="pt-grade pt-grade-0">n</span> wrong</p>
</section>
<?php } ?>

</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>
<script>
$(document).on('click', '.mp-toggle', function () {
	var row = document.getElementById($(this).data('target'));
	if (!row) return;
	var open = row.hasAttribute('hidden');
	if (open) row.removeAttribute('hidden'); else row.setAttribute('hidden', '');
	$(this).attr('aria-expanded', open ? 'true' : 'false').text(open ? 'Hide answers' : 'Show answers');
});
</script>
