<?php

class Estimate extends DataObject {

	private static $db = array(
        'Name'                      => 'Varchar(255)',
        'Description'               => 'HTMLText',
        'BusinessRequirements'      => 'HTMLText',
        'FunctionalRequirements'    => 'HTMLText',
        'TechnicalApproach'         => 'HTMLText',

        'RomLow'                    => 'Int',
        'RomHigh'                   => 'Int',

        'BudgetConfidence'          => 'Varchar(100)',
	    'ScheduleConfidence'        => 'Varchar(100)',
	    'TechnicalConfidence'       => 'Varchar(100)'
	);

    private $_confidenceLevels = array(
        'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low',
    );

    private static $indexes = array(
        'SearchFields' => array(
            'type' => 'fulltext',
            'name' => 'SearchFields',
            'value' => '"Name", "Description", "BusinessRequirements", "FunctionalRequirements", "TechnicalApproach"'
        )
    );

    private static $create_table_options = array(
        'MySQLDatabase' => 'Engine=MyISAM'
    );

    private static $many_many = array(
        'Risks' => 'Risk',
        'Clients' => 'Client',
        'Stories' => 'Story',
        'Platforms' => 'Platform',
        'Files' => 'File',
        'Categorys' => 'Category',
        'Actuals' => 'Actual'
    );

    private static $has_one = array(
        'EstimatePage' => 'EstimatePage'
    );

    /** @var FieldList */
    private $_estimateFields = null;

    /**
     * @return FieldList
     */
    private function _getFields(){
        if($this->_estimateFields){
            return $this->_estimateFields;
        }
        $this->_estimateFields = parent::getCMSFields();
        $this->_estimateFields = FieldList::create(TabSet::create('Root'));
        return $this->_estimateFields;
    }

    private function _getMainTab(){
        $this->_getFields()->addFieldsToTab('Root.Main', array(
            TextField::create('Name'),
            TextField::create('RomLow', 'Lower Rom Range'),
            TextField::create('RomHigh', 'Higher Rom Range'),
            ReadonlyField::create('Total Hours')->setValue($this->TotalHours()),
            HtmlEditorField::create('Description')->setRows(5),
            ListboxField::create('Platforms', 'Platform')
                ->setSource(Platform::get()->map('ID', 'Name')->toArray())
                ->setMultiple(true),
            ListboxField::create('Clients', 'Client')->setSource(
                Client::get()->map('ID', 'Name')->toArray()
            )->setMultiple(true)
        ));

        return;
    }

    private function _getRequirementTab(){
        $this->_getFields()->addFieldsToTab('Root.Requirements', array(
            HtmlEditorField::create('BusinessRequirements')->setRows(5),
            HtmlEditorField::create('FunctionalRequirements')->setRows(5),
        ));

        return;
    }

    private function _getTechnicalTab(){
        $gridField = new GridField('Risks', 'Risk', $this->Risks());

        $gridField->setConfig(
            GridFieldConfig_RelationEditor::create()
        );

        $this->_getFields()->addFieldsToTab('Root.Technical', array(
            HtmlEditorField::create('TechnicalApproach')->setRows(5),
            DropdownField::create(
                'BudgetConfidence',
                'Budget Confidence',
                $this->_confidenceLevels)
                ->setEmptyString('-- Select a level --'),
            DropdownField::create(
                'ScheduleConfidence',
                'Schedule Confidence',
                $this->_confidenceLevels),
            DropdownField::create(
                'TechnicalConfidence',
                'Technical Confidence',
                $this->_confidenceLevels),
            ListboxField::create('Categorys', 'Category')
                ->setSource(Category::get()->map('ID', 'Name')->toArray())
                ->setMultiple(true),
            $gridField

        ));

        return;
    }

    private function _getStoryTab(){


        /** @var GridFieldConfig_RelationEditor $storiesConfig */
        $storiesConfig = GridFieldConfig_RelationEditor::create()
        ->addComponent(new GridFieldDataColumns());
        //@todo: Add line items within column as subtable
        $storiesConfig
            ->getComponentByType('GridFieldAddExistingAutocompleter')
            ->setSearchFields(array('Name'))
            ->setResultsFormat('$getClients - $Name - $TotalHours');



        //@todo: Add total hours in search results

        $gridField = new GridField('Stories', 'Story', $this->Stories(), $storiesConfig);

        //Add total hours per story
        $storiesConfig->getComponentByType('GridFieldDataColumns')->setDisplayFields(array(
            'Name' => 'Name',
            'getTotalHours' => 'Total Hours'

        ));
        $this->_getFields()->addFieldsToTab('Root.Stories', array(
            $gridField,
            ReadonlyField::create('Total Hours')->setValue($this->TotalHours())
            ));

        return;
    }

    private function _getArtifactsTab(){
        $this->_getFields()->addFieldsToTab('Root.Artifacts', array(
            new UploadField(
                $name = 'Files',
                $title = 'Upload file'
            )
        ));

        return;
    }

    private function _getActualsTab(){


        $actuals = new GridField('Actuals', 'Actuals', $this->Actuals());

        /** @var $LineItems GridFieldDataColumns */
        $actuals->getConfig()
            ->addComponent(new GridFieldButtonRow('before'))
            ->addComponent(new GridFieldEditableColumns())
            ->addComponent(new GridFieldDeleteAction())
            ->addComponent(new GridFieldDataColumns())
            ->addComponent(new GridFieldAddNewInlineButton())
            ->getComponentByType('GridFieldEditableColumns')
            ->setDisplayFields(array(
                'LegacyURL' => array(
                    'title' => 'Legacy URL',
                    'callback' => function($record, $column, $grid) {
                        return new TextField('LegacyURL', 'Legacy Workfront URL');
                    },
                ),
                'WorkfrontRef' => array(
                    'title' => 'WorkFront Reference',
                    'callback' => function($record, $column, $grid) {
                        return new TextField('WorkfrontRef', 'Workfront Reference');
                    }
                ),
                'Hours' => array(
                    'title' => 'Hours',
                    'callback' => function($record, $column, $grid) {
                        return new TextField('Hours', 'Actual Hours');
                    }
                )
        ));

        $this->_getFields()->addFieldsToTab('Root.Actuals', array(
            $actuals
        ));

        return;
    }

    public function getCMSFields()
    {

        Requirements::javascript( 'mysite/js/total-hours.js');
        $this->_getMainTab();
        $this->_getRequirementTab();
        $this->_getTechnicalTab();
        $this->_getStoryTab();
        $this->_getArtifactsTab();
        $this->_getActualsTab();

        return $this->_getFields();
    }

    public function TotalHours(){
        $hours = 0;
        foreach ($this->Stories() as $story){
            foreach ($story->LineItems() as $lineItem){
                $hours += $lineItem->NumHours;
            }
        }
        return $hours;
    }

    public function Link(){
            return $this->EstimatePage()->Link('estimate/view/' . $this->ID);
    }

    private function _getConfidenceLevel($idx){
        return array_key_exists($idx, $this->_confidenceLevels) ? $this->_confidenceLevels[$idx] : '';
    }

    public function canView($member = null){
        return true;
    }

}

class Actual extends DataObject{

    const ISSUE_IDENTIFIER = 'issue';
    const TASK_IDENTIFIER = 'task';
    const WORKFRONT_BASE_URL = 'https://blueacorn.my.workfront.com/';


    private static $db = array(
        'LegacyURL' => 'Varchar(255)',
        'WorkfrontRef' => 'Varchar(255)',
        'Hours' => 'Varchar(255)'
    );

    private static $belongs_many_many = array(
        'Estimates' => 'Estimate'
    );

    public function isIssue($link){
        return strpos(self::ISSUE_IDENTIFIER, $link) !== false;
    }

    public function isTask($link){
        return strpos(self::TASK_IDENTIFIER, $link) !== false;
    }

    public function getWorkFrontBaseUrl($type = self::TASK_IDENTIFIER){
        return self::WORKFRONT_BASE_URL . $type . '/view?ID=';
    }

    public function getWorkFrontTaskLink($uuid = ''){
        return $this->getWorkFrontBaseUrl(self::TASK_IDENTIFIER) . $this->WorkfrontRef;
    }

    public function getWorkFrontIssueLink($uuid = ''){
        return $this->getWorkFrontBaseUrl(self::ISSUE_IDENTIFIER) . $this->WorkfrontRef;
    }

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('WorkfrontRef'),
            TextField::create('Hours')
        );

        return $fields;
    }
}

/**
 * Defines the platforms used, for examples,
 * Magento 1, Sales Force Commerce Cloud, etc
 *
 * Class Platform
 */
class Platform extends DataObject {
    private static $db = array(
        'Name' => 'Varchar(255)'
    );

    private static $belongs_many_many = array(
        'Estimates' => 'Estimate'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name')
        );

        return $fields;
    }
}

/**
 * Defines client name and code, for example, XYZ
 *
 * Class Client
 */
class Client extends DataObject {
    private static $db = array(
        'Name'                      => 'Varchar(255)',
        'Code'                      => 'Varchar'
    );

    private static $belongs_many_many = array(
        'Estimates' => 'Estimate'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name'),
            TextField::create('Code')
        );

        return $fields;
    }
}

/**
 * Defines risks associated with work
 *
 * Class Risk
 */
class Risk extends DataObject {
    private static $db = array(
        'Name'                      => 'Varchar',
        'Description'               => 'HTMLText',
        'MitigationPlan'            => 'HTMLText',
        'MitigationCost'            => 'Int',
        'PotentialCost'             => 'Int',
        'Probability'               => 'Int'

    );

    private $_riskProbabilities = array('High', 'Med', 'Low');

    private static $belongs_many_many = array(
        'Estimates' => 'Estimate'
    );

    private static $many_many = array(
        'RiskTypes' => 'RiskType'
    );

    private static $searchable_fields = array(
        'Name',
        'Description'
    );

    public static $summary_fields = array(
        'Name' => 'Name',
        'Type' => 'Type',
        'Probability' => 'Probability',
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name'),
            ListboxField::create('RiskTypes', 'Risk Types')
                ->setSource(RiskType::get()->map('ID', 'Name')->toArray())
                ->setMultiple(true),
            DropdownField::create('Probability')->setSource($this->_riskProbabilities),
            HtmlEditorField::create('Description'),
            HtmlEditorField::create('MitigationPlan'),
            TextField::create('MitigationCost'),
            TextField::create('PotentialCost')
        );

        return $fields;
    }
}

/**
 * Defines risk type, for example,
 * high, medium, or low
 *
 * Class RiskType
 */
class RiskType extends DataObject {
    private static $db = array(
        'Name' => 'Varchar(255)',
    );

    private static $belongs_many_many = array(
        'Risks' => 'Risk'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name')
        );

        return $fields;
    }
}

/**
 * Defines roles, for example,
 * Applications Engineer, Quality Engineer, etc
 *
 * Class Role
 */
class Role extends DataObject {
    private static $db = array(
        'Name' => 'Varchar(255)'
    );

    private static $belongs_many_many = array(
        'LineItems' => 'LineItem'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name')
        );

        return $fields;
    }
}

/**
 * Defines skillsets that can be used to determine who can do the work,
 * for example, javascript, php, html, css, etc
 *
 * Class Skill
 */
class Skill extends DataObject {

    private static $db = array(
        'Name' => 'Varchar(255)'
    );

    private static $belongs_many_many = array(
        'LineItems' => 'LineItem'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name')
        );

        return $fields;
    }
}

class Category extends DataObject {

    private static $db = array(
        'Name' => 'Varchar(255)'
    );

    private static $belongs_many_many = array(
        'Estimates' => 'Estimate'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('Name')
        );

        return $fields;
    }
}

/**
 * Defines stories, for example,
 * Install module or Create integration skeleton
 *
 * Class Story
 */
class Story extends DataObject {
    private static $db = array(
        'Name' => 'Varchar(255)',
    );

    private $searchable_fields = array('Name');

    private static $belongs_many_many = array(
        'Estimates' => 'Estimate'
    );

    private static $many_many = array(
        'LineItems' => 'LineItem'
    );

    public function getTotalHours(){
        $totalHours = 0;
        foreach($this->LineItems() as $lineItem){
            $totalHours += (int) $lineItem->NumHours;
        }
        return $totalHours;
    }

    public function getClients(){
        $clients = array();
        foreach ($this->Estimates() as $estimate) {
            foreach ($estimate->Clients() as $client) {
                array_push($clients, $client->Code);
            }
        }
        return implode(',', array_unique($clients));
    }

    public function getCMSFields()
    {
        $lineItems = new GridField('LineItems', 'Line Items', $this->LineItems());

        /** @var $LineItems GridFieldDataColumns */
        $lineItems->getConfig()
            ->addComponent(new GridFieldButtonRow('before'))
            ->addComponent(new GridFieldEditableColumns())
            ->addComponent(new GridFieldDeleteAction())
            ->addComponent(new GridFieldAddNewInlineButton())
            ->getComponentByType('GridFieldEditableColumns')
            ->setDisplayFields(array(
            'NumHours' => array('title' => 'Hours', 'callback' => function($record, $column, $grid) {
                return new TextField('NumHours', 'Number of hours');
            }),
            'Description' => array('title' => 'Description', 'callback' => function($record, $column, $grid) {
                return new TextField('Description', 'Description');
            }),
            'Roles' => array('title' => 'Roles', 'callback' => function($record, $column, $grid) {
                return ListboxField::create($column)
                    ->setMultiple(true)
                    ->setValue(($record->ID && get_class($record) == 'LineItem') ? $record->Roles()->column('ID') : '')
                    ->setSource(Role::get()->map('ID', 'Name')->toArray());
            }),
            'Skills' =>  array('title' => 'Skills', 'callback' => function($record, $column, $grid) {
                return ListboxField::create($column)
                    ->setMultiple(true)
                    ->setValue(($record->ID && get_class($record) == 'LineItem') ? $record->Skills()->column('ID') : '')
                    ->setSource(Skill::get()->map('ID', 'Name')->toArray());
            })
        ));


        //@todo: Add additional field for summing line items
        $fields = FieldList::create(
            TextField::create('Name'),
            $lineItems,
            ReadonlyField::create('Total Hours')->setValue(
                $this->getTotalHours()
            )
        );

        return $fields;
    }

    public function TotalHours(){
        $hours = 0;
        foreach ($this->LineItems() as $lineItem){
            $hours += $lineItem->NumHours;
        }

        return $hours;
    }
}

/**
 * Defines line items which make up story.
 * Line items contains number of hours and description of work
 * For example, 1 Install module
 *
 * Class LineItem
 */
class LineItem extends DataObject {
    private static $db = array(
        'NumHours' => 'Int',
        'Description' => 'Varchar(255)'
    );

    private static $many_many = array(
        'Roles' => 'Role',
        'Skills' => 'Skill'
    );

    private static $belongs_many_many = array(
        'Stories' => 'Story'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('NumHours'),
            TextField::create('Description'),
            ListboxField::create('Roles', 'Role')
                ->setSource(Role::get()->map('ID', 'Name')->toArray())
                ->setMultiple(true),
            ListboxField::create('Skills', 'Skill')
                ->setSource(Skill::get()->map('ID', 'Name')->toArray())
                ->setMultiple(true)

        );

        return $fields;
    }


}

class Page extends SiteTree {
    private static $db = array();
    private static $has_one = array();


    public function getCssClass($arg) {
        $ret = '';
        if(is_array($arg)){
            foreach ($arg as $str){
                $ret = str_replace(' ', '-', strtolower($str)) . ' ';
            }
        } else {
            $ret = str_replace(' ', '-', strtolower($arg)) . ' ';
        }
        return $ret;
    }

    public function getRoleAbbr($arg) {
        preg_match_all('/(?<=[\s,_-]|^)[a-z]/i', $arg, $matches);
        $result = implode('', $matches[0]);

        return strtoupper($result);
    }

}
class Page_Controller extends ContentController {

    /**
     * List of layered Nav classes
     *
     * @var array
     */
    protected $_leftNavClasses =  array(
        'Platform',
        'Client',
        'Role',
        'Skill',
        'Category'
    );

    /**
     * Some of the left nav filters are not directly related
     * to the estimate, but instead, are related to children.
     *
     * @var array
     */
    protected $_unrelatedLeftNavClasses = array(
        'Role',
        'Skill'
    );

    private static $allowed_actions = array ('ajax', 'index');

    public function init() {
        parent::init();
    }


    /**
     * Gets top level layered nav classes
     *
     * @return ArrayList
     */
    public function GetLeftNav(){
        $classArray = new ArrayList();
        foreach ($this->_leftNavClasses as $class){
           $classArray->add(ArrayData::create(array('className' => $class)));
        }
        return $classArray;
    }

    /**
     *
     * Utility function for template looping on arrays
     *
     * @param array $arr
     * @param string $key
     * @return ArrayList
     */
    public function arrayToArrayList($arr = array(), $key = 'key'){
        $classArray = new ArrayList();
        foreach ($arr as $val){
            $classArray->add(ArrayData::create(array($key => $val)));
        }
        return $classArray;
    }

    /**
     * Build links for layered navigation.
     * Multiple filters are possible per filter group.
     * For example: Filter estimates by platform Magento 1 OR Magento 2
     * Url would be encoded but represent example.com?platform=12,13
     *
     * @param $filterGroup string Filter Group, for example, Platform
     * @param $filterId int ID of platform option, for example, Magento 1
     * @param $forRemove bool used for removing parameter from url
     * @return string
     */
    public function GetFilterLink($filterGroup = null, $filterId = null, $forRemove = false){
        $filterGroup = strtolower($filterGroup);
        $getVars = Controller::curr()->getRequest()->getVars();

        // Hide complexity of de-duping/removing filters via query params
        $getVars[$filterGroup] = $this->_handleFilterQueryParams($filterGroup, $filterId, $forRemove);

        // Just in case filter group was emptied from removing
        if(empty($getVars[$filterGroup])){
            unset($getVars[$filterGroup]);
        }

        $newUrl = new SS_HTTPRequest(null, '/', $getVars);
        return $newUrl->getURL(true);
    }

    /**
     * Check if filter already exists and is active to prevent dupe
     * query params and instead use comma delimited values
     * For example: Filter estimates by platform Magento 1 OR Magento 2
     * Url would be encoded but represent example.com?platform=12,13
     *
     * @param $filterGroup string
     * @param $filterId int
     * @param $shouldRemove bool
     * @return string
     */
    protected function _handleFilterQueryParams($filterGroup, $filterId, $shouldRemove = false){
        $currentGetParams = array();

        // Turn active filters into arrays to handle push/pops without string manipulation
        $currentGetParams = ($this->IsActiveFilterGroup($filterGroup)) ?
            $this->_getQueryParamByKey($filterGroup) : $currentGetParams;


        // Don't add filter if it is remove link for active filters
        $currentGetParams = ($shouldRemove) ?
            $this->_removeArrayValue($currentGetParams, $filterId) :
            $this->_addArrayValue($currentGetParams, $filterId);


        // Give back filter value as array
        return $this->_stringifyQueryParamsArray($currentGetParams);
    }

    /**
     *
     *
     * @param null $key
     * @param bool $asArray
     * @return array | string
     */
    protected function _getQueryParamByKey($key = null, $asArray = true){
        $getVars = Controller::curr()->getRequest()->getVars();

        if(array_key_exists($key, $getVars)){
            return ($asArray) ?
                $this->_arrayizeQueryParam($getVars[$key]) : $getVars[$key];

        } else {
            return false;
        }
    }

    /**
     * Turn query params into array
     *
     * @param string $values
     * @return array
     */
    protected function _arrayizeQueryParam($values = ''){
        return explode(',', $values);
    }

    /**
     * Turn array into query params
     *
     * @param array $arr
     * @return string
     */
    protected function _stringifyQueryParamsArray($arr = array()){
        return implode(',', $arr);
    }

    /**
     * Remove val from array
     *
     * @param array $arr
     * @param string $val
     * @return array
     */
    protected function _removeArrayValue($arr = array(), $val = ''){
            return array_diff($arr, array($val));
    }

    /**
     * Add val to array
     *
     * @param array $arr
     * @param string $val
     * @return array
     */
    protected function _addArrayValue($arr = array(), $val = ''){
        array_push($arr, $val);
        return array_unique($arr);
    }


    /**
     * Check if current filter group is actively filtered
     *
     * @param null $filterGroup string
     * @return bool
     */
    public function IsActiveFilterGroup($filterGroup = null){
        $filterGroup = strtolower($filterGroup);
        $getVars = Controller::curr()->getRequest()->getVars();
        return in_array(strtolower($filterGroup), array_keys($getVars));
    }

    /**
     * Check if filter is already applied. If so, don't show it
     *
     * @param null $filterGroup
     * @param null $filterId
     * @return bool
     */
    public function IsActiveFilter($filterGroup = null, $filterId = null){
        $filterGroup = strtolower($filterGroup);
        return $this->IsActiveFilterGroup($filterGroup) &&
            $this->_getQueryParamByKey($filterGroup) &&
            in_array($filterId, $this->_getQueryParamByKey($filterGroup));
    }

    /**
     * Gets a collection of records for specified $className
     * Used in layered navigation
     *
     * @param $className
     * @return mixed
     */
    public function getMembers($className){
        if($className && in_array($className, $this->_leftNavClasses) && class_exists($className)){
            return $className::get()->sort('Name', 'ASC');
        }
    }

    /**
     * Remove active filters and those without matching records
     *
     * @param $className
     * @return ArrayList
     */
    public function getSanitizeMembers($className){
        $filters = array_filter($this->getMembers($className)->toArray(), function($filter) use ($className){
            return  !$this->IsActiveFilter($className, $filter->ID) && $this->GetFilterCount($className, $filter->ID) > 0;
        });
        return $this->arrayToArrayList($filters, 'filter', array('className', $className));
    }


    /**
     * Get a list of currently applied filters
     *
     * @return ArrayList
     */
    public function GetActiveFilters(){
        $activeFilter = new ArrayList();

        $getVars = Controller::curr()->getRequest()->getVars();
        foreach ($getVars as $key => $value){
            if(in_array(ucfirst($key), $this->_leftNavClasses)){
                $activeFilter->add(
                    ArrayData::create(
                        array(
                            'label' => ucfirst($key),
                            'values' => $this->_getActiveFilterNames(ucfirst($key), explode(',', $value))
                        )
                    )
                );
            }
        }
        return $activeFilter;
    }

    /**
     * Get applied filter's values names
     *
     * @param null $filterGroup
     * @param array $filterIds
     * @return ArrayList
     */
    protected function _getActiveFilterNames($filterGroup = null, $filterIds = array()){
        $filterNames = new ArrayList();
        foreach($filterIds as $filterId){
            $filterNames->add(
                ArrayData::create(
                    array(
                        'filter' => $filterGroup::get()->byID($filterId),
                        'ID' => $filterId
                    )
                )
            );
        }
        return $filterNames;
    }

    /**
     * @return string
     */
    public function GetSearchText()
    {
        return Controller::curr()->getRequest()->getVar('Search');
    }

    /**
     * Get total number of estimates available based on filtering
     *
     * @param null $filterGroup
     * @param $filterId
     * @return int
     *
     */
    public function GetFilterCount($filterGroup = null, $filterId){
        //@todo: Filter current estimate collection
        $estimates = $this->_getCurrentlyFilteredEstimates(Controller::curr()->getRequest());

        if(!$this->_isUnrelatedFilter($filterGroup)){
            $estimates = $estimates->filter(array(
                $filterGroup.'s.ID' => $filterId
            ));
        } else {
            //Watch out for specially related filters
            $estimates = $estimates->filter(array(
                'Stories.LineItems.'.$filterGroup.'s.ID' => $filterId
            ));
        }

        return $estimates->count();
    }

    protected function _isUnrelatedFilter($filterGroup){
        return in_array($filterGroup, $this->_unrelatedLeftNavClasses);
    }

    public function index(SS_HTTPRequest $request){
        $estimateList = new PaginatedList($this->_getCurrentlyFilteredEstimates($request), $request);
        return array('Results' => $estimateList);
    }

    /**
     *
     * @param SS_HTTPRequest $request
     */
    protected function _getCurrentlyFilteredEstimates(SS_HTTPRequest $request){
        $estimates = Estimate::get();

        if($searchTerms = $request->getVar('Search')){
            $estimates = $estimates->filter('SearchFields:fulltext', $searchTerms);
        }


        /*
         * Handle dynamic filtering via layered navigation.
         */
        foreach ($this->_leftNavClasses as $filterGroup) {
            $param = strtolower($filterGroup);
            if($filter = $request->getVar($param)){
                $filter = explode(',', $filter);
                if(!$this->_isUnrelatedFilter($filterGroup)){
                    $estimates = $estimates->filter(array(
                        //@todo: Fix for multiple of same filter group. Url decode
                        $filterGroup.'s.ID' => $filter
                    ));
                } else {
                    //Watch out for specially related filters
                    $estimates = $estimates->filter(array(
                        //@todo: Fix for multiple of same filter group. Url decode
                        'Stories.LineItems.'.$filterGroup.'s.ID' => $filter
                    ));
                }
            }
        }

        return $estimates;
    }

    public function getEstimatesForSearchJSON(){
        $resp = array();

        $estimates = Estimate::get();
        if($estimates->count() > 0){
            foreach ($estimates as $estimate){
                $resp[$estimate->ID] = array(
                    'Name' => $estimate->Name,
                    'Clients' => implode(', ', $estimate->Clients()->column('Name')),
                    'Platforms' => implode(', ', $estimate->Platforms()->column('Name'))
                );
            }
        }
        return json_encode($resp);
    }
}

class EstimatePage extends Page{
    private static $has_many = array(
        'Estimates' => 'Estimate'
    );
}

class EstimateController extends Page_Controller {

    private static $allowed_actions = array(
        'view',
        'ajax'
    );

    public function view(SS_HTTPRequest $request){
        $estimate = Estimate::get()->byID($request->param('ID'));

        if(!$estimate){
            return $this->httpError(404, 'Estimate specified could not be found');
        }

        return $this->customise(array('Estimate' => $estimate))->renderWith('EstimateController');
    }

    public function ajax(SS_HTTPRequest $request){
        $resp = '[]';
        if($searchTerms = $request->getVar('Search')){

            $fields = array('Name', 'ID');

            $estimates = Estimate::get();
            $estimates = $estimates->filter('SearchFields:fulltext', $searchTerms);

            $formatter = new JSONDataFormatter();

            $resp = $formatter->convertDataObjectSet($estimates, $fields);
        }
        $this->getResponse()->addHeader('Content-type', 'application/json')->setBody($resp);
        return $this->getResponse();

    }
}