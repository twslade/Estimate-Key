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

    private $_confidenceLevels = array('High', 'Med', 'Low');

    public function getCMSFields()
    {
        $fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', array(
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

        $fields->addFieldsToTab('Root.Requirements', array(
            HtmlEditorField::create('BusinessRequirements')->setRows(5),
            HtmlEditorField::create('FunctionalRequirements')->setRows(5),

        ));


        $gridField = new GridField('Risks', 'Risk', Risk::get());
        $gridField->setConfig(
            GridFieldConfig_RelationEditor::create()
                ->addComponent(new GridFieldAddExistingAutocompleter())
                ->removeComponent(new GridFieldDeleteAction(true))
                ->addComponent(new GridFieldDeleteAction('unlinkrelation'))
        );

        $fields->addFieldsToTab('Root.Technical', array(
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

        return $fields;
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