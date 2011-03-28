// Copyright Zikula Foundation 2009 - license GNU/LGPLv2.1 (or at your option, any later version).

var adding = Array();
var allownameedit = Array();
allownameedit[0] = true;
 /**
 * Inits the ajax stuff: show ajax buttons, remove non ajax buttons etc.
 *
 *@params none;
 *@return none;
 *@author Frank Chestnut
 */
function moduleinit(frstmodule)
{
    firstmodule = frstmodule;

    // craigh specialsuperusability extension :-)
    deleteiconhtml = $('moduleeditdelete_'+firstmodule).innerHTML;
    canceliconhtml = $('moduleeditcancel_'+firstmodule).innerHTML;

    appending = false;
    Element.removeClassName('appendajax', 'z-hide');

    // set observers on all existing modules images
    $$('button.z-imagebutton').each(
    function(singlebutton)
    {
        var moduleid = singlebutton.id.split('_')[1];
        switch(singlebutton.id.split('_')[0])
        {
            case "moduleeditsave":
                Event.observe('moduleeditsave_'   + moduleid, 'click', function(){ modulemodify(moduleid)},       false);
                break;
            case "moduleeditdelete":
                Event.observe('moduleeditdelete_' + moduleid, 'click', function(){ moduledelete(moduleid)},       false);
                break;
            case "moduleeditcancel":
                Event.observe('moduleeditcancel_' + moduleid, 'click', function(){ modulemodifycancel(moduleid)}, false);
                break;
        }
    });
}

/**
 * Append a new module override at the end of the list
 *
 *@params none;
 *@return none;
 *@author Frank Schummertz
 */
function moduleappend()
{
    if(appending == false) {
        appending = true;
        new Zikula.Ajax.Request(
            "ajax.php?module=doctastic&func=createoverride",
            {
                onComplete: moduleappend_response
            });
    }
}

/**
 * Ajax response function for appending a new module: adds a new li,
 * updates fields and makes them visible. More important: renames all ids
 *
 *@params req reponse from ajax call;
 *@return none;
 *@author Frank Schummertz
 */
function moduleappend_response(req)
{
    appending = false;
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();

    // copy new module li from module_1.
    var newmodule = $('module_'+firstmodule).cloneNode(true);

    // update the ids. We use the getElementsByTagName function from
    // protoype for this. The 6 tags here cover everything in a single li
    // that has a unique id
    newmodule.id   = 'module_' + data.id;
    $A(newmodule.getElementsByTagName('a')).each(function(node)       { node.id = node.id.split('_')[0] + '_' + data.id; });
    $A(newmodule.getElementsByTagName('div')).each(function(node)     { node.id = node.id.split('_')[0] + '_' + data.id; });
    $A(newmodule.getElementsByTagName('span')).each(function(node)    { node.id = node.id.split('_')[0] + '_' + data.id; });
    $A(newmodule.getElementsByTagName('input')).each(function(node)   { node.id = node.id.split('_')[0] + '_' + data.id; node.value = ''; });
    $A(newmodule.getElementsByTagName('select')).each(function(node)  { node.id = node.id.split('_')[0] + '_' + data.id; });
    $A(newmodule.getElementsByTagName('button')).each(function(node)  { node.id = node.id.split('_')[0] + '_' + data.id; });
    $A(newmodule.getElementsByTagName('textarea')).each(function(node){ node.id = node.id.split('_')[0] + '_' + data.id; });

    // append new module to the module list
    $('modulelist').appendChild(newmodule);

    // set initial values in input, hidden and select
    //$('modname_'         + data.id).value = data.modname;
    //$('description_'     + data.id).value = data.description;
    //$('members_'         + data.id).href  = data.membersurl;

//    Zikula.setselectoption('modulenavtype_' + data.id, data.navtype_disp);
//    Zikula.setselectoption('moduleenablelang_' + data.id, data.enablelang);

    // hide cancel icon for new modules
//    Element.addClassName('moduleeditcancel_' + data.id, 'z-hide');
    // update delete icon to show cancel icon
//    Element.update('moduleeditdelete_' + data.id, canceliconhtml);

    // update some innerHTML
//    Element.update('moduleid_'         + data.id, data.id);
//    Element.update('modulemodname_'        + data.id, data.modname);
//    Element.update('modulenavtype_'       + data.id, data.navtype_disp);
//    Element.update('moduleenablelang_' + data.id, data.enablelang);
    //Element.update('members_'          + data.id, data.membersurl);

    // add events
    Event.observe('modifyajax_'       + data.id, 'click', function(){modulemodifyinit(data.id)}, false);
    Event.observe('moduleeditsave_'   + data.id, 'click', function(){modulemodify(data.id)}, false);
    Event.observe('moduleeditdelete_' + data.id, 'click', function(){moduledelete(data.id)}, false);
    Event.observe('moduleeditcancel_' + data.id, 'click', function(){modulemodifycancel(data.id)}, false);

    // remove class to make edit button visible
    Element.removeClassName('modifyajax_' + data.id, 'z-hide');
    Event.observe('modifyajax_' + data.id, 'click', function(){modulemodifyinit(data.id)}, false);

    // turn on edit mode
    allownameedit[data.id] = true;
    enableeditfields(data.id);

    // we are ready now, make it visible
    Element.removeClassName('module_' + data.id, 'z-hide');
    new Effect.Highlight('module_' + data.id, { startcolor: '#99ff66', endcolor: '#ffffff' });


    // set flag: we are adding a new module
    adding[data.id] = 1;
}

/**
 * Start edit of modules: hide/show the neccessary fields
 *
 *@params moduleid the module id;
 *@return none;
 *@author Frank Schummertz
 */
function modulemodifyinit(moduleid)
{
    allownameedit[moduleid] = false;
    if(getmodifystatus(moduleid) == 0) {
//        Zikula.setselectoption('modname_' + moduleid, $F('modname_' + moduleid));
        Zikula.setselectoption('navtype_' + moduleid, $F('navtype_' + moduleid));
        Zikula.setselectoption('enablelang_' + moduleid, $F('enablelang_' + moduleid));

        enableeditfields(moduleid);
    }
}

/**
 * Show/hide all fields needed for modifying a module
 *
 *@params moduleid the module id;
 *@return none;
 *@author Frank Schummertz
 */
function enableeditfields(moduleid)
{
    Element.addClassName('modulenavtype_'           + moduleid, 'z-hide');
    Element.addClassName('moduleenablelang_'        + moduleid, 'z-hide');
    Element.addClassName('moduleaction_'            + moduleid, 'z-hide');
    Element.addClassName('moduleexempt_'            + moduleid, 'z-hide');
    Element.removeClassName('editmodulenavtype_'    + moduleid, 'z-hide');
    Element.removeClassName('editmoduleenablelang_' + moduleid, 'z-hide');
    Element.removeClassName('editmoduleaction_'     + moduleid, 'z-hide');
    Element.removeClassName('editmoduleexempt_'     + moduleid, 'z-hide');
    if(allownameedit[moduleid] == true) {
        Element.addClassName('modulename_'          + moduleid, 'z-hide');
        Element.removeClassName('editmodulename_'   + moduleid, 'z-hide');
    }
}

/**
 * Show/hide all fields needed for not modifying a module
 *
 *@params moduleid the module id;
 *@return none;
 *@author Frank Schummertz
 */
function disableeditfields(moduleid)
{
    Element.addClassName('editmodulenavtype_'    + moduleid, 'z-hide');
    Element.addClassName('editmoduleenablelang_' + moduleid, 'z-hide');
    Element.addClassName('editmoduleaction_'     + moduleid, 'z-hide');
    Element.addClassName('editmoduleexempt_'     + moduleid, 'z-hide');
    Element.removeClassName('modulenavtype_'     + moduleid, 'z-hide');
    Element.removeClassName('moduleenablelang_'  + moduleid, 'z-hide');
    Element.removeClassName('moduleaction_'      + moduleid, 'z-hide');
    Element.removeClassName('moduleexempt_'      + moduleid, 'z-hide');
    if(allownameedit[moduleid] == true) {
        Element.addClassName('editmodulename_'   + moduleid, 'z-hide');
        Element.removeClassName('modulename_'    + moduleid, 'z-hide');
    }
}

/**
 * Cancel module modification
 *
 *@params none;
 *@return none;
 *@author Frank Schummertz
 */
function modulemodifycancel(moduleid)
{
    if(adding[moduleid] == 1) {
        moduledelete(moduleid);
        adding = adding.without(moduleid);
        return;
    }
    disableeditfields(moduleid);
    setmodifystatus(moduleid, 0)
}

/**
 * Reads a hidden field that holds the modification status
 *
 *@params moduleid the module id;
 *@return 1 if modification is in progress, otherwise 0;
 *@author Frank Schummertz
 */
function getmodifystatus(moduleid)
{
    return $F('modifystatus_' + moduleid);
}

/**
 * Set the hidden field the holds the modification status
 *
 *@params moduleid the module id;
 *@return none;
 *@author Frank Schummertz
 */
function setmodifystatus(moduleid, newvalue)
{
    $('modifystatus_' + moduleid).value = newvalue;
}

/**
 * Store updated module in the database
 *
 *@params moduleid the module id;
 *@return none;
 *@author Frank Schummertz
 */
function modulemodify(moduleid)
{
    new Effect.Highlight('module_' + moduleid, { startcolor: '#99ff66', endcolor: '#ffffff' });
    disableeditfields(moduleid);
    if(getmodifystatus(moduleid) == 0) {
        setmodifystatus(moduleid, 1);
        showinfo(moduleid, Zikula.__('Updating module override...','module_DocTastic'));
        // store via ajax
        var pars = {
            id: moduleid,
            modname: $F('modname_' + moduleid),
            navtype: $F('navtype_' + moduleid),
            enablelang: $F('enablelang_' + moduleid),
            exempt: $F('exempt_' + moduleid)
        };
        new Zikula.Ajax.Request(
            "ajax.php?module=doctastic&func=updateoverride",
            {
                parameters: pars,
                onComplete: modulemodify_response
            });


    }
}


/**
 * Ajax response function for updating the module: update fields, cleanup
 *
 *@params none;
 *@return none;
 *@author Frank Schummertz
 */
function modulemodify_response(req)
{
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        showinfo();
        return;
    }

    var data = req.getData();

    // check for modules internal error
    if(data.error == 1) {
        showinfo();
        Element.addClassName($('moduleinfo_' + data.id), 'z-hide');
        Element.removeClassName($('modulecontent_' + data.id), 'z-hide');

        /*
        // add events
        Event.observe('modifyajax_'      + data.id, 'click', function(){modulemodifyinit(data.id)}, false);
        Event.observe('moduleeditsave_'   + data.id, 'click', function(){modulemodify(data.id)}, false);
        Event.observe('moduleeditdelete_' + data.id, 'click', function(){moduledelete(data.id)}, false);
        Event.observe('moduleeditcancel_' + data.id, 'click', function(){modulemodifycancel(data.id)}, false);
        enableeditfields(data.id);
        */
        Zikula.showajaxerror(data.message);
        setmodifystatus(data.id, 0);
        modulemodifyinit(data.id);

        // refresh view/reload ???
        return;
    }

    $('enablelang_' + data.id).value = data.enablelang;

    Element.update('modulename_' + data.id, data.modname);
    Element.update('modulenavtype_' + data.id, data.navtype_disp);
    Element.update('moduleenablelang_' + data.id, data.enablelang_disp);
    Element.update('moduleexempt_' + data.id, data.exempt_disp);

    adding = adding.without(data.id);

    // show trascan icon for new moduless if necessary
    Element.removeClassName('moduleeditcancel_' + data.id, 'z-hide');
    // update delete icon to show trashcan icon
    Element.update('moduleeditdelete_' + data.id, deleteiconhtml);

    setmodifystatus(data.id, 0);
    showinfo(data.id);
}

/**
 * Delete a module
 *
 *@params moduleid the module id;
 *@return none;
 *@author Frank Schummertz
 */
function moduledelete(moduleid)
{
    if(confirm(Zikula.__('Do you really want to delete this module override?','module_DocTastic')) && getmodifystatus(moduleid) == 0) {
        new Effect.Highlight('module_' + moduleid, { startcolor: '#ff9999', endcolor: '#ffffff' });
        showinfo(moduleid, Zikula.__('Deleting module override...','module_DocTastic'));
        setmodifystatus(moduleid, 1);
        // delete via ajax
        var pars = {id: moduleid};
        new Zikula.Ajax.Request(
            "ajax.php?module=doctastic&func=deleteoverride",
            {
                parameters: pars,
                onComplete: moduledelete_response
            });
    }
}

/**
 * Ajax response function for deleting a module: simply remove the li
 *
 *@params none;
 *@return none;
 *@author Frank Schummertz
 */
function moduledelete_response(req)
{
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();

    setmodifystatus(data.id, 0);
    Element.remove('module_' + data.id);
}

/**
 * Generic Ajax response function for failures; restores previous view
 *
 *@params moduleid module id;
 *@return none;
 */
function modulefailure_response(moduleid)
{
    showinfo(moduleid);
    disableeditfields(moduleid);
    setmodifystatus(moduleid, 0);
}



/**
 * Use to temporarily show an infotext instead of the module. Must be
 * called twice:
 * #1: Show the infotext
 * #2: restore normal display
 * If both parameters are missing all infotext fields will be restored to
 * normal display
 *
 *@params moduleid the module id;
 *@params infotext the text to show;
 *@return none;
 *@author Frank Schummertz
 */
function showinfo(moduleid, infotext)
{
    if(moduleid) {
        var moduleinfo = 'moduleinfo_' + moduleid;
        var module = 'modulecontent_' + moduleid;
        if(!Element.hasClassName(moduleinfo, 'z-hide')) {
            Element.update(moduleinfo, '&nbsp;');
            Element.addClassName(moduleinfo, 'z-hide');
            Element.removeClassName(module, 'z-hide');
        } else {
            Element.update(moduleinfo, infotext);
            Element.addClassName(module, 'z-hide');
            Element.removeClassName(moduleinfo, 'z-hide');
        }
    } else {
        $A(document.getElementsByClassName('z-moduleinfo')).each(function(moduleinfo){
            Element.update(moduleinfo, '&nbsp;');
            Element.addClassName(moduleinfo, 'z-hide');
        });
        $A(document.getElementsByClassName('modulecontent')).each(function(modulecontent){
            Element.removeClassName(modulecontent, 'z-hide');
        });
    }
}