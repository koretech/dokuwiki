function skillsOnCreate() {
  var $c = this;
  
  $c.find('.toggle').click(function(){
    $icon = jQuery(this).find('i');
    if ($icon.hasClass('fa-toggle-off')) {
      $icon.removeClass('fa-toggle-off').addClass('fa-toggle-on');
      $c.find('tbody tr.hidden').removeClass('hidden');
    } else {
      $icon.removeClass('fa-toggle-on').addClass('fa-toggle-off');
      $c.find('tbody tr').not('.defined').addClass('hidden');
    }
  });
}

function installSkillsPlugin() {
  jQuery('.skillsContainer').each(function(){
    skillsOnCreate.call(jQuery(this));
  });  
}

jQuery(function(){installSkillsPlugin();});