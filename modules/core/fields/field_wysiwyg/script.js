$(document).ready(function() {
    parsiEdit.registerTool("fieldwysiwyg",function(){
        if(typeof window['wysiwygy'] == "undefined"){
            window['wysiwygy'] = new wysiwyg();
            window['wysiwygy'].init(".fieldwysiwyg",["bold","underline","italic","justifyLeft","justifyCenter","justifyRight","strikeThrough","subscript","superscript","orderedList","unOrderedList","outdent","indent","removeFormat","createLink","unlink","formatBlock","foreColor","hiliteColor"]);
        }
        $(this).attr("contenteditable", "true");
        $(".HTML5editorToolbar").show();
        $(document).on('focusout.edit',".fieldwysiwyg",function(e){
            parsiEdit.currentElmt = "";
            $(".HTML5editorToolbar").hide();
            if(typeof top.ParsimonyAdmin != "undefined") $(".HTML5editorToolbar", top.document).hide();
            $(document).off('focusout.edit',".fieldwysiwyg");
            if(typeof top.ParsimonyAdmin == "undefined"){
                $.post(BASE_PATH + $(this).data("module") + '/callField',{
                    module: $(this).data("module"), 
                    entity:$(this).data("entity"),
                    fieldName: $(this).data("property"), 
                    method:'saveEditInline',
                    args:"html=" + encodeURIComponent($(this).html()) + "&id=" + $(this).data("id")
                },function(data){
                    console.log(data);
                });
            }
        });
    });
});