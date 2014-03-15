<?php
namespace app\modules\purchase\models\docs;

use Yii;
use yii\base\Model;

/**
 * Purchase Request Purchase Order Model will create the complete PODoc
 */
class PurchaseRequestDoc extends Model
{
	/**
	* @var $PHPWord object will hold the complete document instance
	*/
	public $PHPWord = NULL;

	public $globalStyles = array(
		'heading1' => array('name'=>'Avenir','color'=>'00688B', 'size'=>18, 'bold'=>true),
		'heading1right' => array('name'=>'Avenir','color'=>'454545', 'size'=>18, 'bold'=>true,'align'=>'right'),
		'heading2' => array('name'=>'Avenir','color'=>'2E2E2E', 'size'=>14, 'bold'=>false),
		'heading3' => array('name'=>'Avenir','color'=>'000000', 'size'=>12, 'bold'=>false),
		'heading5' => array('name'=>'Avenir','color'=>'000000', 'size'=>10, 'bold'=>false),
		'pwhite' => array('color'=>'FFFFFF','align'=>'center')
	);

	public $globalTableStyles = array(
		'table' => array('borderColor'=>'9FB6CD','borderSize'=>1,'cellMargin'=>5),
		'invoicehead' => array('borderColor'=>'FFFFFF','borderSize'=>0,'cellMargin'=>5),
		'invoice' => array('borderColor'=>'FFFFFF','borderSize'=>0,'cellMarginLeft'=>10),
		'thead' => array('bgColor'=>'E0EEEE', 'bold'=>true),
		'darkthead' => array('bgColor'=>'454545', 'bold'=>true,'cellMargin'=>10),
		'darktable' => array('borderColor'=>'9A9A9A','borderSize'=>1,'cellMargin'=>20),
	);

	/**
	 * Returns an instance of the current phpWord instance
	 * @return [type] [description]
	 */
	public function getPHPWord(){
		return $this->PHPWord;
	}
	
	public function createDocument($PHPWord,$id,$supplier_id)
	{	
		//load the relevant purchase order group
		if (($PurchaseOrderGroup = \app\modules\purchase\models\PurchaseOrderGroup::find($id)) == null) {
			throw new NotFoundHttpException('The OrderGroup page does not exist.');
		}	

		if (($Party = \app\modules\parties\models\Party::find()->where(['id'=>$supplier_id])->one()) == null) {
			throw new NotFoundHttpException('The Party page does not exist.');
		}

		$this->PHPWord = $PHPWord;
		
		// Every element you want to append to the word document is placed in a section. So you need a section:
		$sectionStyle = array(
			'orientation'  => null,
			'marginLeft'   => 900,
			'marginRight'  => 900,
			'marginTop'    => 900,
			'marginBottom' => 900
		);

		$section = $this->PHPWord->createSection($sectionStyle);

		$pgHeader = $section->createHeader();
		$pgHeader->addPreserveText('Page {PAGE} of {NUMPAGES}');
		
		//logo
		$logoStyle = array('width'=>100, 'height'=>40, 'align'=>'right');
		$section->addImage(\Yii::$app->basePath."/web/img/logo.png", $logoStyle);
		$section->addTextBreak();
		//header
		
		$section->addText(utf8_decode(Yii::t('app','Purchase Order')),$this->globalStyles['heading1right']);
		$section->addTextBreak();


		

		$this->PHPWord->addTableStyle('testformat', $this->globalTableStyles['invoice'], $this->globalTableStyles['invoicehead']);
		$TestFormat = $section->addTable('testformat');
		$TestFormat->addRow();
		$TestFormat->addCell(5000)->addText(utf8_decode(\Yii::t('app','Supplier')),$this->globalStyles['heading2']);
		$TestFormat->addCell(2000)->addText('');
		$TestFormat->addCell(5000)->addText(utf8_decode(\Yii::t('app','Delivery')),$this->globalStyles['heading2']);
		$TestFormat->addRow();

		$cellStyle = array('bgColor'=>'C0C0C0');

		$suppliersection = $TestFormat->addCell(5000,$cellStyle);
		$suppliersection->addTextBreak();
		$suppliersection->addText(utf8_decode($Party->organisationName),$this->globalStyles['heading3']);
		$suppliersection->addText(utf8_decode($Party->addresses['0']->addressLine));
		$suppliersection->addText(utf8_decode($Party->addresses['0']->postCode .' '.$Party->addresses['0']->cityName));
		$suppliersection->addTextBreak();
		$TestFormat->addCell(2000)->addText('');
		$requestersection = $TestFormat->addCell(5000,$cellStyle);
		$requestersection->addTextBreak();
		$requestersection->addText(utf8_decode($PurchaseOrderGroup->contact->contactName),$this->globalStyles['heading3']);
		$requestersection->addText(utf8_decode($PurchaseOrderGroup->contact->department));
		$requestersection->addTextBreak();

		$section->addTextBreak();
		$section->addText(utf8_decode(Yii::t('app','General')),$this->globalStyles['heading2']);
		$section->addTextBreak();

		$this->PHPWord->addTableStyle('supplierlines', $this->globalTableStyles['table'], $this->globalTableStyles['darkthead']);
		$supplierlines = $section->addTable('supplierlines');
		$supplierlines->addRow();
		$supplierlines->addCell(2000)->addText(Yii::t('app','Terms'),$this->globalStyles['pwhite']);
		$supplierlines->addCell(3000)->addText(Yii::t('app','F.O.B'),$this->globalStyles['pwhite']);
		$supplierlines->addCell(3000)->addText(Yii::t('app','Delivery Date'),$this->globalStyles['pwhite']);
		$supplierlines->addCell(3000)->addText(Yii::t('app','Requested By'),$this->globalStyles['pwhite']);
		$supplierlines->addCell(1000)->addText(Yii::t('app','Req.No'),$this->globalStyles['pwhite']);
		$supplierlines->addRow();
		$supplierlines->addCell(2000)->addText(Yii::t('app',' '));
		$supplierlines->addCell(3000)->addText(Yii::t('app',' '));
		$supplierlines->addCell(3000)->addText(Yii::t('app',' '));
		$supplierlines->addCell(3000)->addText(utf8_decode($PurchaseOrderGroup->contact->contactName));
		$supplierlines->addCell(1000)->addText('#'.utf8_decode($PurchaseOrderGroup->id));

		$section->addTextBreak();
		$section->addText(utf8_decode(Yii::t('app','Order Items')),$this->globalStyles['heading2']);
		$section->addTextBreak();

		$this->PHPWord->addTableStyle('orderlines', $this->globalTableStyles['darktable'], $this->globalTableStyles['darkthead']);
		$orderlines = $section->addTable('orderlines');
		$orderlines->addRow();
		$orderlines->addCell(1000)->addText(Yii::t('app','LN'),$this->globalStyles['pwhite']);
		$orderlines->addCell(1500)->addText(Yii::t('app','Quantity'),$this->globalStyles['pwhite']);
		$orderlines->addCell(3500)->addText(Yii::t('app','Description'),$this->globalStyles['pwhite']);
		$orderlines->addCell(1500)->addText(Yii::t('app','Delivery'),$this->globalStyles['pwhite']);
		$orderlines->addCell(1500)->addText(Yii::t('app','Price/Unit'),$this->globalStyles['pwhite']);
		$orderlines->addCell(1500)->addText(Yii::t('app','Total'),$this->globalStyles['pwhite']);

		$orderLines = $PurchaseOrderGroup::adapterForPolinesbysupplier($PurchaseOrderGroup->id,$Party->id);
		$counter = 1;
		foreach($orderLines AS $record){
			$orderlines->addRow();
			$orderlines->addCell(1000)->addText($counter++);
			$orderlines->addCell(1500)->addText(utf8_decode($record->order_amount));
			$orderlines->addCell(3000)->addText(utf8_decode($record->article));
			$orderlines->addCell(1500)->addText(utf8_decode($record->date_delivery));
			$orderlines->addCell(1500)->addText(utf8_decode($record->order_price));
			$total_amount += $record->order_amount * $record->order_price;
			$orderlines->addCell(1500,array('valign'=>'right'))->addText(utf8_decode($record->order_amount * $record->order_price));
		}
		$orderlines->addRow();
		$orderlines->addCell(1000,array('valign'=>'right',bgColor=>'EFEFEF'))->addText('');
		$orderlines->addCell(1500,array('valign'=>'right',bgColor=>'EFEFEF'))->addText('');
		$orderlines->addCell(3000,array('valign'=>'right',bgColor=>'EFEFEF'))->addText('');
		$orderlines->addCell(1500,array('valign'=>'right',bgColor=>'EFEFEF'))->addText('');
		$orderlines->addCell(1500,array('valign'=>'right',bgColor=>'EFEFEF'))->addText(utf8_decode('TOTAL:'));
		$orderlines->addCell(1500,array('valign'=>'right',bgColor=>'EFEFEF'))->addText(utf8_decode($total_amount));

		$footer = $section->createFooter();
		$footer->addText(utf8_decode('Your Company Name'));
		$footer->addText(utf8_decode('Streetname'));
		$footer->addText(utf8_decode('12345 Hometown'));
		$footer->addPreserveText('Page {PAGE} of {NUMPAGES}');
	}

}
