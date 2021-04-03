<?php
require_once(__DIR__ . "/../models/UserAction.php");
require_once(__DIR__ . "/../controllers/UserActionController.php");

class CurlController
{
    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function getFileContent($url): bool|string
    {
        curl_reset($this->curl);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($this->curl);
    }

    public function getContent($url): bool|string
    {
        curl_reset($this->curl);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        return curl_exec($this->curl);
    }

    //https://stackoverflow.com/questions/48593819/php-curl-github-api-403
    public function getHeader($url): bool|string
    {
        curl_reset($this->curl);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        // 1 = TRUE
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($this->curl, CURLOPT_NOBODY, true);
        curl_setopt($this->curl, CURLOPT_HEADER, true);

        return curl_exec($this->curl);
    }

    public function deserializeHeader(string $string): array
    {
        $lines = explode(PHP_EOL, $string);
        $array = array();
        foreach ($lines as $index => $line) {
            if ($index > 0) {
                $charIndex = strpos($line, ":");
                $key = substr($line, 0, $charIndex);
                $value = substr($line, $charIndex + 1, strlen($line) - $charIndex - 1);

                $array[$key] = $value;
            }
        }
        return $array;
    }

    //returns array of UserAction objects
    //if $update = true, also push to db
    public function deserializeContent(string $string): array
    {
        $lines = explode(PHP_EOL, $string);
        $array = array();

        foreach ($lines as $index => $line) {
            $lineArray = str_getcsv($line, "\t");

            if ($index > 0 && count($lineArray) == 3) {
                $user = new UserAction();

                $user->setName($lineArray[0]);
                $user->setAction($lineArray[1]);
                try {
                    $user->setTimestamp(date('Y-m-d H:i:s', date_create_from_format('d/m/Y, H:i:s', $lineArray[2])->getTimestamp()));
                } catch (Error $e) {
                    $user->setTimestamp(date('Y-m-d H:i:s', date_create_from_format('m/d/Y, H:i:s A', $lineArray[2])->getTimestamp()));
                }

                array_push($array, $user);
            }
        }
        return $array;
    }

    public function closeUrl()
    {
        curl_close($this->curl);
    }
}