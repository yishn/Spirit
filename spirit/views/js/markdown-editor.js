document.addEvent('domready', function() {

var value = $$('textarea').get('value');
$$('textarea').setStyle('display', 'none').grab(new Element('div', { 
    id: 'ace',
    text: value
}), 'after');

var editor = ace.edit('ace');
editor.getSession().setMode('ace/mode/markdown');
editor.getSession().setUseWrapMode(true);
editor.setHighlightActiveLine(false);
editor.setShowPrintMargin(false);
editor.setDisplayIndentGuides(false);
editor.setHighlightSelectedWord(false);
editor.renderer.setPadding(0);
editor.renderer.setShowGutter(false);

$('ace-tm').set('text', $('ace-tm').get('text') + ' .ace_line * { color: black !important; } .ace_heading, .ace_strong, .ace_function { font-weight: bold; } .ace_underline, .ace_url { text-decoration: underline; } .ace_emphasis { font-style: italic; } .ace_blockquote { opacity: .7; }');

});