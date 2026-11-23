<?php
// 1. privilege block
$content_name="section";
$id_content="5";
$bo_method = $this->router->method;
// index/<order> or lists/<id_unit>/<order>
$sego = ($bo_method == "lists") ? $this->uri->segment(5) : $this->uri->segment(4);
$gallery_status="0";
if($this->session->userdata("level") == 0){
$cond=array("id_user"=>$this->session->userdata("user_id"),"id_content"=>$id_content);
$read_priv=$this->fct->getonecell("priviledge","read_p",$cond);
$write_priv=$this->fct->getonecell("priviledge","write_p",$cond);
$delete_priv=$this->fct->getonecell("priviledge","delete_p",$cond);
} else {
$read_priv = 1;
$write_priv=1;
$delete_priv = 1;
}
if($read_priv != 1){ redirect(base_url()."back_office/home/dashboard"); }
if (!in_array($sego, array('title', 'sort_order'), true)) $sego = '';
// parent chain rendered as trusted HTML: each crumb isolated in <bdi> so Latin titles keep their order inside the RTL breadcrumb;
// the separator is always U+203A: it is bidi-mirrored, so the RTL breadcrumb draws it pointing left
$bo_crumbs = function ($chain) {
    if (!count($chain)) return '';
    $parts = array();
    foreach ($chain as $c) {
        $l = htmlspecialchars((string) $c['label'], ENT_QUOTES, 'UTF-8');
        $parts[] = '<bdi>' . (!empty($c['url']) ? '<a href="' . htmlspecialchars(base_url() . 'back_office/' . $c['url'], ENT_QUOTES, 'UTF-8') . '">' . $l . '</a>' : $l) . '</bdi>';
    }
    return implode(' &rsaquo; ', $parts);
};
// 2. parent chain (legacy red breadcrumb "(class / subject / unit)"): Subjects > [class] > subject > unit kept in the session
$bo_chain = array(array('label' => bo_t('chain_subjects'), 'url' => 'material/index'));
$bo_id_unit = (int) $this->session->userdata("id_unit");
$bo_unit = ($bo_id_unit > 0) ? $this->fct->getonerow("unit", array("id_unit" => $bo_id_unit)) : array();
$bo_id_material = (!empty($bo_unit) && (int) $bo_unit["id_material"] > 0) ? (int) $bo_unit["id_material"] : (int) $this->session->userdata("id_material");
$bo_mat = ($bo_id_material > 0) ? $this->fct->getonerow("material", array("id_material" => $bo_id_material)) : array();
$bo_id_classes = (int) $this->session->userdata("id_classes");
if ($bo_id_classes > 0 && !empty($bo_mat) && (int) $bo_mat["id_classes"] == $bo_id_classes) {
    $bo_class = $this->fct->getonerow("classes", array("id_classes" => $bo_id_classes, "deleted" => 0));
    if (!empty($bo_class)) $bo_chain[] = array('label' => $bo_class["title"], 'url' => 'material/lists/' . $bo_id_classes);
}
if (!empty($bo_mat)) $bo_chain[] = array('label' => $bo_mat["title"], 'url' => 'unit/lists/' . $bo_id_material);
if (!empty($bo_unit)) $bo_chain[] = array('label' => $bo_unit["title"]);
// 3. grid config (the legacy list showed the drill-down links only to users with write_priv): drill-down links of the legacy list = summary and supplementary problems of each lesson
$grid=array(
'module'=>'section','key'=>'id_section','rows'=>(isset($info) && is_array($info)) ? $info : array(),'title'=>$title,'order'=>$sego,
'subtitle'=>$bo_crumbs($bo_chain),
'columns'=>array('title','id_material','id_unit'),
'labels'=>array('title'=>bo_label('title'),'id_material'=>bo_label('id_material'),'id_unit'=>bo_label('id_unit')),
'lookups'=>array('id_material'=>array('material','title','id_material'),'id_unit'=>array('unit','title','id_unit')),
'actions'=>array('edit'=>$write_priv,'delete'=>$delete_priv,'extra'=>($write_priv == 1 ? array(
    array('label'=>bo_t('chain_summary'),'url'=>'summary/lists/{id}','icon'=>'bx-file'),
    array('label'=>bo_t('chain_supp_problems'),'url'=>'questions_from_exams/lists/{id}','icon'=>'bx-help-circle'),
) : array())),
'add'=>$write_priv,'add_label'=>bo_t('chain_add_lesson'),
'bulk'=>$delete_priv,'reorder'=>$write_priv,'export'=>true,
);
// 4. render
$this->load->view('back_office/includes/dxgrid',$grid);
// 5. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
