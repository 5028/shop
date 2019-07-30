<?php 
namespace app\admin\controller;
use think\Db;
use Request;
use think\facade\Session;
use gmars\rbac\Rbac;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Text extends Common
{
	public function text()
	{
		return $this->fetch();
	}
	function add()
	{
		$file = request()->file('file');
		foreach ($file as $key => $value) {
            $info = $value->move("./Excel");
        }
        if($info){
        	$img=str_replace("\\","/", $info->getSaveName());
        	//echo $img;
        }
		$helper = new Sample();
		$inputFileName = './Excel/'. $img;
		$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory to identify the format');
		$spreadsheet = IOFactory::load($inputFileName);
		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		foreach ($sheetData as $key => $value) {
			$name=$value['B'];
			$new_name=Db::query("select * from new_boot where name='$name'");
			if (empty($new_name)) {
				Db::query("insert into new_boot (`name`) value('$name')");
			}
		}
		unset($info);
		unlink('./Excel/'. $img);
	}

	public function order()
	{
	$helper = new Sample();
	if ($helper->isCli()) {
	    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);
	    return;
	}

	// Create new Spreadsheet object
	$spreadsheet = new Spreadsheet();

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
	    ->setLastModifiedBy('Maarten Balliauw')
	    ->setTitle('Office 2007 XLSX Test Document')
	    ->setSubject('Office 2007 XLSX Test Document')
	    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
	    ->setKeywords('office 2007 openxml php')
	    ->setCategory('Test result file');

	// Add some data
	$new_arr=Db::table('order_new')->select();
	$spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A1', 'id')
	    ->setCellValue('B1', 'name')
	    ->setCellValue('C1', 'type');

	// Miscellaneous glyphs, UTF-8
	    foreach ($new_arr as $key => $value) {
	    	$i=$key+2;
	    	$spreadsheet->setActiveSheetIndex(0)
	    ->setCellValue('A'.$i, $value['id'])
	    ->setCellValue('B'.$i, $value['name'])
	    ->setCellValue('C'.$i, $value['type']);
	    }
	
	// Rename worksheet
	$spreadsheet->getActiveSheet()->setTitle('Simple');

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$spreadsheet->setActiveSheetIndex(0);

	// Redirect output to a client’s web browser (Xls)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="01simple.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0

	$writer = IOFactory::createWriter($spreadsheet, 'Xls');
	$writer->save('php://output');
	exit;

}

function aa(){
		    $new_arr=Db::table('order')->select();
	$spreadsheet->setActiveSheetIndex()
		    ->setCellValue('A1', 'id')
		    ->setCellValue('B1', 'name')
		    ->setCellValue('C1', 'type');
		    
	foreach ($new_arr as $key => $value) {
		$i=$key+2;
		$spreadsheet->setActiveSheetIndex()
		    ->setCellValue('A'.$i, $value['id'])
		    ->setCellValue('B'.$i, $value['name'])
		    ->setCellValue('C'.$i, $value['type']);
	}
}
}
 ?>