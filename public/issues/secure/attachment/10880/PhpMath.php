<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Locale
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: PhpMath.php 6493 2007-09-23 18:22:29Z thomas $
 */


/**
 * Utility class for proxying math function to bcmath functions, if present,
 * otherwise to PHP builtin math operators, with limited detection of overflow conditions.
 * Sampling of PHP environments and platforms suggests that at least 80% to 90% support bcmath.
 * This file should only be loaded for the 10% to 20% lacking access to the bcmath extension.
 *
 * @category   Zend
 * @package    Zend_Locale
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Locale_Math_PhpMath extends Zend_Locale_Math
{
    public static function disable()
    {
        self::$_bcmathDisabled = true;
        self::$add   = 'Zend_Locale_Math_Add';
        self::$sub   = 'Zend_Locale_Math_Sub';
        self::$pow   = 'Zend_Locale_Math_Pow';
        self::$mul   = 'Zend_Locale_Math_Mul';
        self::$div   = 'Zend_Locale_Math_Div';
        self::$comp  = 'Zend_Locale_Math_Comp';
        self::$sqrt  = 'Zend_Locale_Math_Sqrt';
        self::$mod   = 'Zend_Locale_Math_Mod';
        self::$scale = 'Zend_Locale_Math_Scale';
    }
    
    public static $_scale = null;
}

function Zend_Locale_Math_Add($op1, $op2, $op3 = null)
{
    if (empty($op1)) {
        $op1 = 0;
    }
    $result = $op1 + $op2;
    if (($result === INF) or ((string) ($result - $op2) != (string) $op1)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("addition overflow: $op1 + $op2 != $result", $op1, $op2, $result);
    }
    if ($op3 === null) {
        $op3 = Zend_Locale_Math_PhpMath::$_scale;
    }
    if ($op3 <> 0) {
        $result = round($result, $op3);
    } else {
        if ($result > 0) {
            $result = floor($result);
        } else {
            $result = ceil($result);
        }
    }
    if ($op3 > 0) {
        if ((string) $result == "0")  {
            $result = "0.";
        }
        if (strlen($result) < ($op3 + 2)) {
            $result = str_pad($result, ($op3 + 2), "0", STR_PAD_RIGHT);
        }
    }

    return $result;
}

function Zend_Locale_Math_Sub($op1, $op2, $op3 = null)
{
    if (empty($op1)) {
        $op1 = 0;
    }
    $result = $op1 - $op2;
    if (($result === INF) or ((string) ($result + $op2) != (string) $op1)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("subtraction overflow: $op1 - $op2 != $result", $op1, $op2, $result);
    }
    if ($op3 === null) {
        $op3 = Zend_Locale_Math_PhpMath::$_scale;
    }
    if ($op3 <> 0) {
        $result = round($result, $op3);
    } else {
        if ($result > 0) {
            $result = floor($result);
        } else {
            $result = ceil($result);
        }
    }
    if ($op3 > 0) {
        if ((string) $result == "0")  {
            $result = "0.";
        }
        if (strlen($result) < ($op3 + 2)) {
            $result = str_pad($result, ($op3 + 2), "0", STR_PAD_RIGHT);
        }
    }

    return $result;
}

function Zend_Locale_Math_Pow($op1, $op2, $op3 = null)
{
    $result = pow($op1, $op2);
    if ($result === INF) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("power overflow: $op1 ^ $op2", $op1, $op2, $result);
    }
    if ($op3 === null) {
        $op3 = Zend_Locale_Math_PhpMath::$_scale;
    }
    if ($op3 <> 0) {
        $result = round($result, $op3);
    } else {
        if ($result > 0) {
            $result = floor($result);
        } else {
            $result = ceil($result);
        }
    }
    if ($op3 > 0) {
        if ((string) $result == "0")  {
            $result = "0.";
        }
        if (strlen($result) < ($op3 + 2)) {
            $result = str_pad($result, ($op3 + 2), "0", STR_PAD_RIGHT);
        }
    }
    return $result;
}

function Zend_Locale_Math_Mul($op1, $op2, $op3 = null)
{
    if (empty($op1)) {
        $op1 = 0;
    }
    $result = $op1 * $op2;
    if (($result === INF) or ((string)($result / $op2) != (string)$op1)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("multiplication overflow: $op1 * $op2 != $result", $op1, $op2, $result);
    }
    if ($op3 === null) {
        $op3 = Zend_Locale_Math_PhpMath::$_scale;
    }
    if ($op3 <> 0) {
        $result = round($result, $op3);
    } else {
        if ($result > 0) {
            $result = floor($result);
        } else {
            $result = ceil($result);
        }
    }
    if ($op3 > 0) {
        if ((string) $result == "0")  {
            $result = "0.";
        }
        if (strlen($result) < ($op3 + 2)) {
            $result = str_pad($result, ($op3 + 2), "0", STR_PAD_RIGHT);
        }
    }
    return $result;
}

function Zend_Locale_Math_Div($op1, $op2, $op3 = null)
{
    if (empty($op2)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("can not divide by zero", $op1, $op2, null);
    }
    if (empty($op1)) {
        $op1 = 0;
    }
    $result = $op1 / $op2;
    if (($result === INF) or ((string)($result * $op2) != (string)$op1)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("division overflow: $op1 / $op2 != $result", $op1, $op2, $result);
    }
    if ($op3 === null) {
        $op3 = Zend_Locale_Math_PhpMath::$_scale;
    }
    if ($op3 <> 0) {
        $result = round($result, $op3);
    } else {
        if ($result > 0) {
            $result = floor($result);
        } else {
            $result = ceil($result);
        }
    }
    if ($op3 > 0) {
        if ((string) $result == "0")  {
            $result = "0.";
        }
        if (strlen($result) < ($op3 + 2)) {
            $result = str_pad($result, ($op3 + 2), "0", STR_PAD_RIGHT);
        }
    }
    return $result;
}

function Zend_Locale_Math_Comp($op1, $op2, $op3 = null)
{
    if (empty($op1)) {
        $op1 = 0;
    }
    if ($op3 === null) {
        $op3 = Zend_Locale_Math_PhpMath::$_scale;
    }
    if ($op3 <> 0) {
        $op1 = round($op1, $op3);
        $op2 = round($op2, $op3);
    } else {
        if ($op1 > 0) {
            $op1 = floor($op1);
        } else {
            $op1 = ceil($op1);
        }
        if ($op2 > 0) {
            $op2 = floor($op2);
        } else {
            $op2 = ceil($op2);
        }
    }
    if ($op1 > $op2) {
        return 1;
    } else if ($op1 < $op2) {
        return -1;
    }
    return 0;
}

function Zend_Locale_Math_Sqrt($op1, $op3 = null)
{
    if (empty($op1)) {
        $op1 = 0;
    }
    $result = sqrt($op1);
    if (($result === INF) or ((string)($result * $result) != (string)$op1)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("sqrt operand overflow: $op1", $op1, null, $result);
    }
    if ($op3 === null) {
        $op3 = Zend_Locale_Math_PhpMath::$_scale;
    }
    if ($op3 <> 0) {
        $result = round($result, $op3);
    } else {
        if ($result > 0) {
            $result = floor($result);
        } else {
            $result = ceil($result);
        }
    }
    if ($op3 > 0) {
        if ((string) $result == "0")  {
            $result = "0.";
        }
        if (strlen($result) < ($op3 + 2)) {
            $result = str_pad($result, ($op3 + 2), "0", STR_PAD_RIGHT);
        }
    }
    return $result;
}

function Zend_Locale_Math_Mod($op1, $op2)
{
    if (empty($op1)) {
        $op1 = 0;
    }
    if (empty($op2)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("can not modulo by zero: $op1 % $op2", $op1, $op2, null);
    }
    $result = $op1 / $op2;
    if (($result === INF) or ((string)($result * $op2) != (string)$op1)) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("modulo overflow: $op1 % $op2 (result=$result)", $op1, $op2, $result);
    }
    $result = $op1 % $op2;
    return $result;
}

function Zend_Locale_Math_Scale($op1)
{
    if ($op1 > 9) {
        require_once 'Zend/Locale/Math/Exception.php';
        throw new Zend_Locale_Math_Exception("can not scale to precision $op1", $op1, null, null);
    }
    Zend_Locale_Math_PhpMath::$_scale = $op1;
    return true;
}

Zend_Locale_Math_PhpMath::disable(); // disable use of bcmath functions
