<?php
Yii::import('zii.widgets.jui.CJuiAutoComplete');
class JuiAutoComplete extends CJuiAutoComplete
{
	/**
	 * 
	 * @var mixed CHtml::normalizeUrl() will be applied to this property to convert the property
	 */
	public $ajaxlink;
	
	/**
	 * 
	 * @var string value of the autocomplete field on first load.
	 */
	public $valuetext;
	
	public function init()
	{
		if(!isset($this->options['minLength']))$this->options['minLength']=2; //LO: Defaults to 2 if and ONLY if minLength is not specified
		parent::init();
	}
}