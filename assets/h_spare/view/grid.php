<div id="main_wrap" style="margin: 10px 20px 20px">
	<!-- <button id = 'create_trip' class="button">Нэмэх</button> -->

  <div style="color:red; margin:10px 0;">Сэлбэгийн өөрчлөлтийн талаарх мэдээллийг <strong><a href="#" onclick="help_modal();">ЭНД ДАРЖ</a> </strong>үзнэ үү! </div>

	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>



<script type="text/javascript">
var spot, edit;
$( function() {

  var currentDate = new Date();

  $( "#out_dt_").datetimepicker({   
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false         
   });

  $( "#est_dt_").datetimepicker({   
      dateFormat: 'yy-mm-dd',      
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false         
   });

// contexxt menu 2
 $.contextMenu({
        selector: '.context-menu1', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
            var id = $(this).attr('id');       
            console.log('ID:'+id);
        },
        items: {
            <?php foreach ($action as $values) {
              # code...
              switch ($values) {
                case 'add':
                ?>
                   "add": {name: "Алслагдсан обьект Үлдэгдэл тоо/ш бүртгэх", icon: "edit",
                      callback: function(itemKey){
                        var id = $(this).attr('id');     
                        var spare = $(this).find( "td:eq(4)" ).text();
                        //closest('td:eq( 2 )').text();                                             
                        add_modal(id, spare); 
                        return true;             
                      }
                    },            
                <?php
                  # code...
                  break;      

                case 'need':
                ?>
                   "need": {name: "Сэлбэгэнд байх ёстой тоо/ш", icon: "edit",
                      callback: function(itemKey){
                        var id = $(this).attr('id');     

                        var spare = $(this).find( "td:eq(4)" ).text();

                        var need = $(this).find( "td:eq(8)" ).text();

                        if(!isNaN(parseInt(need))) {

                            need = parseInt(need);

                        } else need = 0;

                        if(need!=0){

                           var is_valid = confirm("Сэлбэгэнд байх ёстой тоо/ш аль хэдийн оруулсан байгаа тул дахин оруулбал энэ тоо өөрчлөгдөх болно!"); 

                           if(is_valid)  need_modal(id, spare, need); 

                        }else   
                        need_modal(id, spare, 0); 

                        return true;             
                      }
                    },            
                <?php
                  # code...
                  break;   

                  default:

                  ?>
                  "rest": {name: "Ашиглагдаж буй тоо/ш бүртгэх", icon: "edit",
                      callback: function(itemKey){

                        var id = $(this).attr('id');     

                        var spare = $(this).find( "td:eq(4)" ).text();                        

                        var using_qty = $(this).find( "td:eq(7)" ).text();                        

                        if(!isNaN(parseInt(using_qty))) {

                            using_qty = parseInt(using_qty);

                        } else using_qty = 0;

                        if(using_qty!=0)

                            var is_valid = confirm("Сэлбэгийн тоо аль хэдийн оруулсан байгаа тул дахин оруулах боломжгүй?"); 

                        console.log('uqty'+using_qty);

                        use_modal(id, spare, using_qty); 

                        return true;             
                      }
                    },            

                  <?php break;

              }
            } ?>
            
            "sep1": "---------",
            "help": {name: "Тусламж", icon: function(){
                return 'context-menu-icon context-menu-icon-quit';
              },

                callback: function(itemKey){
                   help_modal();
                   return true;     
                }

            }
        }
  });

  
   // end jquery here
   //menu generate here
 $.contextMenu({
        selector: '.context-menu', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
            var id = $(this).attr('id');       
            console.log('ID:'+id);
        },
        items: {
            <?php foreach ($action as $values) {
              # code...
              switch ($values) {
               
                case 'delete':

                ?>
                  "delete": {name: "Устгах", icon: "delete", 
                              // zasah function
                               callback: function(itemKey){
                                    var id = $(this).attr('id');                         
                                    ///alert("Clicked on " + itemKey + " on element " + id);
                                    // Do not close the menu after clicking an item
                                    //confirm("Press a button!");
                                    var is_confirm = confirm("Энэ сэлбэгийн бичлэгийг устгахдаа итгэлтэй байна уу?");
                                    if(is_confirm){
                                        $.ajax({
                                           type:    'POST',
                                           url:    base_url+'/wh_spare/spare/index/delete',
                                           data:   {id: id},
                                           dataType: 'json', 
                                           success:  function(json) {
                                               if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                                                  // close the dialog                                                
                                                  showMessage(json.message, 'success');
                                                  // amjilttai bolson tohioldold ene heseg uruu shidne
                                                  //load grid here       
                                                  jQuery("#grid").jqGrid('setGridParam', { datatype: 'json' }).trigger('reloadGrid');
                                               }else
                                                  showMessage(json.message, 'error');
                                          }
                                        }).done(function() {
                                            console.log('state: call here dialog');       
                                        });
                                  }
                                    return true;             
                                }
                              },
                <?php 
                  # code...
                  break;
               
              }
            } ?>
            
            "sep1": "---------",
            "help": {name: "Тусламж", icon: function(){
                return 'context-menu-icon context-menu-icon-quit';
            }}
        }
  });



});



	
</script>