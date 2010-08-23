// Copyright Zikula Foundation 2009 - license GNU/LGPLv2.1 (or at your option, any later version).

var adding = Array();
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
        var pars = "module=doctastic&func=createoverride&authid=" + $F('modulesauthid');
        var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'post',
                parameters: pars,
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
    if(req.status != 200 ) {
        Zikula.showajaxerror(req.responseText);
        return;
    }
    var json = Zikula.dejsonize(req.responseText);

    Zikula.updateauthids(json.authid);
    $('modulesauthid').value = json.authid;

    // copy new module li from module_1.
    var newmodule = $('module_'+firstmodule).cloneNode(true);

    // update the ids. We use the getElementsByTagName function from
    // protoype for this. The 6 tags here cover everything in a single li
    // that has a unique id
    newmodule.id   = 'module_' + json.id;
    $A(newmodule.getElementsByTagName('a')).each(function(node)       { node.id = node.id.split('_')[0] + '_' + json.id; });
    $A(newmodule.getElementsByTagName('div')).each(function(node)     { node.id = node.id.split('_')[0] + '_' + json.id; });
    $A(newmodule.getElementsByTagName('span')).each(function(node)    { node.id = node.id.split('_')[0] + '_' + json.id; });
    $A(newmodule.getElementsByTagName('input')).each(function(node)   { node.id = node.id.split('_')[0] + '_' + json.id; node.value = ''; });
    $A(newmodule.getElementsByTagName('select')).each(function(node)  { node.id = node.id.split('_')[0] + '_' + json.id; });
    $A(newmodule.getElementsByTagName('button')).each(function(node)  { node.id = node.id.split('_')[0] + '_' + json.id; });
    $A(newmodule.getElementsByTagName('textarea')).each(function(node){ node.id = node.id.split('_')[0] + '_' + json.id; });

    // append new module to the module list
    $('modulelist').appendChild(newmodule);

    // set initial values in input, hidden and select
    //$('modname_'         + json.id).value = json.modname;
    //$('description_'     + json.id).value = json.description;
    //$('members_'         + json.id).href  = json.membersurl;

    Zikula.setselectoption('modulenavtype_' + json.id, json.navtype_disp);
    Zikula.setselectoption('moduleenable_lang_' + json.id, json.enable_lang);

    // hide cancel icon for new modules
//    Element.addClassName('moduleeditcancel_' + json.id, 'z-hide');
    // update delete icon to show cancel icon
//    Element.update('moduleeditdelete_' + json.id, canceliconhtml);

    // update some innerHTML
    Element.update('moduleid_'         + json.id, json.id);
    Element.update('modulemodname_'        + json.id, json.modname);
    Element.update('modulenavtype_'       + json.id, json.navtype_disp);
    Element.update('moduleenable_lang_' + json.id, json.enable_lang);
    //Element.update('members_'          + json.id, json.membersurl);

    // add events
    Event.observe('modifyajax_'      + json.id, 'click', function(){modulemodifyinit(json.id)}, false);
    Event.observe('moduleeditsave_'   + json.id, 'click', function(){modulemodify(json.id)}, false);
    Event.observe('moduleeditdelete_' + json.id, 'click', function(){moduledelete(json.id)}, false);
    Event.observe('moduleeditcancel_' + json.id, 'click', function(){modulemodifycancel(json.id)}, false);

    // remove class to make edit button visible
    Element.removeClassName('modifyajax_' + json.id, 'z-hide');
    Event.observe('modifyajax_' + json.id, 'click', function(){modulemodifyinit(json.id)}, false);

    // turn on edit mode
    enableeditfields(json.id);

    // we are ready now, make it visible
    Element.removeClassName('module_' + json.id, 'z-hide');
    new Effect.Highlight('module_' + json.id, { startcolor: '#ffff99', endcolor: '#ffffff' });


    // set flag: we are adding a new module
    adding[json.id] = 1;
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
    if(getmodifystatus(moduleid) == 0) {
        // these look wrong craigh
        Zikula.setselectoption('navtype_' + moduleid, $F('navtype_' + moduleid));
        Zikula.setselectoption('enable_lang_' + moduleid, $F('enable_lang_' + moduleid));

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
    Element.addClassName('modulenavtype_'            + moduleid, 'z-hide');
    Element.addClassName('moduleenable_lang_'        + moduleid, 'z-hide');
    Element.addClassName('moduleaction_'             + moduleid, 'z-hide');
    Element.removeClassName('editmodulenavtype_'     + moduleid, 'z-hide');
    Element.removeClassName('editmoduleenable_lang_' + moduleid, 'z-hide');
    Element.removeClassName('editmoduleaction_'      + moduleid, 'z-hide');
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
    Element.addClassName('editmodulenavtype_'       + moduleid, 'z-hide');
    Element.addClassName('editmoduleenable_lang_' + moduleid, 'z-hide');
    Element.addClassName('editmoduleaction_'      + moduleid, 'z-hide');
    Element.removeClassName('modulenavtype_'        + moduleid, 'z-hide');
    Element.removeClassName('moduleenable_lang_'  + moduleid, 'z-hide');
    Element.removeClassName('moduleaction_'       + moduleid, 'z-hide');
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
    disableeditfields(moduleid);
    if(getmodifystatus(moduleid) == 0) {
        setmodifystatus(moduleid, 1);
        showinfo(moduleid, updatingmodule);
        // store via ajax
        var pars = "module=doctastic&func=updateoverride&authid="
                   + $F('modulesauthid')
                   + "&id="          + moduleid
                   + "&navtype="     + $F('navtype_' + moduleid)
                   + "&enable_lang=" + $F('enable_lang_' + moduleid);
        var myAjax = new Ajax.Request("ajax.php", { method: 'post',
                                                    parameters: pars,
                                                    onComplete: modulemodify_response,
                                                    onFailure: function(){modulefailure_response(moduleid);}
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
    if(req.status != 200 ) {
        showinfo();
        Zikula.showajaxerror(req.responseText);
        return;
    }

    var json = Zikula.dejsonize(req.responseText);
    Zikula.updateauthids(json.authid);
    $('modulesauthid').value = json.authid;

    // check for modules internal error
    if(json.error == 1) {
        showinfo();
        Element.addClassName($('moduleinfo_' + json.id), 'z-hide');
        Element.removeClassName($('modulecontent_' + json.id), 'z-hide');

        /*
        // add events
        Event.observe('modifyajax_'      + json.id, 'click', function(){modulemodifyinit(json.id)}, false);
        Event.observe('moduleeditsave_'   + json.id, 'click', function(){modulemodify(json.id)}, false);
        Event.observe('moduleeditdelete_' + json.id, 'click', function(){moduledelete(json.id)}, false);
        Event.observe('moduleeditcancel_' + json.id, 'click', function(){modulemodifycancel(json.id)}, false);
        enableeditfields(json.id);
        */
        Zikula.showajaxerror(json.message);
        setmodifystatus(json.id, 0);
        modulemodifyinit(json.id);
        return;
    }

    $('enable_lang_' + json.id).value = json.enable_lang;

    Element.update('modulenavtype_' + json.id, json.navtype_disp);

    Element.update('moduleenable_lang_' + json.id, json.enable_lang_disp);

    adding = adding.without(json.id);

    // show trascan icon for new moduless if necessary
    Element.removeClassName('moduleeditcancel_' + json.id, 'z-hide');
    // update delete icon to show trashcan icon
    Element.update('moduleeditdelete_' + json.id, deleteiconhtml);

    setmodifystatus(json.id, 0);
    showinfo(json.id);
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
    if(confirm(confirmDeleteModule) && getmodifystatus(moduleid) == 0) {
        showinfo(moduleid, deletingmodule);
        setmodifystatus(moduleid, 1);
        // delete via ajax
        var pars = "module=doctastic&func=deleteoverride&authid="
                   + $F('modulesauthid')
                   + '&id=' + moduleid;
        var myAjax = new Ajax.Request(
            "ajax.php",
            {
                method: 'get',
                parameters: pars,
                onComplete: moduledelete_response,
                onFailure: function(){modulefailure_response(moduleid);}
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
    if(req.status != 200 ) {
        Zikula.showajaxerror(req.responseText);
        return;
    }
    var json = Zikula.dejsonize(req.responseText);

    Zikula.updateauthids(json.authid);
    $('modulesauthid').value = json.authid;

    setmodifystatus(json.id, 0);
    Element.remove('module_' + json.id);
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