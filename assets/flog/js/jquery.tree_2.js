(function($) {     
    $.fn.tree_structure = function(options) {
        var defaults = {
            'add_option': true,
            'edit_option': true,
            'delete_option': true,
            'confirm_before_delete': true,
            'animate_option': [true, 5],
            'fullwidth_option': true,
            'align_option': 'center',
            'draggable_option': true
        };
        return this.each(function() {
            if (options)
                $.extend(defaults, options);
            // тохиргоонууд энд байна
            var add_option = defaults['add_option'];
            var edit_option = defaults['edit_option'];
            var delete_option = defaults['delete_option'];
            var confirm_before_delete = defaults['confirm_before_delete'];
            var animate_option = defaults['animate_option'];
            var fullwidth_option = defaults['fullwidth_option'];
            var align_option = defaults['align_option'];
            var draggable_option = defaults['draggable_option'];
            var vertical_line_text = '<span class="vertical"></span>';
            var horizontal_line_text = '<span class="horizontal"></span>';
            var add_action_text = add_option == true ? '<span class="add_action" title="Нэмэх! "></span>' : '';
            var edit_action_text = edit_option == true ? '<span class="edit_action" title="Засах!"></span>' : '';
            var delete_action_text = delete_option == true ? '<span class="delete_action" title="Татгалзах | Unselect"></span>' : '';
            var highlight_text = '<span class="highlight" title=" Сонгох | Select"></span>';
            var class_name = $(this).attr('class');
            var event_name = 'pageload';
            if (align_option != 'center')
                $('.' + class_name + ' li').css({'text-align': align_option});
            // Хэрэв full width байвал автоматаар бүх өргөнийг ихэсгэнэ.
            if (fullwidth_option) {
                var i = 0;
                var prev_width;
                var get_element;
                $('.' + class_name + ' li li').each(function() {
                    var this_width = $(this).width();
                    if (i == 0 || this_width > prev_width) {
                        prev_width = $(this).width();
                        get_element = $(this);
                    }
                    i++;
                });
                var loop = get_element.closest('ul').children('li').eq(0).nextAll().length;
                var fullwidth = parseInt(0);
                for ($i = 0; $i <= loop; $i++) {
                    fullwidth += parseInt(get_element.closest('ul').children('li').eq($i).width());
                }
                $('.' + class_name + '').closest('div').width(fullwidth);
            } 
            // end full width
            $('.' + class_name + ' li.thide').each(function() {
                $(this).children('ul').hide();
            });
            // тухайн байршилд data html tag нэмэх
            function prepend_data(target) {
                target.prepend(vertical_line_text + horizontal_line_text); //.children('div').prepend(add_action_text + delete_action_text + edit_action_text);
                if (target.children('ul').length != 0)
                    target.hasClass('thide') ? target.children('div').prepend('<b class="thide tshow"></b>') : target.children('div').prepend('<b class="thide"></b>');
                //if target basic element then show select button
                target.children('div.basic').prepend(highlight_text+delete_action_text);
                //target.children('div').prepend(highlight_text);
            }
            // шугам зурах
            function draw_line(target) {
                var tree_offset_left = $('.' + class_name + '').offset().left;
                tree_offset_left = parseInt(tree_offset_left, 10);
                var child_width = target.children('div').outerWidth(true) / 2;
                var child_left = target.children('div').offset().left;
                if (target.parents('li').offset() != null)
                    var parent_child_height = target.parents('li').offset().top;
                vertical_height = (target.offset().top - parent_child_height) - target.parents('li').children('div').outerHeight(true) / 2;
                
                target.children('span.vertical').css({'height': vertical_height, 'margin-top': -vertical_height, 'margin-left': child_width, 'left': child_left - tree_offset_left});
                if (target.parents('li').offset() == null) {
                    var width = 0;
                } else {
                    var parents_width = target.parents('li').children('div').offset().left + (target.parents('li').children('div').width() / 2);
                    var current_width = child_left + (target.children('div').width() / 2);
                    var width = parents_width - current_width;
                }
                var horizontal_left_margin = width < 0 ? -Math.abs(width) + child_width : child_width;
                target.children('span.horizontal').css({'width': Math.abs(width), 'margin-top': -vertical_height, 'margin-left': horizontal_left_margin, 'left': child_left - tree_offset_left});
            } 
            // end draw_line
            if (animate_option[0] == true) {
                function animate_call_structure() {
                    $timeout = setInterval(function() {
                        animate_li();
                    }, animate_option[1]);
                }
                var length = $('.' + class_name + ' li').length;
                var i = 0;
                function animate_li() {
                    prepend_data($('.' + class_name + ' li').eq(i));
                    draw_line($('.' + class_name + ' li').eq(i));
                    i++;
                    if (i == length) {
                        i = 0;
                        clearInterval($timeout);
                    }
                }
            }
            // бүтэцийг дахин зурах
            function call_structure() {
                $('.' + class_name + ' li').each(function() {
                    if (event_name == 'pageload')
                        prepend_data($(this));
                    draw_line($(this));
                });
            }
            animate_option[0] ? animate_call_structure() : call_structure();
            event_name = 'others';
            $(window).resize(function() {
                call_structure();
            });
            $(document).on("click", '.' + class_name + ' b.thide', function() {
                $(this).toggleClass('tshow');
                $(this).closest('li').toggleClass('thide').children('ul').toggle();
                call_structure();
            });
            // hover node хийхэд
            $(document).on("hover", '.' + class_name + ' li > div', function(event) {
                if (event.type == 'mouseenter' || event.type == 'mouseover') {
                    $('.' + class_name + ' li > div.current').removeClass('current');
                    $('.' + class_name + ' li > div.children').removeClass('children');
                    $('.' + class_name + ' li > div.parent').removeClass('parent');
                    $(this).addClass('current');
                    $(this).closest('li').children('ul').children('li').children('div').addClass('children');
                    $(this).closest('li').closest('ul').closest('li').children('div').addClass('parent');                    
                    $(this).children('span.highlight, span.delete_action').show();
                } else {
                    $(this).children('span.highlight, span.delete_action').hide();
                }
            });
            // Сонголт хийхйэд 
            $(document).on("click", '.' + class_name + ' span.highlight', function() {                
                $('.' + class_name + ' li.highlight').removeClass('highlight');
                $('.' + class_name + ' li > div.parent').removeClass('parent');
                $('.' + class_name + ' li > div.children').removeClass('children');
                $(this).closest('li').addClass('highlight');
                $('.highlight li > div').addClass('children');
                var _this = $(this).closest('li').closest('ul').closest('li');
                find_parent(_this);                
                // sent ajax here 
                // get 
                var data ={};
                data['id']=$('.current').attr("id");
                $.ajax({
                    type: 'POST',
                    url: '/ecns/flog/index/node_select',
                    data: data,
                    success: function(data) {
                        if(data.status=="success"){                          
                            $('#myTags').tagit('createTag', data.node);
                            // $('#node_id').val($('.current').attr("id"));                
                            var node_id = $('.current').attr("id");
                            // create form bval
                            if($('#create_form').length)
                               $('#create_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+node_id+"' value='"+node_id+"'/>");
                             // herev zasah form bval
                            if($('#edit_form').length)
                               $('#edit_form').append("<input type='hidden' name='node[]' class='node' id ='node_"+node_id+"' value='"+node_id+"'/>");
                        }else{
                            _editthis.closest('form').prepend("<span style='color:red'>"+data.node+'</span>');
                        }
                    }
                });

            });
            // click hiihed 
            $(document).on("click", '.' + class_name + ' span.highlight', function() {
                //if select basic element then its marked as failed
                $(this).parent('div').addClass('failed');
                //console.log('its class is: '+$(this).parent('div').addClass('selected'));
                //add to input selected value
               if (fullwidth_option)
                  $('.' + class_name + '').parent('div').parent('div').scrollLeft(0);
                  //хэрэв Parent ОR хаалга байгаа тохиолдолд Бүгдээс нь сонгож болно
                  if($('.current').parent().parent().parent().children('span').hasClass('OR')) { 
                     console.log("It's OR gate");
                  }else{
                   // console.log($('.current').parent().parent().parent().children('span').hasClass('OR'));
                    $('.' + class_name + ' li > div').not(".parent, .current, .children").closest('li').addClass('tnone');
                    $('.' + class_name + ' li div b.thide.tshow').closest('div').closest('li').children('ul').addClass('tshow');
                    $('.' + class_name + ' li div b.thide').addClass('tnone');
                    if ($('.back_btn').length == 0) {
                         $('body #container .overflow').prepend('<img src="/ecns/assets/ftree/images/back.png" class="back_btn" />');
                    }
                    call_structure();
                 }               
               
                $('.back_btn').click(function() {
                    $('.' + class_name + ' ul.tshow').removeClass('tshow');
                    $('.' + class_name + ' li.tnone').removeClass('tnone');
                    $('.' + class_name + ' li div b.thide').removeClass('tnone');
                    $(this).remove();
                    call_structure();
                });
            });

            function find_parent(_this) {
                if (_this.length > 0) {
                    _this.children('div').addClass('parent');
                    _this = _this.closest('li').closest('ul').closest('li');
                    return find_parent(_this);
                }
            }
          // herev cancel tovch darahad daraah uildluud hiine!!!
           if (delete_option) {
                $(document).on("click", '.' + class_name + ' span.delete_action', function() {
                   var btn = $(this);
                   var tags = $("input[name='tags']"); 
                   // here is seleced id:
                   var selected = btn.parent('div').attr('id');
                   if(tags.length >0){
                      // each selected
                      tags.each(function () {
                          //check if input has node exits 
                         if($(this).val()==btn.parent('div').text()){
                            $("#myTags").tagit("removeTagByLabel", $(this).val());
                            btn.parent('div').removeClass('failed');                                                                                    
                            //tuhain id-r remove hiine                            
                            $("#node_"+selected).remove();                            
                         }
                      });
                   }
                   // console.log($("input[name='tags']").val());
                   //console.log($(this).parent('div').text());
                  // then delete
                });
            }  
          // herev wrap hiisen bol      
        });
    };
})(jQuery);

function showMessage(message, p_class){
   if (!$('p#notification').length){
      //$('#main_wrap').prepend('<p id="notification"></p>');
      $('#nav-bar').prepend('<p id="notification"></p>');
   }
   var paragraph = $('p#notification');
   paragraph.hide();
   paragraph.removeClass();
   // remove all classes from the <p>
   paragraph.addClass(p_class);
   // add the class supplied
   paragraph.html(message);
   // change the text inside
   paragraph.fadeIn('fast', function(){
      paragraph.delay(3000).fadeOut();
    // fade out again after 3 seconds  
   });
  // fade in the paragraph again
}


//event dialog
function _d_event(){    
    //1. Event-цонхийг харуулна
    //2. Insert hiisen huniig haruulna
    //3. sent by employee_id by data ajax
    console.log('event called');
    
    eventForm.dialog('option', 'title', 'Event үүсгэх');
    eventForm.dialog({ 
     buttons: {
        "Хадгалах": function () {
           $('p.feedback', eventForm).removeClass('success, error').addClass('notify').html('Утгуудыг сервер руу илгээж байна...').show();
              // logical function here
              var data = {};
              var inputs = $('input[type="text"], input[type="hidden"], select, textarea', eventForm);
              
              inputs.each(function(){
                var el = $(this);
                data[el.attr('name')] = el.val();
              });

              if(data['closed_datetime']>data['created_datetime'])   data['duration_time']=1;
              else data['duration_time']=0;
              
              $.ajax({
                  type:     'POST',
                  url:    '/ecns/ftree/index/event',
                  data:   data,
                  dataType: 'json', 
                  success:  function(json){ 
                    if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                      //энд үндсэн утгуудыг нэмэх болно.
                      // close the dialog                         
                      eventForm.dialog('close');
                      // show the success message
                      showMessage(json.message, 'success');
                      //reload grid                   
    
                    }                  
                    else{  // ямар нэг юм нэмээгүй тохиолдолд
                      $('p.feedback', eventForm).removeClass('success, notify').addClass('error').html(json.message).show();                        
                    }
                  }
              });
         },
        "Цуцлах": function () {
            eventForm.dialog("close");
         }
      }
  }); 
  eventForm.dialog('open');   
    //window.open('/ecns/flog/ftree_list/add/'+equipment_id);   
}
