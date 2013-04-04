/**
 * @version		1.1
 * @package		Inceptive Mutliple Extra Field Groups for K2
 * @author		Inceptive Design Labs - http://www.inceptive.gr
 * @copyright	Copyright (c) 2006 - 2012 Inceptive GP. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

window.addEvent('domready', function () {
	if ($('tabIncptvEXFG') !== null)
	{
	   k2tabs_holder 		= $$('.simpleTabsNavigation');
	   tabIncptvEXFG 		= $('tabIncptvEXFG');
	   k2all_tabs	  	= $('k2Tabs');
	   k2tabIncptvEXFG  		= $('k2tabIncptvEXFG');

	   k2tabs_holder[0].grab(tabIncptvEXFG,'bottom');	
	   k2all_tabs.grab(k2tabIncptvEXFG, 'bottom');
	   tabIncptvEXFG.setStyle('visibility','visible');
	   k2tabIncptvEXFG.setStyle('visibility','visible');   
	}
	
	var incptvK2 = jQuery.noConflict();
	extrafieldgroupTabs = incptvK2('.tabIncptvMEFG')
	incptvK2.each(extrafieldgroupTabs, function() {
	    k2tabs_holder_MEFG 		= $$('.simpleTabsNavigation');
	    tabIncptvMEFG		= $(this.id);
	    k2all_tabs_MEFG	  	= $('k2Tabs');
	    k2TabIncptvMEFG  		= $('k2' + this.id.charAt(0).toUpperCase() + this.id.slice(1));

	    k2tabs_holder_MEFG[0].grab(tabIncptvMEFG,'bottom');	
	    k2all_tabs_MEFG.grab(k2TabIncptvMEFG, 'bottom');
	    tabIncptvMEFG.setStyle('visibility','visible');
	    k2TabIncptvMEFG.setStyle('visibility','visible');
	});

});

var $incptvK2 = jQuery.noConflict();
var $container = 0;

$incptvK2(document).ready(function(){
    if($incptvK2("div#k2AdminContainer").length > 0)
	$container = $incptvK2("div#k2AdminContainer");
    else if($incptvK2("div#k2FrontendContainer").length > 0)
	$container = $incptvK2("div#k2FrontendContainer");
    
    var $selectedValue = $incptvK2('#extraFieldsGroup').find(":selected").text();
    $incptvK2('#extraFieldsGroup').change(function() {
	var $newSelectedValue = $incptvK2('#extraFieldsGroup').find(":selected").text();
	var $newSelectedOption = $incptvK2('#pluginsincptvk2multipleextrafieldgroups option:contains("' + $newSelectedValue + '")');
	$newSelectedOption.attr('disabled', 'disabled');
	$newSelectedOption.removeAttr('selected');
	var $selectedOption = $incptvK2('#pluginsincptvk2multipleextrafieldgroups option:contains("' + $selectedValue + '")');
	$selectedOption.removeAttr('disabled');
	$selectedValue = $newSelectedValue;	
    });

    if($incptvK2("#adminFormK2Sidebar").is(":visible"))
	$incptvK2("div#k2Tabs").css( "width", ($container.width() - $incptvK2("#adminFormK2Sidebar").width() - 14));
    else
	$incptvK2("div#k2Tabs").css( "width", ($container.width() - 14));

    $incptvK2('#k2ToggleSidebar').click(function(event) {
	    event.preventDefault();
	    if($incptvK2("#adminFormK2Sidebar").is(":visible"))
		$incptvK2("div#k2Tabs").css( "width", ($container.width() - $incptvK2("#adminFormK2Sidebar").width() - 14));
	    else
		$incptvK2("div#k2Tabs").css( "width", ($container.width() - 14));
    });
    
    $incptvK2('#catid').change(function() {
	var selectedValue = $incptvK2(this).val();
	var tabs = $incptvK2('#k2Tabs').tabs();
	var url = K2BasePath + '../plugins/k2/incptvk2multipleextrafieldgroups/helpers/incptvk2mulitpleextrafieldgroupshelper.php?cid=' + selectedValue + '&id=' + $incptvK2('input[name=id]').val();
	$incptvK2('#mefgTabs').remove();
	$incptvK2.ajax({
		url : url,
		type : 'get',
		success : function(response) {
		    $incptvK2('.tabIncptvMEFG').remove();
		    $incptvK2('.k2TabIncptvMEFG').remove();
		    fixTabsContainer();
		    fixTabsRow();
		    $incptvK2('#k2AdminContainer').prepend(response);
		    var extrafieldgroupTabs = $incptvK2('#mefgTabs li');
		    $incptvK2.each(extrafieldgroupTabs, function() {
			var k2TabIncptvMEFG = $incptvK2('#k2' + this.id.charAt(0).toUpperCase() + this.id.slice(1));		        
			tabs.find(".ui-tabs-nav:first").append(this);
			tabs.append(k2TabIncptvMEFG);
			k2TabIncptvMEFG.addClass('simpleTabsContent');
			var selected = tabs.tabs('option', 'selected');
			tabs.tabs("destroy");
			tabs.tabs();
			$incptvK2('#k2Tabs').tabs('select', selected);
			fixTabsContainer();
			fixTabsRow();
		    });
		}
	    });
	});
});

$incptvK2(window).load(function(){clickRightTab(); fixTabsRow(); });

$incptvK2(window).resize(function(){
    fixTabsContainer();
    fixTabsRow();
});

function fixTabsContainer() {
    if($incptvK2("#adminFormK2Sidebar").is(":visible"))
	$incptvK2("div#k2Tabs").css( "width", ($container.width() - $incptvK2("#adminFormK2Sidebar").width() - 14));
    else
	$incptvK2("div#k2Tabs").css( "width", ($container.width() - 14));
}

function fixTabsRow() {
    var $totalWidth = 0;
    $incptvK2('#k2Tabs ul').first().children('[style!="display: none;"]').each(function() { $totalWidth += $incptvK2(this).width();});
    if($totalWidth > $incptvK2("div#k2Tabs").width()) { 
	$incptvK2('#k2Tabs ul').first().css({ 'overflow': 'auto', 'height': '38px', 'padding': '6px 0 1px 0', 'border': '#c0c0c0 1px solid', 'border-bottom': 'none', 'margin-bottom': '-3px'});
    }
    else {
	$incptvK2('#k2Tabs ul').first().css({ 'overflow': 'visible', 'height': '17px', 'padding': '0 10px', 'border': 'none', 'margin': '0'});
    }
}

function clickRightTab() {
    $incptvK2('#tabExtraFields').remove();
    $incptvK2('#k2Tab5').remove();
    if($incptvK2('#tabAttachments').is(":visible") 
	    && $incptvK2('#tabContent').is(":hidden")  
	    && $incptvK2('#tabImage').is(":hidden") 
	    && $incptvK2('#tabImageGallery').is(":hidden") 
	    && $incptvK2('#tabVideo').is(":hidden") 
	    && $incptvK2('#tabVideo').is(":hidden"))
	$incptvK2('#tabAttachments a').click();
    else if ($incptvK2('#tabPlugins').is(":visible")
		&& $incptvK2('#tabContent').is(":hidden")  
		&& $incptvK2('#tabImage').is(":hidden") 
		&& $incptvK2('#tabImageGallery').is(":hidden") 
		&& $incptvK2('#tabVideo').is(":hidden") 
		&& $incptvK2('#tabVideo').is(":hidden") )
	$incptvK2('#tabPlugins a').click();
}