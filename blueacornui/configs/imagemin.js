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

        themeOptions[name] = {
            expand: true,
            cwd: combo.autoPathImageSrc(name),
            src: ['**/*.{png,jpg,gif}'],
            dest: combo.autoPathImages(name)
        };

    }
}

module.exports = themeOptions;
