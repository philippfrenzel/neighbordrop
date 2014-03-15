<?php
namespace app\modules\app\widgets;

use Yii;
use yii\widgets\Block;


/**
 * Portlet is the base class for portlet widgets.
 *
 * A portlet displays a fragment of content, usually in terms of a block
 * on the side bars of a Web page.
 *
 * To specify the content of the portlet, override the {@link renderContent}
 * method, or insert the content code between the {@link CController::beginWidget}
 * and {@link CController::endWidget} calls. For example,
 *
 *
 * A portlet also has an optional {@link title}. One may also override {@link renderDecoration}
 * to further customize the decorative display of a portlet (e.g. adding min/max buttons).
 *
 */
class Portlet extends Block
{
  /**
   * @var string the tag name for the portlet container tag. Defaults to 'div'.
   */
  public $tagName='div';
  
  /**
   * @var array of admin actions
   */
  public $adminActions = array();

  /**
   * @var array the HTML attributes for the portlet container tag.
   */
  public $htmlOptions=array('class'=>'panel panel-default');
  
  /**
   * @var string the title of the portlet. Defaults to null.
   * When this is not set, Decoration will not be displayed.
   * Note that the title will not be HTML-encoded when rendering.
   */
  public $title = NULL;
  
  /**
   * @var string the CSS class for the decoration container tag. Defaults to 'portlet-decoration'.
   */
  public $decorationCssClass='panel-heading';
  
  /**
   * @var string the CSS class for the portlet title tag. Defaults to 'portlet-title'.
   */
  public $titleCssClass='panel-title';
  
  /**
   * @var string the CSS class for the content container tag. Defaults to 'portlet-content'.
   */
  public $contentCssClass='panel-body';
  
  /**
   * @var boolean whether to hide the portlet when the body content is empty. Defaults to true.
   * @since 1.1.4
   */
  public $hideOnEmpty=true;

  public function init()
  {    
    parent::init();
  }

  /**
   * Renders the content of the portlet.
   */
  public function run()
  {
    $this->renderDecoration();
    $this->renderContent();
    echo ob_get_clean();
  }

  /**
   * [renderDecoration description]
   * @return [type] [description]
   */
  protected function renderDecoration()
  {
  }

  /**
   * Renders the content of the portlet.
   * Child classes should override this method to render the actual content.
   */
  protected function renderContent()
  {
  }
}
