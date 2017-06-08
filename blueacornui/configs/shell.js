/**
 * @package     BlueAcorn/GreenPistachio
 * @version     4.6.0
 * @author      Blue Acorn, Inc. <code@blueacorn.com>
 * @copyright   Copyright © 2017 Blue Acorn, Inc.
 */

'use strict';

var combo = require('./_combo');

module.exports = {
    cache: {
        command: [
            'cd ' + combo.appRootPath(),
            'n98-magerun.phar cache:flush'
        ].join('&&')
    }
};
