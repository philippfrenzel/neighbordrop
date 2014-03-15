<?php

namespace app\modules\categories\controllers;

use app\modules\app\controllers\AppController;

class DefaultController extends AppController
{
	public function actionIndex()
	{
		return $this->render('index');
	}

  /**
   * Will return a JSON array of existing categories for the handovered module, that may have a content passed over as search
   * @param  integer $module the module id used by the main workflow module... see reference their 
   * @param  string $search Text for the lookuk
   * @return [type]         [description]
   */
  public function actionJsonlist($module, $search = NULL)
  {
    header('Content-type: application/json');

    $query = new Query;
    if(!is_Null($search))
    {
      $mainQuery = $query->select('id, name AS text')->distinct()
        ->from('tbl_categories')
        ->where('UPPER(name) LIKE "%'.strtoupper($search).'%"')
        ->limit(10);

      $command = $mainQuery->createCommand();
      $rows = $command->queryAll();
      $clean['results'] = array_values($rows);
    }
    $clean['results'][] = ['id'=>$search,'text'=>$search];
    echo Json::encode($clean);
    exit();
  }
}
