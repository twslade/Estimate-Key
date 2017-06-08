/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

let fs = require('fs'),
    path = require('path'),
    grunt = require('grunt'),
    themes = require('./_themes'),
    settings = require('./_settings');

    // TODO: ADD Modules modules = require('./_modules');

const combo = {

    /**
     * Get the root path of your application (eg. webroot).
     * @return String Path to root application directory.
     */
    rootPath() {
        return path.join(__dirname, settings.gruntRoot);
    },

    appRootPath() {
        return path.join(__dirname, settings.gruntRoot, settings.appRoot);
    },

    autoPath(themeName, folder) {
        let self = this;
        return path.join(
            settings.appRoot,
            folder,
            themes[themeName].appPath,
            themes[themeName].themePath
        );
    },

    autoPathAssets(themeName) {
        return this.autoPath(themeName, settings.assetDir);
    },

    autoPathApp(themeName) {

    },

    autoPathImages(themeName) {
        return this.autoPathAssets(themeName) + '/images/';
    },

    autoPathImageSrc(themeName) {
        return this.autoPathAssets(themeName) + '/src/';
    },

    autoPathSpriteSrc(themeName) {
        return this.autoPathAssets(themeName) + '/spritesrc/';
    },

    autoPathIntermediateSvg(themeName) {
        return this.autoPathImageSrc(themeName) + 'intermediate-svg';
    },

    autoPathSass(themeName) {
        return this.autoPathAssets(themeName) + '/scss/';
    },

    autoPathCss(themeName) {
        return this.autoPathAssets(themeName) + '/css/';
    },

    getNodeModulesDir() {
        return path.join(__dirname, '../', settings.nodeModulesPath);
    },

    readFiles(path, type= false) {
        let dirFiles = fs.readdirSync(path),
            typeFiles = dirFiles;

        if(type) {
            typeFiles = [];
            dirFiles.forEach((file, idx) => {
                let fileObj = file.split('.');

                if(fileObj[fileObj.length - 1] === type) {
                    typeFiles.push(file);
                }
            });
        }

        return typeFiles;
    },

    getJsDirSubName(jsDir, asFile) {
        let subName = '',
            dirSplit = asFile ? '.' : '';

        if(jsDir !== 'blueacorn' || jsDir.indexOf('blueacorn') === -1) {
            subName = dirSplit + jsDir;
        }else if(jsDir.indexOf('blueacorn') !== -1) {
            subName = jsDir.replace('blueacorn','');
        }

        return subName;
    },

    jsSourceFiles(themeName) {
        let currentTheme = themes[themeName],
            assetsPath = this.autoPathAssets(themeName),
            sourceStringArray = [];

        currentTheme.jsDirs.forEach((jsDir, idx) => {
            sourceStringArray.push(assetsPath + '/js/' + jsDir + '/**/*.js');
        });

        return sourceStringArray;
    },

    jsBuildFiles(themeName) {
        let currentTheme = themes[themeName],
            sourceStringArray = [],
            buildStringArray = [],
            jsFiles = {},
            assetsPath = this.autoPathAssets(themeName);

        currentTheme.jsDirs.forEach((jsDir, idx) => {
            var files = this.readFiles(assetsPath + '/js/' + jsDir + '/', 'js');

            if(files.length) {
                files.forEach((file, idx) => {
                    buildStringArray[idx] = assetsPath + '/build/js/' + jsDir + '/' + file;

                    sourceStringArray[idx] = assetsPath + '/js/' + jsDir + '/' + file;

                    jsFiles[buildStringArray[idx]] = sourceStringArray[idx];
                });
            }
        });

        return jsFiles;
    },

    jsUglifyFiles(themeName) {
        let currentTheme = themes[themeName],
            sourceStringArray = [],
            minStringArray = [],
            jsFiles = {},
            sourceFile = [],
            destFile = [],
            assetsPath = this.autoPathAssets(themeName);

        currentTheme.jsDirs.forEach((jsDir, idx) => {
            let subName = this.getJsDirSubName(jsDir, true);

            minStringArray[idx] = assetsPath + '/jsmin/blueacorn' + subName + '.min.js';

            sourceStringArray[idx] = assetsPath + '/build/js/' + jsDir + '/**/*.js';

            jsFiles[minStringArray[idx]] = sourceStringArray[idx];
        });

        if(currentTheme.bowerJs.length > 0) {
            currentTheme.bowerJs.forEach((bowerFile, idx) => {
                sourceFile[idx] = 'bower_components/' + bowerFile;

                destFile[idx] = assetsPath + '/jsmin/' + path.basename(sourceFile[idx]);

                jsFiles[destFile[idx]] = sourceFile[idx];
            });
        }

        return jsFiles;
    },

    themeFallbackJs(themeName) {
        let themeFallbackIncludes = [],
            currentTheme = themes[themeName],
            assetsPath = this.autoPathAssets(themeName);

        currentTheme.jsDirs.forEach((jsDir, idx) => {
            themeFallbackIncludes.push(assetsPath + '/js/' + jsDir + '/**/*.js');
        });

        currentTheme.themeFallback.forEach((theme, idx) => {
            if(themes[theme].grunt) {
                themes[theme].jsDirs.forEach((jsDir, idx) => {

                    themeFallbackIncludes.push(assetsPath + '/js/' + jsDir + '/**/*.js');
                });
            }
        });

        return themeFallbackIncludes;
    },

    themeFallbackCss(themeName) {
        let themeFallbackIncludes = [],
            assetsPath = this.autoPathAssets(themeName),
            currentTheme = themes[themeName];

        themeFallbackIncludes.push(assetsPath + '/css/**/*.css');

        if(currentTheme.themeFallback.length) {
            currentTheme.themeFallback.forEach((theme, idx) => {
                themeFallbackIncludes.push(this.autoPathAssets(theme) + '/css/**/*.css');
            });
        }

        return themeFallbackIncludes;
    },

    themeFallbackSass(themeName) {
        let themeFallbackIncludes = [],
            assetsPath = this.autoPathAssets(themeName),
            currentTheme = themes[themeName];

        themeFallbackIncludes.push(assetsPath + '/scss/');

        if(currentTheme.bowerFallback.length) {
            currentTheme.bowerFallback.forEach((fallbackFiles, idx) => {
                themeFallbackIncludes.push('bower_components/' + fallbackFiles);
            });
        }

        if(currentTheme.themeFallback.length) {
            currentTheme.themeFallback.forEach((theme, idx) => {
                themeFallbackIncludes.push(this.autoPathAssets(theme) + '/scss/');
            });
        }

        return themeFallbackIncludes;
    },

    themeFallbackSassFiles(themeName) {
        let themeFallbackIncludes = [],
            assetsPath = this.autoPathAssets(themeName),
            currentTheme = themes[themeName];

        themeFallbackIncludes.push(assetsPath + '/scss/**/*.scss');

        if(currentTheme.themeFallback.length) {
            currentTheme.themeFallback.forEach((theme, idx) => {
                themeFallbackIncludes.push(this.autoPathAssets(theme) + '/scss/**/*.scss');
            });
        }

        return themeFallbackIncludes;
    },

    scssFiles(themeName) {
        let currentTheme = themes[themeName],
            assetsPath = this.autoPathAssets(themeName),
            scssStringArray = [],
            cssStringArray = [],
            scssFiles = {};

        if(currentTheme.stylesheets.length) {
            currentTheme.stylesheets.forEach((stylesheet, idx) => {
                cssStringArray[idx] = assetsPath + '/css/' + stylesheet + '.css';

                scssStringArray[idx] = assetsPath + '/scss/' + stylesheet + '.scss';

                scssFiles[cssStringArray[idx]] = scssStringArray[idx];
            });
        }

        return scssFiles;
    },

    autoPrefixerFiles(themeName) {
        let cssStringArray = [],
            assetsPath = this.autoPathAssets(themeName),
            currentTheme = themes[themeName];

        if(currentTheme.stylesheets.length > 0) {
            cssStringArray.push(assetsPath + '/css/**/*.css');

            if(currentTheme.autoPrefixerBlackList.length > 0) {
                currentTheme.autoPrefixerBlackList.forEach((file, idx) => {
                    cssStringArray.push('!' + assetsPath + '/css/' + file + '.css');
                });
            }
        }

        return cssStringArray;
    },

    getLiveReload(themeName) {
        let lr = true;

        if(grunt.option('livereload') && typeof grunt.option('livereload') === 'string') {
            if(themeName !== grunt.option('livereload')) {
                lr = false;
            }
        }

        return lr;
    }
};

module.exports = combo;
