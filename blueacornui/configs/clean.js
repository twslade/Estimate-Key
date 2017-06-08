/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

let combo = require('./_combo'),
    themes = require('./_themes'),
    path = require('path'),
    themeOptions = {};

for(let name in themes) {
    let theme = themes[name],
        assetsPath = combo.autoPathAssets(name);

    if(theme.grunt) {

        themeOptions[name] = {
                options: {
                    force: true
                },
                src: [
                    assetsPath + '/jsmin/',
                    assetsPath + '/build/js/'
                ]
        };
    }
}

module.exports = themeOptions;
