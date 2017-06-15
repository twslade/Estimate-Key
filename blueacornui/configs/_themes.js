/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

/**
 * Contains Configuration information for all themes within your
 * build.
 * @type Object
 */
var themes = {
    site: {
        grunt: true,
        appPath: '',
        themePath: 'materialize',
        stylesheets: [
            'custom',
            'materialize.bower'
        ],
        stylesheetsSourceLanguage: 'scss',
        bowerFallback: [
            'materialize/sass/',
        ],
        bowerJs: [
            'materialize/dist/js/materialize.js',
            'jquery/dist/jquery.js',
            'enquire/dist/enquire.min.js',
            'typeahead.js/dist/typeahead.bundle.js'
        ],
        autoPrefixerBlackList: [],
        jsDirs: [
            'blueacorn'
        ],
        themeFallback: []
    }
};

module.exports = themes;
