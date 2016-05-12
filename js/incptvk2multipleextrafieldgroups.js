/**
 * @package		Inceptive Multiple Extra Field Groups for K2
 * @author		Extend by Inceptive Design Labs - http://extend.inceptive.gr
 * @copyright	Copyright (c) 2006 - 2012 Inceptive GP. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

window.addEvent('domready', function () {
    if ($('tabIncptvEXFG') !== null) {
        tabIncptvEXFG = $('tabIncptvEXFG');
        k2all_tabs = $('k2Tabs');
        k2tabIncptvEXFG = $('k2tabIncptvEXFG');

        k2tabs_holder = $$('.simpleTabsNavigation');
        if (typeof (k2tabs_holder[0]) === 'undefined') {
            k2tabs_holder = $$('.k2TabsNavigation');
            k2tabIncptvEXFG.removeChild(k2tabIncptvEXFG.getElementsByClassName('admintable')[0]);
            var node = document.getElementById('tabIncptvEXFGold');
            node.parentNode.removeChild(node);
        }
        else {
            k2tabIncptvEXFG.removeChild(k2tabIncptvEXFG.getElementsByClassName('itemAdditionalField')[0]);
            var node = document.getElementById('tabIncptvEXFGge27');
            node.parentNode.removeChild(node);
        }

        k2tabs_holder[0].grab(tabIncptvEXFG, 'bottom');
        k2all_tabs.grab(k2tabIncptvEXFG, 'bottom');
        tabIncptvEXFG.setStyle('visibility', 'visible');
        k2tabIncptvEXFG.setStyle('visibility', 'visible');
    }

    var incptvK2 = jQuery.noConflict();
    extrafieldgroupTabs = incptvK2('.tabIncptvMEFG')
    incptvK2.each(extrafieldgroupTabs, function () {
        k2tabs_holder_MEFG = $$('.simpleTabsNavigation');
        tabIncptvMEFG = $(this.id);
        k2all_tabs_MEFG = $('k2Tabs');
        k2TabIncptvMEFG = $('k2' + this.id.charAt(0).toUpperCase() + this.id.slice(1));

        if (typeof (k2tabs_holder_MEFG[0]) === 'undefined') {
            k2tabs_holder_MEFG = $$('.k2TabsNavigation');
            var admintableNode = k2TabIncptvMEFG.getElementsByClassName('admintable')[0];
            admintableNode.parentNode.removeChild(admintableNode);
            var node = document.getElementById('tabIncptvMEXFGold');
            node.parentNode.removeChild(node);
        }
        else {
            var itemAdditionalFieldNode = k2TabIncptvMEFG.getElementsByClassName('itemAdditionalField')[0];
            itemAdditionalFieldNode.parentNode.removeChild(itemAdditionalFieldNode);
            var node = document.getElementById('tabIncptvMEXFGge27');
            node.parentNode.removeChild(node);
        }
        
        k2tabs_holder_MEFG[0].grab(tabIncptvMEFG, 'bottom');
        k2all_tabs_MEFG.grab(k2TabIncptvMEFG, 'bottom');
        tabIncptvMEFG.setStyle('visibility', 'visible');
        k2TabIncptvMEFG.setStyle('visibility', 'visible');
    });

});

var $incptvK2 = jQuery.noConflict();
var $container = 0;

$incptvK2(document).ready(function () {
    $incptvK2('#tabExtraFields').remove();
    $incptvK2('#k2TabExtraFields').remove();
    $incptvK2('#k2Tab5').remove();
    if ($incptvK2("div#k2AdminContainer").length > 0)
        $container = $incptvK2("div#k2AdminContainer");
    else if ($incptvK2("div#k2FrontendContainer").length > 0)
        $container = $incptvK2("div#k2FrontendContainer");

    var $selectedValue = $incptvK2('#extraFieldsGroup').find(":selected").text();
    $incptvK2('#extraFieldsGroup').change(function () {
        var $newSelectedValue = $incptvK2('#extraFieldsGroup').find(":selected").text();
        var $newSelectedOption = $incptvK2('#pluginsincptvk2multipleextrafieldgroups option').filter(function () {
            if(this.innerHTML == $newSelectedValue)
                return this;
        });
        $newSelectedOption.attr('disabled', 'disabled');
        $newSelectedOption.removeAttr('selected');
        var $selectedOption = $incptvK2('#pluginsincptvk2multipleextrafieldgroups option').filter(function () {
            if(this.innerHTML == $selectedValue)
                return this;
        });
        $selectedOption.removeAttr('disabled');
        $selectedValue = $newSelectedValue;
        
        $incptvK2('#pluginsincptvk2multipleextrafieldgroups').chosen('destroy');
        $incptvK2('#pluginsincptvk2multipleextrafieldgroups').chosen();
    });

    if ($incptvK2("#adminFormK2Sidebar").is(":visible"))
        $incptvK2("div#k2Tabs").css("width", ($container.width() - $incptvK2("#adminFormK2Sidebar").width() - 14));
    else
        $incptvK2("div#k2Tabs").css("width", ($container.width() - 14));

    $incptvK2('#k2ToggleSidebar').click(function (event) {
        event.preventDefault();
        if ($incptvK2("#adminFormK2Sidebar").is(":visible"))
            $incptvK2("div#k2Tabs").css("width", ($container.width() - $incptvK2("#adminFormK2Sidebar").width() - 14));
        else
            $incptvK2("div#k2Tabs").css("width", ($container.width() - 14));
    });

    $incptvK2('#catid').change(function () {
        var selectedValue = $incptvK2(this).val();
        var tabs = $incptvK2('#k2Tabs').tabs();
        var url = K2BasePath + '../plugins/k2/incptvk2multipleextrafieldgroups/helpers/incptvk2mulitpleextrafieldgroupshelper.php?cid=' + selectedValue + '&id=' + $incptvK2('input[name=id]').val();
        $incptvK2('#mefgTabs').remove();
        $incptvK2.ajax({
            url: url,
            type: 'get',
            success: function (response) {
                $incptvK2('.tabIncptvMEFG').remove();
                $incptvK2('.k2TabIncptvMEFG').remove();
                fixTabsContainer();
                fixTabsRow();
                $container.prepend(response);
                var extrafieldgroupTabs = $incptvK2('#mefgTabs li');
                $incptvK2.each(extrafieldgroupTabs, function () {
                    var k2TabIncptvMEFG = $incptvK2('#k2' + this.id.charAt(0).toUpperCase() + this.id.slice(1));
                    tabs.find(".ui-tabs-nav:first").append(this);
                    tabs.append(k2TabIncptvMEFG);
                    k2TabIncptvMEFG.addClass('simpleTabsContent');
                    var selected = tabs.tabs('option', 'selected');
                    tabs.tabs("destroy");
                    tabs.tabs();
                    if ($incptvK2.fn.chosen) {
                        $incptvK2('select').chosen({ disable_search_threshold: 10, allow_single_deselect: true });
                    }
                    $incptvK2('#k2Tabs').tabs('select', selected);
                    fixTabsContainer();
                    fixTabsRow();
                });

                $incptvK2('.extraFieldsContainerMEFG').on('click', '.k2ExtraFieldImageButton', function (event) {
                    event.preventDefault();
                    //var href = $incptvK2(this).attr('href');
                    var href = 'index.php?option=com_k2&view=media&type=image&tmpl=component&fieldID=K2ExtraField_' + $incptvK2(this).attr('href').substring($incptvK2(this).attr('href').indexOf('K2ExtraField_') + 13);
                    $incptvK2(this).attr('href', href);
                    SqueezeBox.initialize();
                    SqueezeBox.fromElement(this, {
                        handler: 'iframe',
                        url: K2BasePath + href,
                        size: {
                            x: 800,
                            y: 434
                        }
                    });
                });

                $incptvK2('img.calendar').each(function () {
                    inputFieldID = $incptvK2(this).prev().attr('id');
                    imgFieldID = $incptvK2(this).attr('id');
                    calendarSource = $incptvK2(this).attr('src').substring(0, $incptvK2(this).attr('src').indexOf('plugins/'));
                    $incptvK2(this).attr('src', calendarSource + 'administrator/templates/bluestork/images/system/calendar.png');
                    Calendar.setup({
                        inputField: inputFieldID,
                        ifFormat: "%Y-%m-%d",
                        button: imgFieldID,
                        align: "Tl",
                        singleClick: true
                    });
                });

                $incptvK2('.k2ExtraFieldEditor').each(function () {
                    var id = $incptvK2(this).attr('id');
                    if (typeof tinymce != 'undefined') {
                        if (tinyMCE.get(id)) {
                            tinymce.EditorManager.remove(tinyMCE.get(id));
                        }
                        if (tinymce.majorVersion == 4) {
                            tinymce.init({ selector: '#' + id });
                            tinymce.editors[id].show();
                        } else {
                            tinyMCE.execCommand('mceAddControl', false, id);
                        }

                    } else {
                        new nicEditor({
                            fullPanel: true,
                            maxHeight: 180,
                            iconsPath: K2SitePath + 'media/k2/assets/images/system/nicEditorIcons.gif'
                        }).panelInstance($incptvK2(this).attr('id'));
                    }
                });
            }
        });
    });

    $incptvK2('#extraFieldsContainer').on('click', '.k2ExtraFieldImageButton', function (event) {
        event.preventDefault();
        var href = $incptvK2(this).attr('href');
        SqueezeBox.initialize();
        SqueezeBox.fromElement(this, {
            handler: 'iframe',
            url: K2BasePath + href,
            size: {
                x: 800,
                y: 434
            }
        });
    });

    $incptvK2('img.calendar').each(function () {
        inputFieldID = $incptvK2(this).prev().attr('id');
        imgFieldID = $incptvK2(this).attr('id');
        Calendar.setup({
            inputField: inputFieldID,
            ifFormat: "%Y-%m-%d",
            button: imgFieldID,
            align: "Tl",
            singleClick: true
        });
    });
});

$incptvK2(window).load(function () { clickRightTab(); fixTabsRow(); moveToTop(); });

$incptvK2(window).resize(function () {
    fixTabsContainer();
    fixTabsRow();
});

function moveToTop() {
    if (typeof (K2_INSTALLED_VERSION) === 'undefined') {
        $incptvK2('html, body').scrollTop(0);
    }
}

function fixTabsContainer() {
    if (typeof (K2_INSTALLED_VERSION) === 'undefined') {
        if ($incptvK2("#adminFormK2Sidebar").is(":visible"))
            $incptvK2("div#k2Tabs").css("width", ($container.width() - $incptvK2("#adminFormK2Sidebar").width() - 14));
        else
            $incptvK2("div#k2Tabs").css("width", ($container.width() - 14));
    }
}

function fixTabsRow() {
    if (typeof (K2_INSTALLED_VERSION) === 'undefined') {
        var $totalWidth = 0;
        $incptvK2('#k2Tabs ul').first().children('[style!="display: none;"]').each(function () { $totalWidth += $incptvK2(this).width(); });
        if ($totalWidth > $incptvK2("div#k2Tabs").width()) {
            $incptvK2('#k2Tabs ul').first().css({ 'overflow': 'auto', 'height': '38px', 'padding': '6px 0 1px 0', 'border': '#c0c0c0 1px solid', 'border-bottom': 'none', 'margin-bottom': '-3px' });
        }
        else {
            $incptvK2('#k2Tabs ul').first().css({ 'overflow': 'visible', 'height': '17px', 'padding': '0 10px', 'border': 'none', 'margin': '0' });
        }
    }

}

function clickRightTab() {
    if ($incptvK2('#tabAttachments').is(":visible")
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
        && $incptvK2('#tabVideo').is(":hidden"))
        $incptvK2('#tabPlugins a').click();
}