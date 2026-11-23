<?php
/**
 * Answers of one practice-test attempt as a table: one column per attempt
 * (✗ wrong in red, ✓ right in green), then the correct answer and the grade.
 * Used by the student profile (member_profile.php) and the end-of-test screen (practice_result.php).
 * Input: $det = list of questions as stored in practice_result.details
 *        (n, title, attempts, code, grade, points, chosen, chosen_text, correct, correct_text).
 * The correct answer is shown only for questions the student finished (code >= 0).
 */
$pa_rows = (isset($det) && is_array($det)) ? $det : array();
if (!count($pa_rows)) {
    echo '<p class="pa-empty">No answer details were recorded for this attempt.</p>';
    return;
}
$pa_cols = 1;
foreach ($pa_rows as $d) {
    $n = (isset($d['chosen_text']) && is_array($d['chosen_text'])) ? count($d['chosen_text']) : 0;
    if ($n > $pa_cols) $pa_cols = $n;
}
$pa_ord = array(1 => '1st attempt', 2 => '2nd attempt', 3 => '3rd attempt');
?>
<div class="pa-scroll">
<table class="pa-table">
<thead><tr><th>#</th><th>Question</th>
<?php for ($a = 1; $a <= $pa_cols; $a++) { ?><th><?= isset($pa_ord[$a]) ? $pa_ord[$a] : $a . 'th attempt' ?></th><?php } ?>
<th>Correct answer</th><th>Grade</th></tr></thead>
<tbody>
<?php foreach ($pa_rows as $d) {
    $texts = (isset($d['chosen_text']) && is_array($d['chosen_text'])) ? $d['chosen_text'] : array();
    $nums = (isset($d['chosen']) && is_array($d['chosen'])) ? $d['chosen'] : array();
    $correct = isset($d['correct']) ? (int) $d['correct'] : 0;
    $code = isset($d['code']) ? (int) $d['code'] : -1;
?>
<tr>
<td class="pa-n"><?= (int) $d['n'] ?></td>
<td class="pa-q"><?= htmlspecialchars((string) $d['title']) ?></td>
<?php if (!count($texts)) { ?>
<td colspan="<?= $pa_cols ?>"><span class="pa-none"><?= array_key_exists('chosen_text', $d) ? 'Not answered' : 'Not recorded' ?></span></td>
<?php } else {
    for ($a = 0; $a < $pa_cols; $a++) {
        if (!isset($texts[$a])) { echo '<td><span class="pa-none">&ndash;</span></td>'; continue; }
        $known = isset($nums[$a]) && $correct > 0;
        $ok = $known && (int) $nums[$a] === $correct;
        $cls = $known ? ($ok ? 'is-right' : 'is-wrong') : '';
?>
<td><span class="pa-ans <?= $cls ?>" title="<?= $known ? ($ok ? 'Right' : 'Wrong') : '' ?>"><?php if ($known) { ?><i aria-hidden="true"><?= $ok ? '&#10003;' : '&#10007;' ?></i><?php } ?><bdi><?= htmlspecialchars((string) $texts[$a]) ?></bdi></span></td>
<?php } } ?>
<td><?php if ($code >= 0 && !empty($d['correct_text'])) { ?><span class="pa-ans is-key"><bdi><?= htmlspecialchars((string) $d['correct_text']) ?></bdi></span><?php } else { ?><span class="pa-none">&ndash;</span><?php } ?></td>
<td class="pa-g"><span class="pt-grade pt-grade-<?= $code >= 0 ? $code : 'x' ?>"><?= htmlspecialchars((string) $d['grade']) ?></span><?php if (isset($d['points'])) { ?> <small><?= (float) $d['points'] ?> pt</small><?php } ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
