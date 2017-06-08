/**
 * @package     Blueacorn/BlueAcornCoreHelpers
 * @version     1.0
 * @author      Blue Acorn <code@blueacorn.com>
 * @copyright   Copyright © 2016 Blue Acorn.
 */

(function (root, factory) {
    if (typeof exports === 'object') {
        // CommonJS
        module.exports = factory();
    } else if (typeof define === 'function' && define.amd) {
        // AMD
        define(factory);
    } else {
        // Global
        root.ba.moduleLoader.define('baHelpers', ['ba'], function(ba) {
            var helpers = (factory(ba));
            ba.helpers = helpers;
            return helpers;
        });
    }

}(this, function () {
    'use strict';

    function BlueAcornCoreHelpers() {
        return;
    }

    BlueAcornCoreHelpers.prototype = {
        classes: {
            animate: 'h-animate',
            pause: 'h-animate--pause',
            fadeIn: 'h-animate--fadein',
            fadeOut: 'h-animate--fadeout',
            slideUp: 'h-animate--slideup',
            slideLeft: 'h-animate--slideleft',
            slideRight: 'h-animate--slideright',
            duration: function(time) {
                return 'h-animate--duration-' + time;
            },
            delay: function(time) {
                return 'h-animate--delay-' + time;
            },
            show: 'h-show',
            showRow: 'h-show--row'
        }
    };

    return new BlueAcornCoreHelpers();
}));
