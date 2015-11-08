<pre><?php

class FilenameCleaner
{
    private $directory = "gallery";
    private $thumb_directory = 'thumbnails';
    private $allowed = array(
        'jpg' => true,
        'jpeg' => true,
        'png' => true
    );

    public $find_and_replace = array(
        '?' => '',
        '_' => '',
        '™' => '',
        '®' => '',
        '  ' => ' ',
        'ä' => 'ae',
        'ü' => 'ue',
        'ö' => 'oe',
        'ä' => 'ae',
        'ß' => 'ss',
    );

    public $directoryTree;

    public function readDir($dir)
    {
        $result = array();

        $cdir = scandir($dir);

        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {

                    $fixed_value = $this->fixFilename($value);

                    rename($dir . DIRECTORY_SEPARATOR . $value, $dir . DIRECTORY_SEPARATOR . $fixed_value);
                    echo $fixed_value.'<br>';
                } else {

                    $fixed_value = $this->fixFilename($value);

                    if (!$this->startsWith($value, '.')) {
                        rename($dir . DIRECTORY_SEPARATOR . $value, $dir . DIRECTORY_SEPARATOR . $fixed_value);
                        $result[] = $fixed_value;

                        $ext = pathinfo($fixed_value, PATHINFO_EXTENSION);

                    }

                }
            }
        }

        return $result;
    }

    public function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    public function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    public function fixFilename($filename)
    {
        $find_and_replace = $this->find_and_replace;
        $find = array_keys($find_and_replace);
        $replace = array_values($find_and_replace);
        $filename = str_ireplace($find, $replace, $filename);

        return $filename;
    }


    public function __construct($directory = '')
    {
        if ($directory == '') {
            $directory = $this->directory;
        }


        $this->directoryTree = $this->readDir($directory);

    }

}


new FilenameCleaner('gallery');


