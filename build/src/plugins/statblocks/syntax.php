<?php
/**
 * Plugin Statblocks.
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
class syntax_plugin_statblocks extends DokuWiki_Syntax_Plugin {

    public function getType(){ return 'formatting'; }
    public function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }
    public function getSort(){ return 158; }
    public function connectTo($mode) {
      $this->Lexer->addSpecialPattern('<statblock>.*?</statblock>', $mode, 'plugin_statblocks');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){
    
	$baseStats = array(
		'brawn' => '',
		'agility' => '',
		'intellect' => '',
		'cunning' => '',
		'willpower' => '',
		'presence' => ''
	);

	$stats = array(
		'soak' => '',
		'wounds' => '',
		'woundscurrent' => '',
		'strain' => '',
		'straincurrent' => '',
		'force' => '',
		'encumbrance' => '',
		'encumbrancecurrent' => '',
		'meleedefense' => '',
		'rangeddefense' => ''
	);

	$m = '';
	foreach($baseStats as $key => $stat) {
	  $reg = "/^".$key.":\s*(\d+)/im";
	  if (preg_match($reg, $match, $m)) { $baseStats[$key] = $m[1]; }
	}
	foreach($stats as $key => $stat) {
	  $reg = "/^".$key.":\s*(\d+)/im";
	  if (preg_match($reg, $match, $m)) { $stats[$key] = $m[1]; }
	}
	
	$other = '';
	$pos = 1;
	
	if ($stats['soak'] != '') {
	  $other .= "<div class='otherStat soak small pos-$pos'><div>{$stats['soak']}</div></div>";
	  $pos++;
	}
	
	if ($stats['wounds'].$stats['woundscurrent'] != '') {
	  $other .= "<div class='otherStat wounds pos-$pos'><div>{$stats['wounds']}</div><div>{$stats['woundscurrent']}</div></div>";
	  $pos++;
	}
	
	if ($stats['strain'].$stats['straincurrent'] != '') {
	  $other .= "<div class='otherStat strain pos-$pos'><div>{$stats['strain']}</div><div>{$stats['straincurrent']}</div></div>";
	  $pos++;
	}
	
	if ($stats['force'] != '') {
	  $other .= "<div class='otherStat force small pos-$pos'><div>{$stats['force']}</div></div>";
	  $pos++;
	}
	
	if ($stats['encumbrance'].$stats['encumbrancecurrent'] != '') {
	  $other .= "<div class='otherStat encumbrance pos-$pos'><div>{$stats['encumbrance']}</div><div>{$stats['encumbrancecurrent']}</div></div>";
	  $pos++;
	}
	
	if ($stats['meleedefense'].$stats['rangeddefense'] != '') {
	  $other .= "<div class='otherStat defense pos-$pos'><div>{$stats['rangeddefense']}</div><div>{$stats['meleedefense']}</div></div>";
	  $pos++;
	}
	

$html = <<<EOT
<div class='statblock'>
	<div class='baseStat brawn'>{$baseStats['brawn']}</div>
	<div class='baseStat agility'>{$baseStats['agility']}</div>
	<div class='baseStat intellect'>{$baseStats['intellect']}</div>
	<div class='baseStat cunning'>{$baseStats['cunning']}</div>
	<div class='baseStat willpower'>{$baseStats['willpower']}</div>
	<div class='baseStat presence'>{$baseStats['presence']}</div>
	{$other}
</div>
EOT;
	
/*
$html = <<<EOT
<div class='statblock {$stats['type']}'>
	<div class='brawn'>{$stats['brawn']}</div>
	<div class='agility'>{$stats['agility']}</div>
	<div class='intellect'>{$stats['intellect']}</div>
	<div class='cunning'>{$stats['cunning']}</div>
	<div class='willpower'>{$stats['willpower']}</div>
	<div class='presence'>{$stats['presence']}</div>
	<div class='small soak'>{$stats['soak']}</div>
	<div class='small wounds'>{$stats['wounds']}</div>
	<div class='small woundscurrent'>{$stats['woundscurrent']}</div>
	<div class='small strain'>{$stats['strain']}</div>
	<div class='small straincurrent'>{$stats['straincurrent']}</div>
	<div class='small force'>{$stats['force']}</div>
	<div class='small encumbrance'>{$stats['encumbrance']}</div>
	<div class='small encumbrancecurrent'>{$stats['encumbrancecurrent']}</div>
	<div class='small meleedefense'>{$stats['meleedefense']}</div>
	<div class='small rangeddefense'>{$stats['rangeddefense']}</div>
</div>
EOT;
*/

	$style = 'statblock';
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
