<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Alexey Borzov <avb@php.net>                                  |
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'HTML/QuickForm/Rule.php';

/**
 * Rule to compare two form fields
 * 
 * The most common usage for this is to ensure that the password 
 * confirmation field matches the password field
 * 
 * @access public
 * @package HTML_QuickForm
 * @version $Revision$
 */
class HTML_QuickForm_Rule_Compare extends HTML_QuickForm_Rule
{
   /**
    * Possible operators to use
    * @var array
    * @access private
    */
    var $_operators = array(
        'eq'  => '==',
        'neq' => '!=',
        'gt'  => '>',
        'gte' => '>=',
        'lt'  => '<',
        'lte' => '<='
    );


   /**
    * Returns the operator to use for comparing the values
    * 
    * @access private
    * @param  string     operator name
    * @return string     operator to use for validation
    */
    function _findOperator($name)
    {
        if (empty($name)) {
            return '==';
        } elseif (isset($this->_operators[$name])) {
            return $this->_operators[$name];
        } elseif (in_array($name, $this->_operators)) {
            return $name;
        } else {
            return '==';
        }
    }


    function validate($values, $operator = null)
    {
        $compareFn = create_function('$a, $b', 'return $a ' . $this->_findOperator($operator) . ' $b;');
        return $compareFn($values[0], $values[1]);
    }


    function getValidationScript($jsValue, $jsField, $jsMessage, $jsReset = '', $operator = null)
    {
        $operator = $this->_findOperator($operator);
        return $jsValue .
               "\n  if (!(value[0] {$operator} value[1]) && !errFlag['{$jsField}']) {\n" .
               "    errFlag['{$jsField}'] = true;\n" .
               "    _qfMsg = _qfMsg + '\\n - {$jsMessage}';\n" .
               $jsReset .
               "  }\n";
    }
}
?>