<?php

class jVectorMap extends CWidget{

	public $baseUrl;

	public $scriptFile=array('jquery-jvectormap-1.1.1.min.js');
	
	public $cssFile=array('jquery-jvectormap-1.1.1.css');

    public $mapFile=array();

    public $tagName='div';

	public $data=array();
	
	public $options=array();

	public $htmlOptions=array();

	public function init(){
		$this->resolvePackagePath();
		$this->registerCoreScripts();
		parent::init();
	}

	public function run(){
        if(!isset($this->htmlOptions['id']))
            $this->htmlOptions['id']=$this->getId();
        echo CHtml::tag($this->tagName,$this->htmlOptions,'');
        $options=CJavaScript::encode($this->options);
        $id=$this->htmlOptions['id'];
        $jscode = "jQuery('#{$id}').vectorMap({$options});";
		
        Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,$jscode,CClientScript::POS_END);
	}

	protected function resolvePackagePath(){
		if($this->baseUrl===null){
			$basePath=Yii::getPathOfAlias('application.extensions.jvectormap.assets');
			$this->baseUrl=Yii::app()->getAssetManager()->publish($basePath);
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

		if(is_string($this->mapFile))
			$this->registerMapFile($this->mapFile);
		else if(is_array($this->mapFile)){
			foreach($this->mapFile as $mapFile)
				$this->registerMapFile($mapFile);
		}
	}

	protected function registerScriptFile($fileName,$position=CClientScript::POS_END){
		Yii::app()->clientScript->registerScriptFile($this->baseUrl.'/'.$fileName,$position);
	}

	protected function registerCssFile($fileName){
		Yii::app()->clientScript->registerCssFile($this->baseUrl.'/'.$fileName);
	}

	protected function registerMapFile($fileName,$position=CClientScript::POS_END){
		Yii::app()->clientScript->registerScriptFile($this->baseUrl.'/maps/'.$fileName,$position);
	}
}
