/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

let taskList = require('./_taskList'),
    themes = require('../configs/_themes');

module.exports = {

    executeTask(args, type, grunt) {
        if(args[0]) {
            this.runTasks(args[0], type, grunt);
        }else{
            for(let theme in themes) {
                let currentTheme = themes[theme];

                currentTheme.grunt && this.runTasks(theme, 'compile', grunt);
            }
        }
    },

    runTasks(themeName, type, grunt) {
        let currentTheme = themes[themeName];

        for (let obj in taskList[type]) {

            for(let task in taskList[type][obj]) {

                // TODO
                if(task === 'copy') {
                    if(currentTheme.jsDirs.length > 0) {
                        for(let jsDir in currentTheme.jsDirs) {
                            for(let taskValue in task) {
                                grunt.task.run(task + ':' + themeName + taskValue + jsDir);
                            }
                        }
                    }
                }else{
                    grunt.task.run(task + ':' + themeName + taskList[type][obj][task]);
                }
            }
        }
    }
};
