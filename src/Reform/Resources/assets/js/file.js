;(function (window, document, $) {
    'use strict';

    var FILE_API = !!(window.File && window.FileReader && window.FileList && window.Blob);

    var getFilename = function (input) {
        if (FILE_API) {
            if (input.files[0]) {
                return input.files[0].name;
            }

            return null;
        }

        var val = $(input).val();

        if (!val || 'C:\\fakepath\\' === val) {
            return null;
        }

        return val;
    };

    $(document)
        .on('change', "[data-filename][data-filename!='']", function () {
            var name = getFilename(this);

            if (null !== name) {
                $($(this).data('filename')).text(name);
            }
        });
})(window, document, jQuery);
