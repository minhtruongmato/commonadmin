switch(window.location.origin){
    case 'http://commonadmin.com':
        var HOSTNAME = 'http://commonadmin.com/';
        break;
    default:
        var HOSTNAME = 'http://localhost/commonadmin/';
}

$(document).ready(function(){
    "use strict";

    tinymce.init({
        selector: ".tinymce-area",
        theme: "modern",
        height: 300,
        relative_urls: false,
        remove_script_host: false,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | responsivefilemanager | print preview media fullpage | forecolor backcolor emoticons",
        style_formats: [
            {title: "Bold text", inline: "b"},
            {title: "Red text", inline: "span", styles: {color: "#ff0000"}},
            {title: "Red header", block: "h1", styles: {color: "#ff0000"}},
            {title: "Example 1", inline: "span", classes: "example1"},
            {title: "Example 2", inline: "span", classes: "example2"},
            {title: "Table styles"},
            {title: "Table row 1", selector: "tr", classes: "tablerow1"}
        ],
        external_filemanager_path: HOSTNAME + "filemanager/",
        filemanager_title: "Responsive Filemanager",
        external_plugins: {"filemanager": HOSTNAME + "filemanager/plugin.min.js"}
    });

    $('#title_vi').change(function(){
        $('#slug_shared').val(to_slug($('#title_vi').val()));
    });
});

$(window).scroll(function () {
    //if you hard code, then use console
    //.log to determine when you want the
    //nav bar to stick.
    'use strict';
    if ($(window).scrollTop() > 150) {
        $('.nav_side').addClass('nav_side_fix');
    }
    if ($(window).scrollTop() < 150) {
        $('.nav_side').removeClass('nav_side_fix');
    }
});




// $(document).ready(function(){
//     "use strict";

// });

// $(window).scroll(function () {
//     //if you hard code, then use console
//     //.log to determine when you want the
//     //nav bar to stick.
//     'use strict';
//     if ($(window).scrollTop() > 150) {
//         $('.nav_side').addClass('nav_side_fix');
//     }
//     if ($(window).scrollTop() < 150) {
//         $('.nav_side').removeClass('nav_side_fix');
//     }
// });