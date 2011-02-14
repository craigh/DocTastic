<h1>{gt text="DocTastic"}</h1>
<div class='doctastic_container'>
    <div class='doctasticpageicon'>{img modname='core' set='icons/large' src='info.png'}</div>
    <h2>{gt text="Location:"} {$directory}</h2>
    {checkpermissionblock component='DocTastic::' instance='::' level=ACCESS_ADMIN}
        <a class='z-floatright z-icon-es-config' style='padding-right:5em;' href='{modurl modname='DocTastic' type='admin'}'>{gt text='DocTastic Administration'}</a>
    {/checkpermissionblock}
    {$navigation}
    {if $document}
    <div>
        <div class='doctasticpageicon'>{img modname='core' set='icons/large' src='filenew.png'}</div>
        <h2>{gt text="Document Name:"} {$documentname}</h2>
        <div id='DocTastic_document'>
            {$document|safehtml}
        </div>
    </div>
    {/if}
</div>