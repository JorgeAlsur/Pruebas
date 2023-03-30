<?php
// $Id: XML_Codec.php,v 1.1 2001/06/11 15:25:21 cviebrock Exp $


# figure out which DOMXML syntax is in use
/*
$__x = xmltree('<a b="c">d</a>');
if ($__x->children[0]->name=='a' && $__x->children[0]->attributes[0]->name=='b' && 
  $__x->children[0]->attributes[0]->children[0]->content=='c' && $__x->children[0]->content=='d') {
        define ('_DOMXML_VERSION', 5);
} else if ($__x->children[0]->tagname=='a' && $__x->children[0]->attributes[0]->name=='b' &&
  $__x->children[0]->attributes[0]->value=='c' && $__x->children[0]->children[0]->content=='d') {

} else {
	die('Cannot determine DOMXML version ... please email the author');
}*/
        define ('_DOMXML_VERSION', 6);


class XML_Codec {

	var $_OPT		= '';
	var $_SPACER		= ' ';		# indent character
	var $_CRLF		= "\n";
	var $VERSION		= '0.1';



#
#	XML_Codec()
#		Constructor
#

	function XML_Codec($args=array()) {
		if ($args['option']=='compress') {
			$this->_OPT	= 'compress';
			$this->_SPACER	= '';
			$this->_CRLF	= '';
		}
	}


#
#	data_to_xml_string()
#	- converts a data structure to an string (xml)
#

	function data_to_xml_string($ref) {
		return str_repeat($this->_SPACER,2) . '<data_block>' .
			$this->Tree2XML($ref, 3) .
			$this->_CRLF . str_repeat($this->_SPACER,2) . '</data_block>';
	}


#
#	Tree2XML()
#		Takes a PHP array and converts it to XML representation
#

	function Tree2XML(&$array, $indent=0) {
		$string = '';

		if (is_array($array)) {
			if ($this->_is_assoc($array)) {		# HASH REFERENCE
				$string .= $this->_CRLF . str_repeat($this->_SPACER,$indent) . '<dt_assoc>';
				$end = '</dt_assoc>';
			} else {				# ARRAY REFERENCE
				$string .= $this->_CRLF . str_repeat($this->_SPACER,$indent) . '<dt_array>';
				$end = '</dt_array>';
			}
			foreach ($array as $k=>$v) {
				$indent++;
				# don't encode some types of stuff
				if ((gettype($v)=='resource') || (gettype($v)=='user function') || (gettype($v)=='unknown type')) {
					continue;
				}
				$string .= $this->_CRLF . str_repeat($this->_SPACER,$indent) . '<item key="' . $k . '"';
				if (gettype($v)=='object' && get_class($v)) {
					$string .= ' class="' . get_class($v) . '"';
				}
				$string .= '>';
				if (is_array($v) or is_object($v)) {
					$string .= $this->Tree2XML($v, $indent+1);
					$string .= $this->_CRLF . str_repeat($this->_SPACER,$indent) . '</item>';
				} else {
					$string .= $this->quote_XML_chars($v) . '</item>';
				}
				$indent--;
			}
			$string .= $this->_CRLF . str_repeat($this->_SPACER,$indent) . $end;

		} else {					# SCALAR

			$string .= $this->_CRLF . str_repeat($this->_SPACER,$indent) . '<dt_scalar>' .
				$this->quote_XML_chars($array) . '</dt_scalar>';
		}

		return($string);

	}


#
#	quote_XML_chars()
#		Quote special XML characters
#




	function quote_XML_chars($string) {
//horaciod
		$string = strtr($string,
			array ('&'=>'&amp;', '<'=>'&lt;', '>'=>'&gt;', "'"=>'&apos;', '"'=>'&quot;'));

//			array (, , , , ));
//		$string = utf8_encode($string);
		return $string;
	}


#
#	xml_to_data()
#		Accepts an DOM XML tree object, based at the <data_block> level,
#		and converts it to a PHP array
#

	function xml_to_data(&$data_block) {
		$topitem =& $data_block->children[0];		# skip the <data_block> level
		$ref = $this->Undump($topitem);
		return $ref;
	}


#
#	Undump()
#		Takes a DOM XML tree object (node) and recursively undumps it to create an
#		array.
#
#		The top-level object is a scalar, a reference to a scalar, a hash, or an array.
#		Hashes and arrays may themselves contain scalars, or references to
#		scalars, or references to hashes or arrays.
#
#		NOTE: One exception is that scalar values are never "undef" because
#		there's currently no way to accurately represent undef in the dumped data.
#

	function Undump(&$tree) {

		if ($this->_isTextNode($tree)) {
			$ref = $tree->content;

		} else if ($this->_getTagName($tree)=='dt_scalar') {
			$ref = $tree->children[0]->content;

		} else if ($this->_getTagName($tree)=='dt_scalarref') {
			$ref = ${$tree->children[0]->content};		# ???

		} else if ( ($this->_getTagName($tree)=='dt_assoc') || ($this->_getTagName($tree)=='dt_array') ) {

			$ref = array();
			if (is_array($tree->children)) {		# catch empty <dt_assoc></dt_assoc> blocks
				foreach($tree->children as $child) {
					if ($this->_getTagName($child) == 'item') {
						$class = $key = false;
						foreach($child->attributes as $attr) {
							if ($attr->name == 'key') {
								$key = $this->_getAttrValue($attr);
							}
							if ($attr->name == 'class') {
								$class = $this->_getAttrValue($attr);
							}
						}

						$ref[$key] = $this->Undump($child->children[0]);

# Uh ... maybe I'll try and figure out bless()-ing later :)
#						if ($class) {
#							$ref[$key] = $value;
#						}

					}
				}
			}
		} else {
			# Unknown tag ... skip it
		}

		return $ref;
	}




#
#	decode()
#		Accepts and XML protocol string and decodes it into a PHP array
#   recibe una ruta y decodifica a parirtir de ahi

	function decode($path) {
	echo "ruta: $path";
   

		return $this->xml_to_data($tree);
	}


#
#	encode()
#		Accepts a PHP array and encodes it into an XML protocol string
#

	function encode($array) {
		return $this->data_to_xml_string($array);
	}


#
#	_is_assoc()
#		Determines if an array is associative or not, since PHP
#		doesn't really distinguish between the two, but Perl/OPS does
#

	function _is_assoc(&$array) {
	        if (is_array($array)) {
	                foreach ($array as $k=>$v) {
	                        if (!is_int($k)) {
	                                return true;
	                        }
	                }
	        }
	        return false;
	}


	//
	// These methods are required because PHP changed it's syntax for the DOMXML
	// extention between 4.0.5 and 4.0.6 (and may again in the future).  Grr.
	//

	function _getTagName(&$node) {
		switch (_DOMXML_VERSION) {
		    case 5:
			return $node->name;
			break;
		    case 6:
			return $node->tagname;
			break;
		    default:
			die('Unknown _DOMXML_VERSION: ' . _DOMXML_VERSION );
			break;
		}
	}

	function _getAttrValue(&$node) {
		switch (_DOMXML_VERSION) {
		    case 5:
			return $node->children[0]->content;
			break;
		    case 6:
			return $node->value;
			break;
		    default:
			die('Unknown _DOMXML_VERSION: ' . _DOMXML_VERSION );
			break;
		}
	}

	function _isTextNode(&$node) {
		switch (_DOMXML_VERSION) {
		    case 5:
			return ($node->type == XML_TEXT_NODE);
			break;
		    case 6:
			return (strtolower(get_class($node)) == 'domtext');
			break;
		    default:
			die('Unknown _DOMXML_VERSION: ' . _DOMXML_VERSION );
			break;
		}
	}




}

