<?php

class PendientesController extends Controller {

    public static function generarExcel() {

        //Autoload fix
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        Yii::import('application.components.PHPExcel', true);
        $objPHPExcel = new PHPExcel();
        spl_autoload_register(array('YiiBase', 'autoload'));

// Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");


// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Hello')
                ->setCellValue('B2', 'world!')
                ->setCellValue('C1', 'Hello')
                ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A4', 'Miscellaneous glyphs')
                ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save(str_replace('.php', '.xlsx', '/tmp'));
        exit;
    }
}