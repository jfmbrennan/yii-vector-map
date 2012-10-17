<?php

class jVectorMap extends CWidget{

	public $scriptUrl;

	public $scriptFile=array('jquery-jvectormap-1.1.1.min.js');
	
	public $pluginScriptFile=array();
	
	public $cssFile=array('jquery-jvectormap-1.1.1.css');

	public $data=array();
	
	public $options=array();

	public function init(){
		$this->resolvePackagePath();
		$this->registerCoreScripts();
		parent::init();
	}

	public function run(){
		$id=$this->getId();
		$this->htmlOptions['id']=$id;        

    $options=CJavaScript::encode($this->options);
    $jscode = "jQuery('#{$id}').vectorMap({$options});";
		
    Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,$jscode,CClientScript::POS_END);
	}

	protected function resolvePackagePath(){
		if($this->scriptUrl===null){
			$basePath=Yii::getPathOfAlias('application.extensions.jvectormaps.assets');
			$baseUrl=Yii::app()->getAssetManager()->publish($basePath);
			if($this->scriptUrl===null)
				$this->scriptUrl=$baseUrl.'';
		}
	}

	protected function registerCoreScripts(){
		$cs=Yii::app()->getClientScript();
		if(is_string($this->cssFile))
			$this->registerCssFile($this->cssFile);
		else if(is_array($this->cssFile)){
			foreach($this->cssFile as $cssFile)
				$this->registerCssFile($cssFile);
		}

		$cs->registerCoreScript('jquery');
		if(is_string($this->scriptFile))
			$this->registerScriptFile($this->scriptFile);
		else if(is_array($this->scriptFile)){
			foreach($this->scriptFile as $scriptFile)
				$this->registerScriptFile($scriptFile);
		}
	}

	protected function registerScriptFile($fileName,$position=CClientScript::POS_HEAD){
		Yii::app()->clientScript->registerScriptFile($this->scriptUrl.'/'.$fileName,$position);
	}

	protected function registerCssFile($fileName){
		Yii::app()->clientScript->registerCssFile($this->themeUrl.'/'.$fileName);
	}
}
