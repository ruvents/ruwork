;(function (window, document, $) {
    'use strict';

    $(document)
        .on('before.frujax', function (event, request) {
            var block = $(event.target).frujax('options').block;

            if (block) {
                request.headers['Frujax-Block'] = block;
            }
        });
})(window, document, jQuery);
