(function (root, factory) {
    root.ba.moduleLoader.define('EstimatesPage', ['jquery'], factory);
}(this, function($) {
    'use strict';

    function EstimatesPage() {
        this.init();
    }

    EstimatesPage.prototype = {
        init() {
            var self = this;

            $(document).ready(function() {
                self.setupScrollSpy();
                self.setupPushPin();
            });
        },

        setupScrollSpy() {
            $.each($('.table-of-contents a'), function(idx, anchor) {
                $(anchor).prop('href', $(anchor).data('new'));
            });

            $('.scrollspy').scrollSpy();
        },

        setupPushPin: function() {
            $('.toc-wrapper').pushpin({
                top: 75
            });
        }
    };

    return new EstimatesPage();
}));
