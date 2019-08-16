/*
 * Treeview 1.4.2 - jQuery plugin to hide and show branches of a tree
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-treeview/
 *
 * Copyright Jörn Zaefferer
 * Released under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */

;(function($) {

	// TODO rewrite as a widget, removing all the extra plugins
	$.extend($.fn, {
		swapClass: function(c1, c2) {
			// console.log('swap called: '+c1 +' c2:'+c2);
			var c1Elements = this.filter('.' + c1);
			this.filter('.' + c2).removeClass(c2).addClass(c1);
			c1Elements.removeClass(c1).addClass(c2);
			return this;
		},
		replaceClass: function(c1, c2) {
			return this.filter('.' + c1).removeClass(c1).addClass(c2).end();
		},
		hoverClass: function(className) {			
			className = className || "hover";
			return this.hover(function() {
				$(this).addClass(className);
			}, function() {
				$(this).removeClass(className);
			});
		},
		heightToggle: function(animated, callback) {
			// console.log('heightToggle: '+animated+' cb: '+callback);
			animated ?
				this.animate({ height: "toggle" }, animated, callback) :
				this.each(function(){
					jQuery(this)[ jQuery(this).is(":hidden") ? "show" : "hide" ]();
					if(callback)
						callback.apply(this, arguments);
				});
		},
		heightHide: function(animated, callback) {
			if (animated) {
				this.animate({ height: "hide" }, animated, callback);
			} else {
				this.hide();
				if (callback)
					this.each(callback);
			}
		},
		prepareBranches: function(settings) {
			if (!settings.prerendered) {
				// mark last tree items
				this.filter(":last-child:not(ul)").addClass(CLASSES.last);
				// collapse whole tree, or only those marked as closed, anyway except those marked as open
				this.filter((settings.collapsed ? "" : "." + CLASSES.closed) + ":not(." + CLASSES.open + ")").find(">ul").hide();
			}
			// return all items with sublists
			return this.filter(":has(>ul)");
		},
		applyClasses: function(settings, toggler) {
			// TODO use event delegation
			// this.filter(":has(>ul):not(:has(>a))").find(">span").unbind("click.treeview").bind("click.treeview", function(event) {
			// 	// don't handle click events on children, eg. checkboxes
			// 	if ( this == event.target )
			// 		toggler.apply($(this).next());
			// }).add( $("a", this) ).hoverClass();

			this.closest('li').children('ul').children('li').children('span').add( $("a", this) ).hoverClass();
			//console.log('log:'+this.closest('li').children("span").text())

			if (!settings.prerendered) {
				// handle closed ones first
				this.filter(":has(>ul:hidden)")
						.addClass(CLASSES.expandable)
						.replaceClass(CLASSES.last, CLASSES.lastExpandable);

				// handle open ones
				this.not(":has(>ul:hidden)")
						.addClass(CLASSES.collapsable)
						.replaceClass(CLASSES.last, CLASSES.lastCollapsable);

	            // create hitarea if not present
				var hitarea = this.find("div." + CLASSES.hitarea);
				if (!hitarea.length)
					hitarea = this.prepend("<div class=\"" + CLASSES.hitarea + "\"/>").find("div." + CLASSES.hitarea);
				hitarea.removeClass().addClass(CLASSES.hitarea).each(function() {
					var classes = "";
					$.each($(this).parent().attr("class").split(" "), function() {
						classes += this + "-hitarea ";
					});
					$(this).addClass( classes );
				})
			}

			// apply event to hitarea
			 this.find("div." + CLASSES.hitarea).click( toggler );
		},
		treeview: function(settings) {

			//set equipment_id to cookie
			$.cookie("equipment_id", $('#vw_loc_equip_id').val());
			// function counter 
			$.cookie("call", 1);


			settings = $.extend({
				cookieId: "treeview"
			}, settings);

			if ( settings.toggle ) {
				var callback = settings.toggle;
				settings.toggle = function() {
					return callback.apply($(this).parent()[0], arguments);
				};
			}

			// factory for treecontroller
			function treeController(tree, control) {
				// factory for click handlers
				function handler(filter) {
					return function() {
						// console.log($(this).text()+'filter:'+filter+' class'+CLASSES.hitarea+'tree:'+JSON.stringify(tree));
						// reuse toggle event handler, applying the elements to toggle
						// start searching for all hitareas
						toggler.apply( $("div." + CLASSES.hitarea, tree).filter(function() {
							// for plain toggle, no filter is provided, otherwise we need to check the parent element
							return filter ? $(this).parent("." + filter).length : true;
						}) );
						return false;
					};
				}
				// click on first element to collapse tree
				$("a:eq(0)", control).click( handler(CLASSES.collapsable) );
				
				// click on second to expand tree
				$("a:eq(1)", control).click( handler(CLASSES.expandable) );
				
				// click on third to toggle tree
				// $("a:eq(2)", control).click( handler() );
				// $('#reset', control).click( handler());
			}

			// handle toggle event
			function toggler() {
				// console.log('toggled loaded!');
				$(this)
					.parent()
					// swap classes for hitarea
					.find(">.hitarea")
						.swapClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
						.swapClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea )
					.end()
					// swap classes for parent li
					.swapClass( CLASSES.collapsable, CLASSES.expandable )
					.swapClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
					// find child lists
					.find( ">ul" )
					// toggle them
					.heightToggle( settings.animated, settings.toggle );

				if ( settings.unique ) {
					$(this).parent()
						.siblings()
						// swap classes for hitarea
						.find(">.hitarea")
							.replaceClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
							.replaceClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea )
						.end()
						.replaceClass( CLASSES.collapsable, CLASSES.expandable )
						.replaceClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
						.find( ">ul" )
						.heightHide( settings.animated, settings.toggle );
				}
			}

			this.data("toggler", toggler);

			function serialize() {
				function binary(arg) {
					return arg ? 1 : 0;
				}
				var data = [];
				branches.each(function(i, e) {
					data[i] = $(e).is(":has(>ul:visible)") ? 1 : 0;
				});
				$.cookie(settings.cookieId, data.join(""), settings.cookieOptions );
			}

			function deserialize() {
				var stored = $.cookie(settings.cookieId);
				if ( stored ) {
					var data = stored.split("");
					branches.each(function(i, e) {
						$(e).find(">ul")[ parseInt(data[i]) ? "show" : "hide" ]();
					});
				}
			}
			// count_oper = 0;
			// function check_operator(str){
			// 	if(str =''){
			// 		count_oper ++;					
			// 	}else{
			// 		if(count_oper ==1){
			// 			str = str.substr(0, str.length-2);
			// 		}
			// 	}
			// 	count_oper++;
			// 	return str;

			// }

			function check_balance(str){
				var right= 0;
				var left= 0;
				if(str.indexOf('(')){
					right++;
				}

				if(str.indexOf(")")){
					left++;
				}
				if(left!=right){
					str = str+')';
				}
				return str;

			}
			var cnt=0;
			var logic ='';
			// тухайн span-ы Parent-н gate-г олох функц шүү /
			function find_logic(_selected, value){
			//	var logic ='';
				var gate;						
				//alert(_selected.closest('li').closest('ul').closest('li').children('span.module').next().text().length);
				// herev selected li-n parent ul has li bval//
			    if(_selected.closest('li').closest('ul').closest('li').children('span.module').next().text().length){
					//2.find parent ul li yadaj neg elment baih yostoi
					gate = _selected.closest('li').closest('ul').closest('li').children('span.module').next().text();
					gate = gate.replace("[", ""); 
					gate = gate.replace("]", "");
					switch(gate){
				 		case 'AND':
					        gate = '&&'
					        break;
					    case 'OR':
					        gate = '||'
					        break;
				 	}
					//alert(gate);					
					if(_selected.parents('ul:first').children('li').length>1){
						//bol tuhain elementuudin logic-n gate-g avaad hoorond ni nemne :P									
						 _selected.parents('ul:first').children('li').each(function(){	
						    if($(this).text()==_selected.text()){
							   logic = logic + '1'+gate;
							}else
							   logic = logic + '0'+gate;
							   
						 });	
						    logic = logic.substr(0, logic.length-2);						 														
						 
						new_selected = _selected.closest('li').closest('ul').closest('li').children('span.module');				 	
					  	return logic = check_balance('('+logic + find_logic(new_selected, '0'));			 
					}else
						//alert('sorry no children');
						return '';				
				  }else
				  	return '';
				// herev selected li-n parent ul has li bval//				
			}

			// add treeview class to activate styles
			this.addClass("treeview");
			//console.log('add class treeview');

			// prepare branches and find all tree items with child lists
			var branches = this.find("li").prepareBranches(settings);

			switch(settings.persist) {
			case "cookie":
				var toggleCallback = settings.toggle;
				settings.toggle = function() {
					serialize();
					if (toggleCallback) {
						toggleCallback.apply(this, arguments);
					}
				};
				deserialize();
				break;
			case "location":
				var current = this.find("a").filter(function() {
					return location.href.toLowerCase().indexOf(this.href.toLowerCase()) == 0;
				});
				if ( current.length ) {
					// TODO update the open/closed classes
					var items = current.addClass("selected").parents("ul, li").add( current.next() ).show();
					if (settings.prerendered) {
						// if prerendered is on, replicate the basic class swapping
						items.filter("li")
							.swapClass( CLASSES.collapsable, CLASSES.expandable )
							.swapClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
							.find(">.hitarea")
								.swapClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
								.swapClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea );
					}
				}
				break;
			}

			branches.applyClasses(settings, toggler);

			// if control option is set, create the treecontroller and show it
			if ( settings.control ) {
				treeController(this, settings.control);
				$(settings.control).show();
			}
			//тухайн click хийсэн утга
			$(document).on("click", 'li > span.module', function(event) {

				var _this = $(this);
	 	   	   //console.log('tesxt:'+$('span.current').text());			    
	 	   	    $('.treeview' + ' li.highlight').removeClass('highlight');
                // $(' li > span.' + settings.mod_class +'.parent').removeClass('parent');
                $(' li > div.' + settings.mod_class +'.children').removeClass('children');
                
                $(event.target).closest('li').addClass('highlight');
                $('.highlight li > span.module').addClass('children');  

                var type = '';
                if($(this).hasClass('basic')) type ='basic';
               	else type ='not_basic';              

                //Хэрэв top элэемент сонгохыг хориглоно
                //энэ тохиолдолд үйлдлүүд гүйцэтгэгдэх болно.
				//хэрэв сонгосон элемент failure бол
	            // console.log('this'+$(this).attr('class'));  
	            // vw_loc_equip_id ni equipment_id bolno
		        var equipment_id = $('#vw_loc_equip_id').val();	   
		        if(_this.hasClass('top')){
                   alert("Хамгийн дээд элементийг сонгох боломжгүй! Дэд хэсгүүдээс сонгоно уу!");
                }else{  // not top elements  
                	if(_this.hasClass('failure')){
	            	   // хэрэв Failure бол дараах зүйлсийг хийнэ
	                   var node_id = _this.attr('id');
	                   
 	                   var node = _this.text();                	                	
	                   // 1. unselect failure
	                	_this.removeClass('failure');                	 
	                	// 2. remove tag
	                	// remove tag function here	                	
	                	$("#node_"+node_id).remove();	 	                	

	                	$("#myTags").tagit("removeTagByLabel", node);

						if(!_this.hasClass('undevelop')){
							// tuhain function reverse hiihed
							rev_logic(_this, equipment_id, node_id)
		            	}else{
		            	//4. Undeveloped байгаад хэрэв unselect хийхэд өөр .node сонгогдсн байвал юу ч хийх хэрэггүй                	
	                	// Хэрэв зөвхөн энэ node сонгогдсон байгаа бол type-г өөрчилнө.
	                	// ямар нэгэн node байгаа эсэхийг шалгана!
		               	if($('.node').length==0)
		               	  $('#log_type_id option[value="' + 0 + '"]').prop('selected',true);      	
		               	  // else юу ч хийх шаардалгагүй	            		
		            	}
		            }else{ 
		              	// Хэрэв $(location).attr('href'); нээж буй гэмтэл бол бүгдийг сонгоно 
			           // console.log('location'+$(location).attr('href'));
			           var my_url = $(location).attr('href');
			           var form;

			           if(my_url.indexOf("create_form") >= 0){
			           	  form='create';
			           }else if(my_url.indexOf("edit")>= 0 ){
			           	  form='edit';
			           }
			           //create bolon edit form deer
			           if (form=='create'||form=='edit'){
		           			var res_json;                 	  		
				            // тухайн element-n parent gate-d-g avch logic-g haruulah 	 
				            if($(this).hasClass('undevelop')){
			               	    //! Систем унацан тохиолдолд сонгоход хэрэв undeveloped - елементыг сонгоход тухайн
			               	    if($('.node').length==1){
			               	    	  // тохиолдолд
			               	 	   	  $('#log_type_id option[value="' + 2 + '"]').prop('selected',true); 	               		      
			               	 	   }
			               	}else{ 	// ene basic bish buyu  parent bol
			               	   
							   var json= chk_parent(_this.attr('id'), equipment_id, type);							   
				               if(json.status=='success'){
				               		set_tag_node($(this));
				               	    // logic-uudiin daguu buh elementuud-n fault hiiine				               	    
					               res_json = get_logic(equipment_id, $(this));					            
					               // Зөвхөн basic element байх ёстой       
					               if(res_json.logic.result == 'true'){	    
						               	//console.log('true here');
						               	$('#log_type_id option[value="' + 1 + '"]').prop('selected',true);
						       		}else{
						         	   //console.log('false here');
						               //$('#log_type_id select').val(2);
						               $('#log_type_id option[value="' + 2 + '"]').prop('selected',true);
						        	}
				               }else
				                  alert("["+_this.text() + '] энэ мөчир дээр эх мөчир аль хэдийн алдаагаар сонгогдсон учир сонгох боломжгүй! Тусламж цэснээс дэлгэрэнгүй танилцана уу!');
				            }				                
			           }else { // close data deer uncheck module _id ele set_tag_node            	
			               console.log('closing called');
			               // its used for flag its first time or not							
		                   if($(this).hasClass('basic')){		                		
			                	// logic-uudiin daguu buh elementuud-n fault hiiine
			      //           	var call = $.cookie('call');
			      //           	// console.log('call:'+call);
			      //           	if(call==1){ 
			      //           	// anh удаа дуудахад тухайн node-н parent-д дээр алдаа өгсөн тохиолдолд 
			      //           	// тухайн парентийн алдааг устгах хэрэгтэй.
			      //           		$.cookie('call', 0);
			      //           		$.ajax({				   
									//    type:    'POST',
						   // 			   url:    '/ecns/flog/rm_prt/',
									//    data:   {equipment_id:equipment_id, id:$(this).attr("id") },
						   //             dataType: 'json',
						   //             async: false,
						   //            success: function(json){
						   //            	if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
						   //            		find_parent_remove($(this));    
						   //            	}
						   //            }                   
									// });
			      //           	}		                	
 								var json= chk_parent(_this.attr('id'), equipment_id, type);	
 								if(json.status=='success'){ //тухайн basic дээр Parent алдаа өгсөн эсэхийг шалгана! 									
								   set_tag_node($(this));  
								   var res_json = get_logic(equipment_id, $(this));	                	

				                	if(res_json.logic.result == 'true'){	    
					               	   console.log('true here');
					               	  $('#log_type_id option[value="' + 1 + '"]').prop('selected',true);
					                }else{		                  
					               	  //console.log('false here');
					                  //$('#log_type_id select').val(2);
					                  $('#log_type_id option[value="' + 2 + '"]').prop('selected',true);
					                }              		
 								}else alert("["+_this.text() + '] энэ мөчрийн эх аль хэдийн алдаагаар сонгогдсон учир сонгох боломжгүй! \n Эхлээд тухайн алдаа гарсан эх мөчрийг сонголтгүй болго!');
			                		
		                   }else if($(this).hasClass('undevelop')){
		                      if($('.node').length==0)  
						   	     $('#log_type_id option[value="' + 2 + '"]').prop('selected',true);
						   	  set_tag_node($(this));
		                   }else {
		                   	alert("Үндсэн болон тодорхой бус элэментийг сонгоно уу! Дэд хэсгийн сонгохгүй!");
		                   }
		               }
		            } 
                }
                
            //1. Хэрэв тухайн дарсан элэементийн Parent-г авна	                	                
            // if($.inArray(parent.attr('id'), res_json.error)>-1){	                   
            //    //2. тэр нь алдаан дотор байвал parent-г class-д failure өгнө		
            //    parent.addClass('failure');
            //    //3. Tаг-д тухайн олдсон элэментийг node-г авч нэмнэ.
            //    $('#myTags').tagit('createTag', parent.text());
            //    if($('#create_form').length)
         	//       $('#create_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+parent.attr('id')+"' value='"+parent.attr('id')+"'/>");
         	//    // herev zasah form bval
         	//    if($('#edit_form').length)
         	//       $('#edit_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+parent.attr('id')+"' value='"+parent.attr('id')+"'/>");
            // }

            //тухайн select Хийсэн elemetn--n id-g avah 
            //if($(this).hasClass('basic')){
            //	//alert('basic elemetn');
            //	find_logic(equipment_id, $(this).attr('id'));
            //}else alert('its not basic');               
            
            //find logic gates in jquery 
            //var logic_gates = (find_logic($(this), '1'));                
            //var l_pos = logic_gates.indexOf(")");
			//var logics = logic_gates.substr(0, l_pos-2)+logic_gates.slice(l_pos, logic_gates.length);
            //logics =logic_gates;                 
             //console.log('logic:'+logics);
            });

			// function check parent has error 
			function chk_parent(_id, equipment_id, type){
				var ret ;

				$.ajax({				   
				   type:    'POST',
	   			   url:    '/ecns/flog/jx_pr_error/',
				   data:   {equipment_id:equipment_id, id:_id, node_type:type },
	               dataType: 'json',
	               async: false,
	              success: function(json){
	              	if (json.status == "success") { 
	              	// амжилттай нэмсэн тохиолдолд
	              		ret = json;
	              	}else // not allowed
	              		ret =json;
	              }                   
				});

				return ret;
			}

			//remove tag function not used
			function remove_tag(node_id){
				//1. Remove all Tags
				$("#myTags").tagit("removeAll");
				//2. foreach all node's and create tag
				// 3. delete node tag by id    
				// тухайн node_id-г устгах хэрэгтэй!!!
				$("#node_"+node_id).remove();	   

				// get session id by name-s from php ajax
				// then foreach array create tag				                	                 

				$( ".node" ).each(function(){
					// console.log($(this).val());
					var data ={};
		            data['id']=$(this).val();
		            // тухайн мөчрийг сонгоход мэдээллийг авна!!!
		            $.ajax({
		                type: 'POST',
		                url: '/ecns/flog/index/node_select',
		                async: false,
		                data: data,
		                success: function(data) {
		                    if(data.status=="success"){             
		                    //!!!console.log('parent'+data.parent);
		                        $('#myTags').tagit('createTag', data.node);		                       
		                    }
		                }		            
		            });				    
				    // $('#myTags').tagit('createTag', data.node);
				});
			}
			
			// reverse logic function 
			function rev_logic(_this, equipment_id, node_id){
				$.ajax({				   
				   type:    'POST',
       			   url:    '/ecns/flog/jx_rev_logic/',
				   data:   {equipment_id:equipment_id, id:node_id },
                   dataType: 'json',
                   async: false,
                  success: function(json){
                  	if (json.status == "success") { // амжилттай нэмсэн тохиолдолд		                  		
                  		if(json.logic.result=='true'){
                  			//unitl find top of parent find error
                  		   ret_val = json;
                  		   // find_next_parent_remove(_this, json);		                  			 
                  		}else{
                  		   // if false until top remove parent		                  		   
                  		   find_parent_remove(_this); 		                  		   
                  		   // console.log('node:'+$('.node').length);
                  		   if($('.node').length==0)      
                  		      $('#log_type_id option[value="' + 0 + '"]').prop('selected',true);   
                  		   else 
                  		   	  $('#log_type_id option[value="' + 2 + '"]').prop('selected',true);   
                  		   ret_val = json;  
                  		}
                  		//console.log(json.logic.result);
                  	}
                   }                   
				});
			}
			
			//set tag and node
 			function set_tag_node(_this){
				var data ={};
	            data['id']=_this.attr("id");
	            // тухайн мөчрийг сонгоход мэдээллийг авна!!!
	            $.ajax({
	                type: 'POST',
	                url: '/ecns/flog/index/node_select',
	                async: false,
	                data: data,
	                success: function(data) {
	                    if(data.status=="success"){             
	                    //!!!console.log('parent'+data.parent);
	                        $('#myTags').tagit('createTag', data.node);
	                        // $('#node_id').val($('.current').attr("id"));                
	                        var node_id = $('.current').attr("id");
	                        // create form bval
	                        if($('#create_form').length)
	                           $('#create_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+node_id+"' value='"+node_id+"'/>");
	                         // herev zasah form bval
	                        if($('#edit_form').length)
	                           $('#edit_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+node_id+"' value='"+node_id+"'/>");

	                        if($('#close_form').length)
	                           $('#close_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+node_id+"' value='"+node_id+"'/>");
	                    }
	                }
	            });
 			}

            // get logic function runs by ajax
            function get_logic(equip_id, _this){
            	var ret_val;
            	//alert('hoikf;dlaskfd');
            	current_id = _this.attr('id');
            	//_this.attr('style', 'background-color:red'); 
            	_this.addClass('failure');
            	
            	//undevelop-s busad tohioldold run this
            	if(!_this.hasClass('undevelop')){            		
	            	$.ajax({				   
					   type:    'POST',
	       			   url:    '/ecns/flog/jx_logic/',
					   data:   {equipment_id:equip_id, id:current_id },
	                   dataType: 'json',
	                   async: false,
	                  success: function(json){
	                  	if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
	                  		if(json.logic.result=='true'){
	                  			//unitl find top of parent find error
	                  			find_parent(_this);       
	                  			// console.log('get_logic:'+json.logic.result);
	                  			ret_val = json;
	                  		}else{
	                  		   // not until top its if next parent fault find it
	                  		   find_next_parent(_this, json);
	                  		   ret_val = json;                  		
	                  		}
	                  		//console.log(json.logic.result);
	                  	}
	                   }                   
					});
            	}
            	return ret_val;				
            }
            
            // added in 2017:01-26 this find next parents if fault
            function find_next_parent(_this, json){	            
                _this = _this.closest('li').closest('ul').closest('li');	                
                // console.log('hi there'+_this.children('span').html());
                //1. Хэрэв тухайн дарсан элэементийн Parent-г авна	                	                
	            if($.inArray(_this.children('span').attr('id'), json.error)>-1){	                   
	               //2. тэр нь алдаан дотор байвал parent-г class-д failure өгнө		
	               _this.children('span').addClass('failure');		               
	               //3. Tаг-д тухайн олдсон элэментийг node-г авч нэмнэ.
	               $('#myTags').tagit('createTag', _this.children('span').text());
	               if($('#create_form').length)
                      $('#create_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+_this.children('span').attr('id')+"' value='"+_this.children('span').attr('id')+"'/>");
                   // herev zasah form bval
                   if($('#edit_form').length)
                      $('#edit_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+_this.children('span').attr('id')+"' value='"+_this.children('span').attr('id')+"'/>");

                    return find_next_parent(_this, json);
	            }
	            //console.log(_this.children('span').html());
	            //_this.children('span').attr('style', 'background-color:red'); 
	            // ! Хэрэв failure class аль хэдийн өгсөн бол дахин өгөх шаардлагаггүй	                
            }

            //remove parent 
            function find_parent_remove(_this) {
	            if (_this.length > 0) {
	                _this = _this.closest('li').closest('ul').closest('li');	                 
	                //console.log(_this.children('span').html());
	                //_this.children('span').attr('style', 'background-color:red'); 
	                // ! Хэрэв failure class аль хэдийн өгсөн бол дахин өгөх шаардлагаггүй
	                if(_this.children('span').hasClass('failure')){
	                	//herev bval 
	                   _this.children('span').removeClass('failure');

		               console.log('childre'+_this.children('span').text());

		               $("#myTags").tagit("removeTagByLabel", _this.children('span').text());

		               $('#node_'+_this.children('span').attr('id')).remove();
	                }
	                	
	                
	                return find_parent_remove(_this);
	            }
	        }

           // ! this recursive until parent cus its true!!
	        function find_parent(_this) {
	            if (_this.length > 0) {
	                _this = _this.closest('li').closest('ul').closest('li');
	                // console.log('hi there'+_this.children('span').html());
	                //_this.children('span').attr('style', 'background-color:red'); 
	                // ! Хэрэв failure class аль хэдийн өгсөн бол дахин өгөх шаардлагаггүй
	                _this.children('span').addClass('failure');
	                $('#myTags').tagit('createTag', _this.children('span').text());
		            if($('#create_form').length&&_this.children('span').attr('id'))
	                   $('#create_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+_this.children('span').attr('id')+"' value='"+_this.children('span').attr('id')+"'/>");
	                // herev zasah form bval
	                if($('#edit_form').length)
	                   $('#edit_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+_this.children('span').attr('id')+"' value='"+_this.children('span').attr('id')+"'/>");

	                if($('#close_form').length)
	                   $('#close_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+_this.children('span').attr('id')+"' value='"+_this.children('span').attr('id')+"'/>");

	                return find_parent(_this);
	            }
	        }

            $("#reset").on('click', function(event){
               var e_id = $('#vw_loc_equip_id').val();               
               //reset(equipment_id); 
               // хэрэв failure baival arilgana
               //1. бүх failure-г арилгана
               $( ".tree li span" ).each(function( index ) {
				  // console.log( index + ": " + $( this ).text() );
				  //$(this)  	
				  if($(this ).hasClass( "failure" )){
				     $(this).removeClass('failure');
				  }
				});
               //2. tag-g remove hiine              
               $("#myTags").tagit("removeAll");
               //3. бүх Node-дийг устгана
               if($('#create_form').length)
	              //$('.node', '#create_form').empty();	          	  
	          	  $( ".node" ).each(function( index ) {
	          	  	// console.log("nodes: "+$(this).val());	
	          	  	//$(this).empty();
	          	  	$(this).remove();
	          	  });
	             // herev zasah form bval
	           else if($('#edit_form').length)
	            	//$('.node', '#edit_form').empty();
	            	$( ".node" ).each(function( index ) {
	              	   $(this).remove();
	          	  	 //console.log("nodes: "+$(this).val());	
	          	  	});
	          	//4. Гэмтлийн төрлийг 0 болгох
	          	// change log_type_id 0	      
	          	$('#log_type_id option[value="' + 0 + '"]').prop('selected',true);
	          	//5. reset by current 
	          	$.ajax({				   
				   type:    'POST',
			   	   url:    '/ecns/flog/jx_reset/',
				   data:   {equipment_id:e_id},
			       dataType: 'json',
			       async: false,
			       success: function(json){
			          if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
			          	console.log('restor successed');
			          	return true;
			             //alert(json.status);                  		
			          }else
			          	console.log('restor false');
			          	return false;
					    // alert(json.status);                  		
			          }                   
				});
            }); 
	
			// hovr over the nodes
        	$(document).on("hover", ' li > span.module', function(event) {				   
               if (event.type == 'mouseenter' || event.type == 'mouseover') {
                   $('.' + settings.mod_class).removeClass('current');
                   $('.' + settings.mod_class).removeClass('children');
                   $('.' + settings.mod_class).removeClass('parent');
                   $(this).addClass('current');
                   $(this).closest('li').children('ul').children('li').children('span.module').addClass('children');
                   $(this).closest('li').closest('ul').closest('li').children('span.module').addClass('parent');                   
                   //show context menu here
                  }//else {
                   //hide context menu here
               	  //}
       	 	});

			return this;
		}


	});

    // $(document).on("click", '.' + ' span.module', function() {
    //     alert('hihtere');
    // });

	// classes used by the plugin
	// need to be styled via external stylesheet, see first example
	$.treeview = {};
	var CLASSES = ($.treeview.classes = {
		open: "open",
		closed: "closed",
		expandable: "expandable",
		expandableHitarea: "expandable-hitarea",
		lastExpandableHitarea: "lastExpandable-hitarea",
		collapsable: "collapsable",
		collapsableHitarea: "collapsable-hitarea",
		lastCollapsableHitarea: "lastCollapsable-hitarea",
		lastCollapsable: "lastCollapsable",
		lastExpandable: "lastExpandable",
		last: "last",
		hitarea: "hitarea"
	});

})(jQuery);
