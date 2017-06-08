/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

module.exports = function(grunt) {
    'use strict';

    let path = require('path'),
        themes = require('../configs/_themes'),
        configDir = '../configs',
        helper = require('./_helpers');

    grunt.registerTask('production', 'Compile Theme for Production', function() {
        helper.executeTask(arguments, 'production', grunt);
    });

    grunt.registerTask('prod', 'Compile Theme for Production', function() {
        helper.executeTask(arguments, 'production', grunt);
    });
};
