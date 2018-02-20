<?php
Yii::import('zii.widgets.jui.CJuiDatePicker');
class JuiDatePicker extends CJuiDatePicker
{
	/**
	* Run this widget.
	* This method registers necessary javascript and renders the needed HTML code.
	*/
	public function run()
	{
		$originalValue = '';
		if($this->hasModel())
		{
			$attribute = $this->attribute;
			if(!empty($this->model->$attribute))
			{
				$originalValue = $this->model->$attribute;
				$this->model->$attribute = date(Yii::app()->params['datepick_phpDateFormat'], strtotime($this->model->$attribute));
			}
		}
		else
		{
			if(!empty($this->value))
			{
				$originalValue = $this->value;
				$this->value = date(Yii::app()->params['datepick_phpDateFormat'], strtotime($this->value));
			}
		}
		
		parent::run();
		
		if($originalValue !== '')
		{
			if($this->hasModel()) $this->model->$attribute = $originalValue;
			else $this->value = $originalValue;
		}
	}
}