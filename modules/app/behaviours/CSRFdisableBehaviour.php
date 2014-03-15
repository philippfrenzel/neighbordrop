<?php
/**
 * ControllerBehavior class file.
 *
 * @author Philipp Frenzel <philipp@frenzel.net>
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace app\modules\app\behaviours;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;

/**
 * @package application.extensions.eauth
 */
class CSRFdisableBehaviour extends ActionFilter {
  /**
   * This method is invoked right before an action is to be executed (after all possible filters.)
   * You may override this method to do last-minute preparation for the action.
   * @param Action $action the action to be executed.
   * @return boolean whether the action should continue to be executed.
   */
  public function beforeAction($action) {
    $request = Yii::$app->getRequest();
    $request->enableCsrfValidation = false;

    return parent::beforeAction($action);
  }
}
