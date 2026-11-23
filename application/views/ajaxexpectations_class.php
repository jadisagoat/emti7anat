<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $(".txt").each(function() {
            $(this).keyup(function(){
                calculateSum();
                var str = this.id;
                var res = str.replace("note_", "");
                var valmax=$("#note"+res).val();
                var value=this.value;
                if((parseFloat(value) < 0) || (parseFloat(value)>parseFloat(valmax))){
                    $("#"+this.id).val('');
                }
            });
        });
    });

    function calculateSum() {
        var sum = 0;
        //iterate through each textboxes and add the values
        $(".txt").each(function() {
            //add only if the value is number
            if(!isNaN(this.value) && this.value.length!=0) {
                sum += parseFloat(this.value);
            }
        });
        //.toFixed() method will roundoff the final sum to 2 decimal places
        $("#sum").html(sum.toFixed(2));

        var max_grade=$("#summmm").val();
        var sum_grade=sum;
        var max_note=20;
        var new_g=(sum_grade*max_note)/max_grade;
        $("#resulttotal").html(new_g.toFixed(2));

var dd="";
if(new_g>16)dd="Very Good";
if(new_g<15.99)dd="Good";
if(new_g<13.99)dd="Pass";
if(new_g<12)dd="Failure";
$("#totalresu").html(dd);

    }
</script>

<?php
$cond=array('id_expectations_class'=>$id_expectations_class);
$count= count($expectations)+3;
?>
<div class="table-responsive">
<table class="table table-sm table-bordered">
  <thead><tr><th scope="col">Section</th>
  <th style="text-align:center" colspan="<?=$count?>"><?=$this->fct->getonecell('expectations_class','title',$cond)?></th>
  </tr></thead>
<?
$k=0;
$var0='';
$var1='';
$var2='';$var3='';$sum=0;
foreach($expectations as $expectations1) {
    //if ($k = 0) {
    $var0.='<td>'.$expectations1['subject_ar'].'</td>';
    $var1.='<td>'.$expectations1['subject'].'</td>';
    $sum+=$expectations1['maximum_grade'];
    $var2.='<td>'.$expectations1['maximum_grade'].'<input type="hidden" id="note'.$expectations1['id_expectations'].'" value="'.$expectations1['maximum_grade'].'"></td>';
    $var3.='<td><input id="note_'.$expectations1['id_expectations'].'" class="txt" type="text" name="txt"  placeholder="Expectations Note"></td>';
    //}
}

echo '<tr>'.$var0.'<td>المجموع</td><td>المعدل</td><td>النتيجة</td></tr>';
echo '<tr>'.$var1.'<td></td><td></td></td></td></tr>';
echo '<tr>'.$var2.'<td>'.$sum.'<input type="hidden" id="summmm" value="'.$sum.'"></td><td>20</td><td></td></tr>';
echo '<tr style="color:red">'.$var3.'<td style="font-weight: bold"><span  id="sum" style="font-weight: bold"></span><input type="hidden" id="sumtotalh" ></td>
<td id="resulttotal" style="font-weight: bold"></td><td id="totalresu" style="font-weight: bold"></td></tr>';
echo '</tbody></table></div>';

?>
