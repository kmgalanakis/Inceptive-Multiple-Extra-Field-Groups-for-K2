<?php
/**
 * @version		1.0
 * @package		Inceptive Mutliple Extra Field Groups for K2
 * @author		Inceptive Design Labs - http://www.inceptive.gr
 * @copyright	Copyright (c) 2006 - 2012 Inceptive GP. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

defined('JPATH_BASE') or die ;

class plgSystemIncptvk2multipleextrafieldgroups extends JPlugin 
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }
	
    public function onAfterRoute()
    {
	$mainframe = JFactory::getApplication();
	
	if ($mainframe->isAdmin())
	{
	    $file = JPATH_ROOT . DS . 'components' . DS . 'com_k2' . DS . 'models'. DS . 'item.php';
	    $itemListFileMD5 = md5_file($file); 
	    $savedItemListFileMD5 = $this->params->get('itemlistMD5','');
	    
	    if($savedItemListFileMD5 != $itemListFileMD5)
	    {
		$oldClass = file_get_contents(JPATH_ROOT . DS . 'components' . DS . 'com_k2' . DS . 'models'. DS . 'item.php');
		
		$getItemExtraFieldsPosition = strpos($oldClass, 'getItemExtraFields');
		$pos1 = strpos($oldClass, 'SELECT extraFieldsGroup FROM', $getItemExtraFieldsPosition);
		$pos3 = strpos($oldClass, ';', $pos1);
		$newQueryStart = 'SELECT exfgID FROM #__k2_multiple_extra_field_groups WHERE catID=".(int)$item->catid';
		$newClass = substr_replace($oldClass, $newQueryStart, $pos1, $pos3-$pos1);
		
		$toBeReplaced = '$group = $db->loadResult();';
		$replace_string = '$group = $db->loadObjectList();
				    if (count($group) < 1)
				    {
					    return NULL;
				    }

				    foreach ($group as $exfg)
				    {
					    $extraFieldGroupIDs[] = $exfg->exfgID;
				    }
				    JArrayHelper::toInteger($extraFieldGroupIDs);
				    $extraFieldGroupIDs = @implode(\',\', $extraFieldGroupIDs);';
		
		$newClass = str_replace($toBeReplaced, $replace_string, $newClass);
		
		$pos1 = strpos($newClass, 'SELECT * FROM #__k2_extra_fields', $getItemExtraFieldsPosition);
		$pos3 = strpos($newClass, ';', $pos1);
		$newQueryStart = 'SELECT ef.*, mefg.catID, mefg.exfgID, efg.name as group_name FROM #__k2_extra_fields ef 
				RIGHT JOIN #__k2_multiple_extra_field_groups as mefg on mefg.exfgID = ef.`group`
				RIGHT JOIN #__k2_extra_fields_groups as efg ON efg.id = ef.`group`
				WHERE `group` IN ({$extraFieldGroupIDs})
				AND published=1
				AND mefg.catID={$item->catid}
				AND (ef.id IN ({$condition}) OR `type` = \'header\')
				ORDER BY `group` ASC, ordering ASC"';
		$newClass = substr_replace($newClass, $newQueryStart, $pos1, $pos3-$pos1);
		
		
		$file = JPATH_PLUGINS . DS . 'k2' . DS . 'incptvk2multipleextrafieldgroups' . DS . 'models' . DS . 'item.php';
		file_put_contents($file, $newClass);
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		// Fields to update.
		$fields = array( 'params=\'{"itemlistMD5":"'.$itemListFileMD5.'"}\'' );

		// Conditions for which records should be updated.
		$conditions = array(
			'element=\'incptvk2multipleextrafieldgroups\'', 
			'folder=\'system\'');

		$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);

		$db->setQuery($query);

		try
		{
		    $result = $db->query(); // Use $db->execute() for Joomla 3.0.
		} 
		catch (Exception $e)
		{
		    // Catch the error.
		}
		
	    }
	}
	else {
	    if(JRequest::getCMD('option') == 'com_k2' && JRequest::getCMD('view')  == 'item' && JRequest::getCMD('task')  != 'edit' && JRequest::getCMD('task')  != 'save')
	    {
		JLoader::import( 'item', JPATH_PLUGINS . DS . 'k2' . DS . 'incptvk2multipleextrafieldgroups' . DS . 'models' );
	    }	    
	}
     }
}
