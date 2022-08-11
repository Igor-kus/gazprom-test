<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<form action="" method="GET">
	<fieldset>
		<legend>Фильтр</legend>
		<label for="date_start">Введите дату начала:</label>
		<input type="datetime-local" id="date_start" name="<?=$arParams['FIELD_NAME_START']?>" <?=($arResult['FILTER']['DATE_START']) ? 'value="'.$arResult['FILTER']['DATE_START'].'"' : ''?> <?=($arResult['FILTER']['MAX_DATE']) ? 'max="'.$arResult['FILTER']['MAX_DATE'].'"' : ''?> <?=($arResult['FILTER']['MIN_DATE']) ? 'min="'.$arResult['FILTER']['MIN_DATE'].'"' : ''?>>
		<br><br>
		<label for="date_end">Введите дату конца:</label>
		<input type="datetime-local" id="date_end" name="<?=$arParams['FIELD_NAME_END']?>" <?=($arResult['FILTER']['DATE_END']) ? 'value="'.$arResult['FILTER']['DATE_END'].'"' : ''?> <?=($arResult['FILTER']['MAX_DATE']) ? 'max="'.$arResult['FILTER']['MAX_DATE'].'"' : ''?> <?=($arResult['FILTER']['MIN_DATE']) ? 'min="'.$arResult['FILTER']['MIN_DATE'].'"' : ''?>>
	</fieldset>
	<input type="submit">
	<p style="color: red"><?=$arResult['FILTER']['ERROR']?></p>
</form>

<?if($arResult['ITEMS']):?>
	<table border="1px">
		<thead>
			<tr>
				<th>Имя пользователя (id)</th>
				<th>Количество успешных авторизаций</th>
				<th>Адрес с которого был произведен последний вход</th>
				<th>Дата последнего входа</th>
			</tr>
		</thead>
		<tbody>
			<?foreach($arResult['ITEMS'] as $arItem):?>
				<tr>
					<td><?=$arItem['USER_NAME']?> (<?=$arItem['USER_ID']?>)</td>
					<td><?=$arItem['COUNT']?></td>
					<td><?=$arItem['LAST_ADDR']?></td>
					<td><?=$arItem['LAST_AUTH_TIME']?></td>
				</tr>
			<?endforeach;?>
		</tbody>
	</table>
<?else:?>
	По данному запросу ничего не найдено
<?endif;?>