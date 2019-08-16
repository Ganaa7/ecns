<style>
.field {
	display: block;
	width: auto;
	line-height: 2em;
	margin: 5px 0px;
}

th, td {
	padding: 5px;
	text-align: left;
}

th, td {
	border-bottom: 1px solid #ddd;
}
</style>
<div id="main_wrap" style="margin: 10px 20px 20px">
    <div id="wrapper" align="center">
        <fieldset>
            <legend>
               Баркод хайх:
            </legend>
            <input type="text" name="barcode" id="barcode">
            <input type="button" name="filter" id="barcode_filter" value="Хайх">
        </fieldset>
        
    </div>
    	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
</div>


<form id="view_dialog" action="" method="POST">
	<p class="feedback"></p>
	<div class="field">
		<label for="section">Хэсэг:</label> <input type="text" name="section"
			id="section" class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="sector">Тасаг:</label> <input type="text" name="sector"
			id="sector" class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="equipment">Тоног төхөөрөмж:</label> <input type="text"
			size="30" name="equipment" id="equipment"
			class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="spare">Сэлбэг:</label> <input type="text" name="spare"
			id="spare" class="text ui-widget-content ui-corner-all" disabled>
	</div>
	<div class="field">
		<label for="spare">Төрөл:</label> <input type="text" name="spare"
			id="sparetype" class="text ui-widget-content ui-corner-all" disabled>
	</div>

	<!-- <div class="field">
		<label for="">Орлого огноо:</label> <span id="income_date"></span>
	</div>
	<div class="field">
		<label for="">Насжилт:</label> <span id="years_old"></span>
	</div> -->
	<div class="field">
		<label>Тоо:</label> <span id="qty"></span>
	</div>
	<!-- <div class="field">
		<label>Нэг бүрийн үнэ:</label> <span id="amt"></span>
	</div> -->
	<div class="field">
		<label>Нийт үнэ:</label> <span id="total"></span>
	</div>
	<div class="field">
		<label>Агуулах:</label> <span id="warehouse"></span>
	</div>
	<div class="field">
		<table width="100%" id="table_spare" cellspacing="0" cellpadding="0"
			align="center">
			<thead>
				<tr>
					<th align="left">Тавиур</th>
					<th align="left">Too</th>
					<th align="left">Үнэ</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</form>
<script src="<?php echo base_url();?>assets/inputmask/jquery.inputmask.bundle.js"></script>