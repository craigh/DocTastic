{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text="Settings"}</h3>
</div>

<form class="z-form" action="{modurl modname="DocTastic" type="admin" func="updateconfig"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
	<input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    {if !empty($warnings)}<div class='z-warningmsg'>{$warnings}</div>{/if}
    <fieldset>
        <legend>{gt text='General settings'}</legend>
        <div class="z-formrow">
			<label for="navType">{gt text='Navigation type'}</label>
            {$navTypeSelector}
        </div>
	<div class="z-formrow">
		<label for="addCore">{gt text="Add the Core's /docs directory to the Navigation Tree"}</label>
		<input type="checkbox" value="1" id="addCore" name="addCore"{if $modvars.DocTastic.addCore eq true} checked="checked"{/if}/>
	</div>
	<div class="z-formrow">
		<label for="enableLanguages">{gt text='Enable language filter'}</label>
		<input type="checkbox" value="1" id="enableLanguages" name="enableLanguages"{if $modvars.DocTastic.enableLanguages eq true} checked="checked"{/if}/>
	</div>
    </fieldset>
    <fieldset>
        <legend>{gt text='Inline help settings'}</legend>
	<div class="z-formrow">
		<label for="enableInlineHelp">{gt text='Enable inline help'}</label>
		<input type="checkbox" value="1" id="enableInlineHelp" name="enableInlineHelp"{if $modvars.DocTastic.enableInlineHelp eq true} checked="checked"{/if}/>
	</div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" class='z-btgreen' __alt="Save" __title="Save" __text="Save"}
        <a class='z-btred' href="{modurl modname="DocTastic" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}