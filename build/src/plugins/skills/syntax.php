<?php
/**
 * Plugin Skills.
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
class syntax_plugin_skills extends DokuWiki_Syntax_Plugin {

    public function getType(){ return 'formatting'; }
    public function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }
    public function getSort(){ return 159; }
    public function connectTo($mode) {
      $this->Lexer->addSpecialPattern('<skills>.*?</skills>', $mode, 'plugin_skills');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, Doku_Handler $handler){
	
	// Base array of skills
	$skills = array(
	  'brawl' => array('name'=>'Brawl', 'base'=>'Br', 'group'=>'Combat'),
	  'gunnery' => array('name'=>'Gunnery', 'base'=>'Ag', 'group'=>'Combat'),
	  'melee' => array('name'=>'Melee', 'base'=>'Br', 'group'=>'Combat'),
	  'rangedlight' => array('name'=>'Ranged (Light)', 'base'=>'Ag', 'group'=>'Combat'),
	  'rangedheavy' => array('name'=>'Ranged (Heavy)', 'base'=>'Ag', 'group'=>'Combat'),
	  'astrogation' => array('name'=>'Astrogation', 'base'=>'Int', 'group'=>'General'),
	  'athletics' => array('name'=>'Athletics', 'base'=>'Br', 'group'=>'General'),
	  'charm' => array('name'=>'Charm', 'base'=>'Pr', 'group'=>'General'),
	  'coercion' => array('name'=>'Coercion', 'base'=>'Will', 'group'=>'General'),
	  'computers' => array('name'=>'Computers', 'base'=>'Int', 'group'=>'General'),
	  'cool' => array('name'=>'Cool', 'base'=>'Pr', 'group'=>'General'),
	  'coordination' => array('name'=>'Coordination', 'base'=>'Ag', 'group'=>'General'),
	  'deception' => array('name'=>'Deception', 'base'=>'Cun', 'group'=>'General'),
	  'discipline' => array('name'=>'Discipline', 'base'=>'Will', 'group'=>'General'),
	  'leadership' => array('name'=>'Leadership', 'base'=>'Pr', 'group'=>'General'),
	  'mechanics' => array('name'=>'Mechanics', 'base'=>'Int', 'group'=>'General'),
	  'medicine' => array('name'=>'Medicine', 'base'=>'Int', 'group'=>'General'),
	  'negotiation' => array('name'=>'Negotiation', 'base'=>'Pr', 'group'=>'General'),
	  'perception' => array('name'=>'Perception', 'base'=>'Cun', 'group'=>'General'),
	  'pilotingplanetary' => array('name'=>'Piloting (Planetary)', 'base'=>'Ag', 'group'=>'General'),
	  'pilotingspace' => array('name'=>'Piloting (Space)', 'base'=>'Ag', 'group'=>'General'),
	  'resilience' => array('name'=>'Resilience', 'base'=>'Br', 'group'=>'General'),
	  'skulduggery' => array('name'=>'Skulduggery', 'base'=>'Cun', 'group'=>'General'),
	  'stealth' => array('name'=>'Stealth', 'base'=>'Ag', 'group'=>'General'),
	  'streetwise' => array('name'=>'Streetwise', 'base'=>'Cun', 'group'=>'General'),
	  'survival' => array('name'=>'Survival', 'base'=>'Cun', 'group'=>'General'),
	  'vigilance' => array('name'=>'Vigilance', 'base'=>'Will', 'group'=>'General'),
	  'coreworlds' => array('name'=>'Core Worlds', 'base'=>'Int', 'group'=>'Knowledge'),
	  'education' => array('name'=>'Education', 'base'=>'Int', 'group'=>'Knowledge'),
	  'lore' => array('name'=>'Lore', 'base'=>'Int', 'group'=>'Knowledge'),
	  'outerrim' => array('name'=>'Outer Rim', 'base'=>'Int', 'group'=>'Knowledge'),
	  'underworld' => array('name'=>'Underworld', 'base'=>'Int', 'group'=>'Knowledge'),
	  'xenology' => array('name'=>'Xenology', 'base'=>'Int', 'group'=>'Knowledge'),
	  'warfare' => array('name'=>'Warfare', 'base'=>'Int', 'group'=>'Knowledge')
	);

	// Detect our input data
	$m = '';
	preg_match_all("/^.*$/m", $match, $m);
	//array_splice($m[0], 0, 1);
	array_shift($m[0]);
	$basesRaw = array_shift($m[0]); //First line must be base stats
	array_pop($m[0]);
	
	// Parse the base stats
	$basesLine = explode(',',$basesRaw);
	$bases = array(
	  'Br'=>intval($basesLine[0]),
	  'Ag'=>intval($basesLine[1]),
	  'Int'=>intval($basesLine[2]),
	  'Cun'=>intval($basesLine[3]),
	  'Will'=>intval($basesLine[4]),
	  'Pr'=>intval($basesLine[5]),
	);

	/*
	// Iterate through the skills and assign base stat value (needed for calculation)
	foreach($skills as &$s) {
	  $baseKey = $s['base'];
	  $s['baseVal'] = $bases[$s['base']];
	}
	*/
	
	// Iterate through the input lines
	foreach($m[0] as $v) {
	  $line = explode(',',$v);
	  $key = strtolower($line[0]);
	  
	  $skills[$key]['rank'] = intval($line[1]); //Assign rank
	  
	  $skills[$key]['defined'] = true; //Indicate that the skill was defined
	  
	  //Assign icon if is career skill
	  if (!empty($line[2])) {
	    $skills[$key]['career'] = "<span class='eote darkside'></span>";
	  } else {
	    $skills[$key]['career'] = "";
	  }
	}
	
	// Iterate through the skills
	foreach($skills as &$s) {
	  $base = $bases[$s['base']]; //Retrieve base value	  
	  $rank = intval($s['rank']); //Retrieve rank
	  
	  // Build the pool icons
	  $pool = '';
	  for($i=0;$i<min($rank,$base);$i++) {
		  $pool .= "<span class='eote proficiency'></span>";
	  }
	  for($i=0;$i<abs($rank-$base);$i++) {
		  $pool .= "<span class='eote ability'></span>";
	  }
	  $s['pool'] = $pool; //Set the pool
	}
	
	$rows = '';
	foreach($skills as $s) {
	  $rowClass = ($s['defined']) ? 'defined' : 'hidden';
	  $rows .= <<<EOT
<tr class="{$rowClass}">
  <td><span class="skill">{$s['name']}</span> ({$s['base']})</td>
  <td>{$s['career']}</td>
  <td>{$s['pool']}</td>
  <td>{$s['rank']}</td>
</tr>
EOT;
	}
	
	
/*	
	$keys = array_keys($skills);
	for($i=0;$i<count($keys);$i+=2) {
	  $s = $skills[$keys[$i]];
	  $rows .= <<<EOT
<tr>
  <td><span class="skill">{$s['name']}</span> ({$s['base']})</td>
  <td>{$s['career']}</td>
  <td>{$s['pool']}</td>
  <td>{$s['rank']}</td>
EOT;

	  if (($i) < count($keys)) {
	    $s = $skills[$keys[$i+1]];
	    $rows .= <<<EOT
  <td><span class="skill">{$s['name']}</span> ({$s['base']})</td>
  <td>{$s['career']}</td>
  <td>{$s['pool']}</td>
  <td>{$s['rank']}</td>
</tr>
EOT;
	  } else {
	    $rows .= <<<EOT
  <td></td><td></td><td></td><td></td></tr>
EOT;
	  }
	}
*/

$html = <<<EOT
<div class="skillsContainer">
<div class="toggle" title='Toggle full skills list'><i class="fa fa-toggle-off"></i></div>
<table class="skills">
<thead>
<tr>
	<th>Skill</th>
	<th>©</th>
	<th>Pool</th>
	<th>Rank</th>
</tr>
</thead>
<tbody>
{$rows}
</tbody>
</table>
</div>
EOT;

//'<pre>'.htmlspecialchars(print_r($skills,true)).'</pre>';


	$style = 'skills';
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
