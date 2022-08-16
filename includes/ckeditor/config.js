/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */


CKEDITOR.editorConfig = function( config ) {
	config.extraPlugins = 'panelbutton';
	config.extraPlugins = 'colorbutton';
	
	CKEDITOR.config.width = 585
	CKEDITOR.config.language = 'en';
	config.removePlugins = 'forms,flash,about';
	//config.removePlugins = 'flash';
	config.removeButtons = 'Anchor,CreateDiv,Styles';
	
//config.specialChars = config.specialChars.concat('&#8377;');
config.specialChars = config.specialChars.concat(['&#8377;']);

	// Define changes to default configuration here. For example:	
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};

