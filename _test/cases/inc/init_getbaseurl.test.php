<?php

require_once DOKU_INC.'inc/init.php';

class init_getBaseURL_test extends UnitTestCase {

    /**
     * Apache, mod_php, subdirectory
     * 
     * data provided by Andreas Gohr <andi@splitbrain.org>
     */
    function test1(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/var/www/';
        $_SERVER['HTTP_HOST']       = 'xerxes.my.home';
        $_SERVER['SCRIPT_FILENAME'] = '/var/www/dokuwiki/doku.php';
        $_SERVER['REQUEST_URI']     = '/dokuwiki/doku.php?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/dokuwiki/doku.php';
        $_SERVER['PATH_INFO']       = null;
        $_SERVER['PATH_TRANSLATED'] = '/var/www/dokuwiki/doku.php';
        $_SERVER['PHP_SELF']        = '/dokuwiki/doku.php';

        $this->assertEqual(getBaseURL(),'/dokuwiki/');
    }

    /**
     * Apache, CGI, mod_userdir, subdirectory
     *
     * data provided by Hilko Bengen <bengen@hilluzination.de>
     */
		function test2(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/var/www/localhost';
        $_SERVER['HTTP_HOST']       = 'localhost';
				$_SERVER['SCRIPT_FILENAME'] = '/usr/lib/cgi-bin/php4';
        $_SERVER['REQUEST_URI']     = '/~bengen/dokuwiki/doku.php?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/cgi-bin/php4';
        $_SERVER['PATH_INFO']       = '/~bengen/dokuwiki/doku.php';
        $_SERVER['PATH_TRANSLATED'] = '/home/bengen/public_html/dokuwiki/doku.php';
        $_SERVER['PHP_SELF']        = '/~bengen/dokuwiki/doku.php';

        $this->assertEqual(getBaseURL(),'/~bengen/dokuwiki/');
    }

    /**
     * Apache, FastCGI, mod_userdir, subdirectory
     *
     * data provided by Hilko Bengen <bengen@hilluzination.de>
     */
    function test3(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/var/www/localhost';
        $_SERVER['HTTP_HOST']       = 'localhost';
        $_SERVER['SCRIPT_FILENAME'] = '/var/run/php-fastcgi/fcgi-bin/bengen/php4';
        $_SERVER['REQUEST_URI']     = '/~bengen/dokuwiki/doku.php?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/fcgi-bin/php4-bengen';
        $_SERVER['PATH_INFO']       = '/~bengen/dokuwiki/doku.php';
        $_SERVER['PATH_TRANSLATED'] = '/home/bengen/public_html/dokuwiki/doku.php';
        $_SERVER['PHP_SELF']        = '/~bengen/dokuwiki/doku.php';

        $this->assertEqual(getBaseURL(),'/~bengen/dokuwiki/');
    }

    /**
     * Apache, mod_php, mod_userdir, subdirectory
     *
     * data provided by Hilko Bengen <bengen@hilluzination.de>
     */
    function test4(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/var/www/localhost';
        $_SERVER['HTTP_HOST']       = 'localhost';
        $_SERVER['SCRIPT_FILENAME'] = '/home/bengen/public_html/dokuwiki/doku.php';
        $_SERVER['REQUEST_URI']     = '/~bengen/dokuwiki/doku.php?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/~bengen/dokuwiki/doku.php';
        $_SERVER['PATH_INFO']       = null;
        $_SERVER['PATH_TRANSLATED'] = '/home/bengen/public_html/dokuwiki/doku.php';
        $_SERVER['PHP_SELF']        = '/~bengen/dokuwiki/doku.php';

        $this->assertEqual(getBaseURL(),'/~bengen/dokuwiki/');
    }

    /**
     * IIS
     *
     * data provided by David Mach <david.mach@centrum.cz>
     */
    function test5(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = null;
        $_SERVER['HTTP_HOST']       = 'intranet';
        $_SERVER['SCRIPT_FILENAME'] = null;
        $_SERVER['REQUEST_URI']     = null; 
        $_SERVER['SCRIPT_NAME']     = '/wiki/doku.php';
        $_SERVER['PATH_INFO']       = '/wiki/doku.php';
        $_SERVER['PATH_TRANSLATED'] = 'C:\\Inetpub\\wwwroot\\wiki\\doku.php';
        $_SERVER['PHP_SELF']        = '/wiki/doku.php';
    
        $this->assertEqual(getBaseURL(),'/wiki/');
    }

    /**
     * Apache 2, mod_php, real URL rewriting, useslash (bug #292)
     *
     * data provided by Ted <bugsX2904@elcsplace.com>
     */
    function test6(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/home/websites/wiki/htdocs';
        $_SERVER['HTTP_HOST']       = 'wiki.linuxwan.net';
        $_SERVER['SCRIPT_FILENAME'] = '/home/websites/wiki/htdocs/doku.php';
        $_SERVER['REQUEST_URI']     = '/wiki/syntax?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/wiki/syntax';
        $_SERVER['PATH_INFO']       = null;
        $_SERVER['PATH_TRANSLATED'] = null;
        $_SERVER['PHP_SELF']        = '/wiki/syntax';
    
        $this->assertEqual(getBaseURL(),'/');
    }

    /**
     * lighttpd, fastcgi
     *
     * data provided by Andreas Gohr <andi@splitbrain.org>
     */
    function test7(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/var/www/';
        $_SERVER['HTTP_HOST']       = 'localhost';
        $_SERVER['SCRIPT_FILENAME'] = '/var/www/dokuwiki/doku.php';
        $_SERVER['REQUEST_URI']     = '/dokuwiki/doku.php?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/dokuwiki/doku.php';
        $_SERVER['PATH_INFO']       = '';
        $_SERVER['PATH_TRANSLATED'] = null;
        $_SERVER['PHP_SELF']        = '';
   
        $this->assertEqual(getBaseURL(),'/dokuwiki/');
    }

    /**
     * Apache, mod_php, Pseudo URL rewrite, useslash
     *
     * data provided by Andreas Gohr <andi@splitbrain.org>
     */
    function test8(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/var/www/';
        $_SERVER['HTTP_HOST']       = 'xerxes.my.home';
        $_SERVER['SCRIPT_FILENAME'] = '/var/www/dokuwiki/doku.php';
        $_SERVER['REQUEST_URI']     = '/dokuwiki/doku.php/wiki/syntax?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/dokuwiki/doku.php';
        $_SERVER['PATH_INFO']       = '/wiki/syntax';
        $_SERVER['PATH_TRANSLATED'] = '/var/www/wiki/syntax';
        $_SERVER['PHP_SELF']        = '/dokuwiki/doku.php/wiki/syntax';

        $this->assertEqual(getBaseURL(),'/dokuwiki/');
    }

    /**
     * Apache, mod_php, real URL rewrite, useslash
     *
     * data provided by Andreas Gohr <andi@splitbrain.org>
     */
    function test9(){
        global $conf;
        $conf['basedir'] = '';
        $conf['baseurl'] = '';
        $conf['canonical'] = 0;

        $_SERVER['DOCUMENT_ROOT']   = '/var/www/';
        $_SERVER['HTTP_HOST']       = 'xerxes.my.home';
        $_SERVER['SCRIPT_FILENAME'] = '/var/www/dokuwiki/doku.php';
        $_SERVER['REQUEST_URI']     = '/dokuwiki/wiki/syntax?do=debug';
        $_SERVER['SCRIPT_NAME']     = '/dokuwiki/doku.php';
        $_SERVER['PATH_INFO']       = null;
        $_SERVER['PATH_TRANSLATED'] = '/var/www/dokuwiki/doku.php';
        $_SERVER['PHP_SELF']        = '/dokuwiki/doku.php';

        $this->assertEqual(getBaseURL(),'/dokuwiki/');
    }
}

//Setup VIM: ex: et ts=2 enc=utf-8 :