<?php

include './Classes/PHPExcel.php';
include './Classes/PHPExcel/Writer/Excel5.php';
include './Classes/PHPExcel/IOFactory.php';

//--デフォルトタイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
//echo date_default_timezone_get();

function n_write_file($writeData, $writeFile)
{
    //-- 日付取得
    $today = date("Y-m-d");

    try
    {
        $objPHPExcel = new PHPExcel();

        //シートの設定
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle($today);

        //セルの値を設定
        //キーワード難易度測定項目の書き込み
        $col = 0;
        foreach ($writeData as $searchResultData)
        {
            $row = 1;
            foreach ($searchResultData as $value)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                $row++;
            }
            $col++;
        }

        //スタイルの設定(標準フォント、罫線、中央揃え)
        $sheet->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');
        $sheet->getDefaultStyle()->getFont()->setSize(11);
        //$sheet->getStyle('C3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //$sheet->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save($writeFile);
        return 0;
    }
    catch (Exception $e)
    {
       return 1;
    }
}

?>
