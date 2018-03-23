<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;

class Page extends Model
{

    public $disk = 'public';
    public $directory = "pages/";
    public $delimiter = '{:}';
    public $formatFile = '.txt';

    public function getAllPages(){

        $files = Storage::disk($this->disk)->files($this->directory);
        $fileList = array();
        foreach($files as $fileName) {
            $pathname = $this->getPathName($fileName);
            $fileList[$pathname] = $this->getPageInfo($fileName);
        }
        return $fileList;
    }

    public function getCurrentPage($pathName){
        return $this->getPageInfo($this->directory.$pathName.$this->formatFile);
    }

    private function getPageInfo($file_name){
        $pageInfoRawData = trim(Storage::disk($this->disk)->get($file_name));
        $pageInfo = explode("\n", $pageInfoRawData);
        $data = [];

        foreach($pageInfo as $string){

            $match = explode($this->delimiter, (trim($string) ) );
            $parameterName = mb_strtolower($match[0]);

            switch($parameterName){
                case 'modules'  : $data[$parameterName] = explode('|', $match[1]); break;
                default         : $data[$parameterName] = $match[1];
            }
        }

        return $data;
    }

    private function getPathName($fileName){
        return str_replace([$this->directory, $this->formatFile], '', $fileName);
    }
}
