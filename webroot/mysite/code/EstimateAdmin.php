<?php
class EstimateAdmin extends ModelAdmin {
    private static $managed_models = array(
        'Estimate',
        'Risk',
        'Story'
    );

    private static $url_segment = 'estimates';
    private static $menu_title = 'Estimates';
}
