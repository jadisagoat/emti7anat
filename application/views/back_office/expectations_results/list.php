<?php
// 1. privilege block (content_type 28 = expectations results)
list($read_priv, $write_priv, $delete_priv) = bo_priv("28");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: inline mode, same columns as the legacy list (list_legacy.php); the class id is resolved
//    to expectations_class.title like the legacy view did.
$one = bo_module_label('expectations_results', 'one');
if (!bo_rtl()) $one = ucwords($one);
$grid = array(
'module' => 'expectations_results', 'key' => 'id_expectations_results', 'rows' => $info, 'title' => $title,
'order' => $this->uri->segment(4),
'columns' => array('id_expectations_class', 'results', 'rate', 'total'),
'types' => array('rate' => 'number', 'total' => 'number'),
'labels' => array('id_expectations_class' => bo_t('pl_expectations_class'), 'results' => bo_t('pl_result'),
                  'rate' => bo_t('pl_rate'), 'total' => bo_t('pl_total')),
'lookups' => array('id_expectations_class' => array('expectations_class', 'title', 'id_expectations_class')),
'align' => array('rate' => 'center', 'total' => 'center'),
'widths' => array('rate' => 120, 'total' => 120),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => sprintf(bo_t('grid_add_x'), $one),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
