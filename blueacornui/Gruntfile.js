/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

const gruntSetup = function(grunt) {
    'use strict';

    let _ = require('underscore'),
        path = require('path'),
        configDir = './configs',
        taskDir = './tasks',
        tasks = [
            // 'compile',
            // 'staging',

            // 'qc',
            // 'githooks',
            // 'payload'
            '/compile',
            '/css',
            '/img',
            '/js',
            '/production',
        ],
        defaultTasks;

        /**
         * Instantiate `time-grunt` to display task performance.
         */
        require('time-grunt')(grunt);

        /**
         * Register each task with grunt.
         * TODO: Create once tasks have been created.
         */
        tasks.forEach(function(task, idx) {
            require(taskDir + task)(grunt);
        });

        /**
         * Instantiate `load-grunt-config` to automatically load
         * configuration files from `configDir`.
         */
        require('load-grunt-config')(grunt, {
            configPath: path.join(__dirname, configDir),
            init: true,
            jitGrunt: {
                staticMappings: {
                    browsersync: 'browserSync'
                }
            }
        });

        defaultTasks = {

            // Default Task that Runs grunt-watch
            default() {
                grunt.task.run('watch');
            },

            // TODO: SETUP TASK
            // TODO: SYNC TASK
        };

        for(let taskName in defaultTasks) {
            grunt.registerTask(taskName, defaultTasks[taskName]);
        }
};

module.exports = gruntSetup;
