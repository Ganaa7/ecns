<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Гэмтлийн хуудас</title>
</head>

<body>
<?php
$this->load->library ( 'mpdf/mpdf' );
$mpdf = new mPDF ();
$html = "<div id='header_warn' align='center'>
            <div id='logo'>            
                <image height='70' src='/ecns/images/logo.png'>
            </div>
            <div id='workname' align='right'>ХНБАҮАлба</div> 
            <div id='clear'></div>
        </div> 
        <div id='body' align='center'>
        <h4 align='center'>ГЭМТЭЛ, ДУТАГДЛЫН ХУУДАС № $log_num</h4>
        <h5 align='left' style='padding-left:10px; padding-bottom: 3px; margin-bottom: 0px; font-style:italic;'>1.Гэмтэл, дутагдалыг мэдээлэх</h5>
        <table border='1' cellpadding='5' cellspacing='0' class='printed'>
            <tr height='5%' class='clorful'><td colspan='6' style='padding-left: 10px'>Гэмтэл дутагдлыг тодорхойлсон албан тушаалтан</td></tr>                        
            <tr style='height:30px;'>
                <th align='left'><span class='head'>Хэсэг/Тасаг</span></th>
                <th align='left'>Албан тушаал</th>
                <th align='left'>Нэр</th>
                <th align='left'>Гарын үсэг</th>
                <th align='left'>Огноо</th>
                <th align='left'>Утас</th>
            </tr>
            <tr style='height:30px;'>
                <td style='padding-left:15px;'>$cr_sector</td>
                <td>$cr_position; </td>
                <td><label class='styled'>
                        =$cr_fullname 
                        <label></td>
                <td>&nbsp;</td>
                <td>$closed_datetime</td>
                <td>$cr_workphone</td>
            </tr>
            <tr style='height:30px;'>
                <td colspan='3' class='tdcss'><span class='italic' style='text-align:right;'>Гэмтэл гарахад ажилласан Ээлжийн Ерөнхий зохицуулагч инженер</span></td>
                <td colspan='3'>$act_fullname  <span style='margin-left:5px; margin-right:60px;'>/</span><span>/</span></td>
            </tr>
          
            <tr valign='top' ><td colspan='6'>
                <table border='0' width='100%' height='10%'>
                <tr><td width='50%'><span class='head'>Тоног төхөөрөмжийн байрлал:</span></td>
                    <td width='50%'><span class='head'>Тоног төхөөрөмжийн нэр:</span></td></tr>
                <tr><td><div class='value'>$location;</div></td>
                    <td><div class='value'>$equipment; </div></td></tr>
                </table>
              </td>
            </tr>        
            <tr style='height: 50px;'><td colspan='3' class='tdcss'>
                    <span class ='head'>Гэмтэл, дутагдал эхэлсэн хугацаа.</span></td>
                <td colspan='3' class='tdcss' height='2%'><span class ='head'>$created_datetime</span></td>
            </tr>                
            <tr valign='top'>
                <td colspan='6'><div class='head'>Гэмтэл, дутагдлын шалтгаан:</div>
                    <div class='value italic'>$defect', ' $reason
                    </div>
                </td>
            </tr>    
            </table>
         <h5 align='left' style='padding-left:10px; padding-bottom: 3px; margin-bottom: 0px; font-style:italic;'>2. Гэмтэл, дутагдлыг засварласан ажиллагаа, түүний хэрэгжилт, үр дүн:</h5>
            <table border='1' cellpadding='5' cellspacing='0' class='printed_2'>
            <tr class='spacer3' valign='top'><td colspan='6'>
                <div class='value'>$completion
                </div>
            </td></tr>    
            <tr style='height: 50px;'><td colspan='6' class='tdcss'><span class ='head'>Гэмтэл, дутагдлыг засварласан ИТА-нууд.</span></td>
            </tr>                
            <tr style='height: 10px;' align='center'>
                <th align='left'><span class='head'>Хэсэг/Тасаг</span></th>
                <th>Албан тушаал</th>                
                <th>Нэр</th>
                <th>Гарын үсэг</th>
                <th>Огноо</th>
                
            </tr>     
            <tr style='height: 10px;' align='center'>
                <td>$closed_section</td>
                <td>$closedby_position</td>                
                <td>$closedby </td>
                
                <td></td>
                <td> $closed_datetime</td>
                
            </tr>
            <tr style='height:30px;'>
                <td colspan='3' class='tdcss'><span style='text-align:right;'>Гэмтэл засварлаж дуусахад танилцсан Ерөнхий зохицуулагч инженер</span></td>
                <td colspan='3'>$provedby
                    <span style='margin-left:5px; margin-right:60px;'>/</span><span>/</span>
                </td>
            </tr>              
            
            <tr height='2%'>
                <td colspan ='2' rowspan='2' class='tdcss'><span class='head'>Гэмтэл, дутагдлын дууссан, үргэлжилсэн хугацаа:</span></td>        
                <td colspan ='2' class='tdcss'>Дууссан хугацаа:</td> 
                <td colspan ='2' class='tdcss'>Үргэлжилсэн хугацаа /цаг:мин:сек/                    
                </td>
            </tr>    
            <tr height='2%'>
                <td colspan='2'>
                    <span>$closed_date</span>
                    <span style='padding-left: 30px;' align='right'>$closed_time</span>
<!--                    <span class='timer'>12:01AM</span>-->
                </td>
                <td colspan='2'><span> $duration_time;                  
                    </span>
                </td>                
            </tr>
       </table>
         <h5 align='left' style='padding-left:10px; padding-bottom: 3px; margin-bottom: 0px; font-style:italic;'>3. Гэмтэл дутагдлыг арилгах үйл явцад ерөнхий инженерийн хийсэн дүгнэлт</h5>
         <table border='1' class='printed_3'>
            <tr height='50%'><td colspan='6' height='60px' valign='top'><span class='head'>Албаны ерөнхий инженерийн дүгнэлт:</span>
                    $summary
            </td></tr>        
            <tr height='10%'>
                <td colspan ='2' class='tdcss'>Нэр</td>        
                <td colspan ='2' class='tdcss'>Гарын үсэг</td>        
                <td colspan ='2' class='tdcss'>Огноо</td>                
            </tr> 
            <tr>
                <td colspan ='2' style='height:30px;'></td>        
                <td colspan ='2' style='height:30px'>&nbsp;</td>        
                <td colspan ='2' style='height:30px'>&nbsp;</td>                
            </tr> 
        </table>
        </div>        
          } 
        <div align='right' style='padding-right:40px;' class='bottom'>
            <form style='padding-top:15px;'><input type='button' value='Хуудсыг хэвлэ' class='button' onClick='window.print();return false;' /></form>                     
        </div>     
        <a href='/ecns/shiftlog/warn_pdf/$log_id'>PDF Хөрвүүлэх</a>";

echo $html;

// $mpdf->WriteHTML($html, 2);
// $mpdf->Output();
?>		 
</body>
</html>
