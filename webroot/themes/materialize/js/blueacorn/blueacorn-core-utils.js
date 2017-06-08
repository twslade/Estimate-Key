/**
 * @package     Bluecorn/BlueAcornCoreUtils
 * @version     1.0
 * @author      Blue Acorn <code@blueacorn.com>
 * @copyright   Copyright © 2016 Blue Acorn.
 *
 * @todo create dynamic utility to create new methods that are automatically attached to BA. Do I need to?
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
        root.ba.moduleLoader.define('baUtil', ['ba'], function(ba) {
            var utils = (factory(ba));
            ba.utils = utils;
            return utils;
        });
    }

}(this, function () {
    'use strict';

    function BlueAcornCoreUtils() {
        return;
    }

    BlueAcornCoreUtils.prototype = {
        /**
         * Binds a callback to the whole HTML document.
         * For allowing the use of clicking outside of containers, and
         * calling the callback specified in the event. Also allows unbinding
         * that event based on the namespace provided
         *
         * @param nameSpace - named used for the event - eg click.namespace
         * @param bindUnbind - boolean
         * @param callback
         */
        bindHtmlClick: function(nameSpace, bindUnbind, callback) {
            var $html = $('html'),
                event = 'click.' + nameSpace;

            if (bindUnbind && typeof callback !== 'function') {
                return false;
            }

            if (bindUnbind) {
                $html.on(event, callback).addClass('clickout-active');
            } else {
                $html.off(event).removeClass('clickout-active');
            }
        },

        /**
         * Checks for the existence of local storage
         */
        localStorageExists: function() {
            return window.hasOwnProperty("localStorage");
        },

        /**
         * Sets a local storage key based on the key value pair
         *
         * @param key - ID of the local storage key
         * @param value - value of the local storage key
         * @returns null|true - returns true if value is set
         */
        setLocalStorageValue: function(key, value) {
            if (this.localStorageExists()) {
                localStorage.setItem(key, value);
                return true;
            }

            return false;
        },

        /**
         * Gets a local storage key based on the key value pair
         *
         * @param key - ID of the local storage key to access
         */
        getLocalStorageValue: function(key) {
            if (this.localStorageExists()) {
                return localStorage.getItem(key);
            }
        },

        /**
         * Returns first word of a long string.
         * @param  string   longString
         * @return string
         */
        firstWord: function(longString) {
            return longString.split(' ')[0];
        },

        /**
         * Capitalizes the first character of a string.
         * @param  String  string
         * @return String
         */
        capitalizeFirstLetter: function(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },

        /**
         * Adapted from php.js converts new lines into BR tags.
         * @param  String    str      String you want to add BR tags too.
         * @return String
         */
        nl2br: function(str) {
            var breakTag = '<br ' + '/>'; //No idea why it needs to be like that.
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }
    };

    /* jshint ignore:start */
    BlueAcornCoreUtils.prototype.Cookie = {

        /**
         * Cookie.get(name) -> String | null
         * - name (String): The name of the cookie you want to fetch.
         *
         * Returns the cookie’s value for the passed name, or +null+ if the cookie
         * does not exist.
         */
        get: function (name) {
            return this.has(name) ? this.list()[name] : null;
        },

        /**
         * Cookie.has(name) -> Boolean
         * - name (String): The name of the cookie you want to test the presence of.
         *
         * Returns whether the cookie for that name exists or not.
         */
        has: function (name) {
            return new RegExp("(?:;\\s*|^)" + encodeURIComponent(name) + '=').test(document.cookie);
        },

        /**
         * Cookie.list([nameRegExp]) -> { name: value[, name: value …]}
         * - nameRegExp (RegExp) an optional `RegExp` to filter cookie names.  If anything but
         *   an actual `RegExp` is passed, this argument is ignored.
         *
         * Returns a key-value dictionary of existing cookies for the current page.
         * Note the ordering of names is basically browser-dependent (as in, JS-engine-dependent).
         */
        list: function (nameRegExp) {
            var pairs = document.cookie.split(';'), pair, result = {};
            for (var index = 0, len = pairs.length; index < len; ++index) {
                pair = pairs[index].split('=');
                pair[0] = pair[0].replace(/^\s+|\s+$/, '');
                if (!this.isRegExp(nameRegExp) || nameRegExp.test(pair[0]))
                    result[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
            }
            return result;
        },

        /**
         * Cookie.remove(name[, options]) -> String
         * - name (String): The name of the cookie you want to remove.
         * - options (Object): An optional set of settings for cookie removal. See Cookie.set for details.
         *
         * Removes the cookie value for the name you passed, honoring potential filtering options.
         * Returns the actual cookie string written to the underlying `document.cookie` property.
         */
        remove: function (name, options) {
            var opt2 = {};
            for (var key in (options || {})) opt2[key] = options[key];
            opt2.expires = new Date(0);
            opt2.maxAge = -1;
            return this.set(name, null, opt2);
        },

        /**
         * Cookie.set(name, value, [, options]) -> String
         * - name (String): The name of the cookie you want to set.
         * - value (Object): The value for the cookie you want to set.  It will undergo a basic `toString()`
         *     transform, so if it's a complex object you likely want to, say, use its JSON representation instead.
         * - options (Object): An optional set of settings for cookie setting. See below.
         *
         * Sets a cookie for the name and value you passed, honoring potential filtering options.
         * Returns the actual cookie string written to the underlying `document.cookie` property.
         *
         * Possible options are:
         *
         * * `path` sets the path within the current domain. Defaults to the current path. Minimum is '/'.
         *   Ignored if blank.
         * * `domain` sets the (sub)domain this cookie pertains to. At the shortest, the current root
         *   domain (e.g. 'example.com'), but can also be any depth of subdomain up to the current one
         *   (e.g. 'www.demo.example.com'). Ignored if blank.
         * * `maxAge` / `max_age` / `max-age` is one way to define when the cookie should expire; this
         *   is a time-to-live in _seconds_. Any of the three keys is accepted, in this order of
         *   decreasing priority (first found key short-circuits the latter ones).
         * * `expires` is the traditional way of setting a cookie expiry, using an absolute GMT date/time
         *   string with an RFC2822 format (e.g. 'Tue, 02 Feb 2010 22:04:47 GMT').  You can also pass
         *   a `Date` object set appropriately, in which case its `toUTCString()` method will be used.
         * * `secure` defines whether the cookie should only be passed through HTTPS connections.  It's
         *   used as `Boolean`-equivalent (so zero, `null`, `undefined` and the empty string are all false).
         */
        set: function (name, value, options) {
            options = options || {};
            var def = [encodeURIComponent(name) + '=' + encodeURIComponent(value)];
            if (options.path) def.push('path=' + options.path);
            if (options.domain) def.push('domain=' + options.domain);
            var maxAge = 'maxAge' in options ? options.maxAge :
                ('max_age' in options ? options.max_age : options['max-age']), maxAgeNbr;
            if ('undefined' != typeof maxAge && 'null' != typeof maxAge && (!isNaN(maxAgeNbr = parseFloat(maxAge))))
                def.push('max-age=' + maxAgeNbr);
            var expires = this.isDate(options.expires) ? options.expires.toUTCString() : options.expires;
            if (expires) def.push('expires=' + expires);
            if (options.secure) def.push('secure');
            def = def.join(';');
            document.cookie = def;
            return def;
        },

        /**
         * Cookie.test() -> Boolean
         *
         * Tests whether cookies are enabled or not.
         */
        test: function () {
            var key = '70ab3d396b85e670f25b93be05e027e4eb655b71', value = 'Cookies are enabled';
            this.remove(key);
            this.set(key, value);
            var result = value == this.get(key);
            this.remove(key);
            return result;
        },

        /**
         * Check for date Object type
         */
        isDate: function(o) {
            return '[object Date]' == Object.prototype.toString.call(o);
        },

        /**
         * Check for regex object type
         */
        isRegExp: function (o) {
            return '[object RegExp]' == Object.prototype.toString.call(o);
        }
    };
    /* jshint ignore:end */

    /**
     * To access use ba.utils
     */
    return new BlueAcornCoreUtils();
}));
