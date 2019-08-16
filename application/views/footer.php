
		<div id="footer">
			
		   Холбоо, навигаци, ажиглалтын алба:: Цахим систем &copy; <?php echo date("Y");?>. Хуудсыг <strong
					title='Юу байна! <? echo $this->session->userdata('fullname');?>'>{elapsed_time}</strong>
				секундэд дуудлаа.
			
		</div>

	</div>

	<script>

		  $(function() {

		  	 //хэрэв Search form declared

			if($('#search').length >0){

			    $( "#search" ).autocomplete({

			       source: base_url+"/document/filter/",

			       minLength: 3,

			       select: function( event, ui ) {

			          // console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );

			          $('#file_id').val(ui.item.id);
			          $('#filename').val(ui.item.value);
			       }
		    	});

			}

		  });
		
		 

	</script>
</body>
</html>