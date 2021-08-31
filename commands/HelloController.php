<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        $result = $this->revertCharacters("ПриВ-ет! Да*вно не виделИсь?");
        echo $result . "\n";
        echo "ВирП-те! Ад*онв ен ьсилеДив?\n";
        $result = $this->revertCharacters("Привет! Давно не виделись.");
        echo $result . "\n";
        echo "Тевирп! Онвад ен ьсиледив.\n";

        return ExitCode::OK;
    }

    public function revertCharacters($str)
    {
        $upper = [];
        $wordEnd = [];
        for ($i = 0; $i < mb_strlen($str); $i++) {
            $l = mb_substr($str, $i, 1);
            if (in_array($l, [ '.', ',', '!', '?', ';', ":", '-', '*', ' ' ])) {
                $wordEnd[] = $i;
            } elseif ($l === mb_strtoupper($l)) {
                $upper[] = $i;
            }
        }

        $str = mb_strtolower($str);

        $res = '';
        $begin = 0;
        for ($i = 0; $i < sizeof($wordEnd); $i++) {
            $word = mb_substr($str, $begin, ($wordEnd[$i] - $begin));
            $begin = $wordEnd[$i] + 1;
            $word = $this->utf8_strrev($word);
            $res .= $word . mb_substr($str, $wordEnd[$i], 1);
        }

        for ($i = 0; $i < sizeof($upper); $i++) {
            $res = mb_substr($res, 0, $upper[$i]) .
                mb_strtoupper(mb_substr($res, $upper[$i], 1)) .
                mb_substr($res, ($upper[$i] + 1));
        }

        return $res;
    }

    function utf8_strrev($str){
        preg_match_all('/./us', $str, $ar);
        return join('', array_reverse($ar[0]));
    }
}
