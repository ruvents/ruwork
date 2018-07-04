;(function (window, document, $) {
    'use strict';

    $(document)
        .on('before.frujax', function (event, request) {
            var part = $(event.target).frujax('options').part;

            if (part) {
                request.headers['Frujax-Part'] = part;
            }
        });
})(window, document, jQuery);
