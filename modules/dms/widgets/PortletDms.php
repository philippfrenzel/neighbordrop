<?php
namespace app\modules\dms\widgets;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use app\modules\dms\models\Dmsys;

class PortletDms extends \app\modules\app\widgets\Portlet
{
  public $module = 0;
  public $id = 0;

  protected function renderContent()
  {
    $query = Dmsys::getAdapterForFiles($this->module, $this->id);

    $model = new \app\modules\dms\models\Dmsys;
    $model->dms_module = $this->module;
    $model->dms_id = $this->id;

    $dpFiles = new ActiveDataProvider(array(
        'query' => $query,
        'pagination' => array(
          'pageSize' => 15,
        ),
    ));
    //here we don't return the view, here we just echo it!
    echo $this->render('@app/modules/dms/widgets/views/_filelist',['dpFiles'=>$dpFiles,'model'=>$model]);
  }

}
