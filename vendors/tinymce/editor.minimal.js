tinymce.init({
schema: "html5",
extended_valid_elements : "p[style],img[style|src|alt|width|height|align]",
invalid_elements : "form, textarea, header, footer, section, div, span, h1, h2, h3, h4, h5, h6, hr",
mode : "specific_textareas",
editor_selector : "mceEditorSimple",
theme: "modern",
language : 'tr_TR',
width: "100%",
height: 200,
plugins: ["code link"],
toolbar: "link unlink | bold italic | undo redo | code removeformat",
entity_encoding : "raw",
menubar: false,
statusbar: false,
inline : false,
paste_as_text: true,
browser_spellcheck : true,
gecko_spellcheck : true,
branding: false
});
