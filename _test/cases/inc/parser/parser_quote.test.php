<?php
require_once 'parser.test.php';

class TestOfDoku_Parser_Quote extends TestOfDoku_Parser {

    function TestOfDoku_Parser_Quote() {
        $this->UnitTestCase('TestOfDoku_Parser_Quote');
    }
    
    function testQuote() {
        $this->P->addMode('quote',new Doku_Parser_Mode_Quote());
        $this->P->parse("abc\n> def\n>>ghi\nklm");
        $calls = array (
            array('document_start',array()),
            array('p_open',array()),
            array('cdata',array("\nabc")),
            array('p_close',array()),
            array('quote_open',array()),
            array('cdata',array(" def")),
            array('quote_open',array()),
            array('cdata',array("ghi")),
            array('quote_close',array()),
            array('quote_close',array()),
            array('p_open',array()),
            array('cdata',array("klm\n")),
            array('p_close',array()),
            array('document_end',array()),
            
        );
        $this->assertEqual(array_map('stripbyteindex',$this->H->calls),$calls);
    }
    
    function testQuoteWinCr() {
        $this->P->addMode('quote',new Doku_Parser_Mode_Quote());
        $this->P->parse("abc\r\n> def\r\n>>ghi\r\nklm");
        $calls = array (
            array('document_start',array()),
            array('p_open',array()),
            array('cdata',array("\nabc")),
            array('p_close',array()),
            array('quote_open',array()),
            array('cdata',array(" def")),
            array('quote_open',array()),
            array('cdata',array("ghi")),
            array('quote_close',array()),
            array('quote_close',array()),
            array('p_open',array()),
            array('cdata',array("klm\n")),
            array('p_close',array()),
            array('document_end',array()),
            
        );
        $this->assertEqual(array_map('stripbyteindex',$this->H->calls),$calls);
    }
    
    function testQuoteMinumumContext() {
        $this->P->addMode('quote',new Doku_Parser_Mode_Quote());
        $this->P->parse("\n> def\n>>ghi\n ");
        $calls = array (
            array('document_start',array()),
            array('p_open',array()),
            array('cdata',array("\n")),
            array('p_close',array()),
            array('quote_open',array()),
            array('cdata',array(" def")),
            array('quote_open',array()),
            array('cdata',array("ghi")),
            array('quote_close',array()),
            array('quote_close',array()),
            array('p_open',array()),
            array('cdata',array(" \n")),
            array('p_close',array()),
            array('document_end',array()),
            
        );
        $this->assertEqual(array_map('stripbyteindex',$this->H->calls),$calls);
    }
    
    function testQuoteEol() {
        $this->P->addMode('quote',new Doku_Parser_Mode_Quote());
        $this->P->addMode('eol',new Doku_Parser_Mode_Eol());
        $this->P->parse("abc\n> def\n>>ghi\nklm");
        $calls = array (
            array('document_start',array()),
            array('p_open',array()),
            array('cdata',array("abc")),
            array('p_close',array()),
            array('quote_open',array()),
            array('cdata',array(" def")),
            array('quote_open',array()),
            array('cdata',array("ghi")),
            array('quote_close',array()),
            array('quote_close',array()),
            array('p_open',array()),
            array('cdata',array("klm")),
            array('p_close',array()),
            array('p_open',array()),
            array('p_close',array()),
            array('document_end',array()),
            
        );
        $this->assertEqual(array_map('stripbyteindex',$this->H->calls),$calls);
    }

}

/**
* Conditional test runner
*/
if (!defined('TEST_RUNNING')) {
    define('TEST_RUNNING', true);
    $test = &new TestOfDoku_Parser_Quote();
    $test->run(new HtmlReporter());
}