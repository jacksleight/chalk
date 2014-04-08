<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Orm\Query\Mysql;

class Round extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
	private $firstExpression = null;
	private $secondExpression = null;

	public function parse(\Doctrine\ORM\Query\Parser $parser)
	{
		$lexer = $parser->getLexer();
		$parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
		$parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
		$this->firstExpression = $parser->ArithmeticExpression();

		if(\Doctrine\ORM\Query\Lexer::T_COMMA === $lexer->lookahead['type']){
			$parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);
			$this->secondExpression = $parser->ArithmeticExpression();
		}

		$parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
	}

	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
	{
		if (null !== $this->secondExpression){
			return 'ROUND(' 
				. $this->firstExpression->dispatch($sqlWalker)
				. ', '
				. $this->secondExpression->dispatch($sqlWalker)
				. ')';
		}
		return 'ROUND(' . $this->firstExpression->dispatch($sqlWalker) . ')';
	}
}