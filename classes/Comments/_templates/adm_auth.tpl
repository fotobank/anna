<div class="center">
<p class="bb3 p2">����������, ������� ���� ����� � ������, ����� ����� � ���������������� ������.</p>
<div class="form-admin">

<form id="form" name='auth' action='{SCRIPT}' method='post'>
	<fieldset>
		<label>
			<input type="text" name='login' value=""
						 pattern="[?a-zA-Z�-��-�0-9_-\s]{2,30}" placeholder="Login"
						 title="��������� ����� ��������� �����, �����, ������������� � ����.">
		</label>
		<label>
			<input type="password" name='pass' value=""
						 pattern="[0-9a-zA-Z\_\-\!\~\*\:\<\>\+\.]{4,20}" placeholder="Pass"
						 title="��������� ����� ��������� �����, ����� � �����.">
		</label>
<label>
	<input style="width: 100px; margin: 0 80px 0 0; float: right;" type='submit' name='submit' value='ok'>
</label>
	</fieldset>
</form>
</div>
</div>
<div class="clear"></div>
<div style="float: right;">
	<a href='/comments.php' style="padding-right: 20px;">...��������� �����</a>
</div>