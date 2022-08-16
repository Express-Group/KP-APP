CKEDITOR.plugins.add( 'charcount',
{
   init : function( editor )
   {
      var defaultLimit = 'unlimited';
      var defaultFormat = '%remain_letters%';
      var limit = editor.config.MaxLength;
      var format = defaultFormat;

      var intervalId;
      var lastCount = 0;
      var limitReachedNotified = false;
      var limitRestoredNotified = false;
	  var Last_CKUpdate = '';
      
      
      if ( true )
      {   
         function counterId( editor )
         {
            return 'cke_charcount_' + editor.name;
         }
         
         function counterElement( editor )
         {
	
            return document.getElementById( counterId(editor) );
         }
         
         function updateCounter( editor )
         {
			
            var count = $("<div/>").html(editor.getData()).text().length;	
			
            if( count == lastCount ){
               return true;
            } else {
               lastCount = count;
            }
            if( !limitReachedNotified && count > limit ){
               limitReached( editor );
            } else if( !limitRestoredNotified && count < limit ){
               limitRestored( editor );
            }
            
			var remain_letters = editor.config.MaxLength - count;
			
			
			if(remain_letters <= 0) {
				
				editor.setData($("<div/>").html(editor.getData()).text().substr(0,limit));
				 var html = format.replace('%remain_letters%', 0);
				counterElement(editor).innerHTML = html;
				
			} else {
				Last_CKUpdate = editor.getData();
				 editor.setUiColor( '#C4C4C4' );
			}
			console.log(remain_letters);
		if(remain_letters >= 0) {
            var html = format.replace('%remain_letters%', remain_letters);
			counterElement(editor).innerHTML = html;
		}
		
         }
         
         function limitReached( editor )
         {
            limitReachedNotified = true;
            limitRestoredNotified = false;
          //  editor.setUiColor( '#FFC4C4' );
         }
         
         function limitRestored( editor )
         {
            limitRestoredNotified = true;
            limitReachedNotified = false;
          //  editor.setUiColor( '#C4C4C4' );
         }

         editor.on( 'themeSpace', function( event )
         {
            if ( event.data.space == 'bottom' )
            {
               event.data.html += '<div id="'+counterId(event.editor)+'" class="cke_charcount"' +
                  ' title="' + CKEDITOR.tools.htmlEncode( 'Character Counter' ) + '"' +
                  '>&nbsp;</div>';
            }
         }, editor, null, 100 );
         
         editor.on( 'instanceReady', function( event )
         {
            if( editor.config.charcount_limit != undefined )
            {
               limit = editor.config.charcount_limit;
            }
            
            if( editor.config.charcount_format != undefined )
            {
               format = editor.config.charcount_format;
            }
            
            
         }, editor, null, 100 );
         
         editor.on( 'dataReady', function( event )
         {
            var count = $("<div/>").html(editor.getData()).text().length;	
            if( count > limit ){
               limitReached( editor );
            }
			
            updateCounter(event.editor);
			
			var range = editor.createRange();
			range.moveToElementEditEnd( range.root );
			editor.getSelection().selectRanges( [ range ] );

         }, editor, null, 100 );
         
         editor.on( 'key', function( event )
         {
			
           updateCounter(event.editor);
         }, editor, null, 100 );
         
         editor.on( 'focus', function( event )
         {
			
            editorHasFocus = true;
            intervalId = window.setInterval(function (editor) {
                 updateCounter(editor)
            }, 1000, event.editor);
         }, editor, null, 100 );
         
         editor.on( 'blur', function( event )
         {
            editorHasFocus = false;
            if( intervalId )
               clearInterval(intervalId);
         }, editor, null, 100 );
      }
   }
});