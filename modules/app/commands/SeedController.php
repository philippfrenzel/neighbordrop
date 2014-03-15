<?php

namespace app\modules\app\commands;

use Yii;
use yii\console\Exception;
use yii\console\Controller;
use yii\db\Connection;

/**
 * Used for seeding the database with initial data. Usually used during development
 * @author Philipp Frenzel <philipp@frenzel.net>
 */
class SeedController extends Controller
{

  /**
  * @var Connection|string the DB connection object or the application
  * component ID of the DB connection.
  */
  public $db = 'db';

  /**
  * @var string the default command action.
  */
  public $defaultAction = 'seed';

  /**
  * Returns the names of the global options for this command.
  * @return array the names of the global options for this command.
  */
  public function globalOptions()
  {
    return array('db');
  }

  /**
  * This method is invoked right before an action is to be executed (after all possible filters.)
  * It checks the existence of the [[migrationPath]].
  * @param \yii\base\Action $action the action to be executed.
  * @return boolean whether the action should continue to be executed.
  * @throws Exception if the migration directory does not exist.
  */
  public function beforeAction($action)
  {
    if (parent::beforeAction($action)) {
      if (is_string($this->db)) {
        $this->db = Yii::$app->getComponent($this->db);
      }
      if (!$this->db instanceof Connection) {
        throw new Exception("The 'db' option must refer to the application component ID of a DB connection.");
      }

      $version = Yii::getVersion();
      echo "Yii Seed Tool (based on Yii v{$version})\n";
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * This command is used when typing yiic seed. Demo data should be created here
   */
  public function actionSeed()
  {
    $tx = $this->db->beginTransaction();
    try
    {
      $this->seed();
      $tx->commit();
    }
    catch (Exception $e)
    {
      throw new Exception($e->getMessage());
      $tx->rollback();
    }
  }

  /**
   * Seeds the database according to the array defined within the method
   * array('fixtureName' => ActiveRecordClassName)
   */
  protected function seed()
  {
    Yii::$app->getComponent('seeder')->prepare();
  }
}

