#! /usr/bin/perl -w
##############################################################################
##  Name: 		GeneratePhpDocumentation.pl
##
$VERSION = "1.0.2";
##
##  Authors:
##		Nigel Swinson	nigelswinson@users.sourceforge.net
##
##  Description:
##
##  Parses through a php file and generatea a xml documentation suitable
##  for running through an XSL to produce html output for a php component.
##
###############################################################################
##  Copyright:
##
##  GeneratePhpDocumentation.pl: A Perl program that generates an XML file of 
##  documentation for a php file that uses the JavaDoc formatting for function
##  comments
##
##  Copyright (C) 2000-2001  Nigel Swinson, nigelswinson@users.sourceforge.net
##  
##  This program is free software; you can redistribute it and/or
##  modify it under the terms of the GNU General Public License
##  as published by the Free Software Foundation; either version 2
##  of the License, or (at your option) any later version.
##  
##  This program is distributed in the hope that it will be useful,
##  but WITHOUT ANY WARRANTY; without even the implied warranty of
##  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
##  GNU General Public License for more details.
##  
##  You should have received a copy of the GNU General Public License
##  along with this program; if not, write to the Free Software
##  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
##
###############################################################################

package main;

# Put path . onto the front of the inclusion array.
unshift(@INC, '.');

###############################################################################
###############################################################################
## Global variables.

##########################################
## General logic variables.

($PROG = $0) =~ s/.*\///;
$PROGNAME = $PROG;
$PROGNAME =~ s/(.*\\)//g;

$INPUT_FILE = '';	# The name of the php file

$OUTPUT_FILE = '';	# If specified, output goes to this file.

$OUTPUTHANDLE = ''; # File handle for where we are to output the list.

$DEBUG = 0;			# Set this flag to produce debugging output.

$SENT_HTTP_HEADERS = 0;			# Set to 1 when we have sent an HTTP header 
								# (if required).

###############################################################################
## -------------------------Begin MAIN---------------------------------------##
###############################################################################
{
	# Get the command line options.
	&ParseCommandLine();

	&Message("\nBegin Main\n");
	&Message("============================================================\n");	

	# Open the output
	if ($OUTPUT_FILE) {
		&Message("Opening $OUTPUT_FILE for output.\n");
		open(OUTPUTFILE, ">$OUTPUT_FILE")
			|| &Error("Error: Unable to create $OUTPUT_FILE\n");
		$OUTPUTHANDLE = 'OUTPUTFILE';
	} else {
		$OUTPUTHANDLE = 'STDOUT';

		if (!$SENT_HTTP_HEADERS && !$DEBUG) {
			print "Content-Type:text/html\n\n";
			$SENT_HTTP_HEADERS = 1;
		}

	}

	&ProcessFile($INPUT_FILE);

	# Close output file.
	if ($OUTPUT_FILE) {
		close(OUTPUTFILE);
	}

	&Message("============================================================\n");

	exit 0;
}

###############################################################################
## ---------------------------End MAIN---------------------------------------##
###############################################################################

###############################################################################
###############################################################################
## Main functions.

###############################################################################
##	Process the file by appending a ~ to the end of every file if there isn't 
##  one there already.
##
sub ProcessFile {
	&Message("Processing file @_\n");
	($FileName) = @_;

	# If we can open the file then look at it's contents
	if(open(INFILE, "$FileName")) {
		local (@FileContents);
		# Get file attributes
		my @FileAttrib = stat($FileName);
		@FileContents = <INFILE>;
		&Message("File $FileName has ".$FileAttrib[7]." bytes and ".@FileAttrib." lines\n");
		close (INFILE);

		local(@FunctionIndices);
		for ($iIndex = 0; $iIndex < @FileContents; $iIndex++) {
			# Find a function
			if ($FileContents[$iIndex] =~ /^\s*function (.*$)/) {
				push(@FunctionIndices, $iIndex);
			}
		}

		&Message("Found ".@FunctionIndices." functions\n");

		print $OUTPUTHANDLE "<?xml version=\"1.0\"?>\n";
		print $OUTPUTHANDLE "<PhpDocumentation>\n";

		foreach $iFunctionIndex (@FunctionIndices) {
			&ProcessFunction($iFunctionIndex, @FileContents);
		}
		print $OUTPUTHANDLE "</PhpDocumentation>\n";
	} else {
		 &Message("Unable to open $FileName because $!.\n");
	}

	&Message("\n");
}

###############################################################################
##	Process the file by appending a ~ to the end of every file if there isn't 
##  one there already.
##
sub ProcessFunction {
	($iFunctionIndex, @FileContents) = @_;

	&Message("Processing function that is declared on line $iFunctionIndex\n");

	# Get the function prototype
	$ProtoType = $FileContents[$iFunctionIndex];
	$ProtoType =~ s/^\s*//g;
	$ProtoType =~ s/\s*{\s*$//g;
	&Message("\tFunction prototype = $ProtoType\n");

	# Extract the file name and prototype
	$FunctionName = $ProtoType;
	if ($FunctionName !~ /function\s&?\s*([^\(\s]*)\s*\((.*)\)$/g) {
		&Error("Failed to parse $ProtoType into function name and arguments");
	}
	$FunctionName = $1;
	$Arguments = $2;
	&Message("\tFunction name = $FunctionName\n");
	&Message("\tFunction arguments = $Arguments\n");

	# Now trace back to the line that start with /**
	$iCommentStartLine = $iFunctionIndex - 1;
	$iCommentStartLine-- if ($FileContents[$iCommentStartLine] =~ /^\s*\*\/\s*$/);
	local(@aComment);
	while ($iCommentStartLine > 0) {
		last if ($FileContents[$iCommentStartLine] =~ /^\s*\/\*\*/);
		if ($FileContents[$iCommentStartLine] =~ /^\s*\* ?(.*$)/) {
			unshift(@aComment, "$1\n"); 
		}
		$iCommentStartLine--;
	}
	&Message("\tFound ".($iFunctionIndex - $iCommentStartLine).
				" lines of comment before the function decleration\n");

	my($See, $Return, $Author, $FunctionComment, $ShortComment, $Deprecate) = ("","","","","",'');
	@aParameters = ();
	if (@aComment) {
		# Firstly extract the short comment if there was one.
		$ShortComment = '';
		if (($aComment[0] !~ /^\s*@/g)
			&& ((@aComment <= 1)
				|| ($aComment[1] =~ /^\s*$/))) {
			$ShortComment = $aComment[0];
			shift(@aComment);
			shift(@aComment);
		}

		# Now scan through the comment and extract all the @XXX lines.
		
		foreach ($iIndex = 0; $iIndex < @aComment; $iIndex++) {
			$Line = $aComment[$iIndex];

			# Catch lines that start with @
			if ($Line =~ /^\s*@([^\s]*)\s*(.*)\s*$/g) {
				$Name = $1;
				$Value = $2;
				if ($Name =~ /param/) {
					while ((++$iIndex < @aComment) 
								&& ($aComment[$iIndex] =~ /^\s*([^@].*)\s*$/g)) {
						$Value .= " $1";
					}
					$iIndex--;
					push(@aParameters, $Value);
				} elsif ($Name =~ /author/) {
					while ((++$iIndex < @aComment) 
								&& ($aComment[$iIndex] =~ /^\s*([^@].*)\s*$/g)) {
						$Value .= " $1";
					}
					$iIndex--;
					$Author .= $Value;
				} elsif ($Name =~ /return/) {
					while ((++$iIndex < @aComment) 
								&& ($aComment[$iIndex] =~ /^\s*([^@].*)\s*$/g)) {
						$Value .= " $1";
					}
					$iIndex--;
					$Return .= $Value;
				} elsif ($Name =~ /see/) {
					while ((++$iIndex < @aComment) 
								&& ($aComment[$iIndex] =~ /^\s*([^@].*)\s*$/g)) {
						$Value .= " $1";
					}
					$iIndex--;
					$See .= $Value;
				} elsif ($Name =~ /deprecated/) {
					while ((++$iIndex < @aComment) 
								&& ($aComment[$iIndex] =~ /^\s*([^@].*)\s*$/g)) {
						$Value .= " $1";
					}
					$iIndex--;
					$Deprecate .= $Value;
				} else {
					&Error("Unhandled tag.  Please alter function to support the $Name tag".
						" as used in the php file near line $iFunctionIndex\n");
				}
			} else {
				$FunctionComment .= $Line;
			}
		}
	}

	&Message("\tAuthor is: ".$Author."\n");
	&Message("\tParameters are: @aParameters\n");
	&Message("\tReturn value is: $Return\n");
	&Message("\tSimilar functions are: $See\n");
	&Message("\tShort comment is: $ShortComment\n");
	&Message("\tFunction comment is: $FunctionComment\n");
	&Message("\tDepreciated comment is: $Deprecate\n");

	print $OUTPUTHANDLE "\t<Function>\n";
	OutputValue("FunctionName", $FunctionName);
	OutputValue("ShortComment", $ShortComment);
	OutputValue("Prototype", $ProtoType);
	OutputValue("LineNumber", $iFunctionIndex);
	OutputValue("Comment", $FunctionComment);
	OutputValue("Author", $Author);
	if (@aParameters) {
		# Parameters have to be done specially
		print $OUTPUTHANDLE "\t\t<Parameters>\n";
		foreach $Parameter (@aParameters) {
			OutputValue("Param", $Parameter);
		}
		print $OUTPUTHANDLE "\t\t</Parameters>\n";
	}
	OutputValue("Return", $Return);
	OutputValue("See", $See);
	OutputValue("Deprecate", $Deprecate);
	print $OUTPUTHANDLE "\t</Function>\n";

}

###############################################################################
##	Outputs a value to the xml file.
##
sub OutputValue {
	($Name, $Value) = @_;

	$Value =~ s/^\s*(.*)/$1/g;
	$Value =~ s/(.*)\s*$/$1/g;
	return if ($Value =~ /^$/);
	$Value =~ s/&/&amp;/g;
	$Value =~ s/</&lt;/g;
	$Value =~ s/>/&gt;/g;

	print $OUTPUTHANDLE "\t\t<$Name>";
	print $OUTPUTHANDLE "$Value";
	print $OUTPUTHANDLE "</$Name>\n";
}

###############################################################################
###############################################################################
## Configuration information parsing functions.

###############################################################################
##	ParseCommandLine() retrieves all command-line options and intializes
##	global variables.
##
sub ParseCommandLine {
	local(@pairs,$pair,@Unknown);

	# Get the Commandline either by get, post or from the command line.
	if ($ENV{'REQUEST_METHOD'}) {
	    if ($ENV{'REQUEST_METHOD'} eq 'GET') {
	        # Split the name-value pairs
	        @pairs = split(/&/, $ENV{'QUERY_STRING'});
	    } elsif ($ENV{'REQUEST_METHOD'} eq 'POST') {
	        # Get the input
	        read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
	
	        # Split the name-value pairs
	        @pairs = split(/&/, $buffer);
		} else {
	     	&Error("The $ENV{'REQUEST_METHOD'} method is not supported by this ".
					"script. Only GET and POST are allowed.\n");
		}
	} else {
		# Input is from the command line then.
 	  	@pairs = @ARGV;
	}

    # For each name-value pair:
   	foreach $pair (@pairs) {
		next if (!$pair);
        # Split the pair up into individual variables.
		local($name, $value) = split(/=/, $pair);
		next if (!$name);
   	
		# Decode the form encoding on the name and value variables.
		$name =~ tr/+/ /;
		$name =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
      	
		if ($value) {
			$value =~ tr/+/ /;
			$value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
        	# If they try to include server side includes, erase them, so they
   	    	# aren't a security risk if the html gets returned.  Another
       		# security hole plugged up.
	     	$value =~ s/<!--(.|\n)*-->//g;
		}

		# Catch only expected inputs.
		if ($name =~ /^help$/i or $name =~ /^usage$/i) {
			&Usage();
		} elsif ($name =~ /^input$/i){
			$INPUT_FILE = $value;
		} elsif ($name =~ /^output$/i){
			$OUTPUT_FILE = $value;
		} elsif ($name =~ /^debug$/i){
			$DEBUG = 1;
		} else {
			# We only allow the two arguments so ignore the rest.
			unshift(@Unknown,$pair);
		}		
   	}

	# Check that we got the parameters we need.
	if (!$INPUT_FILE) {
		Usage();
	}

	# If we were not accessed from the web then we will not need to send Http headers
	if (!$ENV{'REQUEST_METHOD'}) {	
		$SENT_HTTP_HEADERS = 1;
	}

	if ($DEBUG) {
		&Message("Cgi Environment Details\n");
		&Message("===================\n");
		&Message("Request Method = $ENV{'REQUEST_METHOD'}\n")
												if ($ENV{'REQUEST_METHOD'});
		&Message("Script Name = $ENV{'SCRIPT_NAME'}\n") if $ENV{'SCRIPT_NAME'};
		&Message("Query String = @pairs\n");
		&Message("Recognised debug flag\n\n") if $DEBUG;
		&Message("Unknown:\"@Unknown\"\n\n") if (@Unknown);		
	} else {
    	Usage() if (@Unknown);
	}
}

###############################################################################
###############################################################################
## Information functions.

###############################################################################
## Prints the usage information.
sub Usage {
    select STDOUT;

	# Send Http headers if required
	if ($ENV{'REQUEST_METHOD'}) {	
		print "Content-Type:text/plain\n\n";
	}

    print <<EndOfUsage;

 =============================================================================
 $PROG version $VERSION

 Description:
  $PROGNAME
  Parses through a php file and generatea a skeleton xml documentation.  

 =============================================================================
 Usage: 
  $PROGNAME directory=<directoryname> input=<filename> output=<filename> [debug]

 input	: The file that we are documenting

 output	: Specify the file that you want the output to go to.  No file means
		: output goes to stdout.

 debug	: Provide this flag to produce debugging information about what the
		: script is doing.

 =============================================================================
 Copyright:
  Copyright (C) 2001  Nigel Swinson, NigelSwinson\@users.sourceforge.net :o)

  $PROGNAME comes with ABSOLUTELY NO WARRANTY and may only be copied
  with the permission of the author.  It may be used free for a thirty day
  trial.
 =============================================================================

EndOfUsage
    exit 0;
}

###############################################################################
## Prints an Error message either to the browser, or to STDOUT
##
## Inputs:
##		ErrorMessage: The error to display
sub Error {
	local($ErrorMessage) = @_;

	if (!$SENT_HTTP_HEADERS) {
		print "Content-Type:text/plain\n\n";
	    $SENT_HTTP_HEADERS = 1;
	}

	print "Error: $PROG\n";
	print "\tThe message returned was: $ErrorMessage\n";

	exit;
}

###############################################################################
## Prints a message either to the browser, or to STDOUT
##
## Inputs:
##		Message: The message to display
sub Message {
	return unless $DEBUG;

	local($Message) = @_;

	if (!$SENT_HTTP_HEADERS) {
		print "Content-Type:text/plain\n\n";
	    $SENT_HTTP_HEADERS = 1;
	}

	print STDOUT $Message;
}

###############################################################################
###############################################################################
## File parsing functions

###############################################################################
##	CopyFile() copies file $src to $dst;
##
## Covered by GNU
##
sub CopyFile {
    local($src, $dst) = @_;
    open(SRC, $src) || &Error("Error: Unable to open $src\n");
    open(DST, "> $dst") || &Error("Error: Unable to create $dst\n");
    print DST <SRC>;
    close(SRC);
    close(DST);
}

###############################################################################
1;
