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
        $result = $this->revertCharacters("Привет! Давно не виделись.");
        echo $result . "\n"; // Тевирп! Онвад ен ьсиледив.

        return ExitCode::OK;
    }

    public function revertCharacters($str)
    {
        $arr = explode(' ', $str);

        for ($i = 0; $i < sizeof($arr); $i++) {
            $l = mb_substr($arr[$i], mb_strlen($arr[$i]) - 1, 1);
            $f = mb_substr($arr[$i], 0, 1);

            $upper = false;
            if ($f === mb_strtoupper($f)) {
                $arr[$i] = mb_strtolower($f) . mb_substr($arr[$i], 1);
                $upper = true;
            }

            if (in_array($l, [ '.', ',', '!', '?', ';', ":" ])) {
                $w = $this->utf8_strrev(mb_substr($arr[$i], 0, -1));
                if ($upper) {
                    $f = mb_substr($w, 0, 1);
                    $w = mb_strtoupper($f) . mb_substr($w, 1);
                }
                $w .= $l;
            } else {
                $w = $this->utf8_strrev($arr[$i]);
                if ($upper) {
                    $f = mb_substr($w, 0, 1);
                    $w = mb_strtoupper($f) . mb_substr($w, 1);
                }
            }

            $arr[$i] = $w;
        }

        $str = implode(' ', $arr);
        return $str;
    }

    function utf8_strrev($str){
        preg_match_all('/./us', $str, $ar);
        return join('', array_reverse($ar[0]));
    }
}
