{ajaxheader modname='DocTastic' filename='doctastic.js'}
{include file="admin/menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' set='icons/large' src='info.gif'}</div>
    <h2>{gt text="DocTastic Module Overrides"}&nbsp;({gt text="version"}&nbsp;{$modinfo.version})</h2>

    <a id="appendajax" onclick="moduleappend();" style="margin-bottom: 1em;" class="z-floatleft z-icon-es-new z-hide" title="{gt text="Create new override"}" href="javascript:void(0);">{gt text="Create new override"}</a>

    <p class='z-informationmsg z-clearer'>{gt text="Override the default settings for Navigation Type and Language Filter for a specific module."}</p>
    {* general use authid *}
    <input type="hidden" id="modulesauthid" name="authid" value="{insert name="generateauthkey" module="DocTastic"}" />
    <div class="modulebox z-clearer">
        <ol id="modulelist" class="z-itemlist">
            <li class="z-itemheader z-clearfix">
                <span class="z-itemcell z-w25">{gt text="Module Name"}</span>
                <span class="z-itemcell z-w20">{gt text="Navigation Type"}</span>
                <span class="z-itemcell z-w20">{gt text="Language Filter Enabled"}</span>
                <span class="z-itemcell z-w15">{gt text="Exempt From List"}</span>
                <span class="z-itemcell z-w20">{gt text="Actions"}</span>
            </li>
            {* This li is only here to copy for appending a new item *}
            <li id="module_0" class="z-clearfix z-hide">
                <div id="modulecontent_0">
                    <input type="hidden" id="modifystatus_0" value="0" />
                    {* *}
                    <span id="modulename_0" class="z-itemcell z-w25">
                        This is a Fake Entry
                    </span>
                    {* Hidden until called *}
                    <span id="editmodulename_0" class="z-itemcell z-w25 z-hide">
                        <select id="modname_0" name="modname_0">
                            {html_options options=$moduleOptions selected=0}
                        </select>
                    </span>
                    {* *}
                    <span id="modulenavtype_0" class="z-itemcell z-w20">
                        Tree
                    </span>
                    {* Hidden until called *}
                    <span id="editmodulenavtype_0" class="z-itemcell z-w20 z-hide">
                        <select id="navtype_0" name="navtype_0">
                            {html_options options=$navTypeOptions selected=0}
                        </select>
                    </span>
                    {* *}
                    <span id="moduleenablelang_0" class="z-itemcell z-w20">
                        {1|yesno}
                    </span>
                    {* Hidden until called *}
                    <span id="editmoduleenablelang_0" class="z-itemcell z-w20 z-hide">
                        <select id="enablelang_0" name="enablelang_0">
                            {html_options options=$yesno selected=1}
                        </select>
                    </span>
                    {* *}
                    <span id="moduleexempt_0" class="z-itemcell z-w15">
                        {$module.exempt|yesno|safetext}
                    </span>
                    {* Hidden until called *}
                    <span id="editmoduleexempt_0" class="z-itemcell z-w15 z-hide">
                        <select id="exempt_0" name="exempt_0">
                            {html_options options=$yesno selected=0}
                        </select>
                    </span>
                    {* *}
                    <span id="moduleaction_0" class="z-itemcell z-w20">
                        <button class="z-imagebutton z-hide" id="modifyajax_0"   title="{gt text="Edit"}">{img src=xedit.gif modname=core set=icons/extrasmall __title="Edit" __alt="Edit"}</button>
                        <a id="modify_0"  href="{modurl modname='DocTastic' type='admin' func='modifyoverrides'}" title="{gt text="Edit"}">{img src=xedit.gif modname=core set=icons/extrasmall __title="Edit" __alt="Edit"}</a>
                        <a id="delete_0"     href="{modurl modname='DocTastic' type='admin' func='modifyoverrides'}" title="{gt text="Delete"}">{img src=14_layer_deletelayer.gif modname=core set=icons/extrasmall __title="Delete" __alt="Delete"}</a>
                        <script type="text/javascript">
                            Element.addClassName('insert_0', 'z-hide');
                            Element.addClassName('modify_0', 'z-hide');
                            Element.addClassName('delete_0', 'z-hide');
                            Element.removeClassName('modifyajax_0', 'z-hide');
                            Event.observe('modifyajax_0', 'click', function(){modulemodifyinit(0)}, false);
                        </script>
                    </span>
                    <span id="editmoduleaction_0" class="z-itemcell z-w20 z-hide">
                        <button class="z-imagebutton" id="moduleeditsave_0"   title="{gt text="Save"}">{img src=button_ok.gif modname=core set=icons/extrasmall __alt="Save" __title="Save"}</button>
                        <button class="z-imagebutton" id="moduleeditdelete_0" title="{gt text="Delete"}">{img src=14_layer_deletelayer.gif modname=core set=icons/extrasmall __alt="Delete" __title="Delete"}</button>
                        <button class="z-imagebutton" id="moduleeditcancel_0" title="{gt text="Cancel"}">{img src=button_cancel.gif modname=core set=icons/extrasmall __alt="Cancel" __title="Cancel"}</button>
                    </span>
                </div>
                <div id="moduleinfo_0" class="z-hide z-moduleinfo">
                    &nbsp;
                </div>
            </li>
        {foreach item="module" from=$modules}
            <li id="module_{$module.id}" class="{cycle values='z-odd,z-even'} z-clearfix">
                <div id="modulecontent_{$module.id}">
                    <input type="hidden" id="modifystatus_{$module.id}" value="0" />
                    {* *}
                    <span id="modulename_{$module.id}" class="z-itemcell z-w25">
                        {$module.modname|safetext}
                    </span>
                    {* Hidden until called *}
                    <span id="editmodulename_{$module.id}" class="z-itemcell z-w25 z-hide">
                        <select id="modname_{$module.id}" name="modname_{$module.id}">
                            {html_options options=$moduleOptions selected=$module.modname}
                        </select>
                    </span>
                    {* *}
                    <span id="modulenavtype_{$module.id}" class="z-itemcell z-w20">
                        {$module.navtype_disp|safetext}
                    </span>
                    {* Hidden until called *}
                    <span id="editmodulenavtype_{$module.id}" class="z-itemcell z-w20 z-hide">
                        <select id="navtype_{$module.id}" name="navtype_{$module.id}">
                            {html_options options=$navTypeOptions selected=$module.navtype}
                        </select>
                    </span>
                    {* *}
                    <span id="moduleenablelang_{$module.id}" class="z-itemcell z-w20">
                        {$module.enablelang|yesno|safetext}
                    </span>
                    {* Hidden until called *}
                    <span id="editmoduleenablelang_{$module.id}" class="z-itemcell z-w20 z-hide">
                        <select id="enablelang_{$module.id}" name="enablelang_{$module.id}">
                            {html_options options=$yesno selected=$module.enablelang}
                        </select>
                    </span>
                    {* *}
                    <span id="moduleexempt_{$module.id}" class="z-itemcell z-w15">
                        {$module.exempt|yesno|safetext}
                    </span>
                    {* Hidden until called *}
                    <span id="editmoduleexempt_{$module.id}" class="z-itemcell z-w15 z-hide">
                        <select id="exempt_{$module.id}" name="exempt_{$module.id}">
                            {html_options options=$yesno selected=$module.exempt}
                        </select>
                    </span>
                    {* *}
                    <span id="moduleaction_{$module.id}" class="z-itemcell z-w20">
                        <button class="z-imagebutton z-hide" id="modifyajax_{$module.id}"   title="{gt text="Edit"}">{img src=xedit.gif modname=core set=icons/extrasmall __title="Edit" __alt="Edit"}</button>
                        <a id="modify_{$module.id}"  href="{$module.editurl|safetext}" title="{gt text="Edit"}">{img src=xedit.gif modname=core set=icons/extrasmall __title="Edit" __alt="Edit"}</a>
                        <a id="delete_{$module.id}"     href="{$module.deleteurl|safetext}" title="{gt text="Delete"}">{img src=14_layer_deletelayer.gif modname=core set=icons/extrasmall __title="Delete" __alt="Delete"}</a>
                        <script type="text/javascript">
                            Element.addClassName('insert_{{$module.id}}', 'z-hide');
                            Element.addClassName('modify_{{$module.id}}', 'z-hide');
                            Element.addClassName('delete_{{$module.id}}', 'z-hide');
                            Element.removeClassName('modifyajax_{{$module.id}}', 'z-hide');
                            Event.observe('modifyajax_{{$module.id}}', 'click', function(){modulemodifyinit({{$module.id}})}, false);
                        </script>
                    </span>
                    <span id="editmoduleaction_{$module.id}" class="z-itemcell z-w20 z-hide">
                        <button class="z-imagebutton" id="moduleeditsave_{$module.id}"   title="{gt text="Save"}">{img src=button_ok.gif modname=core set=icons/extrasmall __alt="Save" __title="Save"}</button>
                        <button class="z-imagebutton" id="moduleeditdelete_{$module.id}" title="{gt text="Delete"}">{img src=14_layer_deletelayer.gif modname=core set=icons/extrasmall __alt="Delete" __title="Delete"}</button>
                        <button class="z-imagebutton" id="moduleeditcancel_{$module.id}" title="{gt text="Cancel"}">{img src=button_cancel.gif modname=core set=icons/extrasmall __alt="Cancel" __title="Cancel"}</button>
                    </span>
                </div>
                <div id="moduleinfo_{$module.id}" class="z-hide z-moduleinfo">
                    &nbsp;
                </div>
            </li>
        {/foreach}
        </ol>
    </div>
</div><!-- /z-admincontainer -->

<script type="text/javascript">
    Event.observe(window, 'load', function(){moduleinit(0);}, false);

    // some defines
    var updatingmodule = '...{{gt text="Updating module override"}}...';
    var deletingmodule = '...{{gt text="Deleting module override"}}...';
    var confirmDeleteModule = '{{gt text="Do you really want to delete this module override?"}}';
</script>