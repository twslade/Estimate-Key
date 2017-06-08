/**
 * @package     Blueacorn/Core
 * @version     1.0
 * @author      Blue Acorn <code@blueacorn.com>
 * @copyright   Copyright © 2016 Blue Acorn.
 *
 * @todo Be able to convert this into a simpler JS template.
 * @todo comment parsing?
 * @todo better naming convention
 */

(function (root, factory) {
    if (typeof exports === 'object') {
        // CommonJS
        module.exports = factory(window.jQuery, require('../vendor/requirejs/domReady'), require('./blueacorn-core-utils'), require('./blueacorn-core-helpers'));
    } else if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery', 'domReady', 'baUtils', 'baHelpers'], factory);
    } else {
        // Global Variables
        root.ba = factory(root.jQuery, root.domReady);
    }

}(this, function ($, domReady, utils, helpers) {
    'use strict';

    var blueAcornCore = {
        utils: utils,
        helpers: helpers,
        config: {},
        domReady: false,

        settings: {
            'debug': false,
            'moduleName' : 'blueAcornCore'
        },

        bp: {
            xxsmall: 320,
            xsmall: 480,
            small: 640,
            medium: 768,
            large: 1024,
            xlarge: 1440
        },

        init: function (options) {

            // Overrides the default settings
            this.overrideSettings(this.settings, options);

            // Overrides the default BP settings since M1 has them globally
            if(typeof window.bp !== "undefined"){
                this.overrideSettings(this.bp, window.bp);
            }

            // Start the debugger
            if (this.settings.debug === true) {
                this.setupDebugging(this.settings);
            }

            this.triggerCustomEvent();
        },

        /**
         * Takes default settings in object scope, and
         * merges the optional object passed in on initiation
         * of the class.
         */
        overrideSettings: function (settings, options) {
            if (typeof options === 'object') {
                settings = jQuery.extend(settings, options);
            }
        },

        setupDebugging: function (moduleSettings) {
            if(typeof moduleSettings === 'object'){
                this.watchConsole(moduleSettings.moduleName + ' Loaded!!!');
                this.watchConsole(moduleSettings);
            }
        },

        /**
         * Trigger the baCoreReady custom event, adjust the domReady flag to
         * true for any events registered via the ba.ready function
         */
        triggerCustomEvent: function() {
            this.domReady = true;
            $(document).trigger('baCoreReady');
        },

        /**
         * Will resolve race conditions across each platform incase baCore is
         * loaded after the DOM is already loaded. For instance, if a module
         * depends on baCoreReady event, and it has already fired, it will
         * still execute the callback
         *
         * @param callback - function to call when the DOM is ready
         */
        ready: function(callback) {
            if (this.domReady) {
                callback();
            } else {
                $(document).on('baCoreReady', callback);
            }
        },

        /**
         * Checks if the specified jQuery element exists.
         *
         * If regular HTML element is passed, will change into
         * jQuery selector for use in this function.
         *
         * @param $element - jQuery object
         * @returns {boolean}
         */
        checkForElement: function($element){
            if(!($element instanceof jQuery)){
                $element = jQuery($element);
            }
            return $element.length >= 1;
        },

        /**
         * Adds console log if degubbing is true
         * @param string
         */
        watchConsole: function (message) {
            if(!$('.ie6, .ie7, .ie8, .ie9').length && typeof console !== "undefined" && this.settings.debug) {
                console.log(message);
            }
        },

        /**
         * Returns a function that will only be triggered once, after inactivity of (wait) ms
         * Ported from: http://underscorejs.org/docs/underscore.html
         *
         * @param func
         * @param wait
         * @param immediate
         * @returns {Function}
         */
        debounce: function (func, wait, immediate) {
            var timeout;

            return function() {
                var context = this,
                    args = arguments;

                var later = function() {
                    timeout = null;

                    if (!immediate) {
                        func.apply(context, args);
                    }
                };

                if (immediate && !timeout) {
                    func.apply(context, args);
                }

                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * Convert First Character of String to Capital Character
         * @param  stringText Text to Convert
         * @return String Converted Text
         */
        camelCaseCreator: function(stringText) {
            return stringText.charAt(0).toUpperCase() + stringText.slice(1);
        },

        /**
         * Converts Class into css selector.
         * @param classString setting value you wish to convert to css selector.
         * @returns {string} css selector.
         */
        formatClass: function(classString, classObject) {
            return '.' + classObject[classString];
        },

        /**
         * formatClass Alias
         */
        fc: function(classString, classObject) {
            return this.formatClass(classString, classObject);
        }
    };

    if(typeof domReady === "function") {
        domReady(function () {

            /**
             * Since M1/M2 and DW defer in js config object names
             * we need to watch for both and and save them to the ba
             * object so we can use them regardless of platform
             */

            // M1 and M2
            if(typeof mageConfig !== "undefined" && typeof mageConfig === "object"){
                blueAcornCore.config = mageConfig;
            }

            // DW
            if(typeof SitePreferences !== "undefined" && typeof SitePreferences === "object"){
                blueAcornCore.config = SitePreferences;
            }

            /**
             * @todo Read Me Below
             *
             * What about domReady, should we leave it for DW?
             *
             * Need to determine a global naming convention for our debugging
             * With current M1/M2 mageJsConfig the config object key is the system
             * config path, can we change this to something more abstracted?
             */
            blueAcornCore.init();
        });
    }

    // Deals with issues between jQuery & Prototype Event Triggering
    // @source: http://stackoverflow.com/a/460709

    if (typeof Element.triggerEvent !== 'function') {
        Element.prototype.triggerEvent = function(eventName) {
            if (document.createEvent) {
                var evt = document.createEvent('HTMLEvents');
                evt.initEvent(eventName, true, true);

                return this.dispatchEvent(evt);
            }

            if (this.fireEvent) {
                return this.fireEvent('on' + eventName);
            }
        };
    }

    /**
     *  String#truncate([length = 30[, suffix = '...']]) -> String
     *
     *  Truncates a string to given `length` and appends `suffix` to it (indicating
     *  that it is only an excerpt).
     *
     *  ##### Examples
     *
     *      'A random sentence whose length exceeds 30 characters.'.truncate();
     *      // -> 'A random sentence whose len...'
     *
     *      'Some random text'.truncate();
     *      // -> 'Some random text.'
     *
     *      'Some random text'.truncate(10);
     *      // -> 'Some ra...'
     *
     *      'Some random text'.truncate(10, ' [...]');
     *      // -> 'Some [...]'
     **/
    if (typeof String.truncate !== 'function') {
        String.prototype.truncate = function(length, truncation) {
            length = length || 30;
            truncation = typeof truncation === "undefined" ? '...' : truncation;
            return this.length > length ? this.substring(0, length) + truncation : this;
        };
    }

    /**
     * Polyfill for ES6 startsWith
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/startsWith
     */
    if (typeof String.startsWith !== 'function') {
        String.prototype.startsWith = function(searchString, position) {
            position = position || 0;
            return this.substr(position, searchString.length) === searchString;
        };
    }

    /**
     *  RegExp.escape(str) -> String
     *  - str (String): A string intended to be used in a `RegExp` constructor.
     *
     *  Escapes any characters in the string that have special meaning in a
     *  regular expression.
     *
     *  Use before passing a string into the `RegExp` constructor.
     */
    if (typeof RegExp.escape !== 'function') {
        RegExp.escape = function(str) {
            return String(str).replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
        };
    }


    /**
     *  String#strip() -> String
     *
     *  Strips all leading and trailing whitespace from a string.
     *
     *  ##### Example
     *
     *      '    hello world!    '.strip();
     *      // -> 'hello world!'
     **/
    if (typeof String.strip !== 'function') {
        String.prototype.strip = function() {
            return this.replace(/^\s+/, '').replace(/\s+$/, '');
        };
    }

    /**
     *  String#format() -> String
     **/

    if (typeof String.format !== 'function') {

        String.format = function() {
            var s = arguments[0];
            var i, len = arguments.length - 1;
            for (i = 0; i < len; i++) {
                var reg = new RegExp('\\{' + i + '\\}', 'gm');
                s = s.replace(reg, arguments[i + 1]);
            }
            return s;
        };
    }

    return blueAcornCore;
}));
