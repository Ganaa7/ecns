<div align="center" style="margin: 15px 20px 20px">
	<table id="order_grid" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="order_pager" class="scroll" style="text-align: center;"></div>
</div>
<input type="hidden" id="order_id" name="order_id" />
<!-- Call dialog -->

<div id="dialog_c" class="order" title="Тэмдэглэл хийх">
	<form name="cancel_order" method="post" id="cancel_order"
		action="/ecns/warehouse/orderlist/" />
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<p>Тайлбар:</p>
		<textarea name="comment" id="cancel_comment" rows="7" cols="37" /></textarea>
		<span>Хариуцсан Хан/Инж:</span>
          <? echo form_dropdown('steward_id', $steward, null, "id='steward_id'"); ?>  
    </div>
	</form>
</div>

<!-- Order register-->
<div id="dialog_r" class="register" title="Захиалга бүртгэх">
	<div class="ui-widget" style="margin-top: 1em; font-family: Arial">
		<table>
			<tr>
				<td><strong>Захиалга №:</strong></td>
				<td><input type="input" name="orderno" id="orderno" size="6" /></td>
			</tr>
			<tr>
				<td><strong>Захиалга огноо:</strong></td>
				<td><input type="input" id="regdate" name="regdate" size="12" /></td>
			</tr>
			<tr>
				<td><strong>Хариуцсан Хан/Инж:</strong></td>
				<td>
                <? echo form_dropdown('steward_id', $steward, null, "id='steward_id'"); ?>  
                </td>
			</tr>
		</table>
	</div>
</div>


