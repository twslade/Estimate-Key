/**
 * @package     Blueacorn\ResponsiveNotation
 * @version     2.0
 * @author      Blue Acorn <code@blueacorn.com>
 * @copyright   Copyright © 2016 Blue Acorn.
 */

(function (root, factory) {
    if (typeof exports === 'object') {
        // CommonJS
        module.exports = factory(window.jQuery, require('./_blueacorn-core'), require('../vendor/enquire/enquire.min'));
    } else if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery', 'baCore', 'enquire'], factory);
    } else {
        // Global Variables
        root.ba.moduleLoader.define('responsiveNotation', ['jquery', 'ba', 'enquire'], factory);
    }

}(this, function ($, ba, enquire) {
    'use strict';

    function ResponsiveNotation(options) {
        this.init(options);
    }

    ResponsiveNotation.prototype = {
        init: function (options) {
            this.settings = {
                'moduleName' : 'ResponsiveNotation',
                'mobileClass': 'resp-mobile',
                'tabletClass': 'resp-tablet',
                'desktopClass': 'resp-desktop',
                'html': $('html')
            };

            // Overrides the default settings
            ba.overrideSettings(this.settings, options);

            // Start the debugger
            ba.setupDebugging(this.settings);

            // Fire document event incase you need to observer rNote being loaded before you load something else.
            $(document).trigger('rnote:loaded');

            // Setup Enquire Observers to Change Class Based on
            this.setViewportClass();
        },

        setViewportClass: function(){
            var self = this;
            enquire.register('screen and (min-width:' + (ba.bp.large + 1) + 'px)', {
                match: function() {
                    self.settings.html.addClass(self.settings.desktopClass);
                },
                unmatch: function() {
                    self.settings.html.removeClass(self.settings.desktopClass);
                }
            }).register('screen and (min-width:' + (ba.bp.medium + 1) + 'px) and (max-width:' + ba.bp.large + 'px)', {
                match: function() {
                    self.settings.html.addClass(self.settings.tabletClass);
                },
                unmatch: function() {
                    self.settings.html.removeClass(self.settings.tabletClass);
                }
            }).register('screen and (max-width:' + ba.bp.medium + 'px)', {
                match: function() {
                    self.settings.html.addClass(self.settings.mobileClass);
                },
                unmatch: function() {
                    self.settings.html.removeClass(self.settings.mobileClass);
                }
            });
        },

        is: function(device) {
            return this.settings.html.hasClass(device);
        },

        isMobile: function() {
            return this.is(this.settings.mobileClass);
        },

        isTablet: function() {
            return this.is(this.settings.tabletClass);
        },

        isDesktop: function() {
            return this.is(this.settings.desktopClass);
        },

        isTouch: function() {
            return this.is('touch');
        }
    };

    /**
     * The parameter object is optional.
     * Must be an object.
     */
    ba.ResponsiveNotation = new ResponsiveNotation();

    ba.isDesktop = function() {
        return ba.ResponsiveNotation.isDesktop();
    };

    ba.isTablet = function() {
        return ba.ResponsiveNotation.isTablet();
    };

    ba.isMobile = function() {
        return ba.ResponsiveNotation.isMobile();
    };

    ba.isTouch = function() {
        return ba.ResponsiveNotation.isTouch();
    };

    return ba.ResponsiveNotation;
}));
