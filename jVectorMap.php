<?php
/**
 * jVectorMap Class file
 * 
 * @author John Brennan <jfmbrennan@gmail.com>
 * @link http://www.jbrennan.me
 * 
 * This class uses the codebase from jVectorMap plug-in for jQuery. Its main 
 * purpose is to show interactive vector maps on the web pages.
 * 
 * 
 * THIS SOFTWARE IS PROVIDED BY THE CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class jVectorMap extends CWidget{

  public $baseUrl;

  public $scriptFile=array('jquery-jvectormap-1.1.1.min.js');

  public $cssFile=array('jquery-jvectormap-1.1.1.css');

  public $mapFile=array('jquery-jvectormap-world-mill-en.js');

  public $tagName='div';

  public $data=array();

  public $options=array();

  public $htmlOptions=array();

  public $seriesOptions=array();

  public function init(){
    $this->resolvePackagePath();
    $this->registerCoreScripts();
    parent::init();
  }

  public function run(){
    $id=$this->htmlOptions['id']=$this->getId();
    echo CHtml::tag($this->tagName,$this->htmlOptions,'');

    if(is_array($this->data)) {
      $this->options['series']['regions'][] = 
        array_merge($this->seriesOptions, array('values'=>$this->data));
      $options=CJavaScript::encode($this->options);
      $jscode = "jQuery('#{$id}').vectorMap({$options});";
    }
    else {
      $this->options['container'] = "js:$('#{$this->htmlOptions['id']}')"; 
      $this->options['series']['regions'][] = 
        array_merge($this->seriesOptions, array('values'=>'js:data'));
      $jscode="$.getJSON('{$this->data}',function(data){new jvm.WorldMap(";
      $jscode.=CJavaScript::encode($this->options);
      $jscode.=')})';
    }
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
