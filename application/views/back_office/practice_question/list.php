<?php
// Questions of one practice test (DevExtreme grid, inline mode). Reached through practice_question/lists/<id_practice_test>.
// Legacy table view kept in list_legacy.php.
// 1. privilege block
$content_name = "practice question";
$id_content = $this->fct->getonecell("content_type", "id_content", array("name" => "practice question"));
list($read_priv, $write_priv, $delete_priv) = bo_priv($id_content);
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// index/<order> or lists/<id_practice_test>/<order>
$sego = ($this->uri->segment(3) == "lists") ? $this->uri->segment(5) : $this->uri->segment(4);
if (!in_array($sego, array("title", "status", "correct_option"), true)) $sego = "";
$letters = array(1 => "A", 2 => "B", 3 => "C", 4 => "D");
$pt_e = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };

// 2. computed cells: row number, question text (stripped) and/or image thumbnail, hint yes/default
$rows = array();
$i = 0;
foreach ($info as $val) {
    $i++;
    $plain = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $val["question_text"]), ENT_QUOTES, 'UTF-8')));
    if (mb_strlen($plain, 'UTF-8') > 90) $plain = mb_substr($plain, 0, 90, 'UTF-8') . "…";
    $q = "";
    if (!empty($val["image"]) && is_file("./uploads/practice_question/" . $val["image"])) {
        $src = base_url() . "uploads/practice_question/" . rawurlencode($val["image"]);
        $q .= "<a href='" . $pt_e($src) . "' class='gallery' title='" . $pt_e($val["title"]) . "'><img src='" . $pt_e($src) . "' class='bo-thumb' alt='' style='margin-inline-end:8px;vertical-align:middle;border:1px solid #ddd' /></a>";
    }
    $q .= $plain !== "" ? $pt_e($plain) : "<span class='small muted'>" . $pt_e(bo_t('pt_image_question')) . "</span>";
    $val["num"] = $i;
    $val["question_html"] = $q;
    $val["correct_option"] = (int) $val["correct_option"];
    $val["has_hint"] = trim(strip_tags((string) $val["hint"])) !== "" ? 1 : 0;
    $rows[] = $val;
}
$correct_badges = array();
foreach ($letters as $n => $l) $correct_badges[$n] = array($l, 'label-info');

// subtitle: back link to the tests + current test title + drag hint
if ($test) {
    $subtitle = '<a href="' . base_url() . 'back_office/practice_test">' . $pt_e(bo_t('ptg_back_tests')) . '</a> '
              . (bo_rtl() ? '&lsaquo;' : '&rsaquo;') . ' <b class="text-danger">' . $pt_e($test["title"]) . '</b>'
              . ' &nbsp;' . $pt_e(bo_t('pt_drag_questions'));
} else {
    $subtitle = '<a href="' . base_url() . 'back_office/practice_test">' . $pt_e(bo_t('ptg_back_tests')) . '</a> '
              . (bo_rtl() ? '&lsaquo;' : '&rsaquo;') . ' <span class="text-danger">(' . $pt_e(bo_t('pt_no_test_chosen')) . ')</span>';
}

// 3. grid config
$grid = array(
    'module' => 'practice_question', 'key' => 'id_practice_question', 'rows' => $rows, 'title' => $title, 'order' => $sego,
    'subtitle' => $subtitle,
    'columns' => array('num', 'question_html', 'correct_option', 'has_hint', 'status'),
    'labels' => array('num' => '#', 'question_html' => bo_label('question'), 'correct_option' => bo_t('ptg_correct'),
                      'has_hint' => bo_label('hint'), 'status' => bo_label('status')),
    'types' => array('num' => 'number', 'question_html' => 'raw', 'correct_option' => 'badge', 'has_hint' => 'badge', 'status' => 'badge'),
    'badges' => array(
        'correct_option' => $correct_badges,
        'has_hint' => array(1 => array(bo_t('pt_yes'), 'label-success'), 0 => array(bo_t('ptg_default'), 'label-default')),
        'status' => array(1 => array(bo_t('pt_published'), 'label-success'), 0 => array(bo_t('pt_hidden'), 'label-warning')),
    ),
    'widths' => array('num' => 60, 'correct_option' => 130, 'has_hint' => 110),
    'align' => array('num' => 'center'),
    'keep' => array('num'),
    'search' => array('question_html'),
    'files' => 'uploads/practice_question/',
    'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
    'add' => ($write_priv == 1 && $test) ? true : false, 'add_label' => bo_t('pt_add_question'),
    'bulk' => $delete_priv, 'bulk_options' => array('delete_all' => bo_t('ptg_delete_selected')), 'reorder' => $write_priv, 'export' => true,
    'empty' => bo_t('pt_no_questions'),
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// notices of the legacy list (no test chosen / fewer questions than the test shows), placed above the grid
$pt_notice = '';
if (!$test) {
    $pt_notice = '<div class="alert alert-info">' . $pt_e(bo_t('pt_open_test_first')) . ' <a href="' . base_url() . 'back_office/practice_test">' . $pt_e(bo_t('pt_tests')) . '</a> '
               . bo_t('pt_arrow') . ' <b>' . $pt_e(bo_t('pt_questions')) . '</b>.</div>';
} elseif ($test["questions_per_test"] > 0 && count($info) < $test["questions_per_test"]) {
    $pt_notice = '<div class="alert alert-warning">' . sprintf(bo_t('ptg_too_few'), (int) $test["questions_per_test"], count($info), (int) $test["questions_per_test"] - count($info)) . '</div>';
}
if ($pt_notice !== '') { ?>
<div id="pt_notice"><?= $pt_notice ?></div>
<script>(function () { var n = document.getElementById('pt_notice'), c = document.querySelector('.bo-grid-card'); if (n && c) c.parentNode.insertBefore(n, c); })();</script>
<?php }
// post-init: '#' follows the row position (also after a drag reorder; the grid refreshes after each drop); Delete gets the question text
$pt_js = array('del' => bo_t('pt_confirm_delete_question'));
?>
<script>$(function () {
  if (!window.boGrid || !boGrid.grid) return;
  var T = <?= bo_js($pt_js) ?>, g = boGrid.grid, cfg = boGrid.cfg;
  g.beginUpdate();
  g.columnOption('num', 'calculateCellValue', function (r) { var i = boGrid.rows.indexOf(r); return i < 0 ? r.num : i + 1; });
  g.columnOption('num', 'allowSorting', false);
  g.columnOption('bo_actions', 'cellTemplate', function (c, o) {
    var $w = boGrid.actions(o.data), id = encodeURIComponent(o.data[cfg.key]);
    $w.find('.table-delete-link').off('click').on('click', function () {
      if (window.confirm(T.del)) window.location = String(cfg.deleteUrl).split('{id}').join(id);
      return false;
    });
    c.append($w);
  });
  g.endUpdate();
});</script>
<?php
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
