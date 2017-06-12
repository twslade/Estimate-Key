<?php
class FieldsAdmin extends ModelAdmin {
    private static $managed_models = array(
        'Client',
        'Skill',
        'Role',
        'Platform',
        'RiskType',
        'Category'
    );

    private static $url_segment = 'fields';
    private static $menu_title = 'Fields';
}
