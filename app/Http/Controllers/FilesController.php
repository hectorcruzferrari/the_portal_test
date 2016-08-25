<?php

namespace PortalTest\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PortalTest\File;
use PortalTest\Http\Requests;

class FilesController extends Controller
{

    /**
     * @param null $filepath
     * @return bool
     */
    public static function import($filepath = null)
    {
        if ($filepath == null)
            return false;

        try {
            //It wipe the file table which works as a temporary table to load and helps to sort
            File::truncate();

            //Load the file using the file path
            $reader = Excel::load($filepath)->get();
            $rows = $reader->all();

            foreach ($rows as $row) {

                // Dividing the pick location in bays and shelves to make easier the sorting process
                $pick_location = $row->pick_location;
                $bay = substr($pick_location, 0, strpos($pick_location, ' '));
                $shelf = substr($pick_location, strpos($pick_location, ' ') + 1);

                $file = File::create([
                    'product_code' => $row->product_code,
                    'quantity'     => $row->quantity,
                    'bay'          => $bay,
                    'bay_stage'    => strlen($bay), // This will help to separate the bay depending the bay's name
                                                    // e.g. A-Z AA-AZ even if it's longer: AAAA-ZZZZ
                    'shelf'        => $shelf
                ]);
                $file->save();
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public static function sortTable()
    {
        /**
         * the order by have the next priority: bay name length, bay name, shelves.
         */
        return DB::table('files')->orderBy('bay_stage')
            ->orderBy('bay')
            ->orderBy('shelf')
            ->select('product_code', 'quantity')
            ->selectRaw('CONCAT(bay," ",shelf) as pick_location') //resembling the pick_location
            ->get();
    }

    /**
     * @param $filepathIn
     * @param null $filepathOut
     * @return bool|string
     */
    public static function picking($filepathIn, $filepathOut=null)
    {
        try {

            //If input == output, append "_out" to the name, to avoid replace the original file
            if($filepathOut==null || $filepathOut == $filepathIn)
                $filepathOut = substr($filepathIn, 0, strrpos($filepathIn, '.')) . '_out.csv';

            
            //Import the input file
            self::import($filepathIn);


            //Sorting talbe
            $sortedTable = self::sortTable();


            //Generating the output file
            $echo = "product_code,quantity,pick_location" . "\r\n";
            foreach($sortedTable as $key => $row)
            {
                $echo .= $row->product_code.",". $row->quantity.",". $row->pick_location;
                if($key+1 != count($sortedTable))
                    $echo .="\r\n";
            }

            $handle = fopen($filepathOut, "w+");
            fwrite($handle, $echo);
            fclose($handle);

            return true;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }

    }
}
