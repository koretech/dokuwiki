<?php
/**
 * Plugin Sortchart.
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
class syntax_plugin_sortchart extends DokuWiki_Syntax_Plugin {

    public function getType(){ return 'substition'; }
    //public function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }
    public function getSort(){ return 158; }
    public function connectTo($mode) {
      $this->Lexer->addSpecialPattern('<sortchart>.*?</sortchart>', $mode, 'plugin_sortchart');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){

      $html='';
    
      $lines = array();
      $reg = "/^(.+)$/im";
      if (!preg_match_all($reg, $match, $lines)) {
        return array('style' => '', 'html' => '');
      }
      
      $data = array();
      foreach($lines[1] as $line) {
	$exp = explode(';', $line);	
	if (intval($exp[0]) == 0) continue;
		
	$data[intval($exp[0].strval(rand(100,199)) )] = array(
	  'value' => intval($exp[0]),
	  'name' => trim($exp[1]),
	  'desc' => trim($exp[2])
	);
      }      
      krsort($data);
      
      $pos = 1;
      foreach($data as $key => $dot) {
	$data[$key]['range'] = strval($pos) . '&ndash;' . strval($pos+$dot['value']-1);
	$pos += $dot['value'];
      }
      
      $rows = array();
      foreach($data as $dot) {
	$rows[] = <<<EOT
<tr><td>{$dot['range']}</td><td>{$dot['value']}</td><td>{$dot['name']}</td><td>{$dot['desc']}</td></tr>
EOT;
      }
      
      $rowHtml = implode($rows);
      $html = <<<EOT
<table class="inline">
<thead><tr><th>Range</th><th>Value</th><th>Name</th><th>Description</th></tr></thead>
<tbody>{$rowHtml}</tbody>
</table>
EOT;
     
	$style = 'sortchart';
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
