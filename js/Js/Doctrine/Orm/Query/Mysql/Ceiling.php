<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Orm\Query\Mysql;

class Ceiling extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
	public $expression = null;

	public function parse(\Doctrine\ORM\Query\Parser $parser)
	{
		$lexer = $parser->getLexer();
		$parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
		$parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
		$this->expression = $parser->ArithmeticExpression();
		$parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
	}

	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
	{
		return 'CEILING(' . $this->expression->dispatch($sqlWalker) . ')';
	}
}