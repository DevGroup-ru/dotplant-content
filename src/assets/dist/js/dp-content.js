"use strict";
var DPContent = window.DPContent || {};
(function ($) {
    var $contextSelect = $('#page-context_id');
    $.select2Change = function () {
        var $this = $(this);
        var val = $this.val();
        if (null === val || val == '') {
            $('option', $contextSelect).prop('disabled', false) || $('option', $contextSelect).removeAttr('disabled');
        } else {
            if (false !== Object.prototype.hasOwnProperty.call(DPContent, 'getContextUrl')) {
                $.ajax({
                    url: DPContent.getContextUrl,
                    type: 'POST',
                    data: {'structure_id': val},
                    success: function (data) {
                        $contextSelect.val(data);
                        $('option:not(:selected)', $contextSelect).each(function (i, e) {
                            var $e = $(e);
                            $e.prop('disabled', true);
                        });
                    },
                    error: function (data, textStatus, errorThrown) {
                        alert(data.responseText);
                    }
                });
            } else {
                var missingText = DPContent.missingText || 'Missing parameter "getContextUrl"';
                alert(missingText);
            }
        }
    };
})(jQuery);
