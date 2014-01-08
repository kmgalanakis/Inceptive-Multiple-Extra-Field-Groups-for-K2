--
-- version 1.0.0
-- package Inceptive Multiple Extra Field Groups for K2
-- author Inceptive Design Labs <info@inceptive.gr>
-- link http://www.inceptive.gr
-- copyright Copyright (c) 2012 inceptive.gr
-- license GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
--
 
--
-- Table structure for table `#__k2_multiple_categories`
--

CREATE TABLE IF NOT EXISTS `#__k2_multiple_extra_field_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catID` int(11) NOT NULL DEFAULT '0',
  `exfgID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `#__k2_multiple_extra_field_groups`  (`#__k2_multiple_extra_field_groups`.catID, `#__k2_multiple_extra_field_groups`.exfgID)
SELECT `#__k2_categories`.id, `#__k2_categories`.extraFieldsGroup
FROM `#__k2_categories`
WHERE `#__k2_categories`.extraFieldsGroup <> 0;