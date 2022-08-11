function createCalendarField(arParams) {
	var dataCalendar = JSON.parse(arParams.data);
	var dateStartField = arParams.oCont.appendChild(
		BX.create(
			'input', 
			{
				'attrs': {
					'type':'datetime-local', 
					'value': dataCalendar.select_date, 
					'max': dataCalendar.current_date
				}
			}
		)
	);
	dateStartField.onchange = BX.delegate(function(){
		arParams.oInput.value = this.value;
	}); 
}