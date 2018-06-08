;(function (window, document, $) {
    'use strict';

    $(document)
        .on('before.frujax', function (event, ajaxOptions) {
            if ($(event.target).frujax('options').hideFormErrors) {
                ajaxOptions.headers['Frujax-Hide-Form-Errors'] = 1;
            }
        });
})(window, document, jQuery);
