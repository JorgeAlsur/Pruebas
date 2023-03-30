<?php

/************************************************************************
 * class Template 1.4 - Class for processing templates in PHP           *
 * Copyright (C) 2000 Julio César Carrascal Urquijo.                    *
 *                    <adnoctum@eudoramail.com>                         *
 *                                                                      *
 * Modified and translated to English by: Rob Hudson                    *
 *                                                                      *
 * This library is free software; you can redistribute it and/or        *
 * modify it under the terms of the GNU Lesser General Public           *
 * License as published by the Free Software Foundation; either         *
 * version 2.1 of the License, or (at your option) any later version.   *
 *                                                                      *
 * This library is distributed in the hope that it will be useful,      *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of       *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU    *
 * Lesser General Public License for more details.                      *
 *                                                                      *
 * You should have received a copy of the GNU Lesser General Public     *
 * License along with this library; if not, write to the Free Software  *
 * Foundation Inc. 59 Temple Place Suite 330 Boston, MA 02111-1307  USA *
 ************************************************************************/

if(defined("CLASS_TEMPLATE_PHP")) return;
define("CLASS_TEMPLATE_PHP", 1);

/***************************************************************
 * class Template.                                             *
 ***************************************************************/

class Template {
    var $classname = "Template";
    var $root = ".";
    var $unknowns = "remove";       // "remove" | "comment" | "keep"
    var $halt_on_error = "yes";     // "yes" | "report" | "no"
    var $auto_scan_globals = false;  // "true" | "false"
    var $DEBUG = false;             // "true" | "false"

    /*************************************************************
     * Format of the date, time and datetime variables.          *
     *************************************************************/
    var $locale_string = "es_ES";
    var $datetime_format = "%A %d de %B %Y - %I:%M:%S %p";
    var $date_format = "%A %d de %B %Y";
    var $time_format = "%I:%M:%S %p";

    var $blocks = array();
    var $vars = array();

    /*************************************************************
     * Template([string $root], [string $unknowns]);             *
     * Constructor. $root is the directory were all templates    *
     * will be searched for. $unknows especify what to do with   *
     * undefined variables.                                      *
     *************************************************************/
    function Template($root = ".", $unknowns = "") {
        if($this->DEBUG) { print("<li><b>Constructor</b></li>\n".$this->_show_args(func_get_args())); }
        $this->set_root($root);
        if($unknowns)
            $this->set_unknowns($unknowns);
        if($this->auto_scan_globals)
            $this->scan_globals();
    }

    /*************************************************************
     * void set_root(string $root);                              *
     * $root is the directory were all templates will be         *
     * searched for.                                             *
     *************************************************************/
    function set_root($root) {
        if($this->DEBUG) { print("<li><b>set_root</b></li>\n".$this->_show_args(func_get_args())); }
        if(!is_dir($root)) {
            $this->_halt("set_root: $root isn't a directory.");
            return false;
        }
        $this->root = $root;
        return true;
    }

    /*************************************************************
     * void set_file(mixed $name, [string $filename]);           *
     * Read $filename and store in block $name. If $name is an   *
     * array, create the array like this:                        *
     *     array("name" => "filename", ...);                     *
     *                                                           *
     *************************************************************/
    function set_file($filename, $name = "out") {
        if($this->DEBUG) { print("<li><b>set_file</b></li>\n".$this->_show_args(func_get_args())); }
        if(is_array($filename)) {
            for(reset($filename); list($k, $v) = each($filename); ) {
                $this->_extract_blocks($k, $this->_load_file($v));
            }
        } else {
            $this->_extract_blocks($name, $this->_load_file($filename));
        }
    }

    /*************************************************************
     * void set_var(mixed $var, [string $value]);                *
     *                                                           *
     * Assign $value to $var. If $var is an array of the form    *
     * array("var"=>"value") then assign each value in the array.*
     *************************************************************/
    function set_var($var, $value = "") {
        if($this->DEBUG) { print("<li><b>set_var</b></li>\n".$this->_show_args(func_get_args())); }
        if(is_array($var)) {
            for(reset($var); list($k, $v) = each($var); ) {
                $this->vars["/\{$k}/"] = $v;
            }
        } else {
            $this->vars["/\{$var}/"] = $value;
        }
    }

    /*************************************************************
     * string parse(string $target, [string $block], [bool $append]);
     *                                                           *
     * Process the block specified by $block and store the       *
     * result in $target. If $block is not specified assume it   *
     * is the same as $target. $append specifies if we should    *
     * append (default) or not append the result of the parsed   *
     * block to $target.                                         *
     *************************************************************/
    function parse($target, $block = "", $append = true) {
        if($this->DEBUG) { print("<li><b>parse</b></li>\n".$this->_show_args(func_get_args())); }
        if($block == "") {
            $block = $target;
        }
        if(isset($this->blocks["/\{$block}/"])) {
            if($append) {
                $this->vars["/\{$target}/"] .= @preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/\{$block}/"]);
            } else {
                $this->vars["/\{$target}/"] = @preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/\{$block}/"]);
            }
        } else {
            $this->_halt("parse: \"$block\" does not exist.");
        }
        return $this->vars["/\{$target}/"];
    }

    /*************************************************************
     * int pparse(string $target, [string $block], [bool $append]);
     * Process and print the specified $block. See 'parse' for a *
     * description of the arguments.                             *
     *************************************************************/
    function pparse($target = "out", $block = "", $append = 1) {
        if($this->DEBUG) { print("<li><b>pparse</b></li>\n".$this->_show_args(func_get_args())); }
        $this->parse($target, $block, $append);
        $this->_finish($target);
        return print($this->vars["/\{$target}/"]);
    }

    /*************************************************************
     * int p(string $block);                                     *
     * Print the contents of $block.                             *
     *************************************************************/
    function p($block) {
        if($this->DEBUG) { print("<li><b>p</b></li>\n".$this->_show_args(func_get_args())); }
        $this->_finish($block);
        return print($this->vars["/\{$block}/"]);
    }

    /*************************************************************
     * string o(string $block);                                  *
     * Return the contents of $block.                            *
     *************************************************************/
    function o($block) {
        if($this->DEBUG) { print("<li><b>o</b></li>\n".$this->_show_args(func_get_args())); }
        $this->finish($block);
        return $this->vars["/\{$block}/"];
    }

    /*************************************************************
     * array get_vars(void);                                     *
     * Return an array with the defined variables.               *
     *************************************************************/
    function get_vars() {
        if($this->DEBUG) { print("<li><b>get_vars</b></li>\n".$this->_show_args(func_get_args())); }
        reset($this->vars);
        while(list($k,$v) = each($this->vars)) {
            preg_match('/^{(.+)}$/', $k, $regs);
            $vars[$regs[1]] = $v;
        }
        return $vars;
    }

    /*************************************************************
     * mixed get_var(mixed $varname);                           *
     * Return the contents of the variable $varname. If $varname *
     * is an array, return an array with their values.           *
     *************************************************************/
    function get_var($varname) {
        if($this->DEBUG) { print("<li><b>get_var</b></li>\n".$this->_show_args(func_get_args())); }
        if(is_array($varname)) {
            for(reset($varname); list(,$k) = each($varname); ) {
                $result[$k] = $this->vars["/\{$k}/"];
            }
            return $result;
        } else {
            return $this->vars["/\{$varname}/"];
        }
    }

    /*************************************************************
     * string get(string $varname);                              *
     * Return the contents of $varname.                          *
     *************************************************************/
    function get($varname) {
        if($this->DEBUG) { print("<li><b>get</b></li>\n".$this->_show_args(func_get_args())); }
        return $this->vars["/\{$varname}/"];
    }

    /*************************************************************
     * void set_unknowns(enum $unknowns);                        *
     * Specify what to do with the undefined variables.          *
     * Options are: "remove", "comment", "keep"                  *
     *************************************************************/
    function set_unknowns($unknowns = "quiet") {
        if($this->DEBUG) { print("<li><b>set_unknowns</b></li>\n".$this->_show_args(func_get_args())); }
        $this->unknowns = $unknowns;
    }

/*************************************************************
 * Private Class Methods.                                    *
 *************************************************************/

    /*************************************************************
     *************************************************************/
    function _finish($block) {
        if($this->DEBUG) { print("<li><b>_finish</b></li>\n".$this->_show_args(func_get_args())); }
        switch($this->unknowns) {
            case "keep":
            break;

            case "comment":
            $this->vars["/\{$block}/"] = preg_replace('/{(.+)}/', "<!-- UNDEF: \\1 -->", $this->vars["/\{$block}/"]);
            break;

            case "remove":
            default:
            $this->vars["/\{$block}/"] = preg_replace('/{\w+}/', "", $this->vars["/\{$block}/"]);
            break;
        }
    }

    /*************************************************************
     * string _load_file(string $filename);                      *
     * Return the contents of the file specified "$filename".    *
     *************************************************************/
    function _load_file($filename) {
				global $DOCUMENT_ROOT;
   	     $filename=str_replace("-->","",$filename);
        if($this->DEBUG) { print("<li><b>_load_file</b></li>\n".$this->_show_args(func_get_args())); }
				if (file_exists($DOCUMENT_ROOT."/".$filename) ){
					 $auxdir=		$DOCUMENT_ROOT."/";
					 $fh = fopen($DOCUMENT_ROOT."/".$filename, "r");
				   $file_content = fread($fh, filesize($DOCUMENT_ROOT."/".$filename));
					 fclose($fh); 
				} 
				elseif( file_exists($this->root."/$filename") and  ($fh = fopen($this->root."/$filename", "r"))) {
					 $auxdir=		$this->root."/";
            $file_content = fread($fh, filesize($this->root."/$filename"));
            fclose($fh);
        } else {
            $this->_halt("_load_file: Can not open file $auxdir{$filename} .");
        }
        return $file_content;
    }

    /*************************************************************
     * void _extract_blocks(string $name, string $block);        *
     * Extract the blocks of $block and store in $name           *
     *************************************************************/
    function _extract_blocks($name, $block) {
        if($this->DEBUG) { print("<li><b>_extract_blocks</b></li>\n".$this->_show_args(func_get_args())); }
        $level = 0;
        $current_block = $name;
        $blocks = explode("<!-- ", $block);
        if(list(, $block) = @each($blocks)) {
            $this->blocks["/\{$current_block}/"] .= $block;
            while(list(, $block) = @each($blocks)) {
                preg_match('/^(FILE|BEGIN|END|FILE) (.+) -->(.*)$/s', $block, $regs);
                switch($regs[1]) {
                    case "FILE":
					if (strstr($this->filename,"inc/nav.php")) die("si");
                    $this->_extract_blocks($current_block, $this->_load_file($regs[2]));
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
                    break;

                    case "BEGIN":
                    $this->blocks["/\{$current_block}/"] .= "\{$regs[2]}";
                    $block_names[$level++] = $current_block;
                    $current_block = $regs[2];
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
                    break;

                    case "END":
                    $current_block = $block_names[--$level];
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
                    break;

                    default:
                    $this->blocks["/\{$current_block}/"] .= "<!-- $block";
                    break;
                }
                unset($regs);
            }
        }
    }


    /*************************************************************
     * void scan_globals();                                     *
     * Scan all globals variables so they are available in our   *
     * templates as {G_name}. Ex: {G_PHPSELF}.                   *
     *************************************************************/
    function scan_globals() {
		return false;
        for(@reset($GLOBALS); list($k, $v) = @each($GLOBALS); ) {
            $this->vars["/\{G_$k}/"] = $v;
        }
        // Date and time variables
        setlocale("LC_TIME", $this->locale_string);
        $this->vars["/\{G_DATETIME}/"] = strftime($this->datetime_format, time());
        $this->vars["/\{G_DATE}/"] = strftime($this->date_format, time());
        $this->vars["/\{G_TIME}/"] = strftime($this->time_format, time());
    }

    /*************************************************************
     * bool _halt(string $msg);                                  *
     * Dies if $halt_on_error is set to 'yes', and prints $msg.  *
     *************************************************************/
    function _halt($msg) {
        if($this->DEBUG) { print("<li><b>_halt</b></li>\n".$this->_show_args(func_get_args())); }
        $this->last_error = $msg;
        if ($this->halt_on_error != "no")
            $this->_haltmsg($msg);
        if ($this->halt_on_error == "yes")
            die("<b>Halted.</b>\n");
        return false;
    }

    /*************************************************************
     * void _haltmsg(string $msg);                               *
     * Prints $msg                                               *
     *************************************************************/
    function _haltmsg($msg) {
        if($this->DEBUG) { print("<li><b>_haltmsg</b></li>\n".$this->_show_args(func_get_args())); }
        print("<b>Template Error:</b> $msg<br>\n");
    }

    /*************************************************************
     * void _show_class_values()                                 *
     * Dumps the values for perusal. Good for debugging.         *
     *************************************************************/
    function _show_class_values() {
        reset ($this->vars);
        print("<li><b>_show_class_values:</b></li>\n<ul>\n");
        print("  <li><b>classname</b> $this->classname</li>");
        print("  <li><b>root</b> $this->root</li>");
        print("  <li><b>blocks</b></li>".$this->_show_args($this->blocks));
        print("  <li><b>vars</b></li>".$this->_show_args($this->vars));
        print("  <li><b>unknowns</b> $this->unknowns</li>");
        print("  <li><b>halt_on_error</b> $this->halt_on_error</li>\n</ul>\n");
    }

    /*************************************************************
     * void _show_class_values()                                 *
     * Format the arguments of a function call. Good for         *
     * debugging too.                                            *
     *************************************************************/
    function _show_args($arg_array) {
        $args = "<ul>";
        while (list($key,$value) = each ($arg_array)) {
            $args .= "<li>$key: ".nl2br(htmlspecialchars($value))."</li>\n";
        }
        return $args . "</ul>\n";
    }
}

?>
