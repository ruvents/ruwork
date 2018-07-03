;(function (window, document, $) {
    'use strict';

    $(document)
        .on('before.frujax', function (event, request) {
            var name = $(event.target).frujax('options').name;

            if (name) {
                request.headers['Frujax-Name'] = name;
            }
        });
})(window, document, jQuery);
