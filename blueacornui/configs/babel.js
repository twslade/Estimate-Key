/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

let combo = require('./_combo'),
    themes = require('./_themes'),
    apOptions = require('./_autoprefixer'),
    themeOptions = {},
    babelOptions = {
        options: {
            presets: [
                [
                    combo.getNodeModulesDir() + '/babel-preset-env',
                    {
                        target: {
                            browsers: apOptions.dev.browsers
                        },
                        debug: false,
                        modules: false
                    }
                ]
            ],
            minified: true,
        }
    };

for(let name in themes) {
    let theme = themes[name];

    if(theme.grunt && theme.jsDirs.length) {

        themeOptions[name + 'Dev'] = {
            options: {
                sourceMap: true,
                sourceType: 'script',
                compact: false,
                minified: false,
            },
            files: combo.jsBuildFiles(name)
        };

        themeOptions[name + 'Production'] = {
            options: {
                sourceMap: false,
                minified: true,
                compact: true,
                comments: false
            },
            files: combo.jsBuildFiles(name)
        };
    }
}

module.exports = Object.assign(babelOptions, themeOptions);
