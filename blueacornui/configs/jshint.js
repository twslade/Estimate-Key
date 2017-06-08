/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

let combo = require('./_combo'),
    themes = require('./_themes'),
    settings = require('./_settings'),
    rootPath = combo.rootPath(),
    themeOptions = {},
    jshintOptions = {
        options: {
            jshintrc: rootPath + '.jshintrc'
        }
    };

for(let name in themes) {
    let theme = themes[name],
        fileOptions = [
            rootPath + 'Gruntfile.js',
            rootPath + 'configs/**/*.js',
            rootPath + 'tasks/**/*.js'
        ],
        sourceFiles;

    if(theme.grunt && theme.jsDirs.length) {

        sourceFiles = combo.jsSourceFiles(name);

        themeOptions[name] = {
            files: {
                src: fileOptions.concat(sourceFiles)
            }
        };

    }
}

module.exports = Object.assign(jshintOptions, themeOptions);
