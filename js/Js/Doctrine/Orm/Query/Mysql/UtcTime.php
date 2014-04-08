<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Orm\Query\Mysql;

class UtcTime extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
	public function parse(\Doctrine\ORM\Query\Parser $parser)
	{
		$parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
		$parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
		$parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
	}

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
	{
		return 'UTC_TIME()';
	}
}