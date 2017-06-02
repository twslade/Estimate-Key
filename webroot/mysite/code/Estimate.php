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

        'BudgetConfidence'          => 'Int',
	    'ScheduleConfidence'        => 'Int',
	    'TechnicalConfidence'       => 'Int',
	);

    private static $many_many = array(
        'Risks' => 'Risk',
        'Clients' => 'Client',
        'Stories' => 'Story',
        'Platforms' => 'Platform',
        'Files' => 'File'
    );

    private static $has_one = array(
        'EstimatePage' => 'EstimatePage'
    );

    /** @var FieldList */
    private $_estimateFields = null;

    private $_confidenceLevels = array('High', 'Med', 'Low');

    /**
     * @return FieldList
     */
    private function _getFields(){
        if($this->_estimateFields){
            return $this->_estimateFields;
        }
        $this->_estimateFields = FieldList::create(TabSet::create('Root'));
        return $this->_estimateFields;
    }

    private function _getMainTab(){
        $this->_getFields()->addFieldsToTab('Root.Main', array(
            TextField::create('Name'),
            TextField::create('RomLow', 'Lower Rom Range'),
            TextField::create('RomHigh', 'Higher Rom Range'),
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
            DropdownField::create('BudgetConfidence', 'Budget Confidence')
                ->setSource($this->_confidenceLevels)
                ->setEmptyString('-- Select a level --'),
            DropdownField::create('ScheduleConfidence', 'Schedule Confidence')
                ->setSource($this->_confidenceLevels)
                ->setEmptyString('-- Select a level --'),
            DropdownField::create('TechnicalConfidence', 'Technical Confidence')
                ->setSource($this->_confidenceLevels)
                ->setEmptyString('-- Select a level --'),
            $gridField

        ));

        return;
    }

    private function _getStoryTab(){


        /** @var GridFieldConfig_RelationEditor $storiesConfig */
        $storiesConfig = GridFieldConfig_RelationEditor::create();
        //@todo: Add line items within column as subtable
        $storiesConfig
            ->getComponentByType('GridFieldAddExistingAutocompleter')
            ->setSearchFields(array('Name'))
            ->setResultsFormat('$Name');

        /** @var GridFieldDataColumns $columns */
        //$columns = $storiesConfig->getComponentByType('GridFieldDataColumns');


        //@todo: Add total hours in search results

        $gridField = new GridField('Stories', 'Story', $this->Stories(), $storiesConfig);

        $this->_getFields()->addFieldsToTab('Root.Stories', array(
            $gridField
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

    public function getCMSFields()
    {

        $this->_getMainTab();
        $this->_getRequirementTab();
        $this->_getTechnicalTab();
        $this->_getStoryTab();
        $this->_getArtifactsTab();

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

    public function GetTechnicalConfidence(){
        return $this->_getConfidenceLevel($this->record['TechnicalConfidence']);
    }

    public function GetScheduleConfidence(){
        return $this->_getConfidenceLevel($this->record['ScheduleConfidence']);
    }

    public function GetBudgetConfidence(){
        return $this->_getConfidenceLevel($this->record['BudgetConfidence']);
    }

    private function _getConfidenceLevel($idx){
        return array_key_exists($idx, $this->_confidenceLevels) ? $this->_confidenceLevels[$idx] : '';
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
        'Name' => 'Varchar(255)',
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
        'Name'                      => 'Varchar(255)'
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
        'Name'                      => 'Varchar(255)'
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
        'Name'                      => 'Varchar(255)',
    );

    private $searchable_fields = array('Name');

    private static $belongs_many_many = array(
        'Estimates' => 'Estimate'
    );

    private static $many_many = array(
        'LineItems' => 'LineItem'
    );

    public function getCMSFields()
    {
        $lineItems = new GridField('LineItems', 'Line Items', $this->LineItems(),
            GridFieldConfig::create()
                ->addComponent(new GridFieldButtonRow('before'))
                ->addComponent(new GridFieldToolbarHeader())
                ->addComponent(new GridFieldTitleHeader())
                ->addComponent(new GridFieldEditableColumns())
                ->addComponent(new GridFieldDeleteAction())
                ->addComponent(new GridFieldAddNewInlineButton())
        );

        $lineItems->getConfig()->getComponentByType('GridFieldEditableColumns')->setDisplayFields(array(
            'NumHours' => function($record, $column, $grid) {
                return new TextField('NumHours', 'Number of hours');
            },
            'Description' => function($record, $column, $grid) {
                return new TextField('Description', 'Description');
            }
        ));


        //@todo: Add additional field for summing line items
        $fields = FieldList::create(
            TextField::create('Name'),
            $lineItems
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

    private static $belongs_many_many = array(
        'Stories' => 'Story'
    );

    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create('NumHours'),
            TextField::create('Description')

        );

        return $fields;
    }


}

class Page extends SiteTree {
    private static $db = array();
    private static $has_one = array();
}
class Page_Controller extends ContentController {


    protected $_leftNavClasses =  array(
        'Platform',
        'Client',
        'Role',
        'Skill'
    );

    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = array ();
    public function init() {
        parent::init();
        // You can include any CSS or JS required by your project here.
        // See: http://doc.silverstripe.org/framework/en/reference/requirements
    }

    public function LatestEstimates(){
        //@todo: Make configurable. Set to 9 by default
        return Estimate::get()
            ->sort('Created', 'DESC')
            ->limit(30);
    }

    public function GetLeftNav(){
        $classArray = new ArrayList();
        foreach ($this->_leftNavClasses as $class){
           $classArray->add(ArrayData::create(array('className' => $class)));
        }
        return $classArray;
    }

        public function getMembers($className){
            if($className && in_array($className, $this->_leftNavClasses) && class_exists($className)){
                return $className::get()->sort('Name', 'ASC');
            }
        }

    public function GetEstimates(){
        return  new PaginatedList(Estimate::get(), $this->getRequest());
    }

    public function index(SS_HTTPRequest $request){
        $estimates = Estimate::get();

        if($searchTerms = $request->getVar('Search')){
            $estimates = $estimates->filter(array(
                'Name:PartialMatch' => $searchTerms
            ));
        }
        return array('Results' => $estimates);
    }
}

class EstimatePage extends Page{
    private static $has_many = array(
        'Estimates' => 'Estimate'
    );
}

class EstimatePage_Controller extends Page_Controller {

    private static $allowed_actions = array(
        'view'
    );

    public function view(SS_HTTPRequest $request){
        $estimate = Estimate::get()->byID($request->param('ID'));

        if(!$estimate){
            return $this->httpError(404, 'Estimate specified could not be found');
        }

        return array(
            'Estimate' => $estimate
        );
    }
}