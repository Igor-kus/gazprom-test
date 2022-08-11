<?
use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//Инициализация массива параметров компонента 
//Компонент может работать в режиме AJAX. Реализовано базовыми инструментами (не самая лучшая реализация, лучше посредством реализации интерфейса Controllerable)
//Предусмотрена возможность ограничения выборки снизу, если это необходимо 

$arComponentParameters = [
	 'PARAMETERS' => [
		'AJAX_MODE' => [],
		'LIMIT_DATE' => [
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage("LIMIT_DATE_NAME"),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
		],
	],
];

if ($arCurrentValues['LIMIT_DATE'] == 'Y') {
	$arComponentParameters['PARAMETERS']['DATE_LIMIT_START'] = [
		'PARENT' => 'BASE',
		'NAME' => Loc::getMessage("DATE_LIMIT_START_NAME"),
		'TYPE' => 'CUSTOM',
		'JS_FILE' => '/local/components/custom/log.entrance.list/settings/settings_param.js',
		'JS_EVENT' => 'createCalendarField',
		'JS_DATA' => json_encode([
			'select_date' => $arCurrentValues['DATE_LIMIT_START'], 
			'current_date' => (new DateTime())->format('Y-m-d').' 23:59:59'
		]),
		'DEFAULT' => null,
	];
} else {
	unset($arComponentParameters['PARAMETERS']['DATE_LIMIT_START']);
}
