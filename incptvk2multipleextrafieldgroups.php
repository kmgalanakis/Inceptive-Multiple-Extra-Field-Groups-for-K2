<?php
/**
 * @package		Inceptive Multiple Extra Field Groups for K2
 * @author		Extend by Inceptive Design Labs - http://extend.inceptive.gr
 * @copyright	Copyright (c) 2006 - 2012 Inceptive GP. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

require_once("helpers/incptvk2multipleextrafieldgroups.php");

// Load the base K2 plugin class. All K2 plugins extend this class.
JLoader::register('K2Plugin', JPATH_ADMINISTRATOR.'/'.'components'.'/'.'com_k2'.'/'.'lib'.'/'.'k2plugin.php');

class plgK2Incptvk2multipleextrafieldgroups extends K2Plugin
{
    // K2 plugin name. Used to namespace parameters.
    var $pluginName = 'incptvk2multipleextrafieldgroups';
    
    // K2 human readable plugin name. This the title of the plugin users see in K2 form.
    var $pluginNameHumanReadable = 'Inceptive Multiple Extra Field Groups for K2';
    
    var $plg_copyrights_start		= "\n\n<!-- Inceptive Multiple Extra Field Groups for K2 Plugin (v1.0) starts here -->\n";
    var $plg_copyrights_end		= "\n<!-- Inceptive Multiple Extra Field Groups for K2 Plugin (v1.0) ends here -->\n\n";

    // Constructor
    public function __construct(&$subject, $config)
    {   
        // Construct
        parent::__construct($subject, $config);
    }
    
    function onRenderAdminForm (&$item, $type, $tab='') {
	if($tab == '' && $type == 'category')
	{
	    $mainframe 		= JFactory::getApplication();
	    
	    $selectedExtraFieldGroups = array();
	    
	    if($item->id != 0)
	    {
		$db			    = JFactory::getDBO();
		$rows			    = JTable::getInstance('IncptvK2MultipleExtraFieldGroups', 'Table');
		$retrievedExtraFieldGroups  = $rows->getSomeObjectsList('SELECT * FROM #__k2_multiple_extra_field_groups mefg WHERE mefg.catID = '.$item->id.' ORDER BY mefg.exfgid ASC');
		foreach ($retrievedExtraFieldGroups as $retrievedExtraFieldGroup) { 
		    if($retrievedExtraFieldGroup->exfgID != $item->extraFieldsGroup)
			array_push($selectedExtraFieldGroups, $retrievedExtraFieldGroup->exfgID);
		}
	    }
	    
	    if(empty($selectedExtraFieldGroups))
		array_push($selectedExtraFieldGroups, 0);

	    $document 		= JFactory::getDocument();
	    $path 		= str_replace("administrator/", "",JURI::base());
	    $plugin_folder 	= basename(dirname(__FILE__));
	    $document->addScript($path.'plugins/k2/'.$plugin_folder.'/js/incptvk2multipleextrafieldgroups.js');
	    $document->addStyleSheet($path.'plugins/k2/'.$plugin_folder.'/css/style.css');

	    //Loading the appropriate language files
	    $lang = JFactory::getLanguage();
	    $languagePath = JPATH_PLUGINS.DS.'k2'.DS.'incptvk2multipleextrafieldgroups';
	    $lang->load("plg_k2_incptvk2multipleextrafieldgroups", $languagePath, null, false);
	    	    
	    $extraFieldsModel = K2Model::getInstance('ExtraFields', 'K2Model');
	    $groups = $extraFieldsModel->getGroups();
	    for ($i = 0; $i < sizeof($groups); $i++)
	    {
		if($groups[$i]->id == $item->extraFieldsGroup)
		    $groups[$i]->disable = true;
	    }
	    $group[] = JHTML::_('select.option', '0', JText::_('K2_NONE_ONSELECTLISTS'), 'id', 'name');
	    $group = array_merge($group, $groups);

	    $tabIncptvEXFG_innerHtml = '<table class="admintable"><tbody>';
	    $tabIncptvEXFG_innerHtml .='<tr><td class="key">'.JText::_('PLG_K2_MEFG_ADDITIONAL_EXTRA_FIELD_GROUPS_LABEL').'</td>';
	    $tabIncptvEXFG_innerHtml .='<td class="adminK2RightCol">';
	    $tabIncptvEXFG_innerHtml .= JHTML::_('select.genericlist', $group, 'plugins[incptvk2multipleextrafieldgroups][]', 'style="width:100%;" multiple="multiple" size="10"', 'id', 'name', $selectedExtraFieldGroups);
	    //$tabIncptvEXFG_innerHtml .= '<input type="hidden" name="plugins[incptvk2failsafe]" value="FailSafeValue">';
	    $tabIncptvEXFG_innerHtml .= '</td></tr>';	    
	    $tabIncptvEXFG_innerHtml .='</tbody></table>';
		
		$tabIncptvEXFG_innerHtmlK2ge27 = '<div class="itemAdditionalField">';
		$tabIncptvEXFG_innerHtmlK2ge27 .= '<div class="k2FLeft k2Right itemAdditionalValue">';
		$tabIncptvEXFG_innerHtmlK2ge27 .= '<label>'.JText::_('PLG_K2_MEFG_ADDITIONAL_EXTRA_FIELD_GROUPS_LABEL').'</label>';
		$tabIncptvEXFG_innerHtmlK2ge27 .= '</div>';

		$tabIncptvEXFG_innerHtmlK2ge27 .= '<div class="itemAdditionalData">';
		$tabIncptvEXFG_innerHtmlK2ge27 .= JHTML::_('select.genericlist', $group, 'plugins[incptvk2multipleextrafieldgroups][]', 'style="width:100%;" multiple="multiple" size="10"', 'id', 'name', $selectedExtraFieldGroups);
		$tabIncptvEXFG_innerHtmlK2ge27 .= '</div>';
		$tabIncptvEXFG_innerHtmlK2ge27 .= '</div>';
	    
	    $tabIncptvEXFG	=   '<li id="tabIncptvEXFG">
					<a href="#k2tabIncptvEXFG" id="tabIncptvEXFGold">'.JText::_('PLG_K2_MEFG_MULTIPLE_EXTRA_FIELD_GROUPS_LABEL').'</a>
					<a href="#k2tabIncptvEXFG" id="tabIncptvEXFGge27"><i class="fa fa-clone" aria-hidden="true"></i> '.JText::_('PLG_K2_MEFG_MULTIPLE_EXTRA_FIELD_GROUPS_LABEL').'</a>
				    </li>';
	    $tabIncptvEXFG_content  = '<div id="k2tabIncptvEXFG" class="simpleTabsContent k2TabsContent k2TabsContentLower" >'.$tabIncptvEXFG_innerHtml.$tabIncptvEXFG_innerHtmlK2ge27.'</div>';
	    
	    echo $tabIncptvEXFG.$tabIncptvEXFG_content;
	}
	
	if ($tab == 'other' && $type == 'item') {
	    $mainframe 		= JFactory::getApplication();
	    $db 			= JFactory::getDBO();
	    $document 		= JFactory::getDocument();
	    $path 		= str_replace("administrator/", "",JURI::base());
	    $plugin_folder 	= basename(dirname(__FILE__));
	    
	    $document->addScript($path.'plugins/k2/'.$plugin_folder.'/js/incptvk2multipleextrafieldgroups.js');
	    $document->addStyleSheet($path.'plugins/k2/'.$plugin_folder.'/css/style.css');	    

	    //Loading the appropriate language files
	    $lang = JFactory::getLanguage();
	    $languagePath = JPATH_PLUGINS.DS.'k2'.DS.'incptvk2multipleextrafieldgroups';
	    $lang->load("plg_k2_incptvk2multipleextrafieldgroups", $languagePath, null, false);   
	    
	    $query = "SELECT mefg.*, efg.name FROM #__k2_multiple_extra_field_groups mefg
			RIGHT JOIN #__k2_extra_fields_groups AS efg on efg.id = mefg.exfgID
			WHERE catID = ".(int)$item->catid.' ORDER BY efg.id ASC';
	    $db->setQuery($query);
	    $groups = $db->loadObjectList();
	    $extraFieldModel = K2Model::getInstance('ExtraField', 'K2Model');
	    foreach ($groups as $group) {
		$extraFields = $extraFieldModel->getExtraFieldsByGroup($group->exfgID);
		for ($i = 0; $i < sizeof($extraFields); $i++)
		{
		    $extraFields[$i]->element = $extraFieldModel->renderExtraField($extraFields[$i], $item->id);
		}
		$tabIncptvMEFG	=   '<li id="tabIncptvMEFG_'.$group->exfgID.'" class="tabIncptvMEFG">
					<a href="#k2TabIncptvMEFG_'.$group->exfgID.'" id="tabIncptvMEXFGold">'.$group->name.'</a>
					<a href="#k2TabIncptvMEFG_'.$group->exfgID.'" id="tabIncptvMEXFGge27"><i class="fa fa-object-group" aria-hidden="true"></i> '.$group->name.'</a>
				    </li>';
		$exString = '<div id="extraFieldsContainer">';
		if (count($extraFields)):
		   $exString .= '<table class="admintable table" id="extraFields">';
		    foreach($extraFields as $extraField):
			$exString .= '<tr>';
			    if($extraField->type == 'header'):
				$exString .= '<td colspan="2" ><h4 class="k2ExtraFieldHeader">'.$extraField->name.'</h4></td>';
			    else:
				$exString .= '<td align="right" class="key">';
				$exString .= '<label for="K2ExtraField_'.$extraField->id.'">'.$extraField->name.'</label>';
				$exString .= '</td>';
				$exString .= '<td>';
				$exString .= $extraField->element;
				$exString .= '</td>';
			    endif;
			$exString .= '</tr>';
		    endforeach;
		    $exString .= '</table>';
			
			$exString .= '<div class="itemAdditionalFields" id="extraFields">';
		    foreach($extraFields as $extraField):

			    if($extraField->type == 'header'):
				$exString .= '<div class="itemAdditionalField">';
				$exString .= '<div class="k2FLeft k2Right itemAdditionalValue"><h4 class="k2ExtraFieldHeader">'.$extraField->name.'</h4></div>';
				$exString .= '</div>';
			    else:
				$exString .= '<div class="itemAdditionalField">';
				$exString .= '<div class="k2FLeft k2Right itemAdditionalValue">';
				$exString .= '<label for="K2ExtraField_'.$extraField->id.'">'.$extraField->name.'</label>';
				$exString .= '</div>';
				$exString .= '<div class="itemAdditionalData">';
				$exString .= $extraField->element;
				$exString .= '</div>';
				$exString .= '</div>';
			    endif;
		    endforeach;
		    $exString .= '</div>';
		else:
		    if (K2_JVERSION == '15'):
			$exString .='<dl id="system-message">';
			$exString .='<dt class="notice"><?php '.JText::_('K2_NOTICE').'</dt>';
			$exString .='<dd class="notice message fade">';
			$exString .='<ul>';
			$exString .='<li>'.JText::_('PLG_K2_MEFG_ADD_EXTRA_FIELDS_TO_THE_GROUP').'</li>';
			$exString .='</ul>';
			$exString .='</dd>';
			$exString .='</dl>';
		    elseif (K2_JVERSION == '25'):
			$exString .='<div id="system-message-container">';
			$exString .='<dl id="system-message">';
			$exString .='<dt class="notice">'.JText::_('K2_NOTICE').'</dt>';
			$exString .='<dd class="notice message">';
			$exString .='<ul>';
			$exString .='<li>'.JText::_('PLG_K2_MEFG_ADD_EXTRA_FIELDS_TO_THE_GROUP').'</li>';
			$exString .='</ul>';
			$exString .='</dd>';
			$exString .='</dl>';
			$exString .='</div>';
		    else:
			$exString .='<div class="alert">';
			$exString .='<h4 class="alert-heading">'.JText::_('K2_NOTICE').'</h4>';
			$exString .='<div>';
			$exString .='<p>'.JText::_('PLG_K2_MEFG_ADD_EXTRA_FIELDS_TO_THE_GROUP').'</p>';
			$exString .='</div>';
			$exString .='</div>';
		    endif;
		endif;
		$exString .= '</div>';
		$tabIncptvMEFG_content  = '<div id="k2TabIncptvMEFG_'.$group->exfgID.'" class="k2TabIncptvMEFG simpleTabsContent k2TabsContent k2TabsContentLower" >'.$exString.'</div>';
		echo $tabIncptvMEFG.$tabIncptvMEFG_content;
	    }
	}
    }
}
