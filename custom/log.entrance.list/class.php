<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class LogEntranceList extends CBitrixComponent {
	//$arFilterLog - массив полей для фильтрации в запросе
	private $arFilterLog = [];
	//Элементы массива $arParams['FIELD_NAME_START'] и $arParams['FIELD_NAME_END'] хранят name полей для фильтрации
    public function onPrepareComponentParams($params) {
        $params['FIELD_NAME_START'] = 'date_start';
		$params['FIELD_NAME_END'] = 'date_end';
		
		return $params;
    }

    public function executeComponent() {
		$this->validateRequest();
		$this->getLogList();
        $this->includeComponentTemplate();
    }
	
	public function validateRequest() {
		//$request определен в родительском классе
		$this->arFilterLog = [];
		$this->arFilterLog['AUDIT_TYPE_ID'] = 'USER_AUTHORIZE';
		
		if($this->arParams['LIMIT_DATE'] == 'Y' && !empty($this->arParams['DATE_LIMIT_START'])) {
			$dateLimitStart = new DateTime($this->arParams['DATE_LIMIT_START']);
			$this->arFilterLog['>=TIMESTAMP_X'] = $dateLimitStart->format('d.m.Y H:i:s'); 
			$this->arResult['FILTER']['MIN_DATE'] = $dateLimitStart->format('Y-m-d').' 00:00:00';
		}
		
		if($this->request[$this->arParams['FIELD_NAME_START']]) {
			$dateStart = new DateTime($this->request[$this->arParams['FIELD_NAME_START']]);
			if($dateStart < $dateLimitStart) $dateStart = $dateLimitStart;
		}
		
		if($this->request[$this->arParams['FIELD_NAME_END']]) {
			$dateEnd = new DateTime($this->request[$this->arParams['FIELD_NAME_END']]);
		}
		
		if($dateStart && $dateEnd && $dateStart > $dateEnd) {
			$this->arResult['FILTER']['ERROR'] = Loc::getMessage("ERROR_FILTER");
		} else {
			if($dateStart) {
				$this->arFilterLog['>=TIMESTAMP_X'] = $dateStart->format('d.m.Y H:i:s'); 
				$this->arResult['FILTER']['DATE_START'] = $dateStart->format('Y-m-d H:i:s'); 
			}
			if($dateEnd) {
				$this->arFilterLog['<=TIMESTAMP_X'] = $dateEnd->format('d.m.Y H:i:s'); 
				$this->arResult['FILTER']['DATE_END'] = $dateEnd->format('Y-m-d H:i:s'); 
			}
		}
		$this->arResult['FILTER']['MAX_DATE'] = (new DateTime())->format('Y-m-d').' 23:59:59';
	}
	
	public function getLogList() {
		$objLogList = Bitrix\Main\EventLog\Internal\EventLogTable::getList(
			[
				'select' => ['ID', 'TIMESTAMP_X', 'AUDIT_TYPE_ID', 'REMOTE_ADDR', 'SITE_ID', 'USER_ID', 'ITEM_ID', 'USER_NAME' => 'USER_LIST.SHORT_NAME'],
				'filter' => $this->arFilterLog,
				'order' => ['TIMESTAMP_X' => 'ASC'],
				'runtime' => [
					'USER_LIST' => [
						'data_type' => '\Bitrix\Main\UserTable',
						'reference' => [
							'=this.USER_ID' => 'ref.ID',
						],
						'join_type' => 'inner'
					]
				]
			]
		);

		while ($logItem = $objLogList->fetch()) {
			if(array_key_exists($logItem['USER_ID'], $this->arResult['ITEMS'])) {
				$this->arResult['ITEMS'][$logItem['USER_ID']]['COUNT']++;
			} else {
				$this->arResult['ITEMS'][$logItem['USER_ID']]['COUNT'] = 1;
				$this->arResult['ITEMS'][$logItem['USER_ID']]['USER_NAME'] = $logItem['USER_NAME'];
				$this->arResult['ITEMS'][$logItem['USER_ID']]['USER_ID'] = $logItem['USER_ID'];
			}
			
			$this->arResult['ITEMS'][$logItem['USER_ID']]['LAST_ADDR'] = $logItem['REMOTE_ADDR'];
			$this->arResult['ITEMS'][$logItem['USER_ID']]['LAST_AUTH_TIME'] = $logItem['TIMESTAMP_X']->format('d.m.Y H:i:s');
			
		}	
	}
}