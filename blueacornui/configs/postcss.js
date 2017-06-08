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
    sassOptions = {};

for(let name in themes) {
    let theme = themes[name];

    if(theme.grunt) {

        themeOptions[name + 'Dev'] = {
            options: {
                map: apOptions.dev.map,
                processors: [
                    require('autoprefixer')({
                        browsers: apOptions.dev.options.browsers,
                        map: apOptions.dev.options.map,
                        add: true,
                        remove: true
                    })
                ]
            },
            src: combo.autoPrefixerFiles(name)
        };

        themeOptions[name + 'Production'] = {
            options: {
                map: apOptions.production.map,
                processors: [
                    require('autoprefixer')({
                        browsers: apOptions.production.options.browsers,
                        map: apOptions.production.options.map,
                        add: true,
                        remove: true
                    })
                ]
            },
            src: combo.autoPrefixerFiles(name)
        };

    }
}

module.exports = themeOptions;
