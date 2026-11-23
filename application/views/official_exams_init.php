<div id="headerhomen">
    <?php    $this->load->view('include/_header_home');  ?>
</div>
<div class="content">
    <div class="container">
        <div class="book">

            <?php $_data["id_classes"] =$id_classes;
            $this->load->view('include/menuleft_officialexam',$_data);  ?>
           <!-- <div class="col-md-9 content-top" id="bodycontainer" >
                <div class="col-md-9 " >
                    <h4><?php   echo $info['title']?></h4>
                    <p><?php   echo $info['description']?></p>
                </div>
                <div class="col-md-3 content-top">
                    <div class="grid">
                        <img class="img-responsive" src="<?php   echo base_url()?>uploads/general_secondary/100x100/<?php   echo $info['images']?>" alt=" "/>
                    </div>
                </div>

            </div>-->
            <div class="col-md-9 content-top" id="bodycontainer" >
                <table class="table table-striped" id="table-1">
                    <thead>
                    <tr>
                        <th>Years</th>
                       <!-- <th>Class</th>-->
                        <th>1st</th>
                        <th>2nd</th>
                    </tr>
                    </thead>

<?php $material=[];
$material=$this->fct->getAll("classes","title");
foreach($material as $material1){
    $material[$material1['id_classes']]=$material1['title']; }
$years_1=[];
$years=$this->fct->getAll("years","title");
foreach($years as $years1){
    $years_1[$years1['id_years']]=$years1['title']; }



if(count($official_exams_init)){
                    foreach($official_exams_init as $row){ ?>
                        <tr><td><?php
                            if(isset($years_1[$row['id_years']]))
                                echo $years_1[$row['id_years']];
                            else
                                echo ""; ?> </td>
                            <!--<td><?php
                                if(isset($material[$row['id_classes']]))
                                    echo $material[$row['id_classes']];
                                else
                                    echo "";
                                  ?></td>-->
                            <td><?php if($row['pdf_1st']){?><a href="<?= base_url() ?>uploads/official_exams_init/<?=$row['pdf_1st']?>" target="_blank">1st</a><?php }else{} ?></td>
                            <td><?php if($row['pdf_2st']){?><a href="<?= base_url() ?>uploads/official_exams_init/<?=$row['pdf_2st']?>" target="_blank">2nd</a><?php }else{} ?></td>
                        </tr>
                    <?php } }else{ if($id_classes==0){ ?>
    <tr><td colspan="4" style="text-align: center;color: red"> Please Select a Class  </td> </tr>

<?php }else {  ?>    <tr><td colspan="4" style="text-align: center;color: red"> No Data </td> </tr>



                    <?php } } ?>
                </table>


            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <!--
    <div class="book-top">

        <div class="container">
            <div class="col-md-4 content-grid">
                <img class="img-responsive" src="<?php   echo base_url()?>images/be5.jpg" alt=" "/>
                <h5>But I must explain to you how all.</h5>
                <p>Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature.</p>
                <a class="more hvr-shutter-in-horizontal" href="#">Read More</a>

            </div>
            <div class="col-md-4 content-grid">
                <img class="img-responsive" src="<?php   echo base_url()?>images/be4.jpg" alt=" "/>
                <h5>But I must explain to you how all  </h5>
                <p>Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature.</p>
                <a class="more hvr-shutter-in-horizontal" href="#">Read More</a>

            </div>
            <div class="col-md-4 content-grid">
                <img class="img-responsive" src="<?php   echo base_url()?>images/be6.jpg" alt=" "/>
                <h5>But I must explain to you how all </h5>
                <p>Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature.</p>
                <a class="more hvr-shutter-in-horizontal" href="#">Read More</a>

            </div>
        </div>

        <div class="clearfix"> </div>
    </div>
    <div class="grid-bottom">
        <div class="container">
            <div class="top-1">
                <h3>Our Team</h3>
                <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great</p>
            </div>
            <div class="team">
                <div class="col-md-6 team-m1">
                    <div class="team-1">
                        <div class="col-md-7 col-1-at">
                            <div class="col-md-of">
                                <h5>Inventore veritat</h5>
                                <p>simply random text</p>
                            </div>
                            <div class="col-md-in">
                                <p>Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College.all this mistaken idea.But I must explain.But I must explain to you how all this mistaken idea of denouncing pleasure and praising.</p>

                            </div>
                        </div>
                        <div class="col-md-5 col-1-at">
                            <a href="#"> <img class="img-responsive" src="<?php   echo base_url()?>images/te.jpg" alt=""></a>

                        </div>
                        <div class="clearfix"> </div>
                    </div>
                </div>
                <div class="col-md-6 team-m1">
                    <div class="team-1">

                        <div class="col-md-7 col-1-at">
                            <div class="col-md-of">
                                <h5>Inventore veritat</h5>
                                <p>simply random text</p>
                            </div>
                            <div class="col-md-in">
                                <p>Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College.all this mistaken idea.But I must explain.But I must explain to you how all this mistaken idea of denouncing pleasure and praising.</p>

                            </div>
                        </div>
                        <div class="col-md-5 col-1-at">
                            <a href="#"> <img class="img-responsive" src="<?php   echo base_url()?>images/te1.jpg" alt=""></a>

                        </div>
                        <div class="clearfix"> </div>
                    </div>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="team">
                <div class="col-md-6 team-m1">
                    <div class="team-1">

                        <div class="col-md-7 col-1-at">
                            <div class="col-md-of">
                                <h5>Inventore veritat</h5>
                                <p>simply random text</p>
                            </div>
                            <div class="col-md-in">
                                <p>Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College.all this mistaken idea.But I must explain.But I must explain to you how all this mistaken idea of denouncing pleasure and praising.</p>

                            </div>
                        </div>
                        <div class="col-md-5 col-1-at">
                            <a href="#"> <img class="img-responsive" src="<?php   echo base_url()?>images/te2.jpg" alt=""></a>

                        </div>
                        <div class="clearfix"> </div>
                    </div>
                </div>
                <div class="col-md-6 team-m1">
                    <div class="team-1">

                        <div class="col-md-7 col-1-at">
                            <div class="col-md-of">
                                <h5>Inventore veritat</h5>
                                <p>simply random text</p>
                            </div>
                            <div class="col-md-in">
                                <p>Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College.all this mistaken idea.But I must explain.But I must explain to you how all this mistaken idea of denouncing pleasure and praising.</p>

                            </div>
                        </div>
                        <div class="col-md-5 col-1-at">
                            <a href="#"> <img class="img-responsive" src="<?php   echo base_url()?>images/te3.jpg" alt=""></a>

                        </div>
                        <div class="clearfix"> </div>
                    </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
    -->
</div>
