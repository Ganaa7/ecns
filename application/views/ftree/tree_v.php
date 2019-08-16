<?php
foreach ( $libraries ['js_files'] as $value ) {
	echo $value;
}

?>
<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/treeview/css/jquery.treeview.css"/>

<link rel="stylesheet" type="text/css"
	href="<? echo base_url();?>assets/treeview/css/screen.css">

	<script type="text/javascript">
	var CLIPBOARD = "";
		$(function(){	
			  
            node= $('#node_form');

            node.dialog({
               autoOpen: false,
                width: 460,       
                resizable: false,    
                modal: true,
                close: function () {
                   $('p.feedback', $(this)).html('').hide();          
                   $('input[type="text"], input[type="hidden"], select, textarea', $(this)).val('');          
                   $(this).dialog("close");
                }
               }); 

			// fourth example
			$("#black, #gray").treeview({
				collapsed: true,
				animated: "fast",
				control: "#sidetreecontrol",
				persist: "location",
				cookieId: "treeview-black",
				mod_class: "module"			
			});


			//context menu here
			$(document).contextmenu({
				delegate: ".module",
				preventContextMenuForPopup: true,
				preventSelect: true,
				taphold: true,
				menu: [
					{title: "Сонгох", cmd: "select", uiIcon: "ui-icon-flag"}, //Энэ товших үйлдэлтэй адил үйлдэл хийнэ
					{title: "Нэмэх", cmd: "add", uiIcon: "ui-icon-plus" },
					{title: "Засах", cmd: "edit", uiIcon: "ui-icon-pencil"},
					{title: "Устгах", cmd: "delete", uiIcon: "ui-icon-cancel"},					
					{title: "----"},
					{title: "Тусламж", cmd: "help", uiIcon: "ui-icon-help"},
					// {title: "More", children: [
					// 	{title: "Sub 1 (using callback)", action: function(event, ui) { alert("action callback sub1");} },
					// 	{title: "Sub 2 with a long title<kbd>[F2]</kbd>", cmd: "sub2"},
					// 	{title: "Sub 3 <label>Please select: <input type='checkbox' name='sub2'></label>", cmd: "sub3"}
					// 	]}
					],
				// Handle menu selection to implement a fake-clipboard
				select: function(event, ui) {
					var $target = ui.target;
					switch(ui.cmd){
					case "select":
						//alert('select hiihne'+$target.attr('id'));
						// call function select 
						select($target);
						break
					case "add":
						add();
						break
					case "edit":
						edit();
						break
					case "delete":
						//CLIPBOARD = $target.text();
						del();
						break
					case "paste":
						CLIPBOARD = "";

						break
					}
					//alert("select " + ui.cmd + " on " + $target.text());
					// Optionally return false, to prevent closing the menu now
				},
				// Implement the beforeOpen callback to dynamically change the entries
				beforeOpen: function(event, ui) {
					var $menu = ui.menu,
						$target = ui.target,
						extraData = ui.extraData; // passed when menu was opened by call to open()

					// console.log("beforeOpen", event, ui, event.originalEvent.type);

					ui.menu.zIndex( $(event.target).zIndex() + 1);

					$(document)
		//				.contextmenu("replaceMenu", [{title: "aaa"}, {title: "bbb"}])
		//				.contextmenu("replaceMenu", "#options2")
		//				.contextmenu("setEntry", "cut", {title: "Cuty", uiIcon: "ui-icon-heart", disabled: true})
						.contextmenu("setEntry", "copy", "Copy '" + $target.text() + "'")
						.contextmenu("setEntry", "paste", "Paste" + (CLIPBOARD ? " '" + CLIPBOARD + "'" : ""))
						.contextmenu("enableEntry", "paste", (CLIPBOARD !== ""));

					// Optionally return false, to prevent opening the menu now
				}
			});
		});

		function select(target){
			//TODO: FINISH this
			//alert('select here');
			// Тухайн id-n parent-g olood dahin buh parent-diig olj parent тэмдэглэгээг хийх 
			//var _parent = target.closest('li').closest('ul').closest('li').children('span.module')
			var _this = target.closest('li').closest('ul').closest('li');
			$('.tree li > span').removeClass('parent');
			//target.closest('li').closest('ul li > span.parent').removeClass('parent');
			//alert('parent removed');
			find_parent(_this);						                
            //alert('parent'+_parent.text());
            //close all parent-s children not for selected this
            //show dialog here 
            node.dialog('option', 'title', 'hi');
		    node.dialog({ 
		       buttons: { 
		          "Хадгалах": function () {		          	
		             // $('p.feedback', node).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
		             //  // logical function here
		             //    var data = {};
		             //    var inputs = $('input[type="text"], input[type="hidden"], select' , node);
		              
		             //    inputs.each(function(){
		             //      var el = $(this);
		             //      data[el.attr('name')] = el.val();
		             //    });
		             //   // collect the form data form inputs and select, store in an object 'data'
		             //  $.ajax({
		             //      type:   'POST',
		             //      url:    '/ecns/log/index/quality/',
		             //      data:   data,
		             //      dataType: 'json', 
		             //      success:  function(json){ 
		             //        if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
		             //          //энд үндсэн утгуудыг нэмэх болно.
		             //          node.dialog("close");
		             //          // close the dialog                         		                      
		             //        }                  
		             //        else{  // ямар нэг юм нэмээгүй тохиолдолд
		             //          // $('p.feedback', quality).removeClass('success, notify').addClass('error').html(json.message).show();
		             //          alert('error');
		             //        }
		             //      }		              
		             //  });// send the data via AJAX to our controller 
		          },            
		          "Хаах": function () {
		              node.dialog("close");
		          }
		       }
		      }); 
		    node.dialog('open'); 

		}
		function add(){
			alert('add here');
		}
		function edit(){
			alert('edit here');
		}
		function del(){
			alert('del here');
		}

		 function find_parent(_this) {
            if (_this.length > 0) {
                _this.children('span').addClass('parent');
                _this = _this.closest('li').closest('ul').closest('li');
                return find_parent(_this);
            }
        }

	</script>

<?php echo "<h4 style='text-align:center; margin-top:15px;'>\"$equipment\" ТӨХӨӨӨРӨМЖИЙН ГЭМТЛИЙН МОД</h4>"; ?>

<div style="float: right; margin-right: 200px; margin-bottom: 10px;">	
	<a href="/ecns/ftree/tree/<?=$equipment_id?>/" class="button">Босоогоор харах</a> 
	<!-- <a href="#" onclick="_d_event()" class="button">Event нэмэх</a> -->
	<a href="/ecns/ftree/help" class="button">Тусламж</a>
</div>

<div style="margin-bottom:20px;" id="sidetreecontrol">
	<a href="?#" class="button">Хумих</a>  <a class="button" href="?#">Дэлгэх</a> 
</div>

<div style="clear: both">
	
</div>
<input type="hidden" id='equipment_id' name="equipment_id" value="<?php echo $equipment_id?>">
<a class="button"  id='reset' href="?#">Сэргээх</a> 

<?php echo $tree; ?>


<form id="node_form" action="" method="POST">
	<p class="feedback"></p>
	<div>
		Event : <input type="text" name="event" id='event' size="40" />
	</div>
</form>