<?php $this->load->view('header'); ?>
<style>
.class-form {
	padding-left: 50px;
}

.class-form label {
	font-weight: bold;
}

.class-form .field {
	padding: 5px 0;
}
</style>
<br>
<br>
<form action="" class="class-form">
	<div class="field">
		<label for="">Хэсэг:</label> <select name="section_id" id="section_id"
			style="width: 150px; margin: 0 3px;">
			<option value="0" selected="selected">Бүх хэсэг</option>
			<option value="1">Холбоо</option>
			<option value="2">Навигаци</option>
			<option value="3">Ажиглалт</option>
			<option value="4">Гэрэл суулт, цахилгаан</option>
		</select>
	</div>
	<div class="field">
		<label for="">Тасаг:</label> <select name="sector_id" id="sector_id"
			style="width: 170px; margin: 0 3px;">
			<option value="0" selected="selected">Бүх тасаг</option>
			<option value="12">Радио холбоо, дамжууллын тасаг</option>
			<option value="13">Өгөгдөл, мэдээллийн технологийн тасаг</option>
			<option value="14">Шуурхай ажиллагааны тасаг</option>
			<option value="21">Техник ашиглалтын тасаг</option>
			<option value="22">Шуурхай ажиллагааны тасаг</option>
			<option value="31">Радио локаторын тасаг</option>
			<option value="32">Автоматжуулалтын тасаг</option>
			<option value="41">Хувиарлах сүлжээний тасаг</option>
			<option value="42">Гэрэл суултын тасаг</option>
			<option value="43">Дизелийн тасаг</option>
			<option value="44">Шуурхай ажиллагааны тасаг</option>
		</select>
	</div>
	<div class="field">
		<label for="">Сэлбэг:</label> <select name="size" id="size">
			<option value="40">40</option>
			<option value="50">50</option>
			<option value="60">60</option>
			<option value="70">70</option>
		</select>
	</div>
	<div class="field">
		<label for="">Код төрөл:</label> <select name="codetype" id="codetype">
			<option value="0">Сонго</option>
			<option value="01">E&M module 4 channel</option>
			<option value="02">Кабель телефоны 4 жийлтэй</option>
			<option value="03">Тэжээлийн кабель 220 V</option>
			<option value="04">Power Supply FDW13</option>
			<option value="05">VSU2</option>
		</select> <a href="#">Шинэ сэлбэг</a>
	</div>
	<div class="field">
		<label for="orentation">Төрөл:</label> <select name="orentation"
			id="orentation">
			<option value="Horizontal">Багцлах</option>
			<option value="Vertical">Ширхэгээр</option>
		</select>
	</div>
	<div class="field">
		<label for="orentation">Хэмжих нэгж:</label> <select name="orentation"
			id="orentation">
			<option value="Horizontal">Ширхэг</option>
			<option value="Vertical">Метр</option>
		</select>
	</div>
	<div class="field">
		<label for="orentation">Парт номер:</label> <input type="text"
			name="partnumber">
	</div>
	<div class="field">
		<label for="orentation">Орлогод авсан огноо:</label> <input
			type="text" name="date">
	</div>
	<div class="field" style="padding-left: 250px;">
		<input type="submit" name="generate" value="Үүсгэх">
	</div>
</form>
<?php

if (isset ( $image ) && $image) {
	header ( 'Content-type: image/png' );
	imagepng ( $image );
	imagedestroy($image);   
}
?>

<?php $this->load->view('footer'); ?>