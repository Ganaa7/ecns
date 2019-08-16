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
            console.log('ID:'+id);
        },
        items: {
            <?php foreach ($action as $values) {
              # code...
              switch ($values) {

                case 'add':

                ?>
                "add": {name: "Шинэ", icon: "add",
                      // zasah function
                       callback: function(itemKey){
                            var id = $(this).attr('id');
                            console.log('calling add');
                            add_modal(id);
                        }
                    },

                <?php
                  # code...
                  break;

              case 'edit':

              ?>
              "edit": {name: "Засах", icon: "copy",
                    // zasah function
                     callback: function(itemKey){
                          var id = $(this).attr('id');
                          var book = $(this).find( "td:eq(3)" ).text();
                          console.log('edit add'+book);
                          edit_modal(id, book);
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
                                           url:    base_url+'/library/index/delete',
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
