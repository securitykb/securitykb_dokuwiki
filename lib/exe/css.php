<?php
/**
 * DokuWiki StyleSheet creator
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('NOSESSION')) define('NOSESSION',true); // we do not use a session or authentication here (better caching)
require_once(DOKU_INC.'inc/init.php');
require_once(DOKU_INC.'inc/pageutils.php');
require_once(DOKU_INC.'inc/io.php');
require_once(DOKU_INC.'inc/confutils.php');

// Main (don't run when UNIT test)
if(!defined('SIMPLE_TEST')){
    header('Content-Type: text/css; charset=utf-8');
    css_out();
}


// ---------------------- functions ------------------------------

/**
 * Output all needed Styles
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function css_out(){
    global $conf;
    global $lang;
    $print = (bool) $_REQUEST['print'];   //print mode?

    // The generated script depends on some dynamic options
    $cache = getCacheName('styles'.$print,'.css');

    // Array of needed files and their web locations, the latter ones
    // are needed to fix relative paths in the stylesheets
    $files   = array();
    if($print){
        $files[DOKU_TPLINC.'print.css'] = DOKU_TPL;
        // load plugin styles
        $files = array_merge($files, css_pluginstyles('print'));
        $files[DOKU_CONF.'userprint.css'] = '';
    }else{
        $files[DOKU_INC.'lib/styles/style.css'] = DOKU_BASE.'lib/styles/';
        //fixme extra spellchecker style?
        $files[DOKU_TPLINC.'layout.css'] = DOKU_TPL;
        $files[DOKU_TPLINC.'design.css'] = DOKU_TPL;
        $files[DOKU_TPLINC.'style.css']  = DOKU_TPL;
        if($lang['direction'] == 'rtl'){
            $files[DOKU_TPLINC.'rtl.css'] = DOKU_TPL;
        }
        // load plugin styles
        $files = array_merge($files, css_pluginstyles('screen'));
        $files[DOKU_CONF.'userstyle.css'] = '';
    }

    // check cache age 
    if(css_cacheok($cache,array_keys($files))){
        readfile($cache);
        return;
    }

    // start output buffering and build the stylesheet
    ob_start();

    // print the default classes for interwiki links and file downloads
    css_interwiki();
    css_filetypes();

    // load files
    foreach($files as $file => $location){
        print css_loadfile($file, $location);
    }

    // end output buffering and get contents
    $css = ob_get_contents();
    ob_end_clean();

    // apply style replacements
    $css = css_applystyle($css);

    // compress whitespace and comments
    if($conf['compress']){
        $css = css_compress($css);
    }

    // save cache file
    io_saveFile($cache,$css);

    // finally send output
    print $css;
}

/**
 * Checks if a CSS Cache file still is valid
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function css_cacheok($cache,$files){
    $ctime = @filemtime($cache);
    if(!$ctime) return false; //There is no cache

    // some additional files to check
    $files[] = DOKU_CONF.'dokuwiki.php';
    $files[] = DOKU_CONF.'local.php';
    $files[] = DOKU_TPLINC.'style.ini';
    $files[] = __FILE__;

    // now walk the files
    foreach($files as $file){
        if(@filemtime($file) > $ctime){
            return false;
        }
    }
    return true;
}

/**
 * Does placeholder replacements in the style according to 
 * the ones defined in a templates style.ini file
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function css_applystyle($css){
    if(@file_exists(DOKU_TPLINC.'style.ini')){
        $ini = parse_ini_file(DOKU_TPLINC.'style.ini');
        $css = strtr($css,$ini);
    }
    return $css;
}

/**
 * Prints classes for interwikilinks
 *
 * Interwiki links have two classes: 'interwiki' and 'iw_$name>' where
 * $name is the identifier given in the config. All Interwiki links get
 * an default style with a default icon. If a special icon is available
 * for an interwiki URL it is set in it's own class. Both classes can be
 * overwritten in the template or userstyles.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function css_interwiki(){

    // default style
    echo 'a.interwiki {';
    echo ' background: transparent url('.DOKU_BASE.'lib/images/interwiki.png) 0px 1px no-repeat;';
    echo ' padding-left: 16px;';
    echo '}';

    // additional styles when icon available
    $iwlinks = getInterwiki();
    foreach(array_keys($iwlinks) as $iw){
        if(@file_exists(DOKU_INC.'lib/images/interwiki/'.$iw.'.png')){
            echo "a.iw_$iw {";
            echo '  background-image: url('.DOKU_BASE.'lib/images/interwiki/'.$iw.'.png)';
            echo '}';
        }elseif(@file_exists(DOKU_INC.'lib/images/interwiki/'.$iw.'.gif')){
            echo "a.iw_$iw {";
            echo '  background-image: url('.DOKU_BASE.'lib/images/interwiki/'.$iw.'.gif)';
            echo '}';
        }
    }
}

/**
 * Prints classes for file download links
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function css_filetypes(){

    // default style
    echo 'a.mediafile {';
    echo ' background: transparent url('.DOKU_BASE.'lib/images/fileicons/file.png) 0px 1px no-repeat;';
    echo ' padding-left: 16px;';
    echo '}';

    // additional styles when icon available
    $mimes = getMimeTypes();
    foreach(array_keys($mimes) as $mime){
        if(@file_exists(DOKU_INC.'lib/images/fileicons/'.$mime.'.png')){
            echo "a.mf_$mime {";
            echo '  background-image: url('.DOKU_BASE.'lib/images/fileicons/'.$mime.'.png)';
            echo '}';
        }elseif(@file_exists(DOKU_INC.'lib/images/fileicons/'.$mime.'.gif')){
            echo "a.mf_$mime {";
            echo '  background-image: url('.DOKU_BASE.'lib/images/fileicons/'.$mime.'.gif)';
            echo '}';
        }
    }
}

/**
 * Loads a given file and fixes relative URLs with the
 * given location prefix
 */
function css_loadfile($file,$location=''){
    if(!@file_exists($file)) return '';
    $css = io_readFile($file);
    if(!$location) return $css;

    $css = preg_replace('!(url\( *)([^/])!','\\1'.$location.'\\2',$css);
    return $css;
}

/**
 * Returns a list of possible Plugin Styles (no existance check here)
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function css_pluginstyles($mode='screen'){
    $list = array();
    $plugins = plugin_list();
    foreach ($plugins as $p){
        if($mode == 'print'){
            $list[DOKU_PLUGIN."$p/print.css"]  = DOKU_BASE."lib/plugins/$p/";
        }else{
            $list[DOKU_PLUGIN."$p/style.css"]  = DOKU_BASE."lib/plugins/$p/";
            $list[DOKU_PLUGIN."$p/screen.css"] = DOKU_BASE."lib/plugins/$p/";
        }
    }
    return $list;
}

/**
 * Very simple CSS optimizer
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function css_compress($css){
    // strip whitespaces
    $css = preg_replace('![\r\n\t ]+!',' ',$css);
    $css = preg_replace('/ ?([:;,{}\/]) ?/','\\1',$css);

    // strip comments (ungreedy)
    // We keep very small comments to maintain typical browser hacks
    $css = preg_replace('#(/\*)((?!\*/).){4,}(\*/)#Us','',$css);

    // shorten colors
    $css = preg_replace("/#([0-9a-fA-F]{1})\\1([0-9a-fA-F]{1})\\2([0-9a-fA-F]{1})\\3/", "#\\1\\2\\3",$css);

    return $css;
}


//Setup VIM: ex: et ts=4 enc=utf-8 :
?>