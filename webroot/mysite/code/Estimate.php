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
        'Artifacts' => 'Artifact',
        'Platforms' => 'Platform'
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
        return $this->_getFields()->addFieldsToTab('Root.Main', array(
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
    }

    private function _getRequirementTab(){
        return $this->_getFields()->addFieldsToTab('Root.Requirements', array(
            HtmlEditorField::create('BusinessRequirements')->setRows(5),
            HtmlEditorField::create('FunctionalRequirements')->setRows(5),
        ));
    }

    private function _getTechnicalTab(){
        $gridField = new GridField('Risks', 'Risk', $this->Risks());

        $gridField->setConfig(
            GridFieldConfig_RelationEditor::create()
        );

        return $this->_getFields()->addFieldsToTab('Root.Technical', array(
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
    }

    private function _getStoryTab(){
        $storiesConfig = GridFieldConfig_RelationEditor::create();
        //@todo: Add line items within column as subtable
        $storiesConfig
            ->getComponentByType('GridFieldAddExistingAutocompleter')
            ->setSearchFields(array('Name'))
            ->setResultsFormat('$Name');

        //@todo: Add total hours in search results

        $gridField = new GridField('Stories', 'Story', $this->Stories(), $storiesConfig);

        $this->_getFields()->addFieldToTab('Root.Stories', $gridField);

        return $this->_getFields();
    }

    public function getCMSFields()
    {

        $this->_getMainTab();
        $this->_getRequirementTab();
        $this->_getTechnicalTab();
        $this->_getStoryTab();

        return $this->_getFields();
    }
}

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

class Artifact extends DataObject
{
    private static $db = array(
        'Name' => 'Varchar(255)',
        'File' => 'Varchar(255)',
        'Link' => 'Varchar(255)'
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
            ListboxField::create('RiskTypes', 'Risk Types')
                ->setSource(RiskType::get()->map('ID', 'Name')->toArray())
                ->setMultiple(true),
            TextField::create('Name'),
            DropdownField::create('Probability')->setSource($this->_riskProbabilities),
            HtmlEditorField::create('Description'),
            HtmlEditorField::create('MitigationPlan'),
            TextField::create('MitigationCost'),
            TextField::create('PotentialCost')
        );

        return $fields;
    }
}

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

        $fields = FieldList::create(
            TextField::create('Name'),
            $lineItems
        );

        return $fields;
    }
}

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
    private static $db = array(
    );
    private static $has_one = array(
    );
}
class Page_Controller extends ContentController {
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
    private static $allowed_actions = array (
    );
    public function init() {
        parent::init();
        // You can include any CSS or JS required by your project here.
        // See: http://doc.silverstripe.org/framework/en/reference/requirements
    }
}