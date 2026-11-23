<?php
// 1. privilege block
$content_name="material";
$id_content="3";
$bo_method = $this->router->method;
// index/<order> or lists/<id_classes>/<order>
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
// 2. rows: the legacy list showed the number of visitors of each subject in red next to the title ("Title (Visitor)")
$bo_rows = (isset($info) && is_array($info)) ? $info : array();
$bo_counts = array();
foreach ($this->db->query("SELECT id_material AS k, COUNT(*) AS n FROM visitor WHERE deleted = 0 GROUP BY id_material")->result_array() as $bo_c) {
    $bo_counts[(string) $bo_c['k']] = (int) $bo_c['n'];
}
foreach ($bo_rows as $bo_i => $bo_r) {
    $bo_k = (string) $bo_r['id_material'];
    $bo_rows[$bo_i]['visitors'] = isset($bo_counts[$bo_k]) ? $bo_counts[$bo_k] : 0;
}
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
// 3. parent chain: only material/lists/<id_classes> is filtered by the class kept in the session
$bo_chain = array();
$bo_id_classes = (int) $this->session->userdata("id_classes");
if ($bo_method == "lists" && $bo_id_classes > 0) {
    $bo_class = $this->fct->getonerow("classes", array("id_classes" => $bo_id_classes, "deleted" => 0));
    $bo_chain[] = array('label' => bo_t('chain_classes'), 'url' => 'classes/index');
    if (!empty($bo_class)) $bo_chain[] = array('label' => $bo_class["title"]);
}
// 4. grid config (the legacy list showed the drill-down links only to users with write_priv): drill-down links of the legacy list = units, sample tests and contributors of each subject
$grid=array(
'module'=>'material','key'=>'id_material','rows'=>$bo_rows,'title'=>$title,'order'=>$sego,
'subtitle'=>$bo_crumbs($bo_chain),
'columns'=>array('title','visitors'),
'labels'=>array('title'=>bo_label('title'),'visitors'=>bo_t('chain_visitors')),
'types'=>array('visitors'=>'number'),
'align'=>array('visitors'=>'center'),
'widths'=>array('visitors'=>130),
'search'=>array('title'),
'actions'=>array('edit'=>$write_priv,'delete'=>$delete_priv,'extra'=>($write_priv == 1 ? array(
    array('label'=>bo_t('chain_units'),'url'=>'unit/lists/{id}','icon'=>'bx-layout'),
    array('label'=>bo_t('chain_sample_tests'),'url'=>'test_simple/lists/{id}','icon'=>'bx-task'),
    array('label'=>bo_t('chain_contributors'),'url'=>'contributors/lists/{id}','icon'=>'bx-user'),
) : array())),
'add'=>$write_priv,'add_label'=>bo_t('chain_add_subject'),
'bulk'=>$delete_priv,'reorder'=>$write_priv,'export'=>true,
);
// 5. render
$this->load->view('back_office/includes/dxgrid',$grid);
// 6. session cleanup
$this->session->unset_userdata("success_message");
$this->session->unset_userdata("error_message");
