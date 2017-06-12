(function (root, factory) {
    root.ba.moduleLoader.define('EstimatesPage', ['jquery'], factory);
}(this, function($) {
    'use strict';

    function EstimatesPage() {
        this.init();
    }

    EstimatesPage.prototype = {
        init() {
            this.setupScrollSpy();
        },

        setupScrollSpy() {
            $(document).ready(function() {
                $('.scrollspy').scrollSpy();
            });
        }
    };

    return new EstimatesPage();
}));
