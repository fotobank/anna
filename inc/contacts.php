<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:24
 */
defined( '_SECUR' ) or die( '������ ��������' );
?>
<script type="text/javascript">
	// �������� � ���������
	function add_favorite(a) {
		title = document.title;
		url = document.location;
		try {
// Internet Explorer
			window.external.AddFavorite(url, title);
		}
		catch (e) {
			try {
// Mozilla
				window.sidebar.addPanel(title, url, "");
			}
			catch (e) {
// Opera
				if (typeof(opera) == "object") {
					a.rel = "sidebar";
					a.title = title;
					a.url = url;
					return true;
				}
				else {
// Unknown
					alert('��� ������� �� ������������ �������������� ���������� ��������. ������� Ctrl-D ����� �������� �������� � ��������.');
				}
			}
		}
		return false;
	}
</script>

<div class="page6-row1 pad-1">
	<div class="col-14">
		<div class="h-mod">
			<div class="bb-img-red">
				<h3>��� �����:</h3>
			</div>
		</div>
		<dl>
			<dd>������� ��� ���� ����� ��� ������:</dd>
			<dd class="clr-1"><p>�.������ ��.�������� <br>���� ��. ��������, ���� "C�i�".</p></dd>
			<dd>�� ���� �������� ����������� � 9.00 �� 21.00 �� ���������:</dd>
			<dd class="clr-1">
				<p>
					<span>�������:</span>
					<a class="link" onclick="goog_report_conversion('tel: 094-95-53-167');return false;" href="#">
						<span class="phone">(094)95-53-167</span></a>
				</p>
			</dd>
			<dd class="clr-1">
				<p>
					<span>���������:</span>
					<a class="link" onclick="goog_report_conversion('tel: 067-76-84-086');return false;" href="#">
						<span class="phone">(067)76-84-086</span>
					</a>
				</p>
			</dd>
			<dd>��� ������:</dd>
			<dd class="clr-1">
				<p><span>E-mail:</span>
					<span>
						<script type="text/javascript">
							var cl = 'class="link e-mail"';
							var xv1 = 'ailt';
							var xv2 = 'hre';
							var xv3 = 'f="m';
							var xv4 = 'o:';
							var xv5 = 'bestfoto';
							var xv6 = 'i.ua';
							document.write('<a ' + cl + xv2 + xv3 + xv1 + xv4 + xv5 + '@' + xv6 + '">' + 'bestfoto' + '@' + xv6 + '</a>');
						</script>
					</span>
				</p>
			</dd>
		</dl>
		<div class="add-share">
			<a class="link-5" href="#" onclick="return add_favorite(this);">�������� � ���������</a>
		</div>
	</div>

	<div class="col-15">
		<div class="h-mod">
			<div class="bb-img-red">
				<h3 class="p2">�����:</h3>
			</div>
		</div>
		<div>
			<img src="/images/map.png" alt="" class="img-border img-indent-2">
		</div>
	</div>

	<div class="col-16">
		<div class="h-mod">
			<div class="bb-img-red">
				<h3 class="p2">��� ����� �����:</h3>
			</div>
		</div>

		<form id="form" method="post" action="/about.php">
			<fieldset>
				<label><input type='text' name='nick' value='' required
							  pattern="[?a-zA-Z�-��-�0-9_-]{2,20}" placeholder="�������������"
							  title="��������� ����� ��������� � ������� �����, �����, ������������� � ���� � ����������� �� 2 �� 20��.">
				</label>
				<label style="position: absolute; margin-left: -10000px;">
					<input type='text' name='name' value='cool'>
				</label>
				<label>
					<input type='email' name='email' value='' required
						   pattern="[0-9a-zA-Z_]+@[0-9a-zA-Z_^\.-]+\.[a-z]{2,3}"
						   placeholder="E-mail"
						   title="��������� ����� ��������� �����, ����� � �������������.">
				</label>
				<label>
					<textarea name='mess' cols='90' rows='6' placeholder="���������" required maxlength="300"></textarea>
				</label>
				<label style="width: 100px; float: right; margin-top: 6px; margin-right: 2px;">
					<input type='submit' name='submit' value='���������'>
				</label>
			</fieldset>
		</form>
	</div>
</div>