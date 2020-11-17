<?php declare(strict_types=1);

namespace Advecs\App\Config;

/**
 * Class Config
 * @package Advecs\App\Config
 */
class Config
{
    /** @var array */
    protected $env = [];

    /** @var array */
    protected $param = [];

    /**
     * @param array $env
     * @param array $param
     */
    public function __construct(array $env = [], array $param = [])
    {
        $this->env = $env;
        $this->setConfig($param);
    }

    /**
     * @param $name
     * @param string $default
     * @return string
     */
    public function get(string $name, string $default = 'not-set-in-config')
    {
        if (isset($this->param[$name])) {
            return $this->param[$name];
        }
        return $default;
    }

    /**
     * @param $param
     * @param string $prefix
     * @return bool
     */
    protected function setConfig($param, $prefix = '')
    {
        foreach ($param as $key => $hValue) {
            if (is_array($hValue)) {
                $this->setConfig($hValue, $this->getPrefix($key, $prefix));
                continue;
            }
            $this->param[$this->getPrefix($key, $prefix)] = $this->getConfigValue($hValue);
        }
        return true;
    }

    /**
     * @param $hValue
     * @return mixed
     */
    protected function getConfigValue($hValue)
    {
        if (preg_match('/%([A-Z_]+)%/', $hValue, $arMatch)) {
            if (isset($this->env[$arMatch[1]])) {
                $hValue = str_replace($arMatch[0], $this->env[$arMatch[1]], $hValue);
                if ($hValue === 'false') {
                    $hValue = 0;
                }
                return $hValue;
            } else {
                return 'not-set-in-env-file';
            }
        }
        return $hValue;
    }

    /**
     * @param $key
     * @param $prefix
     * @return string
     */
    protected function getPrefix(string $key, string $prefix): string
    {
        if ($prefix === '') {
            return $key;
        }
        return $prefix . '.' . $key;
    }

    /** @return string */
    protected static function getEnvValuePattern(): string
    {
        return '/^([A-Z_]{1,30})=([0-9A-Za-z_@\-\.\:\/%#~\{\}\$\|\*\?]+)$/';
    }

    /** @return string */
    protected static function getEnvValuePatternWithQuotes(): string
    {
        return '/^([A-Z_]{1,30})="([0-9A-Za-z_@\-\.\:\/%#~\{\}\$\|\*\?]+)"$/';
    }

    /**
     * @param string $text
     * @return null
     */
    public static function getEnvValueFromString(string $text)
    {
        if (preg_match(self::getEnvValuePattern(), $text, $arMatch)) {
            return $arMatch;
        } elseif (preg_match(self::getEnvValuePatternWithQuotes(), $text, $arMatch)) {
            return $arMatch;
        }
        return null;
    }

    /**
     * @param string $file
     * @return array
     */
    public static function getParamFromFile(string $file): array
    {
        if (file_exists($file)) {
            return include $file;
        }
        return [];
    }

    /**
     * @param string $file
     * @return array
     */
    public static function getEnvFromFile(string $file): array
    {
        if (!file_exists($file)) { // файл существует
            return [];
        }
        if (!is_readable($file)) {
            return [];
        }
        $hFile = fopen($file, 'r');
        if (!$hFile) {
            fclose($hFile);
            return [];
        }
        $intFileSize = filesize($file);
        if ($intFileSize == 0) {
            return [];
        }
        $env = [];
        $arLine = explode("\n", fread($hFile, filesize($file)));
        foreach ($arLine as $strLine) {
            $strLine = trim($strLine);
            if ($strLine == '') {
                continue;
            }
            $arValue = self::getEnvValueFromString($strLine);
            if ($arValue) {
                $env[$arValue[1]] = $arValue[2];
            }
        }
        fclose($hFile);
        return $env;
    }
}
