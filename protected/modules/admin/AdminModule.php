<?php
class AdminModule extends CWebModule {
    private $_assetsUrl;

    protected function init() {
        $this->setImport(array(
            'admin.components.*',
        	'admin.models.*'
        ));
    }
    
    public function getAssetsUrl()
    {
        if( $this->_assetsUrl===null )
        {
            $assetsPath = Yii::getPathOfAlias('admin.assets');

            /*
            if(defined('YII_DEBUG') && YII_DEBUG)
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
            else
                $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
            */
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
        }

        return $this->_assetsUrl;
    }
    
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
        	$bootstrapUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.todc-bootstrap'));
        	Yii::app()->clientScript->registerCssFile($bootstrapUrl . '/css/bootstrap.min.css');
        	Yii::app()->clientScript->registerCssFile($bootstrapUrl . '/css/todc-bootstrap.min.css');
        	Yii::app()->clientScript->registerCssFile($this->getAssetsUrl() . '/css/main.css');
        	Yii::app()->clientScript->registerCoreScript('jquery');
        	Yii::app()->clientScript->registerScriptFile($bootstrapUrl . '/js/bootstrap.min.js', CClientScript::POS_END);
        	Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/js/main.js', CClientScript::POS_END);
            return true;
        }
        else
            return false;
    }

}