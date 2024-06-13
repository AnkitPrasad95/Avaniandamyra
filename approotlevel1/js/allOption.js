/*tinymce.init({

  selector: '.tinyTextArea',
  fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt 38pt 40pt 42pt 46pt 48pt 50pt 52pt 54pt 56pt 58pt 60pt",
  height: 300,
  theme: 'modern',
  browser_spellcheck: true,
  spellchecker_rpc_url: 'localhost/ephox-spelling',
  spellchecker_language: 'en',
 
  plugins: [
    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc '

  ],


  toolbar1: 'undo redo pastetext | insert | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fontsizeselect ',
  toolbar2: 'styleselect | fontselect | forecolor backcolor emoticons | codesample | print preview media | blockquote | strikethrough ',
  powerpaste_allow_local_images: true,
  powerpaste_word_import: 'prompt',
  powerpaste_html_import: 'prompt',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'],
  image_advtab: true,
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'
  ]
 });
*/


//For word Copy Paste
tinymce.init({
  selector: '.tinyTextArea',
  fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt 38pt 40pt 42pt 46pt 48pt 50pt 52pt 54pt 56pt 58pt 60pt",
  height: 400,
  menubar: true,
  theme: 'modern',
  browser_spellcheck: true,
  spellchecker_rpc_url: 'localhost/ephox-spelling',
  spellchecker_language: 'en',
  plugins: [
    'advlist autolink lists link image charmap print preview anchor hr pagebreak',
    'searchreplace visualblocks fullscreen wordcount visualchars code',
    'insertdatetime media nonbreaking save table contextmenu directionality',
    'emoticons template textcolor colorpicker textpattern imagetools codesample  toc'
  ],

  toolbar1: 'undo redo pastetext | insert | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fontsizeselect ',
  toolbar2: 'styleselect | fontselect | forecolor backcolor emoticons | codesample | print preview media | blockquote | strikethrough ',
  powerpaste_allow_local_images: true,
  powerpaste_word_import: 'prompt',
  powerpaste_html_import: 'prompt',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'],
  image_advtab: true,
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'
  ],
  //content_css: ['https://fonts.googleapis.com/css?family=Gugi'],
  
  //font_formats: 'Arial Black=arial black,avant garde;Gugi=Gugi, cursive;Times New Roman=times new roman,times;',
});


 
 
 
 var FMath_selectedElement = null;

tinymce.PluginManager.add('FMathEditor', function(editor, url) {

  editor.addButton('FMathEditor', {
    text: '',
    image : url + '/icons/FMathEditor.png',
    onclick: function() {
      // Open window
      editor.windowManager.open({
		  	title: 'FMath Editor - www.fmath.info',
		  	file : url + '/editor/onlyEditor.html',
			width : 1050,
			id: 'FMathEditorIFrame',
			height : 500,
			buttons: [{
			          text: 'Insert Equation',
			          onclick: function(){
						var frame = null;
						var frames = document.getElementsByTagName("iframe");
						for (i = 0; i < frames.length; ++i){
							var src = frames[i].src;
							if(src != null && src.indexOf('onlyEditor.html')>-1){
								frame = frames[i];
								break;
							}
						}
						var mathml = frame.contentWindow.getMathML();
						var img = frame.contentWindow.getBlobOrUrl( function(result){
							if(result.indexOf("ERROR:")==0){
								alert(result);
							}else{
								var img = result;
								tinymce.activeEditor.insertContent('<img alt="MathML (base64):'+ window.btoa(mathml) +'" src="'+img+'"/>')
								editor.windowManager.close();
							}
						});
					}
        	},{
			          text: 'Close',
			          onclick: 'close'
        	}]
		});

    },
    onPostRender: function() {
	        var ctrl = this;
	        editor.on('NodeChange', function(e) {
	            if(e.element.nodeName == 'IMG'){
					FMath_selectedElement = e.element;
				}else{
					FMath_selectedElement = null;
				}
	        });
    }

  });



});

function getMathMLToLoad(){
	if(FMath_selectedElement != null){
		var alt = FMath_selectedElement.alt;
		if(alt !=null && alt.indexOf("MathML (base64):")==0){
			var mathml = alt.substring(16, alt.length);
			return window.atob(mathml);
		}
	}
	return null;
}