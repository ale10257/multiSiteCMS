<?php

namespace app\components\helpers;

use PHPExcel_Worksheet_Drawing as Dr;
use PHPExcel_IOFactory as Factory;
use PHPExcel;
use PHPExcel_STYLE_ALIGNMENT;
use PHPExcel_Style_Border;

class ExcelHelper
{
    /**
     * @var array $alphabet
     * (
     * [0] => A
     * [1] => B
     * [2] => C
     * [3] => D
     * [4] => E
     * [5] => F
     * [6] => G
     * [7] => H
     * [8] => I
     * [9] => J
     * [10] => K
     * [11] => L
     * [12] => M
     * [13] => N
     * [14] => O
     * [15] => P
     * [16] => Q
     * [17] => R
     * [18] => S
     * [19] => T
     * [20] => U
     * [21] => V
     * [22] => W
     * [23] => X
     * [24] => Y
     * [25] => Z
     * )
     */
    private $alphabet;

    public function __construct()
    {
        $this->alphabet = range('A', 'Z');
    }

    /**
     * @param $first_line array or false $first_line = ['img', 'name', 'code', etc]
     * @param $data array $data[0] = [
     * 'img' => 'absolute_path',
     * 'name' => 'my_name',
     * etc]
     * @param $category string
     * @param array $hide_columns
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function generateExcelFile($first_line, $data, $category, $hide_columns = [])
    {
        if (!$data) {
            throw new \DomainException('Now data to generate!');
        }
        $alphabet = $this->alphabet;
        $excel = new PHPExcel();
        $activeSheet = $excel->setActiveSheetIndex(0);
        $i = 1;
        if ($first_line) {
            $style_header = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'borders' => [
                    'allborders' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '696969'
                        ]
                    ]
                ],
                'alignment' => [
                    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                ]
            ];

            for ($count = 0; $count < count($first_line); $count++) {
                $activeSheet->SetCellValue($alphabet[$count] . $i, $first_line[$count]);
                if ($first_line[$count] != 'img' && $first_line[$count] != 'id') {
                    $activeSheet->getColumnDimension($alphabet[$count])->setAutoSize(true);
                }
            }
            $activeSheet->getStyle('A1:' . $alphabet[$count - 1] . '1')->applyFromArray($style_header);
            $i++;
        }
        $style_wrap = [
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => [
                        'rgb' => '696969']
                ]
            ],
            'alignment' => [
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ]
        ];

        foreach ($data as $item) {
            $count = 0;
            foreach ($item as $key => $value) {
                $a = $alphabet[$count] . $i;
                if ($key == 'img' && $key) {
                    if ($value) {
                        $objDrawing = new Dr();
                        $objDrawing->setPath($value);
                        $objDrawing->setOffsetX(5);
                        $objDrawing->setOffsetY(5);
                        $objDrawing->setCoordinates($alphabet[$count] . $i);
                        $objDrawing->setWorksheet($activeSheet);
                    } else {
                        $activeSheet->setCellValueExplicit($a, 'No image');
                    }
                } else {
                    $value = str_replace(['<br>', '<br />'], "\r\n", $value);
                    $value = strip_tags($value);
                    $activeSheet->setCellValueExplicit($a, $value);
                }
                $count++;
            }
            $activeSheet->getRowDimension($i)->setRowHeight(70);
            $activeSheet->getStyle('A' . $i . ':' . $alphabet[$count - 1] . $i)->applyFromArray($style_wrap);
            $i++;
        }
        $activeSheet->getColumnDimension('A')->setWidth(16);

        $file_name = !empty($category) ? $category : uniqid();
        $file_name .= '.xlsx';

        if (!empty($hide_columns) && is_array($hide_columns)) {
            foreach ($hide_columns as $item) {
                $activeSheet->getColumnDimension($item)->setVisible(false);
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');

        $writer = Factory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
        //$writer->save(__DIR__ . '/' . $file_name);
    }

    /**
     * @param $data array [
     * 'file' => 'path_to_file',
     * 'start_row' => int,
     * 'start_letter' => int,
     * 'num_column' => int,
     * 'indexes' => [
     *      'B' => 'id',
     *      'C' => 'code',
     *      'D' => 'name',
     *      'E' => 'price',
     *      etc...
     *      ]
     * ]
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function readExcel($data)
    {
        $excel = Factory::load($data['file']);
        $sheet = $excel->getSheet(0);
        $lastRow = $sheet->getHighestRow();
        $result = [];

        for ($i = $data['start_row']; $i <= $lastRow; $i++) {
            for ($i_ = $data['start_letter']; $i_ < $data['num_column']; $i_++) {
                $index = isset($data['indexes'][$this->alphabet[$i_]]) ? $data['indexes'][$this->alphabet[$i_]] : $i_;
                $result[$i][$index] = $sheet->getCell($this->alphabet[$i_] . $i)->getValue();
            }
        }

        return $result;
    }

}
