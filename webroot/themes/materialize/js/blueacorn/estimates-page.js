(function (root, factory) {
    root.ba.moduleLoader.define('EstimatesPage', ['jquery', 'enquire'], factory);
}(this, function($, enquire) {
    'use strict';

    function EstimatesPage() {
        this.init();
    }

    EstimatesPage.prototype = {
        init() {
            var self = this;

            $(document).ready(function() {
                self.setupScrollSpy();
                self.setupTOCPushpin();
                self.setupLeftPushpin();
            });
        },

        setupScrollSpy() {
            $.each($('.table-of-contents a'), function(idx, anchor) {
                $(anchor).prop('href', $(anchor).data('new'));
            });

            $('.scrollspy').scrollSpy();
        },

        setupTOCPushpin() {
            $('.toc-wrapper').pushpin({
                top: 75
            });
        },

        setupLeftPushpin() {
            enquire.register('screen and (min-width:' + ba.bp.medium + 'px)', {
                match: function() {
                    $('.left-wrapper').pushpin({
                        top: 75
                    });
                },
                unmatch: function() {
                    $('.left-wrapper').pushpin('remove');
                }
            });
        }
    };

    return new EstimatesPage();
}));
