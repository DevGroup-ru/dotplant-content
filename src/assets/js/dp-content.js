"use strict";
var DPContent = window.DPContent || {};
(function ($) {
    var $contextSelect = $('#page-context_id');
    $.select2Change = function () {
        var $this = $(this);
        var val = $this.val();
        if (null === val || val == '') {
            $('option', $contextSelect).prop('disabled', false) || $('option', $contextSelect).removeAttr('disabled');
            if (0 == $('option[value=0]', $this).length) {
                $("option[value='']", $this).val(0).prop('selected', true);
            } else {
                $this.val(0);
            }
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
                        alert(o.responseText);
                    }
                });
            } else {
                alert('getContextUrl is not set');
            }
        }
    };
})(jQuery);

