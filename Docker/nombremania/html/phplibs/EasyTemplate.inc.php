<?php
/*****************************************************************************
 *                                                                           *
 *                Web Application Development with PHP                       *
 *                                 by                                        *
 *                 Tobias Ratschiller and Till Gerken                        *
 *                                                                           *
 *          Copyright (c) 2000, Tobias Ratschiller and Till Gerken           *
 *                                                                           *
 *****************************************************************************
 *                                                                           *
 * $Title: EasyTemplate - a simple FastTemplate-like template handler $      *
 * $Chapter: Web Application Concepts $                                      *
 * $Executable: false $                                                      *
 *                                                                           *
 * $Description:                                                             *
 * This is a simple templating solution. It lacks the advanced features of   *
 * FastTemplate but offers enhanced usability and performance.               *
 * Pleasre refer to the book for a more detailed API overview.               *
 *                                                                           *
 *****************************************************************************/

class EasyTemplate
{
    // Public variable ---------------------------------------------------------
    var $error = "";                // Last error message will be stored here

    // Private variables -------------------------------------------------------
    var $tags = array();            // The tag values pairs, as defined by assign()
    var $template_file = "";        // The template file
    var $first="{";
    var $end = "}";
    /*
     *      void EasyTemplate(string template_file)
     *      Constructor - assign template_file to the class
     */
    function EasyTemplate($template_file)
    {
        // Basic check whether the template file exists or not,
        // unfortunately, we can't return(false) in a constructor
        if(!file_exists($template_file))
        {
            $this->error = "The template file $template_file does not exist.";

        }

        // Assign template file
        $this->template_file = $template_file;
    }

    /*
     *      bool assign(string tag, string value)
     *      Assign a value for template tag
     *      Sets $this->error and returns false on error, returns true on success
     */
    function assign($tag, $value)
    {
        // If the supplied tag name is empty, bail out
        if(empty($tag))
        {
            $this->error = "Tag name is empty";
            return(false);
        }

        // Assign value to tag
        $this->tags[$tag] = $value;

        return(true);
    }

    /*
     *      mixed easy_parse()
     *      Return the parsed template as string
     *      Sets $this->error and returns false on error, or returns the parsed template on success
     */
    function easy_parse()
    {
        // Read in template file, suppress error messages
        $contents = @implode("", (@file($this->template_file)));

        // Loop through all assigned tag-value pairs
        while(list($key, $value) = each($this->tags))
        {
            // Construct the template tag name
            $tag = $this->first.$key.$this->end;

            // Is there such a tag in the template?
            if(!strstr($contents, $tag))
            {
                $this->error .= "Tag $tag not found in template ".$this->template_file.".<br>";
              //  return(false);
            } else {
            // Replace the template tag with the respective value
            $contents = str_replace($tag, $value, $contents);
	    }
        }

        // Return the parsed template
        return($contents);
    }

    /*
     *      bool easy_print()
     *      Parse and print the current template
     *      Sets $this->error and returns false on error, returns true on success
     */
    function easy_print()
    {
        // Parse the template
        $ret = $this->easy_parse();

        // Error found?
        if($ret == false)
        {
            return(false);
        }

        // Output the parsed template
        print($ret);

        return(true);
    }
}

/* $Id: EasyTemplate.inc.php,v 1.1 2000/06/15 18:04:19 tobias Exp $ */
?>
