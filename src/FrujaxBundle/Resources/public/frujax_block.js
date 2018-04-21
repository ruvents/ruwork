;(function (window, document, $) {
    'use strict';

    $(document)
        .on('before.frujax', function (event, ajaxOptions) {
            var block = $(event.target).frujax('options').block;

            if (block) {
                ajaxOptions.headers['Frujax-Block'] = block;
            }
        });
})(window, document, jQuery);
