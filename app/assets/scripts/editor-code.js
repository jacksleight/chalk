/* Libraries */

//= require ../../../bower_components/ace-builds/src-min/ace.js
//= require ../../../bower_components/ace-builds/src-min/mode-html.js
//= require ../../../bower_components/ace-builds/src-min/mode-json.js

/* Initialize */

Chalk.component('textarea.editor-code', function(i, el) {

    $(el).hide();
    var div = $('<div>')
        .css('height', $(el).height() + 'px')
        .insertAfter(el);
    var editor = ace.edit(div[0]);
    editor.getSession().setUseWorker(false);
    editor.getSession().setMode("ace/mode/html");
    editor.getSession().setValue($(el).val());
    editor.getSession().on('change', function(){
        $(el).val(editor.getSession().getValue());
    });

});