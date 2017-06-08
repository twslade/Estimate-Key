/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

let combo = require('./_combo'),
    themes = require('./_themes'),
    themeOptions = {};

for(let name in themes) {
    let theme = themes[name];

    if(theme.grunt) {

        themeOptions[name + 'Sass'] = {
            files: combo.themeFallbackSassFiles(name),
            tasks: ['css:' + name],
            options: {
                interupt: true,
                reload: true,
                livereload: false
            }
        };

        themeOptions[name + 'Js'] = {
            files: combo.themeFallbackJs(name),
            tasks: ['js:' + name],
            options: {
                interupt: true,
                reload: true,
                livereload: false
            }
        };

        themeOptions[name + 'Images'] = {
            files: combo.autoPathImageSrc(name),
            tasks: ['img:' + name],
            options: {
                interupt: true,
                reload: true,
                livereload: false
            }
        },

        themeOptions[name + 'Css'] = {
            files: combo.themeFallbackCss(name),
            options: {
                livereload: combo.getLiveReload(name)
            }
        };

    }
}

module.exports = themeOptions;
