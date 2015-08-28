<?php
/**
 * ����� ��� ������ � ����� � ��������
 * @package   LibDateTime
 * @version   1.1
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright ��������� ����� (C) 2000-2015, Gold Dragon
 * @date      01.01.2015
 * @see       http://wiki.lotos-cms.ru/index.php/LibDateTime
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


class LibDateTime
{
	/** @var array : �������� ������� (������������ �����) */
	private static $MONTH_NAME_I = [
		'---------',
		'������',
		'�������',
		'����',
		'������',
		'���',
		'����',
		'����',
		'������',
		'��������',
		'�������',
		'������',
		'�������'
	];

	/** @var array : �������� ������� (����������� �����) */
	private static $MONTH_NAME_R = [
		'---------',
		'������',
		'�������',
		'�����',
		'������',
		'���',
		'����',
		'����',
		'�������',
		'��������',
		'�������',
		'������',
		'�������'
	];

	/** @var array : ������� ��� ���� */
	private static $SUFFIX = ['', '�.', '����'];

	/**
	 * ���������� ���� � ������� [12 ������� 2014 ����]
	 *
	 * @param datetime|null $date   : ���� � ����� ��������
	 * @param int           $suffix : �������� �� ����� ����� ����
	 *                              0 - ��� (�� ���������)
	 *                              1 - �������� �����
	 *                              2 - ������� �����
	 *
	 * @return string : ����
	 *
	 * @example:
	 *         echo LibDateTime::getDateName('01.01.2015');
	 *         echo LibDateTime::getDateName('01.01.2015', 2);
	 */
	public static function getDateName($date = null, $suffix = 0)
		{
			if (empty($date)) {
				$date = date('d.m.Y');
			}

			$suffix = intval($suffix);

			if ($suffix < 0 or $suffix > 2) {
				$suffix = 0;
			}

			$result[] = self::formatDate($date, 'd');
			$result[] = self::$MONTH_NAME_R[self::formatDate($date, 'n')];
			$result[] = self::formatDate($date, 'Y');
			$result[] = self::$SUFFIX[$suffix];

			return trim(implode(' ', $result));
		}

	/**
	 * ���������� ������� ���
	 *
	 * @param datetime      $date1 : �����������
	 * @param datetime|null $date2 : ���������� (���� �� ������ �� �������)
	 *
	 * @return int : �������� (� ����)
	 *
	 * @example:
	 *         echo LibDateTime::getDateDiff('01.01.2015');
	 *         echo LibDateTime::getDateDiff('11.11.2020', '01.01.2015');
	 */
	public static function getDateDiff($date1, $date2 = null)
		{
			if (empty($date2)) {
				$date2 = date('Y-m-d');
			}
			$d2 = new DateTime($date1);
			$d1 = new DateTime($date2);

			$result = intval($d1->diff($d2)->format('%r%a'));
			return $result;
		}

	/**
	 * ���������� �������� � ����
	 *
	 * @param string $date     - ����
	 * @param string $interval - �������� � ������� ISO 8601, ��������, P5D (5 ����) ��� P3Y (3 ����)
	 *                         Y - ���������� ���
	 *                         M - ���������� �������
	 *                         D - ���������� ����
	 * @param string $format   - ������������ ������ (�� ���������  d.m.Y)
	 *
	 * @return string - ���� � ������� $format
	 *
	 * @example:
	 *         echo LibDateTime::getDateAdd('01.01.2015', 'P5D');
	 *         echo LibDateTime::getDateAdd('01.01.2015', 'P5D', 'Y-m-d');
	 */
	public static function getDateAdd($date, $interval, $format = 'd.m.Y')
		{
			$d1 = new DateTime($date);
			$result = $d1->add(new DateInterval($interval))->format($format);
			return $result;
		}

	/**
	 * �������� �������� �� ����
	 *
	 * @param string $date     - ����
	 * @param string $interval - �������� � ������� ISO 8601, ��������, P5D (5 ����) ��� P3Y (3 ����)
	 *                         Y - ���������� ���
	 *                         M - ���������� �������
	 *                         D - ���������� ����
	 * @param string $format   - ������������ ������ (�� ���������  d.m.Y)
	 *
	 * @return string - ���� � ������� $format
	 *
	 * @example:
	 *         echo LibDateTime::getDateSub('01.01.2015', 'P5D');
	 *         echo LibDateTime::getDateSub('01.01.2015', 'P5D', 'Y-m-d');
	 */
	public static function getDateSub($date, $interval, $format = 'd.m.Y')
		{
			$d1 = new DateTime($date);
			$result = $d1->sub(new DateInterval($interval))->format($format);
			return $result;
		}

	/**
	 * ����������� ���� � ������ ������
	 *
	 * @param string $date   - ���� (���� �� ������ �� �������)
	 * @param string $format - ������������ ������ (�� ���������  d.m.Y)
	 *
	 * @return string
	 *
	 * @example:
	 *         echo LibDateTime::formatDate();
	 *         echo LibDateTime::formatDate('', 'Y-m-d');
	 *         echo LibDateTime::formatDate('01.01.2015', 'Y-m-d');
	 */
	public static function formatDate($date = null, $format = 'd.m.Y')
		{
			if (empty($date)) {
				$date = date('d.m.Y');
			}

			$date_obj = new DateTime($date);
			return $date_obj->format($format);
		}

	/**
	 * ���������� ���������� ������ ���� ������
	 *
	 * @param int      $month       : ����� ������
	 * @param int      $year        : ���
	 * @param string   $tag_name    : ��� ����
	 * @param null|int $selected    : �������� ��� ������
	 * @param string   $tag_attribs : �������������� �������� ����
	 * @param int      $calendar    : ���������, ������������ ��� ����������
	 *                              0 or CAL_GREGORIAN - ������������� ��������� (�� ���������)
	 *                              1 or CAL_JULIAN - ��������� ���������
	 *                              2 or CAL_JEWISH - ��������� ���������
	 *                              3 or CAL_FRENCH - ��������� �� ��� ����������� ���������
	 *
	 * @return string : HTML-���
	 *
	 * @example:
	 *         echo LibDateTime::selectDays(2, 2015, 'qqq');
	 *         echo LibDateTime::selectDays(2, 2015, 'qqq', 12);
	 *         echo LibDateTime::selectDays(2, 2015, 'qqq', '', ' id="qq1" style="color:#900"');
	 */
	public static function selectDays($month, $year, $tag_name, $selected = null, $tag_attribs = '', $calendar = CAL_GREGORIAN)
		{
			$result = '';
			$_day = cal_days_in_month($calendar, $month, $year);

			$result .= '<select name="' . $tag_name . ' ' . $tag_attribs . '">';

			for ($i = 1; $i <= $_day; $i++) {
				$extra = ($i == $selected) ? ' selected="selected"' : '';
				$result .= '<option value="' . $i . '" ' . $extra . '>' . $i . '</option>';
			}

			$result .= '</select>';

			return $result;
		}

	/**
	 * ���������� ���������� ������ �������
	 *
	 * @param string   $tag_name    : ��� ����
	 * @param null|int $selected    : �������� ��� ������
	 * @param string   $tag_attribs : �������������� �������� ����
	 * @param bool     $padez       : �����
	 *                              true : ����������� (�� ���������)
	 *                              false : �����������
	 *
	 * @return string : HTML-���
	 *
	 * @example:
	 *         echo LibDateTime::selectMonth('qqq');
	 *         echo LibDateTime::selectMonth('qqq', 5);
	 *         echo LibDateTime::selectMonth('qqq', '', ' id="qq1" style="color:#900"', false);
	 */
	public static function selectMonth($tag_name, $selected = null, $tag_attribs = '', $padez = true)
		{
			$result = '';
			$month = ($padez) ? self::$MONTH_NAME_I : self::$MONTH_NAME_R;

			$result .= '<select name="' . $tag_name . ' ' . $tag_attribs . '">';

			for ($i = 1; $i < 13; $i++) {
				$extra = ($i == $selected) ? ' selected="selected"' : '';
				$result .= '<option value="' . $i . '" ' . $extra . '>' . $month[$i] . '</option>';
			}

			$result .= '</select>';

			return $result;
		}

	/**
	 * ���������� ���������� ������ �����
	 *
	 * @param int $year1 : ��������� ���
	 * @param string   $tag_name    : ��� ����
	 * @param null|int $selected    : �������� ��� ������
	 * @param string   $tag_attribs : �������������� �������� ����
	 * @param null|int $year2 : �������� ��� (���� �� �����, �� �������)
	 *
	 * @return string : HTML-���
	 *
	 * @example:
	 *         echo LibDateTime::selectYear(2000, 'qqq');
	 *         echo LibDateTime::selectYear(2000, 'qqq', 2013);
	 *         echo LibDateTime::selectYear(2000, 'qqq', '', ' id="qq1" style="color:#900"', 2020);
	 */
	public static function selectYear($year1, $tag_name, $selected = null, $tag_attribs = '', $year2 = null)
		{
			$result = '';
			if (empty($year2)) {
				$year2 = date('Y');
			}

			$result .= '<select name="' . $tag_name . ' ' . $tag_attribs . '">';

			for ($i = $year1; $i <= $year2; $i++) {
				$extra = ($i == $selected) ? ' selected="selected"' : '';
				$result .= '<option value="' . $i . '" ' . $extra . '>' . $i . '</option>';
			}

			$result .= '</select>';

			return $result;

		}
}