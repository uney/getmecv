
<?
$ROOT_PATH = dirname(dirname(__FILE__));

function downloadImg($url, $folderName, $imageName){
	$img = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.$folderName.DIRECTORY_SEPARATOR.$imageName;
	file_put_contents($img, file_get_contents($url));
}

function object_to_array($data)
{
    if(is_array($data) || is_object($data)){
        $result = array();
 
        foreach($data as $key => $value) {
            $result[$key] = $this->object_to_array($value);
        }
        return $result;
    }
    return $data;
}

if (!function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( ! isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return -1;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( ! isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return -1;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return -1;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
		return $array;
        //return  count($array) > 0 ? true : false ;
    }
}
?>