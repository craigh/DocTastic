{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='modules/DocTastic/javascript/markitup/jquery.markitup.js'}
{pageaddvar name='javascript' value='modules/DocTastic/javascript/markitupsettings.js'}
{pageaddvar name='stylesheet' value='modules/DocTastic/javascript/markitup/skins/markitup/style.css'}
{pageaddvar name='stylesheet' value='modules/DocTastic/javascript/markitup/sets/markdown/style.css'}

{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="new" size="small"}
    <h3>{gt text="DocTastic document generator"}</h3>
</div>

<div class="z-informationmsg">
        {gt text="This editor <strong>does not save</strong> your generated content anywhere. You have to cut and past the raw text to your own text file and save to your /docs directory. This is merely provided as a markdown viewer for ease in doc creation."}
    </div>
<textarea cols="80" rows="40" id="markItUp"></textarea>
<script type="text/javascript" >
   jQuery(document).ready(function() {
      jQuery("#markItUp").markItUp(mySettings);
   });
</script>
{adminfooter}