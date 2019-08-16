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
				$("a:eq(2)", control).click( handler() );
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
			function find_parent(_selected, value){
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
					  	return logic = check_balance('('+logic + find_parent(new_selected, '0'));			 
					}else
						//alert('sorry no children');
						return '';				
				  }else
				  	return '';
				// herev selected li-n parent ul has li bval//
				 // if(_selected.closest('li').closest('ul').closest('li').children('span.module').next().text().length){
				 //    node = _selected.closest('li').closest('ul').closest('li').children('span.module').next().text();
		 		// 	node = node.replace("[", ""); 
				 // 	node = node.replace("]", "");
				 // 	switch(node){
				 // 		case 'AND':
					//         node = '&&'
					//         break;
					//     case 'OR':
					//         node = '||'
					//         break;
					    
				 // 	}
				 // 	//_gates = _gates+'('+value+node+'';
				 // 	_gates = _gates+''+value+node+'';
				 // 	new_selected = _selected.closest('li').closest('ul').closest('li').children('span.module');				 	
				 // 	return _gates+find_parent(new_selected, '0') + '';//')';
				 // }else
				 // 	return _gates+'0';
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

			$(document).on("click", 'li > span.module', function(event) {
	 	   	   console.log('tesxt:'+$('span.current').text());			    
	 	   	    $('.treeview' + ' li.highlight').removeClass('highlight');
                // $(' li > span.' + settings.mod_class +'.parent').removeClass('parent');
                $(' li > div.' + settings.mod_class +'.children').removeClass('children');
                
                $(event.target).closest('li').addClass('highlight');
                $('.highlight li > span.module').addClass('children');                

                //тухайн select Хийсэн elemetn--n id-g avah 
               	// alert('id'+$(this).attr('id'));
               // тухайн element-n parent gate-d-g avch logic-g haruulah
                var equipment_id = $('#equipment_id').val();
                // хэрэв тухайн элемент нь 
                if($(this).hasClass('undevelop'))
                	alert('its undeveloped')
                else get_logic(equipment_id, $(this).attr('id'));
                $(this).attr('style', 'background-color:rgb(255, 113, 86)');
                
                //if($(this).hasClass('basic')){
                //	//alert('basic elemetn');
                //	get_logic(equipment_id, $(this).attr('id'));
                //}else alert('its not basic');               
                
                //find logic gates in jquery 
                //var logic_gates = (find_parent($(this), '1'));                
                //var l_pos = logic_gates.indexOf(")");
				//var logics = logic_gates.substr(0, l_pos-2)+logic_gates.slice(l_pos, logic_gates.length);
                //logics =logic_gates;                 
                 //console.log('logic:'+logics);
            });

            // get logic function runs by ajax
            function get_logic(equip_id, _id){
            	//alert('hoikf;dlaskfd');
            	$.ajax({				   
				   type:    'POST',
       			   url:   base_url+'/ftree/jx_logic/',
				   data:   {equipment_id:equip_id, id:_id },
                   dataType: 'json',
                  success: function(json){
                  	if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                  		//alert(json.logic);                  		
                  		alert(json.status);
                  	}else
						alert(json.status);                  		
                  }                   
				}).done(function() {
				   $( this ).addClass( "done" );
				});
            }

            $("#reset").on('click', function(event){
               var equipment_id = $('#equipment_id').val();
               //alert($(this).attr('id'));
               $.ajax({				   
				  type:    'POST',
       			  url:    base_url+'/ftree/jx_reset/',
				  data:   {equipment_id:equipment_id},
                  dataType: 'json',
                  success: function(json){
                     if (json.status == "success") { // амжилттай нэмсэн тохиолдолд
                  		alert(json.status);                  		
                  	 }else
						alert(json.status);                  		
                    }                   
				}).done(function() {
				   $( this ).addClass( "done" );
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
