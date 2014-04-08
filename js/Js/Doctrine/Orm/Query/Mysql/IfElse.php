<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Orm\Query\Mysql;

class IfElse extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
	private $expressions = array();

	public function parse(\Doctrine\ORM\Query\Parser $parser)
	{
		$parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
		$parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
		$this->expressions[] = $parser->ConditionalExpression();

		for ($i = 0; $i < 2; $i++)
		{
			$parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);
			$this->expressions[] = $parser->ArithmeticExpression();
		}

		$parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
	}

	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
	{
		return sprintf('IF(%s, %s, %s)',
			$sqlWalker->walkConditionalExpression($this->expressions[0]),
			$sqlWalker->walkArithmeticPrimary($this->expressions[1]),
			$sqlWalker->walkArithmeticPrimary($this->expressions[2]));
	}
}