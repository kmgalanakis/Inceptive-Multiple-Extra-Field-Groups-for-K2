<?php
/**
 * @package		Inceptive Multiple Extra Field Groups for K2
 * @author		Extend by Inceptive Design Labs - http://extend.inceptive.gr
 * @copyright	Copyright (c) 2006 - 2012 Inceptive GP. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

    define( '_JEXEC', 1 );	
    // no direct access
    defined( '_JEXEC' ) or die( 'Restricted access' ); 
    define( 'DS', DIRECTORY_SEPARATOR );
    	
    $jpath_base = realpath(dirname(__FILE__).'/'.'../../../..' );
    define( 'JPATH_COMPONENT_ADMINISTRATOR', $jpath_base.DS.'administrator'.DS.'components'.DS.'com_k2' );    
	if(file_exists($jpath_base .'/'.'includes')):
		define( 'JPATH_BASE', $jpath_base);
		require_once ( $jpath_base .'/'.'includes'.'/'.'defines.php' );
		require_once ( $jpath_base .'/'.'includes'.'/'.'framework.php' );
	else:
		$jpath_base = realpath(dirname(__FILE__).'/'.'../../../../../../..' );
		define( 'JPATH_BASE', $jpath_base);
		require_once ( $jpath_base .'/'.'includes'.'/'.'defines.php' );
		require_once ( $jpath_base .'/'.'includes'.'/'.'framework.php' );
	endif;

    $mainframe = JFactory::getApplication('site');
    
    $db 			= JFactory::getDBO();
    $document 		= JFactory::getDocument();
    $path 		= str_replace("administrator/", "",JURI::base());
    $plugin_folder 	= basename(dirname(__FILE__));
    
    $catid = JRequest::getInt('cid');
    $itemid = JRequest::getInt('id');

    $document->addScript($path.'plugins/k2/'.$plugin_folder.'/js/incptvk2multipleextrafieldgroups.js');
    $document->addStyleSheet($path.'plugins/k2/'.$plugin_folder.'/css/style.css');	    

    //Loading the appropriate language files
    $lang = JFactory::getLanguage();
    $languagePath = JPATH_PLUGINS.DS.'k2'.DS.'incptvk2multipleextrafieldgroups';
    $lang->load("plg_k2_incptvk2multipleextrafieldgroups", $languagePath, null, false);   

    $query = "SELECT mefg.*, efg.name FROM #__k2_multiple_extra_field_groups mefg
		RIGHT JOIN #__k2_extra_fields_groups AS efg on efg.id = mefg.exfgID
		WHERE catID = ".(int)$catid.' ORDER BY efg.id ASC';
    $db->setQuery($query);
    $groups = $db->loadObjectList();
    
    JLoader::register('K2Model', JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'model.php');
    JLoader::register('K2Table', JPATH_COMPONENT_ADMINISTRATOR.DS.'tables'.DS.'table.php');
    @require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'extrafield.php');
	$language = JFactory::getLanguage();
	$language->load('com_k2', JPATH_ADMINISTRATOR);
    $extraFieldModel = K2Model::getInstance('ExtraField', 'K2Model');
    echo '<div id="mefgTabs">';
    foreach ($groups as $group) {
	$extraFields = $extraFieldModel->getExtraFieldsByGroup($group->exfgID);
	
	for ($i = 0; $i < sizeof($extraFields); $i++)
	{
	    $extraFields[$i]->element = @$extraFieldModel->renderExtraField($extraFields[$i], $itemid);
	}
	$tabIncptvMEFG	=   '<li id="tabIncptvMEFG_'.$group->exfgID.'" class="tabIncptvMEFG">
				<a href="#k2TabIncptvMEFG_'.$group->exfgID.'">'.$group->name.'</a>
			    </li>';
	$exString = '<div id="extraFieldsContainer_'.$group->exfgID.'" class="extraFieldsContainerMEFG">';
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
	else:
	    $exString .='No extra fields here!';
	endif;
	$exString .= '</div>';
	$tabIncptvMEFG_content  = '<div id="k2TabIncptvMEFG_'.$group->exfgID.'" class="k2TabIncptvMEFG simpleTabsContent" >'.$exString.'</div>';
	echo $tabIncptvMEFG.$tabIncptvMEFG_content;
    }
    echo '</div>';
?>
