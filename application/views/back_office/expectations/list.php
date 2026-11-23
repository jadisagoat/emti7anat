<?php
// 1. privilege block (content_type 26 = expectations)
list($read_priv, $write_priv, $delete_priv) = bo_priv("26");
if ($read_priv != 1) { redirect(base_url() . "back_office/home/dashboard"); }
// 2. grid config: inline mode, same columns as the legacy list (list_legacy.php); the class id is resolved
//    to expectations_class.title like the legacy view did.
$one = bo_module_label('expectations', 'one');
if (!bo_rtl()) $one = ucwords($one);
$grid = array(
'module' => 'expectations', 'key' => 'id_expectations', 'rows' => $info, 'title' => $title,
'order' => $this->uri->segment(4),
'columns' => array('id_expectations_class', 'subject', 'subject_ar', 'maximum_grade'),
'types' => array('maximum_grade' => 'number'),
'labels' => array('id_expectations_class' => bo_t('pl_expectations_class'), 'subject' => bo_t('pl_subject'),
                  'subject_ar' => bo_t('pl_subject_ar'), 'maximum_grade' => bo_t('pl_maximum_grade')),
'lookups' => array('id_expectations_class' => array('expectations_class', 'title', 'id_expectations_class')),
'align' => array('maximum_grade' => 'center'),
'widths' => array('maximum_grade' => 140),
'actions' => array('edit' => $write_priv, 'delete' => $delete_priv),
'add' => $write_priv, 'add_label' => sprintf(bo_t('grid_add_x'), $one),
'bulk' => $delete_priv, 'reorder' => $write_priv, 'export' => true,
);
// 3. render
$this->load->view('back_office/includes/dxgrid', $grid);
// 4. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
