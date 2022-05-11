<?php

// Changement look des "forms" de Moodle pour répondre aux besoins du projet RSG.

require_once ($CFG->dirroot.'/config.php');
require_once $CFG->libdir.'/formslib.php';

class HTML_QuickForm_Renderer_RSG extends HTML_QuickForm_Renderer_Tableless{
    /**
    * Header Template string
    * @var      string
    * @access   private
    */
    var $_headerTemplate = 
        "\n\t\t<legend>{header}</legend>";

   /**
    * Element template string
    * @var      string
    * @access   private
    */
    var $_elementTemplate = 
        "\n\t\t<div class=\"control-group\"><label class=\"control-label\"><!-- BEGIN required --><span class=\"required\">*</span><!-- END required -->{label}</label><div class=\"controls<!-- BEGIN error --> error<!-- END error -->\"><!-- BEGIN error --><div class=\"error\">{error}</div><!-- END error -->{element}</div></div>";

   /**
    * Form template string
    * @var      string
    * @access   private
    */
    var $_formTemplate = 
        "\n<form{attributes}>\n\t<div class=\"row-fluid\">\n\t\t
	<div class=\"span12\">\n\t<div style=\"display: none;\">{hidden}</div>\n{content}\n</div></div></form>";

   /**
    * Template used when opening a fieldset
    * @var      string
    * @access   private
    */
    var $_openFieldsetTemplate = "";//\n\t<div class=\"control-group\" {id}>";

   /**
    * Template used when opening a hidden fieldset
    * (i.e. a fieldset that is opened when there is no header element)
    * @var      string
    * @access   private
    */
    var $_openHiddenFieldsetTemplate = "";//"\n\t<div class=\"control-group\">";

   /**
    * Template used when closing a fieldset
    * @var      string
    * @access   private
    */
    var $_closeFieldsetTemplate = "";//"\n\t</div>";

   /**
    * Required Note template string
    * @var      string
    * @access   private
    */
    var $_requiredNoteTemplate = 
        "\n\t\t<div class=\"qfreqnote\">{requiredNote}</div>";

   /**
    * How many fieldsets are open
    * @var      integer
    * @access   private
    */
   var $_fieldsetsOpen = 0;

   /**
    * Array of element names that indicate the end of a fieldset
    * (a new one will be opened when a the next header element occurs)
    * @var      array
    * @access   private
    */
    var $_stopFieldsetElements = array();
    
}

