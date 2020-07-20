<?php

if (!defined('ABSPATH'))
    exit;

return apply_filters('nfzohocrm_fieldmaplookup', array(
    'None' => array(
        'label' => __('None', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'none',
        'map_instructions_v2' => 'none',
    ),
    'LeadFirstName' => array(
        'label' => __('Lead First Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.First Name',
        'map_instructions_v2' => 'Leads.First_Name',
        'validation_functions' => array(),
    ),
    'LeadLastName' => array(
        'label' => __('Lead Last Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Last Name',
        'map_instructions_v2' => 'Leads.Last_Name',
    ),
    'LeadEmail' => array(
        'label' => __('Lead Email', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Email',
        'map_instructions_v2' => 'Leads.Email',
    ),
    'LeadEmailOptOut' => array(
        'label' => __('Lead Email Opt-out', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Email.Email Opt Out',
        'map_instructions_v2' => 'Leads.Email_Opt_Out',
        'validation_functions' => array(
            'forceBoolean',
        ),
    ),
    'LeadPhone' => array(
        'label' => __('Lead Phone', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Phone',
        'map_instructions_v2' => 'Leads.Phone',
    ),
    'LeadStreet' => array(
        'label' => __('Lead Street', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Street',
        'map_instructions_v2' => 'Leads.Street',
    ),
    'LeadCity' => array(
        'label' => __('Lead City', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.City',
        'map_instructions_v2' => 'Leads.City',
    ),
    'LeadState' => array(
        'label' => __('Lead State', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.State',
        'map_instructions_v2' => 'Leads.State',
    ),
    'LeadZipCode' => array(
        'label' => __('Lead Zip Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Zip Code',
        'map_instructions_v2' => 'Leads.Zip_Code',
    ),
    'LeadCountry' => array(
        'label' => __('Lead Country', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Country',
        'map_instructions_v2' => 'Leads.Country',
    ),
    'LeadCompany' => array(
        'label' => __('Lead Company', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Company',
        'map_instructions_v2' => 'Leads.Company',
    ),
    'LeadTitle' => array(
        'label' => __('Lead Title', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Title',
        'map_instructions_v2' => 'Leads.Designation',
    ),
    'LeadWebsite' => array(
        'label' => __('Lead Website', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Website',
        'map_instructions_v2' => 'Leads.Website',
    ),
    'LeadIndustry' => array(
        'label' => __('Lead Industry', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Industry',
        'map_instructions_v2' => 'Leads.Industry',
    ),
    'LeadAnnualRevenue' => array(
        'label' => __('Lead Annual Revenue', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Annual Revenue',
        'map_instructions_v2' => 'Leads.Annual_Revenue',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceInteger',
        ),
    ),
    'LeadMobile' => array(
        'label' => __('Lead Mobile', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Mobile',
        'map_instructions_v2' => 'Leads.Mobile',
    ),
    'LeadFax' => array(
        'label' => __('Lead Fax', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Fax',
        'map_instructions_v2' => 'Leads.Fax',
    ),
    'LeadSecondaryEmail' => array(
        'label' => __('Lead Secondary Email', 'ninja-forms-zoho-crmohocrm'),
        'map_instructions' => 'Leads.Secondary Email',
        'map_instructions_v2' => 'Leads.Secondary_Email',
    ),
    'LeadSkypeID' => array(
        'label' => __('Lead Skype ID', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Skype ID',
        'map_instructions_v2' => 'Leads.Skype_ID',
    ),
    'LeadSaluation' => array(
        'label' => __('Lead Salutation', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Salutation',
        'map_instructions_v2' => 'Leads.Salutation',
    ),
    'LeadSource' => array(
        'label' => __('Lead Source', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Lead Source',
        'map_instructions_v2' => 'Leads.Lead_Source',
    ),
    'LeadCampaignSource' => array(
        'label' => __('Lead Campaign Source', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Campaign Source',
        'map_instructions_v2' => 'Leads.Campaign_Source',
    ),
    'LeadOwner' => array(
        'label' => __('Lead Owner', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Lead Owner',
        'map_instructions_v2' => 'Leads.Lead_Owner',
    ),
    'LeadStatus' => array(
        'label' => __('Lead Status', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Lead Status',
        'map_instructions_v2' => 'Leads.Lead_Status',
    ),
    'LeadRating' => array(
        'label' => __('Lead Rating', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Rating',
        'map_instructions_v2' => 'Leads.Rating',
    ),
    'LeadDescription' => array(
        'label' => __('Lead Description', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.Description',
        'map_instructions_v2' => 'Leads.Description',
    ),
    'LeadCustom' => array(
        'label' => __('Lead Custom ->', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Leads.custom',
        'map_instructions_v2' => 'Leads.custom',
    ),
    'AccountName' => array(
        'label' => __('Account Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Account Name',
        'map_instructions_v2' => 'Accounts.Account_Name',
    ),
    'AccountOwner' => array(
        'label' => __('Account Owner', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Account Owner',
        'map_instructions_v2' => 'Accounts.Owner',
    ),
    'AccountWebsite' => array(
        'label' => __('Account Website', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Website',
        'map_instructions_v2' => 'Accounts.Website',
    ),
    'AccountTickerSymbol' => array(
        'label' => __('Account Ticker Symbol', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Ticker Symbol',
        'map_instructions_v2' => 'Accounts.Ticker_Symbol',
    ),
    'AccountParentAccount' => array(
        'label' => __('Account Parent Account', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Parent Account',
        'map_instructions_v2' => 'Accounts.Parent_Account',
    ),
    'AccountEmployees' => array(
        'label' => __('Account Employees', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Employees',
        'map_instructions_v2' => 'Accounts.Employees',
    ),
    'AccountOwnership' => array(
        'label' => __('Account Ownership', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Ownership',
        'map_instructions_v2' => 'Accounts.Ownership',
    ),
    'AccountIndustry' => array(
        'label' => __('Account Industry', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Industry',
        'map_instructions_v2' => 'Accounts.Industry',
    ),
    'AccountType' => array(
        'label' => __('Account Type', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Account Type',
        'map_instructions_v2' => 'Accounts.Account_Type',
    ),
    'AccountNumber' => array(
        'label' => __('Account Number', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Account Number',
        'map_instructions_v2' => 'Accounts.Account_Number',
    ),
    'AccountSite' => array(
        'label' => __('Account Site', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Account Site',
        'map_instructions_v2' => 'Accounts.Account_Site',
    ),
    'AccountPhone' => array(
        'label' => __('Account Phone', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Phone',
        'map_instructions_v2' => 'Accounts.Phone',
    ),
    'AccountFax' => array(
        'label' => __('Account Fax', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Fax',
        'map_instructions_v2' => 'Accounts.Fax',
    ),
    'AccountEmail' => array(
        'label' => __('Account E-mail', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.E-mail',
        'map_instructions_v2' => 'Accounts.E-mail',
    ),
    'AccountBillingStatus' => array(
        'label' => __('Account Billing Street', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Billing Street',
        'map_instructions_v2' => 'Accounts.Billing_Street',
    ),
    'AccountBillingCity' => array(
        'label' => __('Account Billing City', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Billing City',
        'map_instructions_v2' => 'Accounts.Billing_City',
    ),
    'AccountBillingState' => array(
        'label' => __('Account Billing State', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Billing State',
        'map_instructions_v2' => 'Accounts.Billing_State',
    ),
    'AccountBillingCode' => array(
        'label' => __('Account Billing Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Billing Code',
        'map_instructions_v2' => 'Accounts.Billing_Code',
    ),
    'AccountBillingCountry' => array(
        'label' => __('Account Billing Country', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Billing Country',
        'map_instructions_v2' => 'Accounts.Billing_Country',
    ),
    'AccountShippingStreet' => array(
        'label' => __('Account Shipping Street', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Shipping Street',
        'map_instructions_v2' => 'Accounts.Shipping_Street',
    ),
    'AccountShippingCity' => array(
        'label' => __('Account Shipping City', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Shipping City',
        'map_instructions_v2' => 'Accounts.Shipping_City',
    ),
    'AccountShippingState' => array(
        'label' => __('Account Shipping State', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Shipping State',
        'map_instructions_v2' => 'Accounts.Shipping_State',
    ),
    'AccountShippingCode' => array(
        'label' => __('Account Shipping Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Shipping Code',
        'map_instructions_v2' => 'Accounts.Shipping_Code',
    ),
    'AccountShippingCountry' => array(
        'label' => __('Account Shipping Country', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Shipping Country',
        'map_instructions_v2' => 'Accounts.Shipping_Country',
    ),
    'AccountRating' => array(
        'label' => __('Account Rating', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Rating',
        'map_instructions_v2' => 'Accounts.Rating',
    ),
    'AccountSICCode' => array(
        'label' => __('Account SIC Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.SIC Code',
        'map_instructions_v2' => 'Accounts.SIC_Code',
    ),
    'AccountAnnualRevenue' => array(
        'label' => __('Account Annual Revenue', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Annual Revenue',
        'map_instructions_v2' => 'Accounts.Annual_Revenue',
    ),
    'AccountDescription' => array(
        'label' => __('Account Description', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.Description',
        'map_instructions_v2' => 'Accounts.Description',
    ),
    'AccountCustom' => array(
        'label' => __('Account Custom ->', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Accounts.custom',
        'map_instructions_v2' => 'Accounts.custom',
    ),
    'ContactSalutation' => array(
        'label' => __('Contact Salutation', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Salutation',
        'map_instructions_v2' => 'Contacts.Salutation',
    ),
    'ContactFirstName' => array(
        'label' => __('Contact First Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.First Name',
        'map_instructions_v2' => 'Contacts.First_Name',
    ),
    'ContactLastName' => array(
        'label' => __('Contact Last Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Last Name',
        'map_instructions_v2' => 'Contacts.Last_Name',
    ),
    'ContactOwner' => array(
        'label' => __('Contact Owner', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Contact Owner',
        'map_instructions_v2' => 'Contacts.Owner',
    ),
    'ContactCampaignSource' => array(
        'label' => __('Contact Campaign Source', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Campaign Source',
        'map_instructions_v2' => 'Contacts.Campaign_Source',
    ),
    'ContactLeadSource' => array(
        'label' => __('Contact Lead Source', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Lead Source',
        'map_instructions_v2' => 'Contacts.Lead_Source',
    ),
    'ContactTitle' => array(
        'label' => __('Contact Title', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Title',
        'map_instructions_v2' => 'Contacts.Title',
    ),
    'ContactDepartment' => array(
        'label' => __('Contact Department', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Department',
        'map_instructions_v2' => 'Contacts.Department',
    ),
    'ContactDateOfBirth' => array(
        'label' => __('Contact Date of Birth', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Date of Birth',
        'map_instructions_v2' => 'Contacts.Date_of_Birth',
    ),
    'ContactDescription' => array(
        'label' => __('Contact Description', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Description',
        'map_instructions_v2' => 'Contacts.Description',
    ),
    'ContactEmail' => array(
        'label' => __('Contact Email', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Email',
        'map_instructions_v2' => 'Contacts.Email',
    ),
    'ContactSecondaryEmail' => array(
        'label' => __('Contact Secondary Email', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Secondary Email',
        'map_instructions_v2' => 'Contacts.Secondary_Email',
    ),
    'ContactHomePhone' => array( 'label' => __('Contact Home Phone', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Home Phone',
        'map_instructions_v2' => 'Contacts.Home_Phone',
    ),
    'ContactOtherPhone' => array(
        'label' => __('Contact Other Phone', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Other Phone',
        'map_instructions_v2' => 'Contacts.Other_Phone',
    ),
    'ContactSkypeID' => array(
        'label' => __('Contact Skype ID', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Skype ID',
        'map_instructions_v2' => 'Contacts.Skype_ID',
    ),
    'ContactPhone' => array(
        'label' => __('Contact Phone', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Phone',
        'map_instructions_v2' => 'Contacts.Phone',
    ),
    'ContactMobile' => array(
        'label' => __('Contact Mobile', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Mobile',
        'map_instructions_v2' => 'Contacts.Mobile',
    ),
    'ContactFax' => array(
        'label' => __('Contact Fax', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Fax',
        'map_instructions_v2' => 'Contacts.Fax',
    ),
    'ContactEmailOptOut' => array(
        'label' => __('Contact Email Opt-out', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Email.Email Opt Out',
        'map_instructions_v2' => 'Contacts.Email_Opt_Out',
        'validation_functions' => array(
            'forceBoolean',
        ),
    ),
    'ContactMailingStreet' => array(
        'label' => __('Contact Mailing Street', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Mailing Street',
        'map_instructions_v2' => 'Contacts.Mailing_Street',
    ),
    'ContactMailingCity' => array(
        'label' => __('Contact Mailing City', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Mailing City',
        'map_instructions_v2' => 'Contacts.Mailing_City',
    ),
    'ContactMailingState' => array(
        'label' => __('Contact Mailing State', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Mailing State',
        'map_instructions_v2' => 'Contacts.Mailing_State',
    ),
    'ContactMailingCode' => array(
        'label' => __('Contact Mailing Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Mailing Zip',
        'map_instructions_v2' => 'Contacts.Mailing_Zip',
    ),
    'ContactMailingCountry' => array(
        'label' => __('Contact Mailing Country', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Mailing Country',
        'map_instructions_v2' => 'Contacts.Mailing_Country',
    ),
    'ContactOtherStreet' => array(
        'label' => __('Contact Other Street', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Other Street',
        'map_instructions_v2' => 'Contacts.Other_Street',
    ),
    'ContactOtherCity' => array(
        'label' => __('Contact Other City', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Other City',
        'map_instructions_v2' => 'Contacts.Other_City',
    ),
    'ContactOtherState' => array(
        'label' => __('Contact Other State', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Other State',
        'map_instructions_v2' => 'Contacts.Other_State',
    ),
    'ContactOtherCode' => array(
        'label' => __('Contact Other Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Other Zip',
        'map_instructions_v2' => 'Contacts.Other_Zip',
    ),
    'ContactOtherCounty' => array(
        'label' => __('Contact Other Country', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.Other Country',
        'map_instructions_v2' => 'Contacts.Other_Country',
    ),
    'ContactCustom' => array(
        'label' => __('Contact Custom ->', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Contacts.custom',
        'map_instructions_v2' => 'Contacts.custom',
    ),
    'PotentialName' => array(
        'label' => __('Potential Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Potential Name',
        'map_instructions_v2' => 'Potentials.Deal_Name',
    ),
    'PotentialType' => array(
        'label' => __('Potential Type', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Type',
        'map_instructions_v2' => 'Potentials.Type',
    ),
    'PotentialLeadSource' => array(
        'label' => __('Potential Lead Source', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Lead Source',
        'map_instructions_v2' => 'Potentials.Lead_Source',
    ),
    'PotentialCampaignSource' => array(
        'label' => __('Potential Campaign Source', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Campaign Source',
        'map_instructions_v2' => 'Potentials.Campaign_Source',
    ),
    'PotentialAmount' => array(
        'label' => __('Potential Amount', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Amount',
        'map_instructions_v2' => 'Potentials.Amount',
    ),
    'PotentialClosingDate' => array(
        'label' => __('Potential Closing Date', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Closing Date',
        'map_instructions_v2' => 'Potentials.Closing_Date',
        'validation_functions' => array(
            'convertDateInterval',
            'formatDate',
        ),
    ),
    'PotentialNextStep' => array(
        'label' => __('Potential Next Step', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Next Step',
        'map_instructions_v2' => 'Potentials.Next_Step',
    ),
    'PotentialStage' => array(
        'label' => __('Potential Stage', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Stage',
        'map_instructions_v2' => 'Potentials.Stage',
    ),
    'PotentialProbability' => array(
        'label' => __('Potential Probability', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Probability',
        'map_instructions_v2' => 'Potentials.Probability',
    ),
    'PotentialExpectedRevenue' => array(
        'label' => __('Potential Expected Revenue', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Expected Revenue',
        'map_instructions_v2' => 'Potentials.Expected_Revenue',
    ),
    'PotentialDescription' => array(
        'label' => __('Potential Description', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.Description',
        'map_instructions_v2' => 'Potentials.Description',
    ),
    'PotentialCustom' => array(
        'label' => __('Potential Custom ->', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Potentials.custom',
        'map_instructions_v2' => 'Potentials.custom',
    ),
    'TaskSubject' => array(
        'label' => __('Task Subject', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Tasks.Subject',
        'map_instructions_v2' => 'Tasks.Subject',
    ),
    'TaskDueDate' => array(
        'label' => __('Task Due Date', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Tasks.Due Date',
        'map_instructions_v2' => 'Tasks.Due_Date',
        'validation_functions' => array(
            'convertDateInterval',
            'formatDate',
        ),
    ),
    'TaskStatus' => array(
        'label' => __('Task Status', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Tasks.Status',
        'map_instructions_v2' => 'Tasks.Status',
    ),
    'TaskPriority' => array(
        'label' => __('Task Priority', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Tasks.Priority',
        'map_instructions_v2' => 'Tasks.Priority',
    ),
    'TaskDescription' => array(
        'label' => __('Task Description', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Tasks.Description',
        'map_instructions_v2' => 'Tasks.Description',
    ),
    'NoteTitle' => array(
        'label' => __('Note Title', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Notes.Note Title',
        'map_instructions_v2' => 'Notes.Note_Title',
    ),
    'NoteContent' => array(
        'label' => __('Note Content', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Notes.Note Content',
        'map_instructions_v2' => 'Notes.Note_Content',
    ),
    'QuoteSubject' => array(
        'label' => __('Quote Subject', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Subject',
        'map_instructions_v2' => 'Quotes.Subject',
    ),
    'QuoteAccountName' => array(
        'label' => __('Quote Account Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Account Name',
        'map_instructions_v2' => 'Quotes.Account_Name',
    ),
    'QuoteDealName' => array(
        'label' => __('Quote DealName', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Deal Name',
        'map_instructions_v2' => 'Quotes.Deal_Name',
    ),
    'QuoteContactName' => array(
        'label' => __('Quote Contact Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Contact Name',
        'map_instructions_v2' => 'Quotes.Contact_Name',
    ),
    'QuoteStage' => array(
        'label' => __('Quote Stage', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Quote Stage',
        'map_instructions_v2' => 'Quotes.Quote_Stage',
    ),
    'QuoteValidTill' => array(
        'label' => __('Quote Valid Until', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Valid Till',
        'map_instructions_v2' => 'Quotes.Valid_Till',
        'validation_functions' => array(
            'formatDate',
        ),
    ),
    'QuoteOwner' => array(
        'label' => __('Quote Owner', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Owner',
        'map_instructions_v2' => 'Quotes.Owner',
    ),
    'QuoteTag' => array(
        'label' => __('Quote Tag', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'QuoteTags.Tag',
        'map_instructions_v2' => 'QuoteTags.name',
    ),
    'QuoteBillingStreet' => array(
        'label' => __('Quote Billing Street', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Billing Street',
        'map_instructions_v2' => 'Quotes.Billing_Street',
    ),
    'QuoteBillingCity' => array(
        'label' => __('Quote Billing City', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Billing City',
        'map_instructions_v2' => 'Quotes.Billing_City',
    ),
    'QuoteBillingState' => array(
        'label' => __('Quote Billing State', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Billing State',
        'map_instructions_v2' => 'Quotes.Billing_State',
    ),
    'QuoteBillingCode' => array(
        'label' => __('Quote Billing Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Billing Code',
        'map_instructions_v2' => 'Quotes.Billing_Code',
    ),
    'QuoteBillingCountry' => array(
        'label' => __('Quote Billing Country', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Billing Country',
        'map_instructions_v2' => 'Quotes.Billing_Country',
    ),
    'QuoteProductDetails' => array(
        'label' => __('Quote Product Details', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Product Details',
        'map_instructions_v2' => 'Quotes.Product_Details',
        'validation_functions' => array(
            'commaExplode',
        ),
    ),
    'QuoteDiscount' => array(
        'label' => __('Quote Discount', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Discount',
        'map_instructions_v2' => 'Quotes.Discount',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    /*
      'QuoteTax' => array(
      'label' => __('Quote Tax', 'ninja-forms-zoho-crm'),
      'map_instructions' => 'Quotes.Tax',
      'map_instructions_v2' => 'Quotes.Tax',
      'validation_functions' => array(
      'removeNonNumeric',
      'forceFloat',
      ),
      ),
     * 
     */
    'QuoteAdjustment' => array(
        'label' => __('Quote Adjustment', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Adjustment',
        'map_instructions_v2' => 'Quotes.Adjustment',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    'QuoteTermsandConditions' => array(
        'label' => __('Quote Terms and Conditions', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Terms and Conditions',
        'map_instructions_v2' => 'Quotes.Terms_and_Conditions',
    ),
    'QuoteDescription' => array(
        'label' => __('Quote Description', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Quotes.Description',
        'map_instructions_v2' => 'Quotes.Description',
    ),
    'SalesOrderSubject' => array(
        'label' => __('Sales Order Subject', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Subject',
        'map_instructions_v2' => 'Sales_Orders.Subject',
    ),
    'SalesOrderAccountName' => array(
        'label' => __('Sales Order Account Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Account Name',
        'map_instructions_v2' => 'Sales_Orders.Account_Name',
    ),
    'SalesOrderDealName' => array(
        'label' => __('Sales Order DealName', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Deal Name',
        'map_instructions_v2' => 'Sales_Orders.Deal_Name',
    ),
    'SalesOrderContactName' => array(
        'label' => __('Sales Order Contact Name', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Contact Name',
        'map_instructions_v2' => 'Sales_Orders.Contact_Name',
    ),
    'SalesOrderDueDate' => array(
        'label' => __('Sales Order Due Date', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Due Date',
        'map_instructions_v2' => 'Sales_Orders.Due_Date',
        'validation_functions' => array(
            'formatDate',
        ),
    ),
    'SalesOrderStatus' => array(
        'label' => __('Sales Order Status', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Status',
        'map_instructions_v2' => 'Sales_Orders.Status',
        'validation_functions' => array(
            'formatDate',
        ),
    ),
    'SalesOrderPending' => array(
        'label' => __('Sales Order Pending', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Pending',
        'map_instructions_v2' => 'Sales_Orders.Pending',
        'validation_functions' => array(
            'formatDate',
        ),
    ),
    'SalesOrderOwner' => array(
        'label' => __('Sales Order Owner', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Owner',
        'map_instructions_v2' => 'Sales_Orders.Owner',
    ),
    'SalesOrderTag' => array(
        'label' => __('Sales Order Tag', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'SalesOrderTags.Tag',
        'map_instructions_v2' => 'SalesOrderTags.name',
    ),
    'SalesOrderBillingStreet' => array(
        'label' => __('Sales Order Billing Street', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Billing Street',
        'map_instructions_v2' => 'Sales_Orders.Billing_Street',
    ),
    'SalesOrderBillingCity' => array(
        'label' => __('Sales Order Billing City', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Billing City',
        'map_instructions_v2' => 'Sales_Orders.Billing_City',
    ),
    'SalesOrderBillingState' => array(
        'label' => __('Sales Order Billing State', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Billing State',
        'map_instructions_v2' => 'Sales_Orders.Billing_State',
    ),
    'SalesOrderBillingCode' => array(
        'label' => __('Sales Order Billing Code', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Billing Code',
        'map_instructions_v2' => 'Sales_Orders.Billing_Code',
    ),
    'SalesOrderBillingCountry' => array(
        'label' => __('Sales Order Billing Country', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Billing Country',
        'map_instructions_v2' => 'Sales_Orders.Billing_Country',
    ),
    'SalesOrderDiscount' => array(
        'label' => __('Sales Order Discount', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Discount',
        'map_instructions_v2' => 'Sales_Orders.Discount',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    'SalesOrderTax' => array(
        'label' => __('Sales Order Tax', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Tax',
        'map_instructions_v2' => 'Sales_Orders.Tax',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    'SalesOrderAdjustment' => array(
        'label' => __('Sales Order Adjustment', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Adjustment',
        'map_instructions_v2' => 'Sales_Orders.Adjustment',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    'SalesOrderTermsandConditions' => array(
        'label' => __('Sales Order Terms and Conditions', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Terms and Conditions',
        'map_instructions_v2' => 'Sales_Orders.Terms_and_Conditions',
    ),
    'SalesOrderDescription' => array(
        'label' => __('Sales Order Description', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Sales_Orders.Description',
        'map_instructions_v2' => 'Sales_Orders.Description',
    ),
    'ProductId' => array(
        'label' => __('Product Id', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Products.product.id',
        'map_instructions_v2' => 'Products.nested.product.id',
    ),
    'ProductQuantity' => array(
        'label' => __('Product Quantity', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Products.quantity',
        'map_instructions_v2' => 'Products.quantity',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    'ProductTax' => array(
        'label' => __('Product Tax', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Products.Tax',
        'map_instructions_v2' => 'Products.Tax',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    'ProductDiscount' => array(
        'label' => __('Product Discount', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Products.Discount',
        'map_instructions_v2' => 'Products.Discount',
        'validation_functions' => array(
            'removeNonNumeric',
            'forceFloat',
        ),
    ),
    'TriggerWorkflows' => array(
        'label' => __('Trigger Workflows', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Parameters.wfTrigger',
        'map_instructions_v2' => 'Parameters.wfTrigger',
    ),
    'RequiresApproval' => array(
        'label' => __('Requires Approval', 'ninja-forms-zoho-crm'),
        'map_instructions' => 'Parameters.isApproval',
        'map_instructions_v2' => 'Parameters.isApproval',
    ),
        ));


