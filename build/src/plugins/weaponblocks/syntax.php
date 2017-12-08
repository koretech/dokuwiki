<?php
/**
 * Plugin Weaponblocks.
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
class syntax_plugin_weaponblocks extends DokuWiki_Syntax_Plugin {

    public function getType(){ return 'substition'; }
    //public function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }
    public function getSort(){ return 158; }
    public function connectTo($mode) {
      $this->Lexer->addSpecialPattern('<weaponblock>.*?</weaponblock>', $mode, 'plugin_weaponblocks');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){
    
      $info = array(
	'type' => '',
	'make' => '',
	'special' => '',
	'range' => '',
	'skill' => '',
	'damage' => '',
	'crit' => '',
	'encumbrance' => '',
	'hp' => '',
	'condition' => 'good',
      );


      $m = '';
      foreach($info as $key => $value) {
	$reg = "/^".$key.":\s*(.+)/im";
	if (preg_match($reg, $match, $m)) { $info[$key] = $m[1]; }
      }
      
      if ($info['range'] != '') $info['range'] .= ' Range';

      $special = '';
      $specials = explode(',', $info['special']);
      foreach ($specials as $sp) {
	$special .= "<span>$sp</span>";
      }
      
      
$html = <<<EOT
<div class='weaponblock'>
	<div class='top'>
	  <p><span class='make'>{$info['make']}</span><span class='type'>{$info['type']}</span></p>
	  <p><span class='skill'>{$info['skill']}</span> &mdash; <span class='range'>{$info['range']}</span></p>
	  <div class='special'>$special</div>
	</div>
	<div class='stats'>
	  <div class='damage'>{$info['damage']}</div>
	  <div class='crit'>{$info['crit']}</div>
	  <div class='encumbrance'>{$info['encumbrance']}</div>
	  <div class='hp'>{$info['hp']}</div>
	  <div class='condition {$info['condition']}'>x</div>
	</div>	
</div>
EOT;

	$style = 'weaponblock';
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
