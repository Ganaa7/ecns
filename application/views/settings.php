<? include('header.php'); ?>
<div style="margin: 20px 10px;">
	<script>
   $(function(){  
     //when employee load select this element      
      //$('input[name=]')
      $('field_section_id_chzn').on('change', function(evt, params) {
         alert('hello');
      });
   }); 
   </script>
<?  echo $output; ?>
</div>

<script>

<? include('footer.php'); ?>