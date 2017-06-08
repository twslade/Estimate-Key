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
    sassOptions = {};

for(let name in themes) {
    let theme = themes[name];

    if(theme.grunt && theme.stylesheetsSourceLanguage === 'scss' && theme.stylesheets) {

        themeOptions[name + 'Dev'] = {
            options: {
                sourceMap: true,
                sourceMapEmbed: false,
                includePaths: combo.themeFallbackSass(name)
            },
            files: combo.scssFiles(name)
        };

        themeOptions[name + 'Production'] = {
            options: {
                sourceMap: false,
                sourceMapEmbed: false,
                outputStyle: 'compact',
                includePaths: combo.themeFallbackSass(name)
            },
            files: combo.scssFiles(name)
        };

    }
}

sassOptions = {
    options: {
        sourceComments: false,
        outputStyle: 'expanded',
        precision: 4,
        sourceMapContents: true
    }
};

module.exports = Object.assign(themeOptions, sassOptions);
