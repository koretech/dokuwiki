<?php
/**
 * Plugin Eote.
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Mike Kornelson <mike@durbn.net>
 */
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_eote extends DokuWiki_Syntax_Plugin {
 
    public function getType(){ return 'formatting'; }
    public function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
    public function getSort(){ return 158; }
    public function connectTo($mode) {
      $this->Lexer->addSpecialPattern('`[^`]*?`', $mode, 'plugin_eote');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){
	$val = strtoupper(substr($match, 1, -1));
	$style = '';
	switch ($val) {
	  case 'ABILITY':
	  case 'AB':
	    $style = 'ability'; break;
	  case 'ADVANTAGE':
	  case 'AD':
	    $style = 'advantage'; break;
	  case 'BOOST':
	  case 'BO':
	  case 'B':
	    $style = 'boost'; break;
	  case 'CHALLENGE':
	  case 'CH':
	  case 'C':
	    $style = 'challenge'; break;
	  case 'DIFFICULTY':
	  case 'DI':
	    $style = 'difficulty'; break;
	  case 'DESPAIR':
	  case 'DE':
	    $style = 'despair'; break;
	  case 'DARKSIDE':
	  case 'DA':
	    $style = 'darkside'; break;
	  case 'FAILURE':
	  case 'FA':
	    $style = 'failure'; break;
	  case 'FORCE':
	  case 'FO':
	    $style = 'force'; break;
	  case 'LIGHTSIDE':
	  case 'LI';
	  case 'L';
	    $style = 'lightside'; break;
	  case 'PROFICIENCY':
	  case 'PR':
	  case 'P':
	    $style = 'proficiency'; break;
	  case 'SUCCESS':
	  case 'SU':
	    $style = 'success'; break;
	  case 'SETBACK':
	  case 'SE':
	    $style = 'setback'; break;
	  case 'THREAT':
	  case 'TH':
	    $style = 'threat'; break;
	  case 'TRIUMPH':
	  case 'TR':
	    $style = 'triumph'; break;
	  default:
	    return array('style'=>'','html'=>'');
	}
	
	$html = "<span class='eote " . $style . "'></span>";
	
	return array('style' => $style, 'html' => $html);
    }
 
    /**
     * Create output
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        // $data is what the function handle() return'ed.
        if($mode == 'xhtml'){
            /** @var Doku_Renderer_xhtml $renderer */
            
            $renderer->doc .= $data['html'];
            return true;
        }
        return false;
    }
 
}