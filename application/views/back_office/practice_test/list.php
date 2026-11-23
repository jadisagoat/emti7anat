<?php
// Practice tests list (DevExtreme grid, inline mode). Legacy table view kept in list_legacy.php.
// 1. privilege block
$content_name = "practice test";
$id_content = $this->fct->getonecell("content_type", "id_content", array("name" => "practice test"));
list($read_priv, $write_priv, $delete_priv) = bo_priv($id_content);
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// index/<order> or lists/<id_section>/<order>
$sego = ($this->uri->segment(3) == "lists") ? $this->uri->segment(5) : $this->uri->segment(4);
if (!in_array($sego, array("title", "status", "questions_per_test"), true)) $sego = "";

// 2. computed cells (same information as the legacy table)
$pt_e = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
// counted phrase with Arabic number agreement: <base>_1 / _2 / _few (3..10) / _many
$pt_n = function ($n, $base) { $n = (int) $n; $m = $n % 100; $k = $n == 1 ? '_1' : ($n == 2 ? '_2' : (($m >= 3 && $m <= 10) ? '_few' : '_many')); return sprintf(bo_t($base . $k), $n); };
// hidden separator before each <br>: raw cells are searched / exported as plain text (stripHtml drops <br>)
$pt_br = "<span class='d-none'> / </span><br>";
$pt_counts = array();
$pt_ids = array();
foreach ($info as $val) $pt_ids[] = (int) $val["id_practice_test"];
if (count($pt_ids)) {
    $this->db->select("id_practice_test, COUNT(*) AS n", false);
    $this->db->where("deleted", 0);
    $this->db->where_in("id_practice_test", $pt_ids);
    $this->db->group_by("id_practice_test");
    foreach ($this->db->get("practice_question")->result_array() as $r) $pt_counts[(int) $r["id_practice_test"]] = (int) $r["n"];
}
$pt_lessons = array();
$rows = array();
foreach ($info as $val) {
    $id = (int) $val["id_practice_test"];
    $qcount = isset($pt_counts[$id]) ? $pt_counts[$id] : 0;
    $need = (int) $val["questions_per_test"];
    // lesson (section) or "standalone"
    $sid = (int) $val["id_section"];
    if ($sid && !array_key_exists($sid, $pt_lessons)) $pt_lessons[$sid] = (string) $this->fct->getonecell("section", "title", array("id_section" => $sid));
    $lesson = $sid ? $pt_lessons[$sid] : "";
    $val["lesson_html"] = $lesson !== "" ? $pt_e($lesson) : "<span class='small muted'>" . $pt_e(bo_t('ptg_standalone')) . "</span>";
    // questions: count vs questions_per_test
    if ($need > 0) {
        $q = "<span class='fw-semibold " . ($qcount < $need ? "text-danger" : "text-success") . "' dir='ltr'>" . $qcount . " / " . $need . "</span>";
        if ($qcount < $need) $q .= " <span class='small muted'>" . $pt_e(sprintf(bo_t('pt_missing'), $need - $qcount)) . "</span>";
        elseif ($qcount > $need) $q .= " <span class='small muted'>" . $pt_e(sprintf(bo_t('pt_shows_of'), $need, $qcount, $val["random_questions"] ? bo_t('pt_random') : bo_t('pt_in_order'))) . "</span>";
    } else {
        $q = "<span class='fw-semibold'>" . $qcount . "</span> <span class='small muted'>" . $pt_e(sprintf(bo_t('pt_shows_all'), $val["random_questions"] ? bo_t('pt_random_order') : "")) . "</span>";
    }
    $val["questions_html"] = $q;
    $val["q_count"] = $qcount; // sort key of the Questions column
    // settings summary
    $val["settings_html"] = "<span class='small'>"
        . $pt_e($val["label_first"]) . " = " . (float) $val["points_first"] . " &nbsp;/&nbsp; "
        . $pt_e($val["label_second"]) . " = " . (float) $val["points_second"] . " &nbsp;/&nbsp; "
        . $pt_e($val["label_fail"]) . " = " . (float) $val["points_fail"] . $pt_br
        . $pt_e(sprintf(bo_t('ptg_attempts_reveal'), $pt_n($val["max_attempts"], 'ptg_attempts'), $val["reveal_mode"] == "auto" ? bo_t('pt_reveal_auto') : bo_t('ptg_reveal_button'))) . $pt_br
        . $pt_e(sprintf(bo_t('pt_pass_time_tries'),
            (float) $val["pass_percent"],
            (int) $val["time_limit"] ? $pt_n($val["time_limit"], 'ptg_minutes') : bo_t('pt_no_limit'),
            (int) $val["max_tries"] ? sprintf(bo_t('pt_per_student'), (int) $val["max_tries"]) : bo_t('pt_no_limit'),
            (isset($val["display_mode"]) && $val["display_mode"] == "one") ? bo_t('pt_one_at_a_time') : bo_t('pt_one_page')))
        . "</span>";
    $rows[] = $val;
}

// 3. grid config
$grid = array(
    'module' => 'practice_test', 'key' => 'id_practice_test', 'rows' => $rows, 'title' => $title, 'order' => $sego,
    'subtitle' => bo_t('pt_drag_hint'),
    'columns' => array('title', 'lesson_html', 'questions_html', 'settings_html', 'status'),
    'labels' => array('title' => bo_label('title'), 'lesson_html' => bo_t('ptg_lesson'), 'questions_html' => bo_t('ptg_questions'),
                      'settings_html' => bo_label('settings'), 'status' => bo_label('status')),
    'types' => array('title' => 'link', 'lesson_html' => 'raw', 'questions_html' => 'raw', 'settings_html' => 'raw', 'status' => 'badge'),
    'links' => array('title' => 'practice_question/lists/{id}'),
    'badges' => array('status' => array(1 => array(bo_t('pt_published'), 'label-success'), 0 => array(bo_t('pt_draft'), 'label-warning'))),
    'search' => array('title', 'lesson_html', 'status'),
    'keep' => array('q_count'),
    'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => array(
        array('label' => bo_t('grid_questions'), 'url' => 'practice_question/lists/{id}', 'icon' => 'bx-list-ul'),
        array('label' => bo_t('pt_results'), 'url' => 'practice_test/results/{id}', 'icon' => 'bx-bar-chart-alt-2'),
    )),
    'add' => $write_priv, 'add_label' => bo_t('pt_add_test'),
    'bulk' => $delete_priv, 'bulk_options' => array('delete_all' => bo_t('ptg_delete_selected')), 'reorder' => $write_priv, 'export' => true,
    'empty' => bo_t('pt_no_tests'),
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// post-init: the actions column (4 links) never collapses into the adaptive "..." row (only settings / lesson may hide);
// Questions sorts by the question count; Delete gets the test text.
$pt_js = array('del' => bo_t('pt_confirm_delete_test'));
?>
<script>$(function () {
  if (!window.boGrid || !boGrid.grid) return;
  var T = <?= bo_js($pt_js) ?>, g = boGrid.grid, cfg = boGrid.cfg;
  g.beginUpdate();
  g.columnOption('settings_html', 'hidingPriority', 0);
  g.columnOption('lesson_html', 'hidingPriority', 1);
  g.columnOption('questions_html', 'calculateSortValue', 'q_count');
  g.columnOption('bo_actions', 'width', 'auto');
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
