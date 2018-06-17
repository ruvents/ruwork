;(function (window, document, $) {
    'use strict';

    $(document)
        .on('before.frujax', function (event, request) {
            var hideFormErrors = $(event.target).frujax('options').hideFormErrors;

            if (hideFormErrors) {
                request.headers['Frujax-Hide-Form-Errors'] = 1;
            }
        });
})(window, document, jQuery);
