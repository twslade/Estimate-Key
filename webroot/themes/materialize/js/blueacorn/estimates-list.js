(function (root, factory) {
    root.ba.moduleLoader.define('EstimateList', ['jquery', 'enquire'], factory);
}(this, function($, enquire) {
    'use strict';

    function EstimateList() {
        var self = this;

        $(document).ready(function() {
            self.init();
        });
    }

    EstimateList.prototype = {
        init() {
            this.platforms = $('.label.platform');
            this.roles = $('.label.role');
            this.skills = $('.label.skill');

            this.addPlatformChips();
            this.addRoleChips();
            this.addSkillChips();
        },

        addPlatformChips() {
            $.each(this.platforms, function(idx, el) {
                $(el).find('.simple').addClass('chip');
            });
        },

        addRoleChips() {
            $.each(this.roles, function(idx, el) {
                $(el).find('.simple').addClass('chip').text($(el).data('abbr'));
            });
        },

        addSkillChips() {
            $.each(this.skills, function(idx, el) {
                $(el).find('.simple').addClass('skill-chip').html('<span>' + $(el).text() + '</span>');
            });
        }
    };

    return new EstimateList();
}));
