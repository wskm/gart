/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.config.toolbar='Art';
CKEDITOR.editorConfig = function( config )
{

	config.uiColor = '#F5F5F5';
    config.skin='kama';

    config.toolbar_Art =
    [
        ['Maximize','Source','-','Paste','PasteText','PasteFromWord','RemoveFormat','Undo','Redo','-','Bold','Italic','Underline','Strike','TextColor','BGColor','Link','Unlink','SelectAll','Table','-','HorizontalRule','PageBreak','ShowBlocks'],'/',
        ['Format','Font','FontSize','NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Find','Replace','Image','Flash','Smiley']
    ];
	config.smiley_images	= ['1.gif','10.gif','11.gif','12.gif','13.gif','14.gif','15.gif','16.gif','17.gif','18.gif','19.gif','2.gif','20.gif','21.gif','22.gif','23.gif','24.gif','25.gif','26.gif','27.gif','28.gif','29.gif','3.gif','30.gif','31.gif','32.gif','33.gif','34.gif','35.gif','36.gif','37.gif','38.gif','39.gif','4.gif','40.gif','41.gif','42.gif','43.gif','44.gif','45.gif','46.gif','47.gif','48.gif','49.gif','5.gif','50.gif','51.gif','52.gif','53.gif','54.gif','55.gif','56.gif','57.gif','58.gif','59.gif','6.gif','60.gif','61.gif','62.gif','63.gif','64.gif','7.gif','8.gif','9.gif'] ;
	config.smiley_columns  = 8;


};
