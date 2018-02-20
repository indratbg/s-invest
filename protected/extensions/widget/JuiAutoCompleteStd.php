<?php
Yii::import('application.extensions.widget.JuiAutoComplete');
class JuiAutoCompleteStd extends JuiAutoComplete
{
	public function run()
	{
		list($name,$id)=$this->resolveNameID();
		$id='at_'.$id;
	
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
	
		$jxlink = CHtml::normalizeUrl($this->ajaxlink);
		$this->options['source']="js:function(request, response) {
									$.ajax({
										type: 'POST',
										url: '{$jxlink}',
										data: {'term': request.term},
										dataType: 'json',
										success: function(data) {
											$(\"#{$id}\").parent().find(\".hf-autocomplete\").val(\"\");
											$(\"#{$id}\").parent().find(\".note-autocomplete\").html(\"\");
											response(data);
										},
									});
								}";
		
		$this->options['select']='js:function(event, ui) {
											$(this).parent().find(".hf-autocomplete").val(ui.item.value);
											//$(this).parent().find(".note-autocomplete").html(ui.item.value);
											return false;
										}';
		$this->options['focus']='js:function(event, ui) {
											$(this).val(ui.item.label);
											return false;
										}';
		$this->htmlOptions['onfocus']='/*this.value="";*/ $(this).parent().find(".hf-autocomplete").val(""); $(this).parent().find(".note-autocomplete").html("");';
		
		//Custom for 'CREATE' form.
		if(!isset($this->valuetext)) $this->valuetext = (isset($_POST[$id]) && $this->model->hasErrors())? $_POST[$id] : '';
		else $this->valuetext = (empty($this->valuetext)? (isset($_POST[$id])? $_POST[$id] : '') : $this->valuetext);
		
		if($this->hasModel())$this->valuetext = $this->model->{$this->attribute}; //LO : Supaya isi field muncul dalam text field
		
		echo CHtml::textField($name,$this->valuetext,$this->htmlOptions);
		
		if($this->hasModel()) {
			echo CHtml::activeHiddenField($this->model, $this->attribute,array('class'=>'hf-autocomplete'));
			//echo '<span class="note-autocomplete">'.$this->model->{$this->attribute}.'</span>';
		} else {
			echo CHtml::hiddenField($name,$this->value,array('class'=>'hf-autocomplete'));
			echo '<span class="note-autocomplete">'.$this->value.'</span>';
		}
	
		$options=CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').autocomplete($options).data('ui-autocomplete')._renderItem = function(ul, item) {return $( \"<li>\" ).append( $( \"<a>\" ).html( (typeof item.labelhtml != 'undefined')? item.labelhtml : item.label ) ).appendTo( ul );};");
	}
}