<aside>
	<div id="main" class="page1-row1 tabs clearfix">
<div class="center">
	<div class="form-comments">
<p class="m1">�������������� ��������� #{ID}:</p>
<form id='form2' name='edit' action='{SCRIPT}?mode=edit&id={ID}' method='post'>


	<label>	<strong class="fleft">�����:</strong>
		<input style="margin-left: 50px;" type='text' name='nick' value='{POSTER}' required
						pattern="[?a-zA-Z�-��-�0-9_-\s]{2,20}" placeholder="��� ��� ���"
						title="��������� ����� ��������� � ������� �����, �����, ������������� � ����.">
	</label>

	<label>	<strong class="fleft">E-mail:</strong>
		<input style="margin-left: 50px;" type='email' name='email' value='{EMAIL}'
					 pattern="[0-9a-zA-Z_]+@[0-9a-zA-Z_^\.-]+\.[a-z]{2,3}"
					 placeholder="�� �����������"
					 title="��������� ����� ��������� �����, ����� � �������������.">
	</label>

	<label><strong class="fleft">���������:</strong>
		<textarea style="margin: 0 0 10px 10px;" name='mess' cols='90' rows='10' required
							title="���� ��������� ������!">{TEXT}</textarea>
	</label>
    <label class="fleft"><span class="fleft">������ ���������: </span><span style="color: #FF9900; "><b>{STATUS}</b></span>
		<br><span class="fleft"> ������� ��� �������: </span>

		<select style="display: inline-block;" name='flag'>
			<option value='1'>�������</option>
			<option value='0'>�������</option>
		</select>

	</label>
	<label>
	<input style="margin: 0 150px 30px 0;" type='submit' value='OK' name='submit'>
	</label>
</form>
		<div class="clear"></div>
<span class="s"> ����� �� ������ �������� ���� <a href='{SCRIPT}?mode=reply&id={ID}' target=_blank>�����</a> �� ��� ��������� ���
	<a href='{SCRIPT}?mode=del&id={ID}' target=_blank>�������</a> ��� �� ���� ������.</span>
		<div class="center"><span class="s">���������� �� ������������ HTML-����, ������ ��� ��� �� ����� ����� �������� ��� ������ ��������� � �������� �����.</span></div>
</div></div>
	</div>
</aside>