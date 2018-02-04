window.SONATA_CONFIG = {
    CONFIRM_EXIT: true,
    USE_SELECT2: true,
    USE_ICHECK: true,
    USE_STICKYFORMS: true                    };
window.SONATA_TRANSLATIONS = {
    CONFIRM_EXIT: 'Vous\x20avez\x20effectu\u00E9\x20des\x20modifications\x20non\x20sauvegard\u00E9es.'
};

// http://getbootstrap.com/getting-started/#support-ie10-width
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style');
    msViewportStyle.appendChild(document.createTextNode('@-ms-viewport{width:auto!important}'));
    document.querySelector('head').appendChild(msViewportStyle);
}

require('jquery.scrollto');
require('moment');
require('jquery-ui');
require('../../../../../node_modules/jquery-ui-datepicker-with-i18n/ui/jquery.ui.datepicker');
require('bootstrap');
require('../../../../../web/bundles/sonatacore/vendor/eonasdan-bootstrap-datetimepicker');
require('jquery-form');
require('../../../../../web/bundles/sonataadmin/jquery/jquery.confirmExit');
require('../../../../../web/bundles/sonataadmin/vendor/x-editable/dist/bootstrap3-editable/js/bootstrap-editable');
require('select2');
require('admin-lte');
require('icheck');
require('../../../../../node_modules/jquery-slimscroll/jquery.slimscroll');
require('../../../../../node_modules/waypoints/lib/jquery.waypoints.min.js');
require('../../../../../node_modules/waypoints/lib/shortcuts/sticky.min.js');
require('readmore-js');
require('masonry-layout');

require('../../../../../web/bundles/sonataadmin/Admin.js');
require('../../../../../web/bundles/sonataadmin/treeview.js');
require('../../../../../web/bundles/sonatacore/vendor/moment/locale/fr.js');
require('../../../../../web/bundles/sonatacore/vendor/select2/select2_locale_fr.js');

require('../../../../../web/bundles/mmcsonataadmin/tinymce/tinymce.min.js');

    tinymce.init({
        selector:'textarea.rich',
        setup: function(editor) {
            editor.on('focusout', function () {
                tinyMCE.triggerSave();
            });
        },
        menubar: false,
        oninit : "setPlainText",
        plugins: [ 'link paste', 'lists' ],
        toolbar: [
            'undo redo',
            'formatselect styleselect',
            'bold italic underline',
            'alignleft aligncenter alignright',
            'bullist numlist outdent indent',
            'link'
        ].join(" | "),
        language_url : '/bundles/mmcsonataadmin/tinymce/langs/fr_FR.js',
        body_class: 'tinymce',
        content_css: [
            '{{ mmc_sonata_admin_tinymce_content_css }}'
        ],
        block_formats: 'Paragraph=p;' +
            'Heading 1=h1;' +
            'Heading 2=h2;' +
            'Heading 3=h3;' +
            'Heading 4=h4;',
        style_formats: [
            {title: 'Titre 1 - Art', block: 'h1', classes: 'art-title'},
            {title: 'Titre 2 - Art', block: 'h2', classes: 'art-title'}
        ]
    });

require('../../../../../web/bundles/mmcsonataadmin/main.js');
