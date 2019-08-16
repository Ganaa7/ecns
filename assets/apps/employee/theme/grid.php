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

        var m = "clicked: " + key;

        window.console && console.log(m) || alert(m);

        var id = $(this).attr('id');

    },
    items: {
        <?php foreach ($action as $values) {
          # code...
          switch ($values) {            

          case 'edit':

          ?>
          "edit": {name: "Засах", icon: "copy",
              
              callback: function(itemKey){
             
                  var id = $(this).attr('id');
             
                  var title = $(this).find( "td:eq(3)" ).text();
             
                  edit_modal(id, title);
              }
          },
          <?php
            break;

            case 'delete':
            ?>
                 "delete": {name: "Устгах", icon: "delete",

                     callback: function(itemKey){
                    
                          var id = $(this).attr('id');
                    
                          var is_confirm = confirm("Энэ хэрэглэгчийн мэдээлллийг устгахдаа итгэлтэй байна уу?");
                    
                          if(is_confirm){
                    
                              $.ajax({

                                 type:    'POST',

                                 url:    base_url+'/user/index/delete',

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
        }}
    }
  });

});

</script>