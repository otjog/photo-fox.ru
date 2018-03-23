<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Work extends Model
{

    public $disk = "public";
    public $directory = "images/works/";
    public $imageFormats = ['jpeg', 'jpg', 'png', 'gif'];
    public $infoFileName = 'info.txt';
    public $delimiter = '{:}';
    /**
     * Массив будущего списка секвенций их параметров
     *
     * Прогоняя в цикле существующие папки в папке works, мы будем
     * записывать в массив $listWorks данные о каждой 3d-модели.
     * Где
     *      - Ключ Массива - id 3d-модели (Имя конкретной папки)
     *      - Значение Ключа - вложенный массив с параметрами 3d-модели
     *
     * @var array $listWorks
     */
    public $listWorks = array();
    /**
     * Массив путей до папок с секвенциями в папке works.
     *
     * В массиве будет храниться полный путь до каждой папки
     * с секвенциями.
     *
     * @var array $allWorksPaths
     */
    public $allWorksPaths = array();
    public $uniqueTagsWorks = array();

    /**
     * Возвращает все существующие секвенции
     *
     * Каждая 3D-модель хранится в родительской папке WORKS.
     * (полный путь в приложении: public/storage/images/works/)
     * 3D-модель представляет из себя папку в которой хранятся фотографии
     * необходимых для воспроизведения секвенции данной 3d-модели.
     * Хранимый путь к каждой папке имеет вид:
     *  'images/works/coffee'
     * где coffee, папка 3d-модели, где и хранятся фотографии.
     * Имя самой папки является Id 3D-модели.
     * Кроме фотографий в папке может хранится файл с дополнительной
     * информацией о 3D-модели.
     *
     * @return array
     */
    public function getAllWorks(){
        $this->allWorksPaths = Storage::disk($this->disk)->directories($this->directory);

        foreach( $this->allWorksPaths as $pathToCurrentWork) {

            /**
             * Временная переменная, массив.
             *
             * Переменная хранит в виде массива путь до папки с 3d-моделью.
             * Строка $pathToWork, хранящая полный путь до папки 3d-модели,
             * делится на части по символу '/'. Каждый элемент записывается в ячейку массива $matches.
             * Последний элемент массива будет хранить имя папки 3d-модели (имя 3d-модели).
             *
             * @var array $matches
             */
            $matches = explode('/', $pathToCurrentWork);
            /**
             * ID (имя) 3d-Модели.
             *
             * ID 3d-модели - это название папки, в которой хранятся фотографии для секвенции.
             * Из временной переменной $matches мы извлекаем последний элемент массива, который
             * содержит имя нашей папки
             *
             * @var string $idWork
             */
            $idCurrentWork = array_pop($matches);

            $listImages     = $this->getAllImagesInCurrentWork($pathToCurrentWork);
            $countImages    = count($listImages);
            $dataImage      = $this->createDataImageString($listImages, $countImages);
            $description    = $this->getDescriptionWork($pathToCurrentWork);

            if($dataImage === false){
                //todo что делать, если не получилось переименовать фотографии;
            }

            $frontImage     = $this->getFrontImage($listImages);

            $this->listWorks[] = [
                'idWork'       => $idCurrentWork,
                'dataImage'     => Storage::url($dataImage),
                'frontImage'    => Storage::url($frontImage),
                'countImages'   => $countImages,
                'description'   => $description
            ];
        }

        $this->uniqueTagsWorks = $this->getUniqueTagsWorks($this->listWorks);

        return [
            'works' => $this->listWorks,
            'tags'  => $this->uniqueTagsWorks
            ];
    }

    /**
     * Возвращает неободимую строку для секвенции.
     *
     * Строка типа: img_###.jpg, как регулярное выражение, должна подходить
     * к любому имени изображения секвенции. Если изображения имеют разный формат имени,
     * то предпринимается попытка переименовать все изображения.
     * Если не получается, то возвращается false.
     *
     * @param array $listImages Массив изображений текущей секвенцит
     * @param integer $countImages Кол-во изображений в секвенции
     * @return bool|string
     */
    private function createDataImageString(&$listImages, $countImages){
        /**
         * Разрядность цифр, которая должно быть у фотографий секвенции.
         *
         * В зависимости от количества фотографий в секвенции, каждая
         * фотография должна иметь правильную разрядность нумерации.
         * Т.е. если фотографий меньше 100 (например 93), то разрядность
         * нумерации должна быть не менее 2 цифр:
         *  img_01.jpg
         *  img_02.jpg
         * ...
         *  img_93.jpg
         * Или
         *  img_001.jpg
         *  img_002.jpg
         * ...
         *  img_093.jpg
         * Но, не так:
         *  img_1.jpg
         *  img_2.jpg
         * ...
         *  img_93.jpg
         *
         * Соответственно, если фотографий в секвенции больше 100, то разрядность
         * нумерации должна быть у каждой фотографии не менее 3 цифр.
         *
         * @var int$countNumbers
         */
        $countNumbers = strlen((string)$countImages);
        /**
         * Инициализируем строковую переменную.
         *
         * В данную строку в каждом цикле после вычислений,
         * будет подставлять "тело" имени текущего изображения.
         * Под "телом" имени изображения, понимается та часть имени,
         * которая должна быть одинакова у всех изображений.
         * Например у изображений:
         *  img_01.jpg
         *  img_02.jpg
         * ...
         *  img_93.jpg
         * телом будет: "img_"
         * В каждом цикле вычесленное "тело" имени сравнивается с первым
         * вычисленным, и если оно не совпадает, то вызывается функция
         * $this->newNameImage($listImages, $dataImage, $format)
         *
         * @var string $dataImage
         */
        $bodyName = '';

        foreach($listImages as $imageName){
            $matches = explode('.', $imageName);
            $format = array_pop($matches);
            $string = implode($matches);

            $currentBodyName = substr($string, 0, strlen($string) - $countNumbers);
            if($bodyName === ''){
                $bodyName = $currentBodyName;
            }else{
                if($bodyName !== $currentBodyName){
                    if($this->newNameImages($listImages, $bodyName, $format) === false){
                        return false;
                    }
                }
            }
        }
        $dataImage = str_pad($bodyName,  strlen($bodyName)+$countNumbers, "#");
        return $dataImage.'.'.$format;
    }

    /**
     * Проверяет текущий файл на соответствие расширению.
     *Список доступных расширений указан в массиве $this->imageFormats
     *
     * @param string $fileName Имя файла, которое будут проверять на соотвествие
     * @return bool
     */
    private function checkImageFormat($fileName){
        $matches = explode('.', $fileName);
        if(in_array(end($matches), $this->imageFormats)){
            return $fileName;
        }
    }

    /**
     * Возвращает путь к картинке, которая будет отображаться по умолчанию
     *
     * @param array $listImages массив изображений текущей секвенции
     * @return string путь к изображению
     */
    private function getFrontImage($listImages) {
        /**
         * на данный момент функция возвращает первую картинку в папке
         * в дальнейшем расширим функционал, чтобы можно было выбирать картинку
         */
        return $listImages[0];
    }

    /**
     * Переименовывает все изображения определенной секвенции.
     *
     * @param array $listImages Массив имен изображений в данной секвенции
     * @param string $bodyName Тело нового имени. Новое тело будет формироваться след.образом: $dataImage+Номер+$format
     * @param string $format Расширение файла. Jpeg, png и т.п.
     * @return bool
     */
    private function newNameImages(&$listImages, $bodyName, $format){

        $number = 1;
        $newListImages = [];

        foreach ($listImages as $oldName){

            if($number < 10){
                $numberString = '00'.(string)$number;
            }elseif($number >= 10 && $number < 100){
                $numberString = '0'.(string)$number;
            }else{
                $numberString = (string)$number;
            }

            $newListImages[] = $newName = $bodyName . $numberString.'.'.$format;

            if(true){
                //todo  в условии сделать проверку на наличие прав для переименования
                Storage::disk($this->disk)->move($oldName, $newName);
                $number++;
            }else{
                return false;
            }
        }
        $listImages = $newListImages;
        return true;
    }

    /**
     * Возвращает массив фотографий текущей секвенции
     *
     * Получаем все файлы лежащие в папке текущей секвенции ($pathToCurrentWork).
     * Сортируем массив в правильном порядке.
     * Фильтруем массив, оставляя только файлы с расширением,
     * имеющимся в списке доступных расширений в переменной $this->imageFormats
     *
     * @param string $pathToCurrentWork Путь до текущей папки секвенцииЯ
     * @return array Массив всех изображений текущей секвенции
     */
    private function getAllImagesInCurrentWork($pathToCurrentWork){
        /**
         * Получаем все файлы лежащие в папке текущей 3d-модели.
         *
         * Кроме фотографий для секвенции в папке также может лежать
         * текстовый файл с дополнительной информацией о 3d-модели.
         * В итоге мы получаем все файлы лежащие в папке в виде массива.
         *
         * @var array $files
         */
        $allFiles = Storage::disk($this->disk)->files($pathToCurrentWork);

        /**
         * Сортируем массив человекопонятно
         *
         * Если в фотографии нашей секвенции пронумированы по принципу:
         * img_01.jpg ... img_10.jpg ... img_100.jpg
         * то массив будет хранить фотографии не в правильном, с точки зрения человека, порядке
         * array = [
         *  ...
         *  8   => img_09.jpg
         *  9   => img_10.jpg
         *  10  => img_100.jpg
         *  11  => img_11.jpg
         * ... ];
         *
         *  Функция natsort() отсуортирует с точк зрения правильного возрастания чисел наш массив.
         *
         * @param array $files
         */
        natsort($allFiles);

        /**
         * Фильтруем и сбрасываем ключи
         *
         * array_filter фильтрует файлы с помощью функции обратного вызова $this->checkImageFormat($fileName),
         * array_values сбрасывает ключи у получившегося массива.
         */
        return array_values(array_filter($allFiles, [$this,'checkImageFormat']));
    }

    private function getDescriptionWork($pathToCurrentWork){

        $exists = Storage::disk($this->disk)->has($pathToCurrentWork.'/'.$this->infoFileName);

        if($exists){
            $workInfoRawData = trim(Storage::disk($this->disk)->get($pathToCurrentWork.'/'.$this->infoFileName));
            $workInfo = explode("\n", $workInfoRawData);
            $data = [];

            foreach($workInfo as $string){
                $match = explode($this->delimiter, (trim($string) ) );
                $parameterName = mb_strtolower($match[0]);
                $data[$parameterName] = $match[1];
            }

            return $data;
        }else{
            return [];
        }
    }

    private function getUniqueTagsWorks($listWorks){
        $listUniqueTags = array();
        foreach($listWorks as $work){
            if(isset($work['description']['tag'])){
                $tag = $work['description']['tag'];
                if(!in_array($tag, $listUniqueTags)){
                    $listUniqueTags[] = $tag;
                }
            }
        }
        return $listUniqueTags;
    }
}
