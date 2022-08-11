<?
use \Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => Loc::getMessage("LOG_COMP_NAME"),
	"DESCRIPTION" => Loc::getMessage("LOG_COMP_DESCRIPTION"),
	"PATH" => array(
		"ID" => "logs",
		"NAME" => Loc::getMessage("LOG_COMP_PATH_NAME"),
	),
);

?>