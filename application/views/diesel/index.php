<?=$library_src; ?>

<div id="main_wrap" style="margin: 20px auto; width: 1260px">
	<table id="grid" class="scroll" cellpadding="0" cellspacing="0">
	</table>
	<div id="pager" class="scroll" style="text-align: center;"></div>
	<input type="hidden" name="action" id="action" value=<?=$action?>>
</div>
<form id="fuel" action="" method="POST">
	<p class="feedback"></p>
	<div class="field">
		<label for="location">Байршил:</label> <span id="location"></span>
	</div>
	<div class="field">
		<label for="main_equipment">Байгууламж:</label> <span
			id="main_equipment"></span>
	</div>
	<div class="field">
		<label for="equipment">Тоног төхөөрөмж:</label> <span id="equipment"></span>
	</div>
	<div class="field">
		<label for="power">Чадал:</label> <span id="power"></span>
	</div>
	<div class="field">
		<label for="consumption">Зарцуулалт:</label> <span id="consumption"></span>/литр/
	</div>
	<div class="field">
		<label>Банкний хэмжээ:</label> <span id="bank"></span> /литр/
	</div>
	<div class="field">
		<label>Нөөц ёнкость:</label> <span id="capacity"></span> /литр/
	</div>
	<div class="field">
		<label>Байгаа түлшний хэмжээ:</label> <input type="text"
			id="bank_fuel" name="bank_fuel"> /литр /
	</div>
	<input type="hidden" name="bank_id" id="bank_id">
	</div>
</form>

<form id="fill" action="" method="POST">
	<p class="feedback"></p>
	<div class="field">
		<label for="location">Байршил:</label> <span id="location"></span>
	</div>
	<div class="field">
		<label for="main_equipment">Байгууламж:</label> <span
			id="main_equipment"></span>
	</div>
	<div class="field">
		<label for="equipment">Тоног төхөөрөмж:</label> <span id="equipment"></span>
	</div>
	<div class="field">
		<label for="power">Чадал:</label> <span id="power"></span>
	</div>
	<div class="field">
		<label for="consumption">Зарцуулалт:</label> <span id="consumption"></span>/литр/
	</div>
	<div class="field">
		<label>Банкний хэмжээ:</label> <span id="bank"></span> /литр/
	</div>
	<div class="field">
		<label>Нөөц ёнкость:</label> <span id="capacity"></span> /литр/
	</div>
	<div class="field">
		<label>Нийт цэнэглэсэн түлшний хэмжээ:</label>
		 <input type="text"
			id="fuel" name="fuel"> /литр /
		<p>Оруулахад Нийт банкний үлдэгдэл автоматаар нэмэгдэхийг анхаарана уу!</p>			
	</div>

	<input type="hidden" name="bank_id" id="bank_id">
	</div>
</form>