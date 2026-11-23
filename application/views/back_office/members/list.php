<?php
// Students list (DevExtreme grid, inline mode). Legacy table view kept in list_legacy.php.
// The password and personal columns are never sent to the page (see 'strip').
// 1. privilege block
$content_name = "members";
$id_content = $this->fct->getonecell("content_type", "id_content", array("name" => "members"));
list($read_priv, $write_priv, $delete_priv) = bo_priv($id_content);
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
$sego = $this->uri->segment(4);
if (!in_array($sego, array("name", "username", "access_end", "last_login", "status"), true)) $sego = "";
$today = date("Y-m-d");
$pt_e = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$pt_d = function ($d) { return (!empty($d) && strpos($d, '0000-00-00') !== 0) ? date("d/m/Y", strtotime($d)) : ''; };
// counted phrase with Arabic number agreement: <base>_1 / _2 / _few (3..10) / _many
$pt_n = function ($n, $base) { $n = (int) $n; $m = $n % 100; $k = $n == 1 ? '_1' : ($n == 2 ? '_2' : (($m >= 3 && $m <= 10) ? '_few' : '_many')); return sprintf(bo_t($base . $k), $n); };
$pt_years = $pt_n(PT_ACCESS_YEARS, 'ptg_years');

// 2. computed cells: name (+ notes), access period (start -> end, expired / not started badges)
// the hidden " — " / sort keys keep search, export and sorting readable (raw cells are searched/exported as plain text)
$rows = array();
foreach ($info as $val) {
    $val["name_html"] = $pt_e($val["name"]) . (!empty($val["notes"]) ? "<span class='d-none'> — </span><br><span class='small muted'>" . $pt_e($val["notes"]) . "</span>" : "");
    $has_end = !empty($val["access_end"]) && strpos($val["access_end"], '0000-00-00') !== 0;
    if (empty($val["access_start"]) && !$has_end) {
        $a = "<span class='small muted'>" . $pt_e(bo_t('ptg_unlimited')) . "</span>";
    } else {
        $a = "<span class='text-nowrap' style='white-space:nowrap' dir='" . (bo_rtl() ? 'rtl' : 'ltr') . "'><bdi>" . $pt_d($val["access_start"]) . "</bdi> " . (bo_rtl() ? '&larr;' : '&rarr;') . " <bdi>" . $pt_d($val["access_end"]) . "</bdi></span>";
        if ($has_end && $val["access_end"] < $today) $a .= " <span class='label label-important'>" . $pt_e(bo_t('pt_expired')) . "</span>";
        elseif (!empty($val["access_start"]) && $val["access_start"] > $today) $a .= " <span class='label label-warning'>" . $pt_e(bo_t('pt_not_started')) . "</span>";
    }
    $val["access_html"] = $a;
    // sort key of the Access Period column: end date; no end date (unlimited) sorts last
    $val["access_sort"] = $has_end ? substr($val["access_end"], 0, 10) : "9999-12-31";
    $rows[] = $val;
}

// 3. grid config
$grid = array(
    'module' => 'members', 'key' => 'id_members', 'rows' => $rows, 'title' => $title, 'order' => $sego,
    'subtitle' => bo_t('pt_members_subtitle'),
    'columns' => array('name_html', 'username', 'access_html', 'status', 'last_login'),
    'labels' => array('name_html' => bo_label('name'), 'username' => bo_label('username'), 'access_html' => bo_t('ptg_access'),
                      'status' => bo_label('status'), 'last_login' => bo_t('ptg_last_login')),
    'types' => array('name_html' => 'raw', 'access_html' => 'raw', 'status' => 'badge', 'last_login' => 'datetime'),
    'badges' => array('status' => array(1 => array(bo_t('pt_active'), 'label-success'), 0 => array(bo_t('pt_inactive'), 'label-important'))),
    'search' => array('name_html', 'username', 'status'),
    'keep' => array('access_sort', 'name'),
    'strip' => array('password', 'email', 'tel', 'code', 'id_gender', 'date_of_birth', 'image', 'address', 'title'),
    'actions' => array('edit' => $write_priv, 'delete' => $delete_priv, 'extra' => array_merge(
        array(array('label' => bo_t('pt_results'), 'url' => 'members/results/{id}', 'icon' => 'bx-bar-chart-alt-2')),
        $write_priv == 1 ? array(array('label' => bo_t('pt_renew'), 'url' => 'members/renew/{id}', 'icon' => 'bx-refresh',
                                       'confirm' => sprintf(bo_t('ptg_renew_confirm'), $pt_years))) : array()
    )),
    'add' => $write_priv, 'add_label' => bo_t('pt_add_student'),
    'bulk' => $delete_priv, 'bulk_options' => array('delete_all' => bo_t('ptg_delete_selected')),
    'reorder' => $write_priv, 'export' => true,
    'empty' => bo_t('pt_no_students'),
);
// 4. render
$this->load->view('back_office/includes/dxgrid', $grid);
// post-init: the actions column (4 links) never collapses into the adaptive "..." row (only last login / access may hide);
// Access Period sorts by its end date; Renew gets a per-row confirm naming the student + a tooltip; Delete gets the student text.
$pt_js = array(
    'renew' => sprintf(bo_t('ptg_renew_confirm_name'), '%s', $pt_years),
    'renewTitle' => sprintf(bo_t('ptg_renew_title'), $pt_years),
    'del' => bo_t('pt_confirm_delete_student'),
);
?>
<script>$(function () {
  if (!window.boGrid || !boGrid.grid) return;
  var T = <?= bo_js($pt_js) ?>, g = boGrid.grid, cfg = boGrid.cfg;
  g.beginUpdate();
  g.columnOption('last_login', 'hidingPriority', 0);
  g.columnOption('access_html', 'hidingPriority', 1);
  g.columnOption('access_html', 'calculateSortValue', 'access_sort');
  g.columnOption('bo_actions', 'width', 'auto');
  g.columnOption('bo_actions', 'cellTemplate', function (c, o) {
    var $w = boGrid.actions(o.data), d = o.data, id = encodeURIComponent(d[cfg.key]);
    $w.find('a[href*="/members/renew/"]').attr('title', T.renewTitle).off('click').on('click', function (e) {
      if (!window.confirm(T.renew.split('%s').join(d.name == null ? '' : String(d.name)))) e.preventDefault();
    });
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
