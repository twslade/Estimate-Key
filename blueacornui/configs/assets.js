/**
* @package     BlueAcorn/GreenPistachio
* @version
* @author      Blue Acorn, Inc. <code@blueacorn.com>
* @copyright   Copyright © 2016 Blue Acorn, Inc.
*/

'use strict';

var path = require('path'),
    themes = require('./_themes'),
    settings = require('./_settings'),
    combo = require('./_combo');

module.exports = {
    getAssets: function(normalizePath) {
        var assets = [],
            self = this;

        for(var i in themes){
            if(themes[i].grunt === true){
                assets = this.concatNarrays(assets, combo.jsMinFiles(i), combo.cssFiles(i));
            }
        }

        return normalizePath ? assets.map(this.normalizePath) : assets;
    },

    getAssetDirs: function() {
        var dirs = [];

        for(var i in themes){
            if(themes[i].grunt === true){
                dirs.push(combo.autoPathAssets(i) + "/css");
                dirs.push(combo.autoPathAssets(i) + "/jsmin");
            }
        }

        return dirs.map(this.normalizePath);
    },

    normalizePath: function(pathToFile) {
        return pathToFile.replace(settings.appRoot, "");
    },

    concatNarrays: function(args) {
        args = Array.prototype.slice.call(arguments);
        var newArr = args.reduce(function(prev, next) {
            return prev.concat(next) ;
        });

        return newArr;
    }
};
