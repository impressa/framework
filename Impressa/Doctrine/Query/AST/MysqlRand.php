<?php

namespace Impressa\Doctrine\Query\AST;

use \Doctrine\ORM\Query\AST\Functions\FunctionsNode;
use Doctrine\ORM\Query\Lexer;

class MysqlRand extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'RAND()';
    }
}