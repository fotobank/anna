<?php

AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', array('CIblockLimitImg', 'ChekImage'));
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', array('CIblockLimitImg', 'ChekImage'));
class CIblockLimitImg {
	public static function ChekImage($arFields) {
		// settings
		$arPropCode = array('PICS');
		$arIBs = array(3);
		$maxW = '200';
		$maxH = '300';
		$bbCheckBasePic = true;
		//----------------
		$arFiles = array();
		if (in_array($arFields['IBLOCK_ID'], $arIBs) && $arFields['ACTIVE']=='Y') {
			if (!empty($arPropCode)) {
				$rsProp = CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'PROPERTY_TYPE' => 'F'));
				while ($arProp = $rsProp->Fetch()) {
					if (!in_array($arProp['CODE'], $arPropCode)) {
						continue;
					}
					if (isset($_FILES['PROP']) && isset($_FILES['PROP']['tmp_name']) && isset($_FILES['PROP']['tmp_name'][$arProp['ID']])) {
						foreach ($_FILES['PROP']['tmp_name'][$arProp['ID']] as $k => $path) {
							if (!$_FILES['PROP']['error'][$arProp['ID']][$k]) {
								$arFiles[] = $path;
							}
						}
					}
					if (isset($arFields['PROPERTY_VALUES'][$arProp['ID']])) {
						foreach ($arFields['PROPERTY_VALUES'][$arProp['ID']] as $k => $arPr) {
							if (!is_array($arPr['VALUE']) && $arPr['VALUE']>0) {
								$arFiles[] = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($arPr['VALUE']);
							}
						}
					}
				}
			}
			if ($bbCheckBasePic) {
				foreach (array('PREVIEW', 'DETAIL') as $code) {
					if (isset($_FILES[$code.'_PICTURE']) && !$_FILES[$code.'_PICTURE']['error']) {
						$arFiles[] = $_FILES[$code.'_PICTURE']['tmp_name'];
					}
					if (isset($arFields[$code.'_PICTURE']) && $arFields[$code.'_PICTURE']['del']!='Y' && $arFields[$code.'_PICTURE']['old_file']>0) {
						$arFiles[] = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($arFields[$code.'_PICTURE']['old_file']);
					}
				}
			}
			if (!empty($arFiles)) {
				foreach ($arFiles as $path) {
					list($w, $h) = getimagesize($path);
					if ($w > $maxW || $h > $maxH) {
						$GLOBALS['APPLICATION']->ThrowException('Размеры некоторых изображений не соответствуют дозволенным. Пожалуйста, обрежьте их. Или сохраните элемент сначала неактивным.');
						return false;
					}
				}
			}
		}
	}
}