<?php

namespace Impressa\Doctrine\Query\AST;

use \Doctrine\ORM\Query\AST\Functions\FunctionsNode;
use Doctrine\ORM\Query\Lexer;

class MysqlDistance extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public $lat1;
    public $lat2;
    public $lat1a;
    public $lat2a;
    public $long1;
    public $long2;

    //TODO
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $wlat1 = $sqlWalker->walkSimpleArithmeticExpression($this->lat1);
        $wlat1a = $sqlWalker->walkSimpleArithmeticExpression($this->lat1a);
        $wlat2 = $sqlWalker->walkSimpleArithmeticExpression($this->lat2a);
        $wlat2a = $sqlWalker->walkSimpleArithmeticExpression($this->lat2);
        $wlong1 = $sqlWalker->walkSimpleArithmeticExpression($this->long1);
        $wlong2 = $sqlWalker->walkSimpleArithmeticExpression($this->long2);
                
        return "6371*2*ASIN(SQRT(POWER(SIN(( $wlat1a - $wlat2a)*PI()/180/2),2) + COS($wlat1 * PI()/180) * COS( $wlat2 * PI()/180) * POWER(SIN(( $wlong1 - $wlong2)*PI()/180/2),2)))"  ;
        
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->lat1 = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->lat1a = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->long1 = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->lat2 = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->lat2a = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->long2 = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}