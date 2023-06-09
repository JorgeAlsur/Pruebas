<?php
/*
 * class Template 1.0 - Clase para procesar plantillas en PHP.
 * Copyright (C) 2000 Julio C�sar Carrascal Urquijo.
 *                    <adnoctum@eudoramail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

 */

if(defined("CLASS_TEMPLATE_PHP"))return;

define("CLASS_TEMPLATE_PHP",1);

/*
 * class Template.
 */
 
class Template
{
	var $classname = "Template";
    var $root = ".";
    var $blocks = array();
    var $vars = array();
    var $unknowns = "remove";  // "remove" | "comment" | "keep"
    var $halt_on_error = "no";   // "yes" | "report" | "no"
	
    /*
     * Template([string $root], [string $unknowns]);
     * Constructor. $root es el directorio donde se buscaran las plantillas(el
     * directorio actual por defecto) y $unknowns especifica qu� se debe hacer
     * con las variables no definidas.
     */

    function Template($root = ".", $unknowns = "")
	{
        $this->set_root($root);
        $this->set_unknowns($unknowns);
    }

    /*
     * void set_root(string $root);
     * Selecciona a $root como el directorio donde se buscar�n las plantillas.
     */

    function set_root($root) 
	{
        if(!is_dir($root)) 
		{
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

        if(is_array($name))
		{
            for(reset($name); list($k, $v) = each($name); )
			{
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
            return preg_replace("/\{\{$handle}}/", "\{\{$name}}", $this->blocks[$parent]);
        }

    }

    /*
     * void set_var(mixed $var, [string $value]);
     * Registra el valor $value como $var. Si $var es un vector de la forma
     * array("var"=>"value) entonces todo el vector es registrado.
     */

    function set_var($var, $value = "")
	{
		//if($_SERVER['REMOTE_ADDR']=='89.130.215.195' || $_SERVER['REMOTE_ADDR']=='192.168.0.14')$_debug=1;
		
		if($_debug)
		{
			echo "$var=";
			print_r($this->vars);
			echo "<br/>";
			
		}
		
        if(is_array($var))
		{
            for(reset($var); list($k, $v) = each($var);)
                $this->vars["/{{{$k}}}/"] = $v;
        } else {
            $this->vars["/{{{$var}}}/"] = $value;
        }
    }

    /*
     * string parse(string $target, [string $block], [bool $append]);
     * Procesa el bloque especificado por $block y almacena el resultado en
     * $target. Si $block no se ha especificado se asume igual a $target.
     * $append especifica si se debe a�adir o sobreescribir la variable
     * $target(sobreescribir por defecto).
     */

    function parse($target, $block="", $append = false)
	{
		$_debug=0;
		//if($_SERVER['REMOTE_ADDR']=='89.130.215.195' || $_SERVER['REMOTE_ADDR']=='192.168.0.14')$_debug=1;
		
        if($block == "")
		{
            $block = $target;
        }
		if($_debug)echo "target=$target block=".print_r($block,1)." vars=".print_r($this->vars,1)."<br/>";
        if(isset($this->blocks["/{{{$block}}}/"]))
		{
			if(!isset($this->vars["/{{{$target}}}/"]))$this->vars["/{{{$target}}}/"]='';
			if($_debug)
			{
				echo "Antes: ".print_r($this->blocks["/{{{$block}}}/"]);
			}
			
			//$this->vars["/\{\{$target}}/"]=str_replace("\\",'',$this->vars["/\{\{$target}}/"]);
			
           	if(isset($this->vars["/{{{$target}}}/"]) || $append)
			{
               	$this->vars["/{{{$target}}}/"] .= preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/{{{$block}}}/"]);
            }
			else
			{
           		$this->vars["/{{{$target}}}/"] = preg_replace(array_keys($this->vars), array_values($this->vars), $this->blocks["/{{{$block}}}/"]);
			}
			
			if($_debug)
			{
				echo "Despues: ".print_r($this->blocks["/{{{$block}}}/"]);
			}

            switch($this->unknowns)
			{
				case "keep":
                break;
                case "comment":
                	$this->vars["/{{{$target}}}/"] = preg_replace('/{(.+)}/', "<!-- UNDEF: \\1 -->", $this->vars["/\{\{$target}}/"]);
                break;
                case "remove":

                default:
	                $this->vars["/{{{$target}}}/"]=preg_replace('/{{\w+}}/', "", $this->vars["/{{{$target}}}/"]);
					if($_debug)$this->vars["/{{{$target}}}/"]=str_replace("\\",'',$this->vars["/{{{$target}}}/"]);
                break;
            }
		}
        else
		{
            $this->halt("parse: No existe ningun bloque llamado \"$block\".");
        }
		//$this->vars["/\{\{$target}}/"]=str_replace("\\",'',$this->vars["/\{\{$target}}/"]);
		//print_r($this->vars["/\{\{$target}}/"]);
        return $this->vars["/{{{$target}}}/"];
    }

    /*
     * int pparse(string $target, [string $block], [bool $append]);
     * Procesa e imprime el bloque especificado. Vea "parse" para una
     * descripci�n de los argumentos.
     */

	function pparse($target, $block="", $append = false)
	{
		//echo "target=$target<br/>";
        return print($this->parse($target, $block, $append));
    }

    /*
     * int p(string $block);
     * Imprime el bloque especificado por $block.
     */

    function p($block)
	{
        return print($this->vars["/{{{$block}}}/"]);
    }



    /*
     * int o(string $block);
     * Regresa el contenido del bloque especificado por $block.
     */

    function o($block)
	{
        return $this->vars["/{{{$block}}}/"];
    }

    /*

     * void scan_globals(void);
     * Escanea los contenidos de las variables globales y las almacena como
     * G_X donde X es el nombre de la variable.

    /*
     * int get_vars(void);
     * Regresa un vector con las variables.
     */

    function get_vars()
	{
        reset($this->vars);

        while(list($k,$v) = each($this->vars))
		{
            preg_match('/^{{(.+)}}$/', $k, $regs);
            $vars[$regs[1]] = $v;
        }
		//print_r($vars);exit;
        return $vars;
    }

    /*

     * string get_var(string $varname);
     * Regresa el contenido de la variable $varname. Si $varname es un arreglo
     * regresa otro con los valores de las mismas.

     */

    function get_var($varname)
	{
        if(is_array($varname))
		{
            for(reset($varname); list(,$k) = each($varname); )
                $result[$k] = $this->vars["/{{{$k}}}/"];
            return $result;
        }
		else
		{
            return $this->vars["/{{{$varname}}}/"];
        }
    }

    /*
     * string get(string $varname);
     * Regresa el contenido de $varname.
     */

    function get($varname)
	{
        return $this->vars["/{{{$varname}}}/"];
    }

    /*
     * void set_unknowns(enum $unknowns);
     * Especifica qu� se debe hacer con las variables no definidas. Puede ser
     * uno de "remove"(Eliminar), "comment"("Comentar") o "keep"(Eliminar).
     */

    function set_unknowns($unknowns = "keep")
	{
        $this->unknowns = $unknowns;
    }

/* private: */



    /*
     * string load_file(string $filename);
     * Regresa el contenido del fichero especificado por $filename.
     */

    function load_file($filename)
	{
        if(($fh = fopen("$this->root/$filename", "r"))) {
            $file_content = fread($fh, filesize("$this->root/$filename"));
            fclose($fh);
        } else {
            $this->halt("load_file: No se puede abrir $this->root/$filename.");
        }

        return $file_content;
    }

    /*

     * void extract_blocks(string $name, string $block);

     * Extrae los bloques de $block y los almacena en el bloque $name.

     */

    function extract_blocks($name, $block)
	{
        $level = 0;
        $current_block = $name;
        $blocks = explode("<!-- ", $block);
        if(list(, $block) = each($blocks)) {
             if (isset($this->blocks["/{{{$current_block}}}/"])){
            $this->blocks["/{{{$current_block}}}/"] .= $block;
        }
        else {
            $this->blocks["/{{{$current_block}}}/"] = $block;
            }
            while(list(, $block) = each($blocks)) {

                preg_match('/^(FILE|BEGIN|END) (.+) -->(.*)$/s', $block, $regs);
                $que= (isset($regs[1]))? $regs[1]:"otro";
                switch($que) {

                    case "FILE":
                    $this->extract_blocks($current_block, $this->load_file($regs[2]));
                    $this->blocks["/{{{$current_block}}}/"] .= $regs[3];

                    break;
                    case "BEGIN":

                    $this->blocks["/{{{$current_block}}}/"] .= "{{{$regs[2]}}}";

                    $block_names[$level++] = $current_block;

                    $current_block = $regs[2];
                    if (isset($this->blocks["/{{{$current_block}}}/"])){
                    $this->blocks["/{{{$current_block}}}/"] .= $regs[3];
                }
                else {
                     $this->blocks["/{{{$current_block}}}/"] = $regs[3];
                    }
                    break;
                    case "END":
                    $current_block = $block_names[--$level];
                    $this->blocks["/{{{$current_block}}}/"] .= $regs[3];
                    break;

                    default:
                    $this->blocks["/{{{$current_block}}}/"] .= "<!-- $block";
                    break;
                }
                unset($regs);
            }
        }
    }

    function halt($msg)
	{
        $this->last_error = $msg;
        if ($this->halt_on_error != "no")
            $this->haltmsg($msg);
        if ($this->halt_on_error == "yes")
            die("<b>Halted.</b>\n");
        return false;
    }

    function haltmsg($msg)
	{
        print("<b>Template Error:</b> $msg<br>\n");
    }
};
?>
