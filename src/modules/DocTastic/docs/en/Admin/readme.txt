DocTastic Read Me
=================

**20 August 2010**

Some things you can do:

Change the Navigation type in the Settings (Select or Tree for now)

----

Append <code>&docmodule=yourmodulename</code> to the end of this url to see the docs from another module
like:

    index.php?module=doctastic&type=admin&func=view&docmodule=PostCalendar

----

Change the code in <code>lib/DocTastic/Controller/Admin.php</code> around line 124 from this:

    $control = new $classname(array(
        'docsDirectory' => $this->docsDirectory,
        'languageEnabled' => false));

to this:

    $control = new $classname(array(
        'docsDirectory' => $this->docsDirectory,
        'languageEnabled' => true));

  and it will only scan directories by their language code (this is planned to be the default behavior).

----

Change that same code as above to this:

    $control = new $classname(array(
        'languageEnabled' => false));

  and you will scan the core's /docs directory (change to <code>languageEnabled => true</code> flag to only get the /en dir)