<?php
/**
 * @package		Inceptive Multiple Extra Field Groups for K2
 * @author		Extend by Inceptive Design Labs - http://extend.inceptive.gr
 * @copyright	Copyright (c) 2006 - 2012 Inceptive GP. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

defined('JPATH_BASE') or die ;

jimport('joomla.application.component.helper');

// Load the base adapter.
require_once JPATH_ADMINISTRATOR.'/components/com_finder/helpers/indexer/adapter.php';

require_once(JPATH_PLUGINS.DS.'k2'.DS.'incptvk2multipleextrafieldgroups'.DS.'helpers'.DS.'incptvk2multipleextrafieldgroups.php');

class plgFinderIncptvk2multipleextrafieldgroups extends FinderIndexerAdapter
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onFinderAfterSave($context, $row, $isNew)
    {
        // Check for access changes in the category
        if ($context == 'com_k2.category')
        {
            global $mainframe;

	    $db 	= &JFactory::getDBO();

	    /* Checking if some Extended fields to process */
	    $request_params		= JRequest::getVar('plugins', array());
	    $fields = (!empty($request_params['incptvk2multipleextrafieldgroups'])) ? $request_params['incptvk2multipleextrafieldgroups']: array();
	    array_push($fields, $row->extraFieldsGroup) ;
	    $fields = array_diff(array_unique($fields), array('0'));

	    /* Checking if the no categories were changed */
	    $selectedExtraFieldGroups = array();
	    $rows 			= &JTable::getInstance('IncptvK2MultipleExtraFieldGroups', 'Table');
	    $retrievedExtraFieldGroups  = $rows->getSomeObjectsList('SELECT * FROM #__k2_multiple_extra_field_groups mefg WHERE mefg.catID = '.$row->id);
	    foreach ($retrievedExtraFieldGroups as $retrievedExtraFieldGroup) { array_push($selectedExtraFieldGroups, $retrievedExtraFieldGroup->exfgID); }

	    /* Evaluating the difference of the previous and current categories arrays */
	    $dif1 = array_diff($fields,$selectedExtraFieldGroups);
	    $dif2 = array_diff($selectedExtraFieldGroups,$fields);
	    if(!empty($dif1) OR !empty($dif2))
	    {
		    //new JTable instance to save the new added values
		    $row_action = &JTable::getInstance('IncptvK2MultipleExtraFieldGroups', 'Table');

		    $row_action->delete($row->id);
		    $row_action->change_key("id",$db);	
		    foreach($fields as $exfgID){
		    $from = array(  'id' => 0,
				    'catID'  => $row->id,
				    'exfgID'  => $exfgID
				      );	   
		    $row_action->bind($from);

		    $row_action->store();
		    }

		    if (!$row_action->check()) {
			    $mainframe->redirect('index.php?option=com_k2&view=item&cid='.$item->id, $row_action->getError(), 'error');
		    }		

		    if (!$row_action->store()) {
			    $mainframe->redirect('index.php?option=com_k2&view=items', $row_action->getError(), 'error');
		    }
	    }

	    return '';
	    }
        
    }
	
	public function onFinderAfterDelete($context, $table)
    {
        $db = JFactory::getDbo();
 
	$query = $db->getQuery(true);

	$conditions = array('catID='.$table->id);

	$query->delete($db->quoteName('#__k2_multiple_extra_field_groups'));
	$query->where($conditions);

	$db->setQuery($query);

	try {
	   $result = $db->query(); // $db->execute(); for Joomla 3.0.
	} catch (Exception $e) {
	   // catch the error.
	}
    }

    protected function index(\FinderIndexerResult $item) {
	
    }

    protected function setup() {
	
    }
}
