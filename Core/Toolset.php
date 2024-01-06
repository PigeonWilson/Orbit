<?php
class Toolset
{
    public function clean(string $content) : string
    {
        $result = htmlspecialchars(strip_tags($content), ENT_QUOTES, 'UTF-8');
        return str_replace(['<', '>','(',')',';'], '', $result);
    }

    public function echo(string $content) : void
    {
        echo $this->clean($content);
    }

    public function location(string $url) : void
    {
        header("Location: $url");
        die();
    }

    public function getScriptName() : string
    {
        $scriptName = $this->clean($_SERVER["SCRIPT_NAME"]);
        return substr($scriptName,strrpos($scriptName,"/")+1);
    }

    public function random_str
    (
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i)
        {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public function random_str_chain(array &$dict, int $wordsCount = 6) : string
    {
        $result = 'S' . random_int(0, 100);
        for ($index = 0; $index < $wordsCount; $index++)
        {
            $result .= '-' . random_int(0, 100) . array_values($dict)[random_int(0, count($dict) - 1)];
        }

        return  $result;
    }
}