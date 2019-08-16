var timer = $.timer(function() {    
    var mydata = { token: $('#token').val() };
       $.ajax({
         type:    'POST',
         url:base_url+'/alert/emailer/',         
         dataType: 'json', 
         data: mydata,
         success:  function(json) {
//           console.log(json);
            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                 // close the dialog
                 console.log(json.message);
           }
         }
       });      
});
timer.set({ time : 300000, autostart : true });

//300000


//notification every 3 minutes check notify bar

// var new_timer = $.timer(function() {    
//     var mydata = { token: $('#token').val() };
//        $.ajax({
//          type:    'POST',
//          url:    '/ecns/alert/notify/',         
//          dataType: 'json', 
//          data: mydata,
//          success:  function(json) {
// //           console.log(json);
//             if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
//                  // close the dialog
//                  //console.log(json.message);
//                  $('#notifier').addClass('badge');
//                  $("#notifier").text(json.count);
//            }
//          }
//        });      
// });
// new_timer.set({ time : 10000, autostart : true });



$(function() {
   // notification read link here!
   $("#notificationLink").click(function(){
      // click tuhain engineer-n notify_id-r msg bichne hiihed update hiine
      var mydata = { token: $('#token').val() };
       $.ajax({
         type:    'POST',
         url:    '/ecns/alert/notified/',
         dataType: 'json', 
         data: mydata,
         success:  function(json) {
            if (json.status == "success") {      // амжилттай нэмсэн тохиолдолд
                 $("#notifier").text('');
                 $("#notifier").removeClass('badge');
           }
         }
       });            
      $("#notificationContainer").fadeToggle(300);
      $("#notification_count").fadeOut("slow");      
      return false;
   });   

  $(document).click(function(){
     $("#notificationContainer").hide();
  });

  //Popup Click
  $("#notificationContainer").click(function(){
    return false
    });

});


