<?php

namespace App\Repositories;

use Maatwebsite\Excel\Facades\Excel;

class ExcelRepository
{

    public function getExcel($results)
    {

        Excel::create('New file', function($excel) use ($results) {

            $excel->sheet('New sheet', function($sheet) use ($results) {

                $sheet->setAutoSize(true);
                $sheet->setOrientation('landscape');
                $sheet->loadView('results', array('report' => $results));

            });

        })->export('xls');
    }

    public function getPdf($results)
    {

        Excel::create('New file', function($excel) use ($results) {

            $excel->sheet('New sheet', function($sheet) use ($results) {

                $sheet->setAutoSize(true);
                $sheet->setOrientation('landscape');
                $sheet->setAllBorders('thin');
                $sheet->loadView('results', array('report' => $results));

            });

        })->export('pdf');
    }

}