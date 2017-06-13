/**
 * @package     Blueacorn/ModuleLoader
 * @version     1.0
 * @author      Blue Acorn <code@blueacorn.com>
 * @copyright   Copyright © 2015 Blue Acorn.
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
         root.ba.moduleLoader = factory(root.jQuery, root.ba, root.enquire);
     }

 })(this, function ($, ba, enquire) {

    function ModuleLoader(options) {
        this.init(options);
    }

    ModuleLoader.prototype = {
        init: function (options) {
            this.settings = {
                'moduleName': 'ModuleLoader'
            };

            // Object to store module declarations
            this.modules = {};

            // Internal Data Storage
            this.data = {
                predefined: [],
                started: false
            };

            // Overrides the default settings
            ba.overrideSettings(this.settings, options);

            // Start the debugger
            ba.setupDebugging(this.settings);

            this.setupObservers();
        },

        /**
         * On ba.ready, start module loading
         */
        setupObservers: function() {
            var self = this;

            ba.ready(function() {
                self.data.ready = true;

                self.data.predefined.forEach(function(module){
                    self.require(module);
                });
            });
        },

        /**
         * Add module to be loaded
         *
         * @param moduleName
         * @param dependencies
         * @param moduleScope
         */
        define: function(moduleName, dependencies, moduleScope) {
            // If 2nd argument is a function
            if (typeof dependencies === 'function'){
                moduleScope = dependencies;
                dependencies = [];
            }

            if (!this.modules[moduleName]) {
                this.modules[moduleName] = {
                    name: moduleName,
                    dependencies: dependencies,
                    moduleScope: moduleScope,
                    loaded: false
                };

                if (this.data.ready) {
                    this.require(this.modules[moduleName]);
                } else {
                    this.data.predefined.push(this.modules[moduleName]);
                }
            }
        },

        /**
         * Get result of all dependencies before loading the module
         *
         * @param module
         */
        require: function(module) {
            // Resolve scoping issues in for each loop
            var self = this;

            // If we have dependencies, load them first
            if (!!module.dependencies.length) {
                module.dependencies.forEach(function(dependency) {
                    // If we're trying to load something that doesn't exist, we wont need to require it
                    self.modules[dependency] && self.require(self.modules[dependency]);
                });
            }

            // If the module isnt already loaded, load it.
            if (!this.modules[module.name].loaded) {
                this.modules[module.name].loaded = true;
                this.modules[module.name].result = this.load(module);
            }
        },

        /**
         * Call module with dependencies injected as arguments
         *
         * @param module
         * @returns {*}
         */
        load: function(module) {
            var self = this,
                dependencyInstances = module.dependencies.map(function(dependency) {
                    return self.modules[dependency] && self.modules[dependency].result;
                });

            // Call the module, with the instances of the dependencies set up
            return module.moduleScope.apply(this, dependencyInstances);
        },

        /**
         * Utility method used for easier reference of module content
         *
         * @param module
         * @returns module prototype
         */
        get: function(module) {
            return this.modules[module].result;
        }
    };

    /**
     * The parameter object is optional.
     * Must be an object.
     */
    var moduleLoader = new ModuleLoader({});

    moduleLoader.define('jquery', function() {
        return jQuery;
    });

    moduleLoader.define('ba', function() {
        return ba;
    });

    moduleLoader.define('enquire', function() {
        return enquire;
    });

    return moduleLoader;
});
