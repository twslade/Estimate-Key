/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

let combo = require('./_combo'),
    themes = require('./_themes'),
    themeOptions = {},
    uglifyOptions = {
        options: {
            mangle: false,
            beautify: true,
            compress: false,
            sourceMap: false,
            wrap: false
        }
    };

for(let name in themes) {
    let theme = themes[name];

    if(theme.grunt && theme.jsDirs.length) {

        themeOptions[name + 'Dev'] = {
            files: combo.jsUglifyFiles(name)
        };

        themeOptions[name + 'Production'] = {
            options: {
                mangle: false,
                compress: true,
                beautify: false,
                sourceMap: false,
                wrap: false
            },
            files: combo.jsUglifyFiles(name)
        };
    }
}

module.exports = Object.assign(uglifyOptions, themeOptions);
