<?php

class Seeder extends \Seeder\Provider {

    public static $shorthand = 'Seeder';

    protected $_skills = array(
        'Javascript',
        'PHP',
        'SOAP',
        'REST',
        'CSS',
        'Node',
        'XSLT',
        'Code Profiling',
        'Server Configuration',
        'Optimizely',
        'LESS',
        'SASS',
        'Grunt',
        'Docker',
        'Mysql',
        'Load Testing',
        'Rhino',
        'HTML'
    );


    protected $_estimateNames = array(
        'Additional Updates to Affirm',
        'Replace Gigya with Native Reviews',
        'Newsletter Signup Welcome Email',
        'Criteo Redirect Tag',
        'Category Page',
        'Implement Cloudfront CDN',
        'Server tuneup',
        'Apply Maintenance Flag switch from Admin',
        'Product positioning threshold change',
        'Product Page',
        'Home Page',
        'Add sitewide advanced GA tagging',
        'My Account Link and Page',
        'Search/Input and Results',
        '301 redirects import',
        'Elite SEO Optimization',
        'Install Product Map',
        'Implement Mobile Checkout Test Permanently',
        'New Server Configuration',
        'Install URF Scheduler',
        'Revert Checkout to Native Magento',
        'Website Creation',
        'Install Amasty Layered Navigation',
        'Security Patch Install SUPEE-9767',
        'Code Audit',
        'Systems Audit',
        'Performance Optimization Category Page',
        'Teamwork integration',
        'Third party module install',
        'Perform Custom Landing Page',
        'Become a customer button',
        'Product Social Sharing',
        'Security Patch Install SUPEE-9652',
        'Funnel only tagging',
        'Prohibit Duplicate Customer Numbers',
        'Savings Story',
        'Exact Target',
        'Header and Footer Theming',
        'Revisit all pages for social Annex',
        'Review splotlights and sorting change order',
        'Ajax compare products',
        'Employee payment method',
        'Refactor Social Login'
    );

    protected function generateField($field, $state)
    {
        if(array_key_exists('arguments', $field->options) && array_key_exists(0, $field->options['arguments'])){
            $funcName = $field->options['arguments'][0];
        } else {
            $funcName = $field->name;
        }

        $func = 'get' . $funcName;
        if(method_exists($this, $func)){
            return $this->$func($state);
        } else {
            throw new Exception('Function ' . $func . ' does not exist');
        }
    }

    protected function getRomLow($state){
        return rand(1, 10) * 10;
    }

    protected function getRomHigh($state){
        if($state->object()->RomLow){
            return (((int) $state->object()->RomLow)) * 1.5;
        } else {
            return rand(1, 15) * 10;
        }
    }

    protected function getName($state){
        return ucwords($this->_estimateNames[array_rand($this->_estimateNames)]);

    }

    protected function getSkill($state){
        return $this->_skills[array_rand($this->_skills)];
    }


}