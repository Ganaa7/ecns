<? $this->load->view('header'); ?> 
<?php
if ($this->session->userdata ( 'message' )) {
	echo "<div id='message' align='center'>";
	echo "<p>";
	echo $this->session->userdata ( 'message' );
	echo "</p>";
	$this->session->unset_userdata ( 'message' );
	echo "</div>";
}
?>
<div style='margin-top: 10px; margin-left: 10px;'>
	<div id="emp_list">
    <?php $atribute = array('name' => 'employee'); ?>
    <?= form_open('alert/set_email/shiftlog', $atribute)?>
        <h3>Имэйл илгээх Албан тушаалтнууд</h3> 
        <?php
								foreach ( $result as $row ) {
									$data = array (
											'id' => $row->id,
											'value' => 'accept',
											'checked' => TRUE 
									);
								}
								?>
        <table class="style_tb">
			<tbody>
				<tr align="center"">
					<th valign="middle"><h3>#</h3></th>
					<th><h3>Хэсэг</h3></th>
					<th><h3>Албан тушаал</h3></th>
				</tr>
            
                <?php
																foreach ( $result as $row ) {
																	echo "<tr>";
																	echo "<td>";
																	if ($row->shiftlog == 'Y') {
																		echo "<input type ='checkbox' name = 'id[]' value='$row->id' checked ='TRUE'/>";
																	} else {
																		echo "<input type ='checkbox' name = 'id[]' value='$row->id'/>";
																	}
																	echo "</td>";
																	echo "<td>";
																	echo $row->section_name;
																	echo "</td>";
																	echo "<td>";
																	echo $row->position;
																	echo "</td>";
																	echo "</tr>";
																}
																?>
            </tbody>
		</table>
		<div class="submits">
            <?=form_submit('email', 'Хадгалах')?>
            <? $attr = array('class' =>'button good'); ?>
            <?=anchor('', 'Болих', $attr);?>           
        </div>
    <?php echo $this->pagination->create_links(); ?>
    <?= form_close()?>
    </div>
</div>

<? $this->load->view('footer'); ?>

