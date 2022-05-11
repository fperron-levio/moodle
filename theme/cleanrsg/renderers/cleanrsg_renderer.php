<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_bootstrapbase
 * @copyright  2012
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class theme_cleanrsg_core_renderer extends core_renderer {

    /** @var custom_menu_item language The language menu if created */
    protected $language = null;
 //   protected $current = null;

  /*  function __construct(moodle_page $page, $target)
    {
         parent::__construct($page,$target);
    }*/
    /*
     * This renders a notification message.
     * Uses bootstrap compatible html.
     */
    public function notification($message, $classes = 'notifyproblem') {
        $message = clean_text($message);
        $type = '';

        if ($classes == 'notifyproblem') {
            $type = 'alert alert-error';
        }
        if ($classes == 'notifysuccess') {
            $type = 'alert alert-success';
        }
        if ($classes == 'notifymessage') {
            $type = 'alert alert-info';
        }
        if ($classes == 'redirectmessage') {
            $type = 'alert alert-block alert-info';
        }
        return "<div class=\"$type\">$message</div>";
    }

    /*
     * This renders the navbar.
     * Uses bootstrap compatible html.
     */
    public function navbar() {
        $items = $this->page->navbar->get_items();
        $breadcrumbs = array();
        foreach ($items as $item) {
            $item->hideicon = true;
            $breadcrumbs[] = $this->render($item);
        }
        $divider = '<span class="divider">'.get_separator().'</span>';
        $list_items = '<li>'.join(" $divider</li><li>", $breadcrumbs).'</li>';
        $title = '<span class="accesshide">'.get_string('pagepath').'</span>';
        return $title . "<ul class=\"breadcrumb\">$list_items</ul>";
    }
    
    /*
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */
    public function custom_menu($custommenuitems = '', $optionsRemoveItemTags = false) {
        global $CFG;
 
        if (!empty($CFG->custommenuitems)) {
            $custommenuitems .= $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu, $optionsRemoveItemTags);
    }

    /*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    protected function render_custom_menu(custom_menu $menu, $optionsRemoveItemTags = false) {
        global $CFG;

        // TODO: eliminate this duplicated logic, it belongs in core, not
        // here. See MDL-39565.
        $addlangmenu = true;
        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
            or empty($CFG->langmenu)
            or ($this->page->course != SITEID and !empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        if (!$menu->has_children() && $addlangmenu === false) {
            return '';
        }

        if ($addlangmenu) {
            $strlang =  get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        $content = '<ul class="nav">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1, $optionsRemoveItemTags);
        }

        return $content.'</ul>';
    }

    /*
     * This code renders the custom menu items for the
     * bootstrap dropdown menu.
     */
    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0, $optionsRemoveItemTags = false) {

        global $PAGE;
        $current = $PAGE->url;
        
        static $submenucount = 0;

        /* 
           Traitement particulier RSG. 
           La structure de custom_menu_item (voir outputcomponents.php) ne définie pas de notion d'id.
           On utilise get_text pour enregistrer l'id et récupère le texte (avec tags de formatage au cas par cas) dans la table de string de mod_rsg.
           MenuTitle est affiché sur le rollover et ne doit pas afficher les tags html (ex. br, nbsp).
           L'id (qui ne contient plus de formatage) peut être utilisé plus facilement pour des traitement particulier (injecté dans le html).
        */
        $menuId  = $menunode->get_text();
        $menuText = get_string($menuId, 'mod_rsg');
        $menuTitle = html_entity_decode (strip_tags($menuText)); /* enlever br (strip tag) et nbsp (html decode) */
        
        if ($optionsRemoveItemTags) {
            /* Scénario RSG menu adaptif + menu titres sur plusieurs lignes (retour de ligne forcé sur mot précis). */
            /* En version "mobile" doit être sur une ligne */
            $menuText = $menuTitle;  /* $menuTitle a déjà été nettoyé. */
        }
 
        if ($menunode->has_children()) {

            if ($level == 1) {
                $class = 'dropdown';
            } else {
                $class = 'dropdown-submenu';
            }

            if ($menunode === $this->language) {
                $class .= ' langmenu';
            }
            
            
            $content = html_writer::start_tag('li', array('class' => $class));
            // If the child has menus render it as a sub menu.
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_'.$submenucount;
            }
            
            $content .= html_writer::start_tag('a', array('href'=>$url, 'class'=>'dropdown-toggle custommenuitem', 'data-toggle'=>'dropdown', 'title'=>$menuTitle, 'id'=>$menuId));
            $content .= $menuText;
            
            
            if ($level == 1) {
                $content .= '<b class="caret"></b>';
            }
            $content .= '</a>';
            $content .= '<ul class="dropdown-menu">';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode, 0);
            }
            $content .= '</ul>';
            
            
        } else {

            $page_current_url = 'http://'.$current->get_host().$current->get_path();
            // moodle page
            if($current->get_path() == '/mod/page/view.php')
                $page_current_url = (string) $current; 
            
           $content = "";

           $isLoggedin = isloggedin();
            // Fix temporaire.
            // todo: alignement à droite + peut-être que les menus devraient apparaitre systméatiquement indépendament du login?
            if (($menuId == "custommenuitems_faq" || $menuId == "custommenuitems_contact") && !($isLoggedin) || $isLoggedin) {
            
                $content = ($menunode->get_url() == $page_current_url) ? '<li class="active">': '<li>' ;
                // todo: Valider ce que fait le traitement suivant...
                // The node doesn't have children so produce a final menuitem.
                if ($menunode->get_url() !== null) {
                    $url = $menunode->get_url();
                } else {
                    $url = '#';
                }
                
                $content .= html_writer::link($url,  $menuText, array('title'=>$menuTitle, 'id'=>$menuId, 'class'=>'custommenuitem'));
            }
        }
        return $content;
    }

    /**
     * Renders tabtree
     *
     * @param tabtree $tabtree
     * @return string
     */
    protected function render_tabtree(tabtree $tabtree) {
        if (empty($tabtree->subtree)) {
            return '';
        }
        $firstrow = $secondrow = '';
        foreach ($tabtree->subtree as $tab) {
            $firstrow .= $this->render($tab);
            if (($tab->selected || $tab->activated) && !empty($tab->subtree) && $tab->subtree !== array()) {
                $secondrow = $this->tabtree($tab->subtree);
            }
        }
        return html_writer::tag('ul', $firstrow, array('class' => 'nav nav-tabs')) . $secondrow;
    }

    /**
     * Renders tabobject (part of tabtree)
     *
     * This function is called from {@link core_renderer::render_tabtree()}
     * and also it calls itself when printing the $tabobject subtree recursively.
     *
     * @param tabobject $tabobject
     * @return string HTML fragment
     */
    protected function render_tabobject(tabobject $tab) {
        if ($tab->selected or $tab->activated) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'active'));
        } else if ($tab->inactive) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'disabled'));
        } else {
            if (!($tab->link instanceof moodle_url)) {
                // backward compartibility when link was passed as quoted string
                $link = "<a href=\"$tab->link\" title=\"$tab->title\">$tab->text</a>";
            } else {
                $link = html_writer::link($tab->link, $tab->text, array('title' => $tab->title));
            }
            return html_writer::tag('li', $link);
        }
    }
    
    /* Pas de manière simple de modifier standard_head_html? */
    public function rsg_head_html() {  
       $rsg_head = "";
       
       $angular_ie8_fix = "<!-- Fix angular IE8 -->
        <!--[if lte IE 8]>
        <script>
          document.createElement('ng-include');
          document.createElement('ng-pluralize');
        </script>
        <![endif]-->";
        $rsg_head .= $angular_ie8_fix;
        
        return $rsg_head;
    }

    /**
     * Return the standard string that says whether you are logged in (and switched
     * roles/logged in as another user).
     * @param bool $withlinks if false, then don't include any links in the HTML produced.
     * If not set, the default is the nologinlinks option from the theme config.php file,
     * and if that is not set, then links are included.
     * @return string HTML fragment.
     */
    public function login_info($withlinks = null) {
        global $CFG;
    
        $loggedinas = parent::login_info($withlinks);
        
        if ($withlinks == true) {
            $loggedinas .= '<div class="links">'; 
            $loggedinas .= $this->rsg_links();
            $loggedinas .= '</div>';
        }
        
        return $loggedinas;
    }
    
    public function rsg_links() {
        global $CFG;
        
        $output = '';

        // À ajuster au besoin:
        $rsg_custom_links = array(
            "requis"  => "requirements_page_title",
            "conditionsutilisation" => "conditionsutilisation_page_title"/*,
            "politiqueachat" => "politique_achat_page_title",
            "politiqueconfidentialite" => "privacypolicy_page_title"*/,
            "faq" => "faq_page_title",
            "contact" => "nous_joindre_page_title"/*,
            "listecapsules" => "capsuleslist_page_title",
            "nouvelles"  => "news_page_title"*/
        );

        
        $firstItemSeparatorSkipped = false;
        $baseUrl = $CFG->wwwroot . '/mod/rsg/';
        
        foreach ($rsg_custom_links as $key => $page_title_string_id){ 
            $page_title = get_string($page_title_string_id, 'mod_rsg');
            
            // Gestion de la décoration (tiret) entre les items.
            if (!$firstItemSeparatorSkipped) {
                $firstItemSeparatorSkipped  = true;
            } else {
                $output .= " <span>|</span> ";
            }
            
            $link = html_writer::link($baseUrl . $key , $page_title, array('title' => $page_title));
            $output .= $link;
        }

        return $output;
    }
}
