<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Orm\Query\Mysql;

class Field extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    private $field = null;
    private $values = array();
    
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
        $parser->match(Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->ArithmeticPrimary();
        $lexer = $parser->getLexer();
        while (count($this->values) < 1 || 
            $lexer->lookahead['type'] != Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS) {
            $parser->match(Doctrine\ORM\Query\Lexer::T_COMMA);
            $this->values[] = $parser->ArithmeticPrimary();
        }
        $parser->match(Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
    }
	
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $query  = 'FIELD(';		
        $query .= $this->field->dispatch($sqlWalker);
        $query .= ',';
        for ($i = 0; $i < count($this->values); $i++) {
            if ($i > 0) {
                $query .= ',';
            }
            $query .= $this->values[$i]->dispatch($sqlWalker);
        }
        $query .= ')';
        return $query;
    }
}