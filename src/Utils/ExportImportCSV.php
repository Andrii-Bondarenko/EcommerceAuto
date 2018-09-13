<?php

namespace App\Utils;


use League\Csv\Statement;
use League\Csv\Reader;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Response;

class ExportImportCSV
{
    public static function importCSV($name)
    {

        $csv = Reader::createFromPath($name, 'r');
        $records = $csv->getRecords();

        return  $records;

    }

    public static function checkFileFormat($name)
    {
        if (empty($name) || !preg_match('/[a-zA-Z0-9-,.\(\)\s]+.csv$/', $name)) {
            return false;
        }
        return true;
    }

    public static function formatData($data) {
        $header = [];
        $newData = [];
        foreach ($data as $num=>$row) {
            $itemArr = [];
            if($num === 0) {
                $header = $row;
                dump( $header);
            } else {
                foreach ($row as $key=>$value) {
                    $itemArr[trim($header[$key])] = trim($value);
                }

                $newData[] = $itemArr;
            }
        }
        return $newData;
    }

}