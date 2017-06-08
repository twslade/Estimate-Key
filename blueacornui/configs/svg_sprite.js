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
    let theme = themes[name];

    if(theme.grunt) {

        themeOptions[name] = {
            expand: true,
            cwd: combo.autoPathSpriteSrc(name),
            src: ['**/*.svg'],
            dest: combo.autoPathImageSrc(name),
            options: {
                svg: {
                    xmlDeclaration: true,
                    doctypeDeclaration: true,
                    namespaceIDs: true,
                    namespaceClassnames: true,
                    dimensionAttributes: true,
                    precision: 3,
                },
                shape: {
                    dimension: {
                        maxWidth: 500,
                        maxHeight: 500
                    },
                    spacing: {
                        padding: 0
                    },
                    dest: '../src/intermediate-svg'
                },
                mode: {
                    symbol: {
                        dest: '../css/',
                        prefix: '.svg-',
                        bust: false,
                        sprite: '../src/sprites.svg',
                        render: {
                            scss: {
                                dest: '../scss/_sprites.scss'
                            }
                        }
                    },
                    view: {
                        dest: '../css/',
                        prefix: '.svg-view-',
                        bust: false,
                        sprite: '../src/sprites.view.svg',
                        mixin: 'svg-view',
                        common: 'svg-view',
                        render: {
                            scss: {
                                dest: '../scss/_sprites-view.scss',
                                template: path.join(__dirname, '../assets/tmpl/_sprite-mixins.scss')
                            }
                        }
                    }
                }
            }
        };

    }
}

module.exports = themeOptions;
