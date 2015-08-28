<?php
/**
 * Класс для работы с Датой и Временем
 * @package   LibDateTime
 * @version   1.1
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright Авторские права (C) 2000-2015, Gold Dragon
 * @date      01.01.2015
 * @see       http://wiki.lotos-cms.ru/index.php/LibDateTime
 * @license   MIT License: http://opensource.org/licenses/MIT
 */


class LibDateTime
{
	/** @var array : название месяцев (именительный падеж) */
	private static $MONTH_NAME_I = [
		'---------',
		'Январь',
		'Февраль',
		'Март',
		'Апрель',
		'Май',
		'Июнь',
		'Июль',
		'Август',
		'Сентябрь',
		'Октябрь',
		'Ноябрь',
		'Декабрь'
	];

	/** @var array : название месяцев (родительный падеж) */
	private static $MONTH_NAME_R = [
		'---------',
		'января',
		'февраля',
		'марта',
		'апреля',
		'мая',
		'июня',
		'июля',
		'августа',
		'сентября',
		'октября',
		'ноября',
		'декабря'
	];

	/** @var array : суффикс для года */
	private static $SUFFIX = ['', 'г.', 'года'];

	/**
	 * Возвращает дату в формате [12 декабря 2014 года]
	 *
	 * @param datetime|null $date   : дата в любом форматие
	 * @param int           $suffix : выводить ли слово после даты
	 *                              0 - нет (по умолчанию)
	 *                              1 - короткая форма
	 *                              2 - длинная форма
	 *
	 * @return string : дата
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
	 * Возвращает разницу дат
	 *
	 * @param datetime      $date1 : уменьшаемое
	 * @param datetime|null $date2 : вычитаемое (если не задана то текущая)
	 *
	 * @return int : разность (в днях)
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
	 * Прибавляет интервал к дате
	 *
	 * @param string $date     - дата
	 * @param string $interval - интервал в формате ISO 8601, например, P5D (5 дней) или P3Y (3 года)
	 *                         Y - Количество лет
	 *                         M - Количество месяцев
	 *                         D - Количество дней
	 * @param string $format   - возвращаемый формат (по умолчанию  d.m.Y)
	 *
	 * @return string - дата в формате $format
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
	 * Отнимает интервал от дате
	 *
	 * @param string $date     - дата
	 * @param string $interval - интервал в формате ISO 8601, например, P5D (5 дней) или P3Y (3 года)
	 *                         Y - Количество лет
	 *                         M - Количество месяцев
	 *                         D - Количество дней
	 * @param string $format   - возвращаемый формат (по умолчанию  d.m.Y)
	 *
	 * @return string - дата в формате $format
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
	 * Преобразует дату в нужный формат
	 *
	 * @param string $date   - дата (если не задана то текущая)
	 * @param string $format - возвращаемый формат (по умолчанию  d.m.Y)
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
	 * Возвращает выпадающий список дней месяца
	 *
	 * @param int      $month       : номер месяца
	 * @param int      $year        : год
	 * @param string   $tag_name    : имя тега
	 * @param null|int $selected    : значение для выбора
	 * @param string   $tag_attribs : дополнительные атрибуты тега
	 * @param int      $calendar    : Календарь, используемый для вычисления
	 *                              0 or CAL_GREGORIAN - Грегорианский календарь (по умолчанию)
	 *                              1 or CAL_JULIAN - Юлианский календарь
	 *                              2 or CAL_JEWISH - Еврейский календарь
	 *                              3 or CAL_FRENCH - Календарь со дня Французской революции
	 *
	 * @return string : HTML-код
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
	 * Возвращает выпадающий список месяцев
	 *
	 * @param string   $tag_name    : имя тега
	 * @param null|int $selected    : значение для выбора
	 * @param string   $tag_attribs : дополнительные атрибуты тега
	 * @param bool     $padez       : падеж
	 *                              true : именительны (по умолчанию)
	 *                              false : родительный
	 *
	 * @return string : HTML-код
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
	 * Возвращает выпадающий список годов
	 *
	 * @param int $year1 : начальный год
	 * @param string   $tag_name    : имя тега
	 * @param null|int $selected    : значение для выбора
	 * @param string   $tag_attribs : дополнительные атрибуты тега
	 * @param null|int $year2 : конечный год (если не задан, то текущий)
	 *
	 * @return string : HTML-код
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