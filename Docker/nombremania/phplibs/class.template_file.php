<?php
/*
 * class Template 1.0 - Clase para procesar plantillas en PHP.
 * Copyright (C) 2000 Julio C�sar Carrascal Urquijo.
 *                    <adnoctum@eudoramail.com>
 *http://nimutt.hypermart.net/index2.html
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
 */

if(defined("CLASS_TEMPLATE_PHP_FILE"))return;
define("CLASS_TEMPLATE_PHP_FILE", 1);

/*
 * class Template.
 */

class Template {
    var $classname = "Template";
    var $root = ".";
    var $blocks = array();
    var $vars = array();
    var $unknowns = "remove";  // "remove" | "comment" | "keep"
    var $halt_on_error = "yes";   // "yes" | "report" | "no"
	var $nav = true; //procesa los navegadores		
	var $filename="";
	//var $cache_dir = "/tmp/cachegis";
	var $cache = false;
	var $cache_dir = false;
	var $cache_expire=3600;
    /*
     * Template([string $root], [string $unknowns]);
     * Constructor. $root es el directorio donde se buscaran las plantillas(el
     * directorio actual por defecto) y $unknowns especifica qu� se debe hacer
     * con las variables no definidas.
     */
    function Template($root = ".", $unknowns = "") {
       $this->set_root($root);
        $this->set_unknowns($unknowns);
    //		    $this->scan_globals();
    }

    /*
     * void set_root(string $root);
     * Selecciona a $root como el directorio donde se buscar�n las plantillas.
     */
    function set_root($root) {
      if(!is_dir($root)){
				   $this->halt("set_root: $root no es un directorio.");
            return false;
        }
	
        $this->root = $root;
        return true;
    }

    /*
     * void set_file(mixed $name, [string $filename]);
     * Lee el fichero $filename y lo almacena en el bloque $name. Si $name es un
     * vector se lee cada uno de los ficheros especificados de la forma
     * array("name" => "filename", ...);.
     */
    function set_file($name, $filename) {
	   $this->filename= $filename;
        if(is_array($name)) {
            for(reset($name); list($k, $v) = each($name); ) {
                $this->extract_blocks($k, $this->load_file($v));
            }
        } else {
            $this->extract_blocks($name, $this->load_file($filename));
        }
    }

    /*
     * void set_block(mixed $parent, $handle, [string $name]);
     * Esta funci�n no hace nada. Solo est� disponible por compatibilidad con
     * PHPLib. Con Template no es necesaria ya que los bloques se extraen
     * autom�ticamente y se almacenan en $this->blocks. Cuando un bloque es
     * procesado, el resultado se almacena en $this->vars, de forma que pueda
     * ser incluido en otro bloque como si fuera una variable normal.
     */
    function set_block($parent, $handle, $name = "") {
        if(isset($name)) {
            return preg_replace("/\{$handle}/", "\{$name}", $this->blocks[$parent]);
        }
    }

    /*
     * void set_var(mixed $var, [string $value]);
     * Registra el valor $value como $var. Si $var es un vector de la forma
     * array("var"=>"value) entonces todo el vector es registrado.
     */
    function set_var($var, $value = "") {
        if(is_array($var)) {
            for(reset($var); list($k, $v) = each($var); )
                $this->vars["/\{$k}/"] = $v;
        } else {
            $this->vars["/\{$var}/"] = $value;
        }
    }

    /*
     * string parse(string $target, [string $block], [bool $append]);
     * Procesa el bloque especificado por $block y almacena el resultado en
     * $target. Si $block no se ha especificado se asume igual a $target.
     * $append especifica si se debe a�adir o sobreescribir la variable
     * $target(sobreescribir por defecto).
     */
    function parse($target, $block = "", $append = false) {
        if($block == "") {
            $block = $target;
        }
        if(isset($this->blocks["/\{$block}/"])) {
            if($append) {
                $this->vars["/\{$target}/"] .= @preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/\{$block}/"]);
            } else {
                $this->vars["/\{$target}/"] = @preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/\{$block}/"]);
            }
            switch($this->unknowns) {
                case "keep":
                break;

                case "comment":
                $this->vars["/\{$target}/"] = preg_replace('/{(.+)}/', "<!-- UNDEF: \\1 -->", $this->vars["/\{$target}/"]);
                break;

                case "remove":
                default:
                $this->vars["/\{$target}/"] = preg_replace('/{\w+}/', "", $this->vars["/\{$target}/"]);
                break;
            }
        } else {
            $this->halt("parse: No existe ningun bloque llamado \"$block\".");
        }
        return $this->vars["/\{$target}/"];
    }

    /*
     * int pparse(string $target, [string $block], [bool $append]);
     * Procesa e imprime el bloque especificado. Vea "parse" para una
     * descripci�n de los argumentos.
     */
    function pparse($target, $block="", $append = false) {
        return print($this->parse($target, $block, $append));
    }

    /*
     * int p(string $block);
     * Imprime el bloque especificado por $block.
     */
    function p($block) {
        return print($this->vars["/\{$block}/"]);
    }

    /*
     * int o(string $block);
     * Regresa el contenido del bloque especificado por $block.
     */
    function o($block) {
        return $this->vars["/\{$block}/"];
    }

    /*
     * void scan_globals(void);
     * Escanea los contenidos de las variables globales y las almacena como
     * G_X donde X es el nombre de la variable.
     */
    function scan_globals() {
        return true;
	/*for(@reset($GLOBALS); list($k, $v) = @each($GLOBALS); ) {
            $this->vars["/\{G_$k}/"] = $v;
        }*/
    }


    /*
     * int get_vars(void);
     * Regresa un vector con las variables.
     */
    function get_vars() {
        reset($this->vars);
        while(list($k,$v) = each($this->vars)) {
            preg_match('/^{(.+)}$/', $k, $regs);
            $vars[$regs[1]] = $v;
        }
        return $vars;
    }

    /*
     * string get_var(string $varname);
     * Regresa el contenido de la variable $varname. Si $varname es un arreglo
     * regresa otro con los valores de las mismas.
     */
    function get_var($varname) {
        if(is_array($varname)) {
            for(reset($varname); list(,$k) = each($varname); )
                $result[$k] = $this->vars["/\{$k}/"];
            return $result;
        } else {
            return $this->vars["/\{$varname}/"];
        }
    }

    /*
     * string get(string $varname);
     * Regresa el contenido de $varname.
     */
    function get($varname) {
        return $this->vars["/\{$varname}/"];
    }

    /*
     * void set_unknowns(enum $unknowns);
     * Especifica qu� se debe hacer con las variables no definidas. Puede ser
     * uno de "remove"(Eliminar), "comment"("Comentar") o "keep"(Eliminar).
     */
    function set_unknowns($unknowns = "keep") {
        $this->unknowns = $unknowns;
    }

/* private: */

    /*
     * string load_file(string $filename);
     * Regresa el contenido del fichero especificado por $filename.
     */
    function load_file_OLD($filename) {
        if(($fh = fopen("$this->root/$filename", "r"))) {
            $file_content = fread($fh, filesize("$this->root/$filename"));
            fclose($fh);
        } else {
            $this->halt("load_file: No se puede abrir $this->root/$filename.");
        }
        return $file_content;
    }
		
		function load_file($filename) {
				global $DOCUMENT_ROOT;
   	     $filename=str_replace("-->","",$filename);
        if($this->DEBUG) { print("<li><b>_load_file</b></li>\n".var_crudas(func_get_args())); }
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
				print"this->root : ";
				var_dump($this->root);
            $this->halt("_load_file: Can not open file $auxdir{$filename} .");
        }
        return $file_content;
    }
		
		function proc_file($filename) {
		global $DOCUMENT_ROOT;
        $filename=str_replace("-->","",$filename);
		if (substr($filename,0,1)!="/") $filename="/".$filename;
		ob_start();
//		ob_implicit_flush(1);
		//virtual($filename);
		include_once($_SERVER['DOCUMENT_ROOT']."/".$filename);
		$ret_str = ob_get_contents();		
		ob_end_clean();
		return $ret_str;
    }
		

    /*
     * void extract_blocks(string $name, string $block);
     * Extrae los bloques de $block y los almacena en el bloque $name.
     */
    function extract_blocks($name, $block) {
        $level = 0;
        $current_block = $name;
        $blocks = explode("<!-- ", $block);
        if(list(, $block) = @each($blocks)) {
            $this->blocks["/\{$current_block}/"] .= $block;
            while(list(, $block) = @each($blocks)) {
                preg_match('/^(FILE|BEGIN|END|FILEPHP) (.+) -->(.*)$/s', $block, $regs);
                switch($regs[1]) {
                    case "FILE":
                    if ($this->nav and strstr($regs[2],"inc/nav.php")) {
					//hack para golfinspain 
					$this->extract_blocks($current_block, $this->proc_file($regs[2]));
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
					}else {
					$this->extract_blocks($current_block, $this->load_file($regs[2]));
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
					}
                    break;
					case "FILEPHP":
					if (!$this->nav  and strstr($regs[2],"inc/nav.php")){
					}else {
					$this->extract_blocks($current_block, $this->proc_file($regs[2]));
                    $this->blocks["/\{$current_block}/"] .= $regs[3];
					}
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

	function halt($msg)
	{
		$this->last_error = $msg;
		if($this->halt_on_error != "no")
		$this->haltmsg($msg);
		if($this->halt_on_error == "yes")
		die("<b>Halted.</b>\n");
        return false;
    }

	function haltmsg($msg)
	{
		print("<b>Template Error:</b> $msg<br>\n");
    }
	function existe_bloque($bloque)
	{
		return isset($this->blocks["/\{$bloque}/"]);
	}

function get_block($bloque)
{
	if($this->existe_bloque($bloque))
	{
		return $this->blocks["/\{$bloque}/"];
	}
	else return false;
}

	function cache_template($target)
	{
		if($this->cache_dir=="" or $this->cache_id=="" or $this->cache==false) return false;
		$fp=fopen($this->cache_dir."/".$this->cache_id.".cache",'w');
		if($fp)
		{
			$x=fwrite($fp,$this->parse($target,"",false));
			$x=fwrite($fp, "<!-- cache generado el ".date('Y-m-d h:i:s',time() ). " -->");		
			fclose($fp);
			readfile($this->cache_dir."/".$this->cache_id.".cache");
		}	
		else die("error en el directorio o al grabar los caches ");
	}

	function check_cache($show=true)
	{
		if($this->cache_dir=="" or $this->cache_id=="" or $this->cache==false or $this->cache_expire==0) return false;
		if(file_exists($this->cache_dir."/".$this->cache_id.".cache"))
		{
			$stat = stat($this->cache_dir."/".$this->cache_id.".cache");
			if($stat[9]+ $this->cache_expire >time())
			{
				if($show)
				{
					readfile($this->cache_dir."/".$this->cache_id.".cache");
					echo "<!-- obtenido de cache ".date('Y-m-d h:i:s',time() )." -->";
				}
				return true;
			}
			else return false;
		}
		else return false;
	}

};
?>
