<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {

    public function index()
    {
        $pages="homepage";
        $data['dataviews']=$this->fct->updateallviews($pages);

        $cond=array('admin_id'=>2);
        $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
        $data['menuactive'] ="home";
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");
        $data['banner']=$this->fct->getAll("banner","title");

        $cond=array('id_general_secondary'=>1);
        $data['info'] = $this->fct->getonerow("general_secondary",$cond);
        $data["content"] ="home";
        $this->load->view('template',$data);
    }

    public function officialsession($official_exams_init=0)
    {


        //$cond=array('ip'=>$_SERVER['REMOTE_ADDR'],'id_class'=>$official_exams_init,'date1'=>date("Y-m-d"));
        $q="SELECT * FROM `visitor` WHERE ip='".$_SERVER['REMOTE_ADDR']."' and date1='".date("Y-m-d")."' AND id_class =$official_exams_init " ;
        $query = $this->db->query($q);
        $number=$query->num_rows();
        if($number=='0')
        {
            $_data["ip"]=$_SERVER['REMOTE_ADDR'];
            $_data["id_class"]=$official_exams_init;
            $_data["date1"]=date("Y-m-d");
            $_data["created_date"]=date("Y-m-d h:i:s");
            $this->db->insert('visitor',$_data);
        }



        $pages="officialsession";
        $data['dataviews']=$this->fct->updateallviews($pages);

        $cond=array('admin_id'=>2);
        $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
        $data['menuactive'] ="Official Exams";
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");
        $data['banner']=$this->fct->getAll("banner","title");

        $cond=array('id_general_secondary'=>1);
        $data['info'] = $this->fct->getonerow("general_secondary",$cond);

        $cond=array('id_classes'=>$official_exams_init);
        $data['official_exams_init']=$this->fct->getAll_cond('official_exams_init',"sort_order",$cond);
        $data["id_classes"] =$official_exams_init;

        $data["content"] ="official_exams_init";
        $this->load->view('template',$data);
    }

    public function unit()
    {
        $data["id_classes"]=(int)$this->input->post("id_classes");
        $data["id_material"]=(int)$this->input->post("id_material");

        /*
        $_data["ip"]=$_SERVER['REMOTE_ADDR'];
        $_data["id_material"]=$data["id_material"];
        $_data["created_date"]=date("Y-m-d h:i:s");
        $this->db->insert('visitor',$_data);
        */
/////////////////////
        $q="SELECT * FROM `visitor` WHERE ip='".$_SERVER['REMOTE_ADDR']."' and date1='".date("Y-m-d")."' AND id_material ='".$data["id_material"]."' " ;
        $query = $this->db->query($q);
        $number=$query->num_rows();
        if($number=='0')
        {
            $_data["ip"]=$_SERVER['REMOTE_ADDR'];
            $_data["id_material"]=$data["id_material"];
            $_data["date1"]=date("Y-m-d");
            $_data["created_date"]=date("Y-m-d h:i:s");
            $this->db->insert('visitor',$_data);
        }



///////////////////////////


//'id_classes'=>$data["id_classes"],
        $cond=array('id_material'=>$data["id_material"]);
        $unit=$this->fct->getAll_cond('unit',"sort_order",$cond);
//$language=$this->fct->getAll('language',"title");

        // "Simulations Class" of the clicked class (admin: Classes > edit), shown above the Unit / Chapter selects
        $sim_html='';
        $sim=(string)$this->fct->getonecell('material','simulations_class',array('id_material'=>$data["id_material"]));
        if(trim($sim)!==''){
            $sim=preg_replace('/<a(?=[[:space:]])(?![^>]*target=)/i','<a target="_blank" rel="noopener"',$sim);
            $sim_html='<div class="col-md-12"><section class="sim-card" aria-labelledby="sim-title">
<div class="sim-head"><span class="sim-icon" aria-hidden="true"></span><h4 id="sim-title">Simulations</h4></div>
<div class="sim-body">'.$sim.'</div>
</section></div>';
        }

        echo '<div class="col-md-12" style="padding:0">
<input type="hidden" value="'.$data["id_classes"].'" id="id_classes" >
<input type="hidden" value="'.$data["id_material"].'" id="id_material" >
'.$sim_html.'
<div class="col-md-5">
<div class="styled-select2">
<select name="id_unit" id="id_unit" onchange="ajaxsection(this.value)">
<option value="all">Unit</option>';
        foreach($unit as $unit1){ echo '<option value="'.$unit1['id_unit'].'">'.$unit1['title'].'</option>';}
        echo '</select>
</div>	
</div>
<div class="col-md-5">			  
<div class="styled-select2" >
<select name="id_section" id="id_section" onchange="ajaxdata(this.value)" >
<option value="all">Chapter</option>
</select>
</div>
</div>
<div class="col-md-12">
<div id="result6"></div>
</div>
';
    }


    /* testsimple*/
    public function testsimple()
    {


        $language=$this->fct->getAll('language',"title");




        echo '<div class="col-md-12" style="padding:0">
<div class="col-md-5">			  
<div class="styled-select2" >
<select name="id_material" id="id_material" onchange="ajaxsubject_class_data(this.value)" >
<option value="all">Subject</option>';
        $material=$this->fct->getAll('material',"sort_order");
        foreach($material as $material1){ echo '<option value="'.$material1['id_material'].'">'.$material1['title'].'</option>';}
        echo '</select>
</div>
</div>
<div class="col-md-12">
<div id="result6"></div>
</div>
';
    }

    public function testexpectations()
    {
        $class=$this->fct->getAll("expectations_class","title");
        echo '<div class="col-md-12" style="padding:0">
<div class="col-md-5">
<div class="styled-select2">
<select name="id_expectations_class" id="id_expectations_class" onchange="ajaxexpectations_class(this.value)">
<option value="all">Class</option>';
        foreach($class as $class1){ echo '<option value="'.$class1['id_expectations_class'].'">'.$class1['title'].'</option>';}
        echo '</select>
</div>	
</div>
<br clear="all">
<div class="col-md-12">
<div id="result6"></div>
</div>
';
    }
    public function ajaxexpectations_class()
    {
        $_data["id_expectations_class"]=$this->input->post("id_classes");
        $cond=array('id_expectations_class'=>$_data["id_expectations_class"]);

        $expectations=$this->fct->getAll_cond('expectations',"sort_order",$cond);
        $expectations_results=$this->fct->getAll_cond('expectations_results',"sort_order",$cond);

        $_data["expectations"]=$expectations;
        $_data["expectations_results"]=$expectations_results;
        $_data["id_expectations_class"]= $_data["id_expectations_class"];

        $this->load->view('ajaxexpectations_class',$_data);

        //  echo $this->db->last_query();
        //  exit();
        /*
        $count= count($expectations)+3;
        echo '<table class="table table-sm table-dark">
          <thead><tr><th scope="col">Section</th>
          <th style="text-align:center" colspan="'.$count.'">'.$this->fct->getonecell('expectations_class','title',$cond).'</th>
          </tr></thead>';
                $k=0;
                $var0='';
                $var1='';
                $var2='';$var3='';$sum=0;
                foreach($expectations as $expectations1) {
                    //if ($k = 0) {
                        $var0.='<td>'.$expectations1['subject'].'</td>';
                        $var1.='<td>'.$expectations1['subject_ar'].'</td>';
                        $sum+=$expectations1['maximum_grade'];
                        $var2.='<td>'.$expectations1['maximum_grade'].'<input type="hidden" id="'.$expectations1['id_expectations'].'" value="'.$expectations1['maximum_grade'].'"></td>';
                        $var3.='<td><input class="txt" name="expgrade" type="text" id="exp'.$expectations1['id_expectations'].'"  placeholder="Expectations Note"></td>';
                    //}
                }


                echo '<tr>'.$var0.'<td>المجموع</td><td>المعدل</td><td>النتيجة</td></tr>';
                echo '<tr>'.$var1.'<td></td><td></td></td></td></tr>';
                echo '<tr>'.$var2.'<td>'.$sum.'<input type="hidden" id="sum" value="'.$sum.'"></td><td>20</td><td></td></tr>';
                echo '<tr>'.$var3.'<td><span  id="sumtotal"></span><input type="hidden" id="sumtotalh" ></td><td id="resulttotal"></td><td></td></tr>';

                echo '</tbody></table>';


                echo '<table width="300px" border="1" style="border-collapse:collapse;background-color:#E8DCFF">
            <tr>
                <td width="40px">1</td>
                <td>Butter</td>
                <td><input class="txt" type="text" name="txt"/></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Cheese</td>
                <td><input class="txt" type="text" name="txt"/></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Eggs</td>
                <td><input class="txt" type="text" name="txt"/></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Milk</td>
                <td><input class="txt" type="text" name="txt"/></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Bread</td>
                <td><input class="txt" type="text" name="txt"/></td>
            </tr>
            <tr>
                <td>6</td>
                <td>Soap</td>
                <td><input class="txt" type="text" name="txt"/></td>
            </tr>
            <tr id="summation">
                <td>&nbsp;</td>
                <td align="right">Sum :</td>
                <td align="center"><span id="sum">0</span></td>
            </tr>
        </table>';
                */
    }


    /*
    public function ajaxexpectations_class()
        {
            $data["id_expectations_class"]=$this->input->post("id_expectations_class");
            $cond=array('id_expectations_class'=>$data["id_expectations_class"]);

            $expectations=$this->fct->getAll_cond('expectations',"sort_order",$cond);
            $expectations_results=$expectations=$this->fct->getAll_cond('expectations_results',"sort_order",$cond);

    $count= count($expectations);
     echo '<table class="table table-sm table-dark">
      <thead><tr><th scope="col">Section</th><th style="text-align:center" colspan="'.$count.'">'.$this->fct->getonecell('expectations_class','title',$cond).'</th></tr></thead>';

     $k=0;
            $var0='';
            $var1='';
            $var2='';$var3='';$sum=0;
     foreach($expectations as $expectations1) {
         if ($k = 0) {
             $var0.='<td>'.$expectations1['subject'].'</td>';
             $var1.='<td>'.$expectations1['subject_ar'].'</td>';
             $sum+=$expectations1['maximum_grade'];
             $var2.='<td>'.$expectations1['maximum_grade'].'<input type="hidden" id="'.$expectations1['id_expectations'].'" value="'.$expectations1['maximum_grade'].'"></td>';
             $var3.='<td><input type="text" id="exp'.$expectations1['id_expectations'].'"  placeholder="Expectations Note"></td>';
         }
    echo '<td>'.$var0.'<td>المجموع</td><td>المعدل</td></td>النتيجة</td></tr>';
    echo '<tr>'.$var1.'<td></td><td></td></td></td></tr>';
    echo '<tr>'.$var2.'<td>'.$sum.'<input type="hidden" id="sum" value="'.$sum.'"></td><td>20</td><td></td></tr>';
    echo '<tr>'.$var3.'<td>'.$sum.'<input type="hidden" id="sumtotal" value="'.$sum.'"></td><td id="">20</td><td></td></tr>';
     }

    echo '</tbody></table>';
        }
    */


    public function classs()
    {
        $class=$this->fct->getAll("classes","title");
        echo '<select name="id_class" id="id_class" onchange="ajaxsubject_class(this.value)">
<option value="all">Class</option>';
        foreach($class as $class1){ echo '<option value="'.$class1['id_classes'].'">'.$class1['title'].'</option>';}
        echo '</select>';
    }

    public function ajaxsubject_class()
    {
        $data["id_classes"]=$this->input->post("id_classes");
        /*
        $cond=array('id_classes'=>$data["id_classes"]);
        $material=$this->fct->getAll_cond('material',"sort_order",$cond);
        */

        $material=$this->fct->getAll('material',"sort_order");
        echo '<div class="styled-select2">
<select name="id_material" id="id_material" onchange="ajaxsubject_class_data(this.value)">
<option value="all">Subject</option>';
        foreach($material as $material1){ echo '<option value="'.$material1['id_material'].'">'.$material1['title'].'</option>';}
        echo '</select>';
    }
    /* testsimple*/


    public function section()
    {

        $data["id_classes"]=$this->input->post("id_classes");
        $data["id_material"]=$this->input->post("id_material");
        $data["id_unit"]=$this->input->post("id_unit");
//'id_classes'=>$data["id_classes"],
        $cond=array('id_unit'=>$data["id_unit"],'id_material'=>$data["id_material"]);
        $section=$this->fct->getAll_cond('section',"sort_order",$cond);
        echo '<div class="styled-select2">
<select name="id_section" id="id_section" onchange="ajaxdata(this.value)">
<option value="all">Chapter</option>';
        foreach($section as $section1){ echo '<option value="'.$section1['id_section'].'">'.$section1['title'].'</option>';}
        echo '</select>';

    }

    public function data()
    {

        $data["id_classes"]=$this->input->post("id_classes");
        $data["id_material"]=$this->input->post("id_material");
        $data["id_unit"]=$this->input->post("id_unit");
        $data["id_section"]=$this->input->post("id_section");
        $data["id_language"]=0 ;//$this->input->post("id_language");

        $alval=0;
//,'id_classes'=>$data["id_classes"]
        if($data["id_language"])
            $cond=array('id_language'=>$data["id_language"],'id_section'=>$data["id_section"],'id_unit'=>$data["id_unit"],'id_material'=>$data["id_material"]);
        else
            $cond=array('id_section'=>$data["id_section"],'id_unit'=>$data["id_unit"],'id_material'=>$data["id_material"]);
// summary
        $section=$this->fct->getAll_cond('summary',"sort_order",$cond);
        foreach($section as $section1){
            $alval++;
            if(is_file("uploads/summary/".$section1['file_pdf'])){
                echo '<div class="title" ><a href="'.base_url().'uploads/summary/'.$section1['file_pdf'].'"  target="_blank" >Summary</a></div>';
            }else
                echo '<div class="title" >'.$section1['title'].'</div>';
            echo '<div style="margin: 10px 0 40px;color:#000">'.$section1['description'].'</div>';

        }


// Practice test (interactive MCQ): published tests of this lesson that have published questions.
// Students must be logged in (members with a valid access period) to start one.
        $practice=$this->fct->getAll_cond('practice_test',"sort_order",array('id_section'=>$data["id_section"],'status'=>1));
        if(count($practice)){
            $member=$this->_member();
            $pt_rows='';
            foreach($practice as $pt){
                $qcount=count($this->fct->getAll_cond('practice_question',"sort_order",array('id_practice_test'=>$pt['id_practice_test'],'status'=>1)));
                if(!$qcount) continue;
                $n=((int)$pt['questions_per_test']>0 && (int)$pt['questions_per_test']<$qcount) ? (int)$pt['questions_per_test'] : $qcount;
                $alval++;
                $pt_rows.='<li><span class="pt-name">'.htmlspecialchars($pt['title']).'</span> ';
                if($member) $pt_rows.='<a href="javascript:void(0)" class="pt-start" onclick="ajaxpractice('.(int)$pt['id_practice_test'].')">Start test ('.$n.' questions)</a>';
                else $pt_rows.='<a href="'.base_url().'login">Login to start ('.$n.' questions)</a>';
                $pt_rows.='</li>';
            }
            if($pt_rows!=='') echo '<div class="title" >Practice test</div><ul class="ulli pt-list" >'.$pt_rows.'</ul>';
        }

//Supplementary Problems
        if($data["id_language"])
            $cond=array('id_language'=>$data["id_language"],'id_section'=>$data["id_section"],'id_unit'=>$data["id_unit"],'id_material'=>$data["id_material"]);
        else
            $cond=array('id_section'=>$data["id_section"],'id_unit'=>$data["id_unit"],'id_material'=>$data["id_material"]);
        $questions_from_exams=$this->fct->getAll_cond('questions_from_exams',"sort_order",$cond);
        if(count($questions_from_exams)){ echo '<div class="title" >Applications & Problems</div><ul class="ulli" >'; }
        foreach($questions_from_exams as $questions_from_exams1){ $alval++;
if(is_file("uploads/questions_from_exams/".$questions_from_exams1['question'])||
    is_file("uploads/questions_from_exams/".$questions_from_exams1['answer'])){
                //'.$questions_from_exams1['title'].':

 if($questions_from_exams1['question']&&$questions_from_exams1['answer'])
echo '<li>  
  <a href="'.base_url().'uploads/questions_from_exams/'.$questions_from_exams1['question'].'" target="_blank" >Question</a> / 
  <a href="'.base_url().'uploads/questions_from_exams/'.$questions_from_exams1['answer'].'" target="_blank" >Answer</a>
 </li>';
 else if($questions_from_exams1['question'])
     echo '<li>  
  <a href="'.base_url().'uploads/questions_from_exams/'.$questions_from_exams1['question'].'" target="_blank" >Question</a> / 
    Answer (Not available)
 </li>';
 else
     echo '<li>  
   Question(Not available) / 
  <a href="'.base_url().'uploads/questions_from_exams/'.$questions_from_exams1['answer'].'" target="_blank" >Answer</a>
 </li>';




            }else
                echo '<li>'.$questions_from_exams1['title'].'</li>';}
        if(count($questions_from_exams)){ echo '</ul>
'; }
        if(count($questions_from_exams))
            echo '<div style="margin: 10px 0 40px;color:#000">'.$questions_from_exams1['description'].'</div>';

//session problem
        if($data["id_language"])
            $cond=array('id_section'=>$data["id_section"],'id_language'=>$data["id_language"],'id_material'=>$data["id_material"]);
        else
            $cond=array('id_section'=>$data["id_section"],'id_material'=>$data["id_material"]);

        $official_exams=$this->fct->getAll_cond('official_exams',"sort_order",$cond);
        if(count($official_exams)){ echo '<div class="title" >Session problems</div><ul class="ulli" >'; }
        foreach($official_exams as $official_exams1){$alval++;
            if(is_file("uploads/official_exams/".$official_exams1['question'])){
                /*
                 echo '<li>
                 '. $this->fct->getonecell("year","title",array("id_year"=>$official_exams1["id_year"])).' '.$this->fct->getonecell("cycle","title",array("id_cycle"=>$official_exams1["id_cycle"])).':
                */
                echo '<li><a href="'.base_url().'uploads/official_exams/'.$official_exams1['question'].'" target="_blank" >Question </a> / 
  <a href="'.base_url().'uploads/official_exams/'.$official_exams1['answer'].'" target="_blank" >Answer</a>
 </li>';
            }else
                echo '<li>'.$official_exams1['title'].'</li>';}
        if(count($official_exams)){ echo '</ul>'; }

//contributors
        if($data["id_language"])
            $cond=array('id_language'=>$data["id_language"],'id_material'=>$data["id_material"]);
        else
            $cond=array('id_material'=>$data["id_material"]);
        $contributors=$this->fct->getAll_cond('contributors',"sort_order",$cond);
        if(count($contributors)){ echo '<div class="title" >Contributors</div><div class="contri">'; }
        foreach($contributors as $contributors1){$alval++;
            echo '<div class="col-md-5"><div class="linen" style="margin-bottom:10px;">';

            echo '<div style="min-height:100px;"><span style="font-weight:bold">'.$contributors1['title'].'</span><br>';
            if(is_file("uploads/contributors/".$contributors1['image'])){
                ?>
                <img width="100" height="100" src="<?=base_url();?>uploads/contributors/100x100/<?=$contributors1['image']?>"  style=" padding-bottom:10px;margin-left:10px;" align="right" />
            <? }
            echo  $contributors1['cv'].'</div>';

            if(is_file("uploads/contributors/".$contributors1['resume'])){
                echo  '<a href="'.base_url().'uploads/contributors/'.$contributors1['resume'].'" target="_blank" >Resume </a> ';
            }

            echo  '</div></div>';
        }
        if(count($contributors)){ echo '</div>'; }

        if($alval==0){  echo "<div style='margin-top:10px'>No Data ... </div>";  }



    }



    public function datasubjectclass()
    {

        $data["id_class"]=$this->input->post("id_class");
        $data["id_material"]=$this->input->post("id_material");

        $_data["ip"]=$_SERVER['REMOTE_ADDR'];
        $_data["title"]='test simple';
        $_data["id_material"]=$data["id_material"];
        $_data["created_date"]=date("Y-m-d h:i:s");
        $this->db->insert('visitor',$_data);


        $data["id_language"]=0 ;//$this->input->post("id_language");
        if($data["id_language"])
            $cond=array('id_language'=>$data["id_language"],'id_class'=>$data["id_class"],'id_material'=>$data["id_material"]);
        else
            $cond=array('id_class'=>$data["id_class"],'id_material'=>$data["id_material"]);

//Supplementary Problems
        $test_simple=$this->fct->getAll_cond('test_simple',"sort_order",$cond);

        if(count($test_simple)){ echo '<div class="title" >Supplementary Problems</div><ul class="ulli" >'; }
        foreach($test_simple as $test_simple1){
            if(is_file("uploads/test_simple/".$test_simple1['simple_exam'])){
                //'.$questions_from_exams1['title'].':
                echo '<li> <a href="'.base_url().'uploads/test_simple/'.$test_simple1['simple_exam'].'" target="_blank" >Sample Exam</a> / <a href="'.base_url().'uploads/test_simple/'.$test_simple1['solution'].'" target="_blank" >Solution</a>
 </li>';
            }else
                echo '<li>'.$test_simple1['title'].'</li>';}
        if(count($test_simple)){ echo '</ul>'; }
        else { echo "<div style='margin-top:10px'>No Data ... </div>"; }


    }


    public function about()
    {
        $pages="aboutus";
        $data['dataviews']=$this->fct->updateallviews($pages);
////////////////////////////////////////

        $cond=array('admin_id'=>2);
        $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
        $data['menuactive'] ="about";
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");
        $cond=array('id_about_us'=>1);
        $data['info'] = $this->fct->getonerow("about_us",$cond);

        $data["content"] ="aboutus";
        $this->load->view('template',$data);
    }

    public function news()
    {
        $pages="news";
        $data['dataviews']=$this->fct->updateallviews($pages);
////////////////////////////////////////

        $cond=array('admin_id'=>2);
        $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
        $data['menuactive'] ="news";
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");

        $data['info'] = $this->fct->getAll("news","title");

        $data["content"] ="news";
        $this->load->view('template',$data);
    }

    public function services()
    {
        $pages="services";
        $data['dataviews']=$this->fct->updateallviews($pages);
////////////////////////////////////////

        $cond=array('admin_id'=>2);
        $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
        $data['menuactive'] ="services";
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");
        $cond=array('id_services'=>1);
        $data['info'] = $this->fct->getonerow("services",$cond);

        $data["content"] ="services";
        $this->load->view('template',$data);
    }



    public function contactus()
    {
        $pages="contactus";
        $data['dataviews']=$this->fct->updateallviews($pages);
////////////////////////////////////////

        $cond=array('admin_id'=>2);
        $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
        $data['menuactive'] ="contactus";
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");
        $cond=array('id_contact_us'=>1);
        $data['info'] = $this->fct->getonerow("contact_us",$cond);

        $data["content"] ="contactus";
        $this->load->view('template',$data);
    }

    public function contactinfo()
    {
        $this->form_validation->set_rules("name", "name", "trim|required");
        $this->form_validation->set_rules("subject", "subject", "trim|required");
        $this->form_validation->set_rules("email", "email", "trim|email|required");
        $this->form_validation->set_rules("comments", "comments", "trim|required");

        if ($this->form_validation->run() == FALSE){
            echo "Error ";
        }
        else{
            $_data["name"]=$this->input->post("name");
            $_data["subject"]=$this->input->post("subject");
            $_data["email"]=$this->input->post("email");
            $_data["comments"]=$this->input->post("comments");

            $_data["created_date"]=date("Y-m-d h:i:s");
            $this->db->insert('contactform',$_data);
            $this->session->set_userdata("success_message","Information was inserted successfully");
            echo "<div class='titledata' >Thanks for your contact</div>";
        }
    }


    public function orderonline()
    {
        $pages="orderonline";
        $data['dataviews']=$this->fct->updateallviews($pages);
////////////////////////////////////////

        $cond=array('admin_id'=>2);
        $data['generalinfo'] = $this->fct->getonerow("admin",$cond);
        $data['menuactive'] ="orderonline";
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");
        $cond=array('id_orderonline_us'=>1);
        $data['info'] = $this->fct->getonerow("orderonline_us",$cond);

        $data["content"] ="orderonline";
        $this->load->view('template',$data);
    }

    public function orderonlineinfo()
    {
        $this->form_validation->set_rules("id_language", "id_language", "trim|required");
        $this->form_validation->set_rules("name", "name", "trim|required");
        $this->form_validation->set_rules("id_books[]", "id_books", "trim|required");
        $this->form_validation->set_rules("email", "email", "trim|email|required");
        $this->form_validation->set_rules("comments", "comments", "trim");
        $this->form_validation->set_rules("address", "address", "trim|required");
        $this->form_validation->set_rules("phone", "phone", "trim|required");

        if ($this->form_validation->run() == FALSE){
            echo "Error ";
        }
        else{
            $_data["name"]=$this->input->post("name");
            $_data["id_language"]=$this->input->post("id_language");
            $_data["email"]=$this->input->post("email");
            $_data["comments"]=$this->input->post("comments");
            $_data["address"]=$this->input->post("address");
            $_data["phone"]=$this->input->post("phone");

            $ppost=$this->input->post("id_books");
            $books=implode(',',$ppost);
            $_data["id_books"]="$books";

            $startDate = time();
            $new_time=date('Y-m-d h:i:s', strtotime('+3 hours', $startDate));
            $_data["created_date"]=$new_time; //date("Y-m-d h:i:s", strtotime('+2 hours', $now));
            $this->db->insert('orderonline',$_data);

            $this->session->set_userdata("success_message","Information was inserted successfully");
            echo "<div class='titledata' >Thanks for your order on line</div>";
        }
    }


    public function books()
    {
        $data["id_language"]=$this->input->post("id_language");
        if($data["id_language"])
            $cond=array('id_language'=>$data["id_language"]);
        if($data["id_language"])
            $books=$this->fct->getAll_cond('books',"sort_order",$cond);
        else
            $books=$this->fct->getAll('books',"sort_order");
        //<option value="">Select a Book</option>
        //echo '<select class="js-example-basic-multiple" name="id_books" multiple="multiple" id="id_books" onchange="infobooks(this.value)">';
        //echo '<select class="js-example-basic-multiple" name="id_books" multiple="multiple" id="id_books" onchange="infobooks(this.value)">';

        foreach($books as $books1){ echo '<option value="'.$books1['id_books'].'">'.$books1['title'].'</option>';}
        //echo '</select>';
    }

    public function infobooks()
    {
        $data["id_books"]=$this->input->post("id_books");
        $books=implode(',',$data["id_books"]);
        //$cond=array('id_books'=>$books);

        $query='select * from  books where id_books in ('.$books.') ';
        $books=$this->fct->search($query);


        foreach($books as $books1){

            $classaa="col-md-6";
            $sty1="height: 262px;width:auto !important;";
            $caption="padding-bottom:10px !important;font-weight:bold;";
            if(count($books)==1){
                $classaa="col-md-12";
                $sty1='height: 262px; margin:0 auto !important;width:auto !important;';
            }


            if($books1['pdf'])
                echo '<div class="'.$classaa.'"><a  href="'.base_url().'uploads/books/'.$books1['pdf'].'" target="_blank" style="font-size:16px;color:#000" >
<img align="center" style="'.$sty1.'" src="'.base_url().'uploads/books/'.$books1['photo'].'" >
</a><div style="'.$caption.'">'.$books1["title"].'</div></div>';
            else
                echo '<div align="center" class="'.$classaa.'"><img style="'.$sty1.'" src="'.base_url().'uploads/books/'.$books1['photo'].'" >
                  <div  style="'.$caption.'">'.$books1["title"].'</div></div>';
        }
        echo '<br clear="all">';

        /*
        if($books['photo'])
        echo '<img src="'.base_url().'uploads/books/'.$books['photo'].'" ><br clear=all>';
        if($books['pdf'])
        echo '<br><a  href="'.base_url().'uploads/books/'.$books['pdf'].'" target="_blank" style="font-size:16px;color:#000" >Intro</a>
<br clear="all"><br clear="all">';
        */
    }


    /* ===================== Student (members) login ===================== */

    // Logged-in student row when the account is active and inside its access period, else FALSE.
    private function _member()
    {
        $id=(int)$this->session->userdata('member_id');
        if(!$id) return FALSE;
        $m=$this->fct->getonerecord('members',array('id_members'=>$id));
        if(!$m || (int)$m['status']!==1) return FALSE;
        $today=date('Y-m-d');
        if(!empty($m['access_start']) && $m['access_start']>$today) return FALSE;
        if(!empty($m['access_end']) && $m['access_end']<$today) return FALSE;
        return $m;
    }

    // New passwords are bcrypt hashes; profiles created before 2026-10 may still hold plain text.
    private function _password_ok($plain,$stored)
    {
        $stored=(string)$stored;
        if($stored==='') return FALSE;
        if(substr($stored,0,4)==='$2y$') return password_verify($plain,$stored);
        return hash_equals($stored,(string)$plain);
    }

    private function _page_data($menuactive)
    {
        $data['dataviews']=$this->fct->updateallviews($menuactive);
        $data['generalinfo']=$this->fct->getonerow("admin",array('admin_id'=>2));
        $data['menuactive']=$menuactive;
        $data['tab']=0;
        $data['class']=$this->fct->getAll("classes","title");
        return $data;
    }

    public function login()
    {
        // a logged-in student lands on the profile instead of the welcome box
        if($this->_member()) redirect(base_url().'profile');
        $data=$this->_page_data('login');
        $data['member']=$this->_member();
        // logged in but inactive / outside the access period: explain instead of showing the form
        $data['blocked']=FALSE;
        if(!$data['member'] && $this->session->userdata('member_id')){
            $data['blocked']=$this->fct->getonerecord('members',array('id_members'=>(int)$this->session->userdata('member_id')));
        }
        $data["content"]="member_login";
        $this->load->view('template',$data);
    }

    public function login_submit()
    {
        $u=trim((string)$this->input->post('username'));
        $p=(string)$this->input->post('password');
        $msg='';
        $m=FALSE;
        if($u==='' || $p===''){
            $msg='Please enter your username and password.';
        } else {
            $m=$this->fct->getonerecord('members',array('username'=>$u));
            if(!$m || !$this->_password_ok($p,$m['password'])){
                $msg='Wrong username or password.'; $m=FALSE;
            } elseif((int)$m['status']!==1){
                $msg='Your account is not active yet. Please contact us.'; $m=FALSE;
            } elseif(!empty($m['access_start']) && $m['access_start']>date('Y-m-d')){
                $msg='Your access starts on '.date('d/m/Y',strtotime($m['access_start'])).'.'; $m=FALSE;
            } elseif(!empty($m['access_end']) && $m['access_end']<date('Y-m-d')){
                $msg='Your access expired on '.date('d/m/Y',strtotime($m['access_end'])).'. Please contact us to renew it.'; $m=FALSE;
            }
        }
        if($m){
            $this->session->set_userdata(array('member_id'=>(int)$m['id_members'],'member_name'=>$m['name']));
            $this->session->unset_userdata('pt');
            $this->db->where('id_members',(int)$m['id_members']);
            $this->db->update('members',array('last_login'=>date('Y-m-d H:i:s')));
            redirect(base_url());
        }
        $this->session->set_userdata('member_error',$msg);
        redirect(base_url().'login');
    }

    // Student profile (route /profile): summary counters, one row per test, full attempt history with answers.
    public function profile()
    {
        $member=$this->_member();
        if(!$member) redirect(base_url().'login');   // the login page explains an inactive or expired account
        $data=$this->_page_data('profile');
        $data['member']=$member;

        $this->db->select('r.*, t.title AS test_title, t.max_tries, t.status AS test_status, t.deleted AS test_deleted, s.title AS section_title');
        $this->db->from('practice_result r');
        $this->db->join('practice_test t','t.id_practice_test = r.id_practice_test','left');
        $this->db->join('section s','s.id_section = r.id_section','left');
        $this->db->where('r.id_members',(int)$member['id_members']);
        $this->db->where('r.deleted',0);
        $this->db->order_by('r.created_date','desc');
        $this->db->order_by('r.id_practice_result','desc');
        $attempts=$this->db->get()->result_array();

        $stats=array('attempts'=>count($attempts),'finished'=>0,'passed'=>0,'failed'=>0,'unfinished'=>0,'best'=>0,'average'=>0);
        $sum=0; $tests=array();
        foreach($attempts as $r){   // newest first
            $fin=((int)$r['status']===1);
            $pct=(float)$r['percent'];
            if($fin){
                $stats['finished']++; $sum+=$pct;
                if($r['passed']) $stats['passed']++; else $stats['failed']++;
                if($pct>$stats['best']) $stats['best']=$pct;
            } else {
                $stats['unfinished']++;
            }
            $k=(int)$r['id_practice_test'];
            if(!isset($tests[$k])){
                $max=(int)$r['max_tries'];
                $tests[$k]=array(
                    'title'=>$r['test_title']!==null ? $r['test_title'] : '(test removed)',
                    'lesson'=>(string)$r['section_title'],
                    'used'=>$this->_pt_tries($member['id_members'],$k),
                    'max_tries'=>$max,
                    'best'=>null, 'last'=>null, 'ever_passed'=>false,
                    'last_date'=>$r['created_date'],
                    'published'=>($r['test_title']!==null && (int)$r['test_status']===1 && (int)$r['test_deleted']===0),
                );
                $tests[$k]['tries_left']=$max>0 ? max(0,$max-$tests[$k]['used']) : -1;   // -1 = unlimited
                $tests[$k]['available']=$tests[$k]['published'] && $tests[$k]['tries_left']!==0;
            }
            if($fin){
                if($tests[$k]['last']===null) $tests[$k]['last']=array('percent'=>$pct,'passed'=>(int)$r['passed']===1);
                if($tests[$k]['best']===null || $pct>$tests[$k]['best']) $tests[$k]['best']=$pct;
                if($r['passed']) $tests[$k]['ever_passed']=true;
            }
        }
        $stats['average']=$stats['finished'] ? round($sum/$stats['finished']) : 0;

        $data['attempts']=$attempts;
        $data['stats']=$stats;
        $data['tests']=array_values($tests);
        $data["content"]="member_profile";
        $this->load->view('template',$data);
    }

    public function logout()
    {
        $this->session->unset_userdata('member_id');
        $this->session->unset_userdata('member_name');
        $this->session->unset_userdata('pt');
        redirect(base_url());
    }


    /* ===================== Practice test (interactive MCQ) ===================== */

    // Tries already used by a student on a test (started counts, finished or not).
    private function _pt_tries($id_members,$id_practice_test)
    {
        $this->db->where(array('id_members'=>(int)$id_members,'id_practice_test'=>(int)$id_practice_test,'deleted'=>0));
        return (int)$this->db->count_all_results('practice_result');
    }

    // Published tests that have at least one published question, with the number of
    // questions served, the lesson title when linked, and the tries used by the student.
    private function _practice_catalog($member)
    {
        $out=array();
        $tests=$this->fct->getAll_cond('practice_test',"sort_order",array('status'=>1));
        foreach($tests as $t){
            $qcount=count($this->fct->getAll_cond('practice_question',"sort_order",array('id_practice_test'=>$t['id_practice_test'],'status'=>1)));
            if(!$qcount) continue;
            $t['question_count']=((int)$t['questions_per_test']>0 && (int)$t['questions_per_test']<$qcount) ? (int)$t['questions_per_test'] : $qcount;
            $t['lesson']=$t['id_section'] ? (string)$this->fct->getonecell('section','title',array('id_section'=>(int)$t['id_section'])) : '';
            $t['tries_used']=$member ? $this->_pt_tries($member['id_members'],$t['id_practice_test']) : 0;
            $t['tries_left']=((int)$t['max_tries']>0) ? max(0,(int)$t['max_tries']-$t['tries_used']) : -1; // -1 = unlimited
            $out[]=$t;
        }
        return $out;
    }

    // Standalone "Practice Tests" page (route /tests): same layout as the home page.
    public function tests()
    {
        $data=$this->_page_data('tests');
        $data['member']=$this->_member();
        $data['tests']=$this->_practice_catalog($data['member']);
        $data["content"]="practice_page";
        $this->load->view('template',$data);
    }

    // AJAX fragment with the list of tests (sidebar item "Practice tests").
    public function practice_list()
    {
        $data['member']=$this->_member();
        $data['tests']=$this->_practice_catalog($data['member']);
        $this->load->view('practice_list',$data);
    }

    private function _pt_points($test,$code)
    {
        if($code===1) return (float)$test['points_first'];
        if($code===2) return (float)$test['points_second'];
        return (float)$test['points_fail'];
    }

    private function _pt_grade($test,$code)
    {
        if($test['grade_style']==='number') return (string)(float)$this->_pt_points($test,$code);
        if($code===1) return $test['label_first'];
        if($code===2) return $test['label_second'];
        return $test['label_fail'];
    }

    private function _json($arr)
    {
        $this->output->set_content_type('application/json');
        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    // Test shell (HTML fragment loaded into #result6). Never contains the correct answers or the hints.
    public function practice()
    {
        $id=(int)$this->input->post('id_practice_test');
        $member=$this->_member();
        if(!$member){
            echo '<div class="pt-msg">Please <a href="'.base_url().'login">log in</a> with your student account to start the practice test.</div>';
            return;
        }
        $test=$this->fct->getonerecord('practice_test',array('id_practice_test'=>$id,'status'=>1));
        $questions=$test ? $this->fct->getAll_cond('practice_question',"sort_order",array('id_practice_test'=>$id,'status'=>1)) : array();
        if(!$test || !count($questions)){
            echo '<div class="pt-msg">This practice test is not available at the moment.</div>';
            return;
        }
        // tries per student (a started test counts, finished or not)
        $max_tries=(int)$test['max_tries'];
        if($max_tries>0 && $this->_pt_tries($member['id_members'],$id)>=$max_tries){
            echo '<div class="pt-msg">You have used all your '.$max_tries.' tries for this practice test.</div>';
            return;
        }
        // random pick: the admin can enter more questions than the number shown
        if((int)$test['random_questions']===1) shuffle($questions);
        if((int)$test['questions_per_test']>0) $questions=array_slice($questions,0,(int)$test['questions_per_test']);
        $ids=array();
        foreach($questions as $q) $ids[]=(int)$q['id_practice_question'];
        // record the try now (status 0 = in progress); practice_result() completes this row
        $this->db->insert('practice_result',array(
            'title'=>$member['name'].' - '.$test['title'],
            'id_members'=>(int)$member['id_members'],
            'id_practice_test'=>$id,
            'id_section'=>(int)$test['id_section'],
            'status'=>0,
            'total_questions'=>count($ids),
            'pass_percent'=>(float)$test['pass_percent'],
            'created_date'=>date('Y-m-d H:i:s')));
        $rid=(int)$this->db->insert_id();
        // server-side state of this run: attempts ('a') and grade code ('g') per question, saved flag ('s'),
        // result row ('rid'), start time and time limit in seconds ('start', 'limit'; 0 = no limit)
        $this->session->set_userdata('pt',array('t'=>$id,'m'=>(int)$member['id_members'],'q'=>$ids,'a'=>array(),'g'=>array(),'s'=>0,
            'rid'=>$rid,'start'=>time(),'limit'=>max(0,(int)$test['time_limit'])*60));
        $data['test']=$test;
        $data['questions']=$questions;
        $data['member']=$member;
        $this->load->view('practice_test',$data);
    }

    // Checks one answer. JSON: correct, attempt, grade/points when the question is finished,
    // hint when a retry is allowed. The correct option is only sent once the question is finished.
    public function practice_answer()
    {
        $st=$this->session->userdata('pt');
        $member=$this->_member();
        $id=(int)$this->input->post('id_practice_test');
        $qid=(int)$this->input->post('id_practice_question');
        $opt=(int)$this->input->post('option');
        if(!$member || !is_array($st) || (int)$st['t']!==$id || (int)$st['m']!==(int)$member['id_members'] || !in_array($qid,$st['q'])){
            $this->_json(array('error'=>'restart')); return;
        }
        if(isset($st['g'][$qid])){ $this->_json(array('error'=>'done')); return; }
        // the test is closed once its result was saved (Finish, or the countdown reached 0)
        if(!empty($st['s'])){ $this->_json(array('error'=>'done')); return; }
        // timed test: the server clock decides, with a small grace for network latency
        if(!empty($st['limit']) && (time()-(int)$st['start']) > (int)$st['limit']+PT_TIME_GRACE_SEC){
            $this->_json(array('error'=>'timeout')); return;
        }
        $test=$this->fct->getonerecord('practice_test',array('id_practice_test'=>$id,'status'=>1));
        $q=$this->fct->getonerecord('practice_question',array('id_practice_question'=>$qid,'id_practice_test'=>$id));
        if(!$test || !$q){ $this->_json(array('error'=>'restart')); return; }
        $max=max(1,(int)$test['max_attempts']);
        $used=isset($st['a'][$qid]) ? (int)$st['a'][$qid] : 0;
        if($used>=$max){ $this->_json(array('error'=>'locked')); return; }
        if($opt<1 || $opt>4){ $this->_json(array('error'=>'bad_option')); return; }

        $used++;
        $st['a'][$qid]=$used;
        $st['c'][$qid][]=$opt;   // every option the student chose, in order (shown in the reports)
        $correct=((int)$q['correct_option']===$opt);
        $res=array('correct'=>$correct,'attempt'=>$used,'max_attempts'=>$max);
        if($correct){
            $code=($used===1) ? 1 : 2;
            $st['g'][$qid]=$code;
            $res['grade']=$this->_pt_grade($test,$code);
            $res['points']=$this->_pt_points($test,$code);
            $res['correct_option']=(int)$q['correct_option'];
            $res['explanation']=(string)$q['explanation'];
        } elseif($used<$max){
            $res['attempts_left']=$max-$used;
            $res['hint']=trim(strip_tags((string)$q['hint'],'<img>'))!=='' ? $q['hint'] : PT_DEFAULT_HINT_TEXT;
        } else {
            $st['g'][$qid]=0;
            $res['attempts_left']=0;
            $res['grade']=$this->_pt_grade($test,0);
            $res['points']=$this->_pt_points($test,0);
            $res['reveal']=$test['reveal_mode'];
            if($test['reveal_mode']==='auto'){
                $res['correct_option']=(int)$q['correct_option'];
                $res['explanation']=(string)$q['explanation'];
            }
        }
        $this->session->set_userdata('pt',$st);
        $this->_json($res);
    }

    // "See answer": only for a question whose attempts are finished.
    public function practice_reveal()
    {
        $st=$this->session->userdata('pt');
        $id=(int)$this->input->post('id_practice_test');
        $qid=(int)$this->input->post('id_practice_question');
        if(!$this->_member() || !is_array($st) || (int)$st['t']!==$id || !isset($st['g'][$qid])){
            $this->_json(array('error'=>'not_allowed')); return;
        }
        $q=$this->fct->getonerecord('practice_question',array('id_practice_question'=>$qid,'id_practice_test'=>$id));
        if(!$q){ $this->_json(array('error'=>'not_allowed')); return; }
        $this->_json(array('correct_option'=>(int)$q['correct_option'],'explanation'=>(string)$q['explanation']));
    }

    // Summary (HTML fragment) built from the server-side state; stored in practice_result once per run.
    public function practice_result()
    {
        $st=$this->session->userdata('pt');
        $member=$this->_member();
        $id=(int)$this->input->post('id_practice_test');
        if(!$member || !is_array($st) || (int)$st['t']!==$id){
            echo '<div class="pt-msg">Your session has expired. Please start the test again.</div>'; return;
        }
        $test=$this->fct->getonerecord('practice_test',array('id_practice_test'=>$id));
        if(!$test){ echo '<div class="pt-msg">This practice test is not available.</div>'; return; }
        $rows=array(); $score=0; $max=0; $n=0; $c1=0; $c2=0; $c0=0;
        foreach($st['q'] as $qid){
            $n++;
            $q=$this->fct->getonerow('practice_question',array('id_practice_question'=>(int)$qid));
            $code=isset($st['g'][$qid]) ? (int)$st['g'][$qid] : -1;
            $pts=($code>=0) ? $this->_pt_points($test,$code) : 0;
            if($code===1) $c1++; elseif($code===2) $c2++; elseif($code===0) $c0++;
            $score+=$pts;
            $max+=(float)$test['points_first'];
            // what the student chose (letters + texts) and the correct option, for the result screen and the reports
            $letters=array(1=>'A',2=>'B',3=>'C',4=>'D');
            $chosen=isset($st['c'][$qid]) && is_array($st['c'][$qid]) ? $st['c'][$qid] : array();
            $chosen_txt=array();
            foreach($chosen as $c){ $c=(int)$c; $chosen_txt[]=$letters[$c].') '.($q ? strip_tags((string)$q['option_'.$c]) : ''); }
            $correct=$q ? (int)$q['correct_option'] : 0;
            $rows[]=array('n'=>$n,'title'=>$q ? $q['title'] : 'Question '.$n,
                'attempts'=>isset($st['a'][$qid]) ? (int)$st['a'][$qid] : 0,
                'code'=>$code,'grade'=>($code>=0) ? $this->_pt_grade($test,$code) : '-','points'=>$pts,
                'chosen'=>$chosen,'chosen_text'=>$chosen_txt,
                'correct'=>$correct,'correct_text'=>$correct ? $letters[$correct].') '.strip_tags((string)$q['option_'.$correct]) : '');
        }
        $percent=$max>0 ? round($score/$max*100) : 0;
        $pass_percent=(float)$test['pass_percent'];
        $passed=($percent>=$pass_percent) ? 1 : 0;
        // time used, frozen at the first call so a reload of the summary does not change it
        $limit=isset($st['limit']) ? (int)$st['limit'] : 0;
        if(isset($st['dur'])){
            $duration=(int)$st['dur']; $timed_out=(int)$st['to'];
        } else {
            $elapsed=isset($st['start']) ? max(0,time()-(int)$st['start']) : 0;
            $timed_out=($limit>0 && $elapsed>=$limit) ? 1 : 0;
            $duration=($limit>0) ? min($elapsed,$limit) : $elapsed;
            $st['dur']=$duration; $st['to']=$timed_out;
        }
        $saved=FALSE;
        if(empty($st['s'])){
            $row=array(
                'title'=>$member['name'].' - '.$test['title'],
                'id_members'=>(int)$member['id_members'],
                'id_practice_test'=>$id,
                'id_section'=>(int)$test['id_section'],
                'status'=>1,
                'score'=>$score,'max_score'=>$max,'percent'=>$percent,'total_questions'=>$n,
                'pass_percent'=>$pass_percent,'passed'=>$passed,
                'duration_sec'=>$duration,'timed_out'=>$timed_out,
                'first_try'=>$c1,'second_try'=>$c2,'failed'=>$c0,
                'details'=>json_encode($rows, JSON_UNESCAPED_UNICODE),
                'updated_date'=>date('Y-m-d H:i:s'));
            // complete the row opened by practice(); insert one if it is missing (older session)
            $rid=isset($st['rid']) ? (int)$st['rid'] : 0;
            $open=$rid ? $this->fct->getonerow('practice_result',array('id_practice_result'=>$rid,'id_members'=>(int)$member['id_members'],'status'=>0)) : FALSE;
            if($open){
                $this->db->where('id_practice_result',$rid);
                $this->db->update('practice_result',$row);
            } else {
                $row['created_date']=date('Y-m-d H:i:s');
                $this->db->insert('practice_result',$row);
            }
            $st['s']=1;
            $saved=TRUE;
        }
        $this->session->set_userdata('pt',$st);
        $data=array('test'=>$test,'rows'=>$rows,'score'=>$score,'max'=>$max,'percent'=>$percent,
            'pass_percent'=>$pass_percent,'passed'=>$passed,
            'duration'=>$duration,'timed_out'=>$timed_out,'limit'=>$limit,
            'c1'=>$c1,'c2'=>$c2,'c0'=>$c0,'unanswered'=>$n-$c1-$c2-$c0,'saved'=>$saved,'member'=>$member);
        $this->load->view('practice_result',$data);
    }

}

