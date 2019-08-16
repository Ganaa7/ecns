<?
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
</style>
<script>
  $(function() {
    $( "#accordion" ).accordion({      collapsible: true, heightStyle: "content"
    });

    $( ".expireddate" ).each(function( index ) {
       if(check_date($(this).text())<=30&&$(this).length>0){                 
           $(this).closest( "li" ).toggleClass( "red" );
       }
       //30-60 хоногийн дотор байвал yellow
       if(check_date($(this).text())>30&&check_date($(this).text())<=60&&$(this).length>0){
           $(this).closest( "li" ).toggleClass( "yellow" );
       }
    });
  });

  function current_date(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    
    if(dd<10) {
       dd='0'+dd;
    } 

    if(mm<10) {
        mm='0'+mm;
    }
     return  yyyy+'-'+mm+'-'+dd;
   }
   
   // here is check date:
    function check_date(vdate){   
       var cdate = new Date(current_date());
       var vdate = new Date (vdate);
       
       var diff =vdate-cdate;   // Math.abs
       var diffDays = Math.ceil(diff / (1000 * 3600 * 24));

       return diffDays;
    }
  </script>
<style type="text/css">
ul {
	line-height: 1.75;
}

.red {
	background-color: #FF4C48;
	color: #000;
}

.yellow {
	background-color: #FFD40D;
	color: #000;
}
</style>
<div style="margin: 10px 20px; font-size: 85%;">
	<div id="accordion">
   <?
			foreach ( $cres as $crow ) {
				echo "<h3>$crow->category</h3>";
				echo "<div>";
				echo "<ul>";
				foreach ( $fres as $row ) {
					if ($crow->id == $row->category_id) {
						echo "<li>";
						echo "<a target='_blank' href='".base_url()."pdf/web/viewer.html?file=../../download/contract_files/$row->filename'>" . $row->title . "</a>";
						echo "<span style='margin-left:7px'>: <small><strong>№:$row->contract_no</strong></small>&nbsp;|&nbsp;<small><strong>Талууд:$row->sides</strong></small>&nbsp;|&nbsp;
            <small><strong>Батлагдсан:$row->approved</strong></small>|&nbsp; <small><strong>Дуусах:</strong></small><small><span class='expireddate'>$row->expireddate</span></small></span>";
						echo "</li>";
					}
				}
				echo "</ul>";
				echo "</div>";
			}
			?>
</div>
</div>