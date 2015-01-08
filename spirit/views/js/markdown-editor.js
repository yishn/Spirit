document.addEvent('domready', function() {

var value = $$('textarea').get('value');
$$('textarea').setStyle('display', 'none').grab(new Element('div', { 
    class: 'ace',
    text: value
}), 'before').each(function(textarea) {
    var editor = ace.edit(textarea.getPrevious('.ace'));
    textarea.getPrevious('.ace').store('ace', editor);

    editor.getSession().setMode('ace/mode/markdown');
    editor.getSession().setUseWrapMode(true);

    editor.setHighlightActiveLine(false);
    editor.setShowPrintMargin(false);
    editor.setDisplayIndentGuides(false);
    editor.setHighlightSelectedWord(false);
    editor.setAutoScrollEditorIntoView(true);
    editor.setFontSize(16);
    editor.setOption('minLines', 5);
    editor.setOption('maxLines', 100);

    editor.commands.bindKey("ctrl-t", null);
    editor.commands.bindKey("ctrl-f", null);
    editor.commands.bindKey("ctrl-l", null);
    editor.commands.bindKey("ctrl-p", null);
    editor.commands.bindKey("ctrl-r", null);
    editor.commands.bindKey("ctrl-d", null);
    editor.commands.bindKey("ctrl-shift-p", null);
    editor.commands.bindKey("ctrl-,", null);

    editor.renderer.setPadding(0);
    editor.renderer.setShowGutter(false);
});

$('ace-tm').set('text', $('ace-tm').get('text') + ' .ace_line * { color: black !important; } .ace_heading, .ace_strong, .ace_function { font-weight: bold; } .ace_underline, .ace_url { text-decoration: underline; } .ace_emphasis { font-style: italic; } .ace_blockquote { opacity: .7; } .ace_hidden-cursors { opacity: 0; }');

});