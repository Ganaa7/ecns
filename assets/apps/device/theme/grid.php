<div id="main_wrap" style="margin: 10px 20px 20px">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>

<script type="text/javascript">

var edit;

$( function() {

  var currentDate = new Date();

  $( "#out_dt_").datetimepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      showOtherMonths: true,
      showWeek: true,
      opened:false
   });

 //menu generate here
 $.contextMenu({

    selector: '.context-menu',

    callback: function(key, options) {

        var id = $(this).attr('id');


    },

    items: {
        <?php foreach ($action as $values) {
          # code...
          switch ($values) {

            case 'pass_no':

            ?>
            "pass_no": {name: "Паспорт дугаар олгох", icon: "add",
                  // zasah function
                callback: function(itemKey){
                     
                   var id = $(this).attr('id');
                       
                   var title = $(this).find( "td:eq(4)" ).text();
                       
                   pass_modal(id, title);
                       
                }
             },
            <?php
            break;  

            case 'add':

            ?>
            "add": {name: "Бусад үзүүлэлтүүдийг нэмэх/засах", icon: "add",
                  // zasah function
                   callback: function(itemKey){
                        var id = $(this).attr('id');
                        var title = $(this).find( "td:eq(4)" ).text();
                       
                        // console.log('edit add'+title+"id"+id);
                       
                        // edit_modal(id, title);
                        window.location.assign(base_url+"/equipment/add/"+id);
                    }
                },
            <?php
            break;   

           case 'edit':

          ?>
          "edit": {name: "Техникийн тодорхойлолтыг засах", icon: "add",
                // zasah function
                 callback: function(itemKey){
                      var id = $(this).attr('id');
                      var title = $(this).find( "td:eq(4)" ).text();
                     
                      // console.log('edit add'+title+"id"+id);
                     
                      // edit_modal(id, title);
                      window.location.assign(base_url+"/equipment/edit/"+id);
                  }
              },
          <?php
            break;

            case 'delete':
            ?>
              "delete": {name: "Устгах", icon: "delete",
                          // zasah function
                           callback: function(itemKey){
                                var id = $(this).attr('id');
                                //confirm("Press a button!");
                                var is_confirm = confirm("Энэ сэлбэгийн бичлэгийг устгахдаа итгэлтэй байна уу?");
                                if(is_confirm){
                                    $.ajax({
                                       type:    'POST',
                                       url:    base_url+'/equipment/index/delete',
                                       data:   {id: id},
                                       dataType: 'json',
                                       success:  function(json) {
                                           if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                                              // close the dialog
                                              showMessage(json.message, 'success');
                                              // amjilttai bolson tohioldold ene heseg uruu shidne                                                  
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
        },
        callback: function(itemKey){
            window.location.assign(base_url+"/equipment/help/");
        }

      }
    }
  });

});

</script>