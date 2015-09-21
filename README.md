# JCrop Plug-in for CONTENTdm 

The **JCrop Plug-in for CONTENTdm** uses a freely available JQuery plug-in tool - JCrop
(http://deepliquid.com/content/Jcrop.html) - to provide clip functionality to
CONTENTdm. This version of the tool adds a few other files designed to integrate
it with CONTENTdm and is comprised of the following files:

* Jcrop.gif - used by JCrop to provide initial black background
* jcrop_page.css - styling for the crop page
* jquery.Jcrop.min.js - main JCrop plug-in script (also available from several CDNs)
* jquery.Jcrop.css - styling for JCrop itself (also available from several CDNs)
* crop_image.php - main script integrating JCrop with CDM
* jcrop_button.js - javascript to insert the clip icons on the CDM image toolbar
* toolbar_clip_out.gif - toolbar clip icon, mouseout
* toolbar_clip_over.gif - toolbar clip icon, mouseover
* loader_white.gif - spinner to display while waiting for things to render
* README.md - this file
* LICENSE - MIT license

Steps to implement the JQuery JCrop plug-in with CONTENTdm using the CDM
Configuration Tool (as of CDM 6.1.4):

* Edit configuration variables
	1. Open 'crop_image.php' and assign the correct values to $CONTENTDM_HOME, $WEBSERVICES, 
$JCROP_PATH, and $CDM_JQUERY_PATH.
	2. Open 'jcrop_button.js' and assign the correct value to JCROPPATH (this will
be the same path as $JCROP_PATH form step #1b).

* Create a new custom folder for your JCrop files:
	1. Go to Global Settings -> Custom Pages/Scripts -> Custom Pages
	2. Click 'Add Custom Page' and name it 'jcrop'. Uncheck 'Use website layout
and styles'.
	
* Upload all of the above listed files:
	1. Click the button 'manage files'.
	2. Locate the 'jcrop' folder you just created and click on it.
	3. Click on the add files button in the upper left corner.
	4. Drag and drop all files into the folder window.
	5. Return to the files list, close the upload window, and click the Publish button.
	
* Upload the button loader script in order to activate the plug-in for a
specific collection.
	1. Click on 'Collections' tab at the top and change the active collection to
the collection for which you would like to make the clip tool active. (If you
want it active for every collection then you might want to click on the 'Global
Settings' tab instead.)
	2. Go to Custom Pages/Scripts -> Custom Scripts.
	3. Next to the box under 'Upload Bottom Includes' click on 'Browse'.
	4. Locate the 'jcrop_button.js' script and drag it to the folder.
	5. Close file 'upload a file' window and click the Publish button.
	6. Step #3 needs to be repeated for each collection for which you would like
the clip tool to be active.

Questions or problems: Phil Sager <psager@ohiohistory.org>