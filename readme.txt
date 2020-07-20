=== Ninja Forms - Zoho CRM ===


== Changelog ==

= 3.4 =
2019.01.23
Add Sales Orders and Quotes modules
- requires API v2 and also Zoho CRM premium subscription

Add Potentials custom field map

= 3.3.3 =
2018.09.20
Add linking for Notes using new API structure
Update Potential Name to API v2 name Deal_Name - was Potential_Name 


= 3.3.2 =
Change Title to Designation - Zoho changed the field name in API v2

= 3.3.1 =
Correct Zip Code to Zip_Code - Zoho changed the field name in their v2

= 3.3.0 =

Display field list functionality - Helps form designers pick the exact field
name for any non-standard field.  Advanced commmand is:
display_fields_MODULE where MODULE is replaced by the name of the module

Add custom validation functionality.  Enables form designer to specify which
validation functions to call on any entry in the option repeater

    {multipicklist} - structures multi-select field for Zoho multi-picklist
    {replace_underscores} - replace underscores with spaces
    {remove_nonnumeric} - ensures value comes in as a number
    {force_boolean} - converts value to true/false
    {convert_date_interval} - converts value to a date interval from submission date
    {format_date} - formats value to a Zoho date format

Add custom format options for fields.  Force boolean, force integer, format date
are all newly available for any value being mapped to Zoho.

= 3.2.1 =
Correct key name for API v1 authtoken

= 3.2.0 =
Add EU domain extension for EU accounts.  Changed from v1

= 3.2.0-RC =

Use Zoho's API v2; v1 is being phased out

Add multiple entries for each module using advanced command 
example: Lead_XX adds XX number of leads

Remove undesired modules from field map list for easier map building
example: Lead_0 removes the lead module from the dropdown

Add Email Opt Out field map for Lead and Contact
 
= 3.1.0 =
2017.07.09
Add EU endpoint with filter and advanced command

= 3.0.2 =
2017.04.15
Remove html tags appearing in text areas; option to keep tags with 
advanced command and filter

Add quick authtoken test link on settings page

= 3.0.1 =
Change name and slug constants for auto updates
Add Lead Description to available field maps

= 3.0 =
2017.01.19
Add upgrade from 2.9 function

2017.01.07
Return $data in Action process

2017.01.07
3.0-compatible ready for testing

= 1.8.0 =
2016.06.28
Add custom field capability for Accounts (standard fields and text area)
Enable text area custom fields for Contacts

= 1.7.1= 
2015.09.16
Change field map value for Contact "Mailing" and "Other" Zip Code


= 1.7 =
2015.04.22
Add custom field mapping for Contact Module

= 1.6.1 =
2014.11.20
Add mapping capability to more NF fields
Change custom field preg_replace to esc_html
 - allows for special characters in custom fields

= 1.5.1 =
Escape attributes for form submission data; prevents error when certain characters are included

= 1.5 =
2014.08.06
New Features:
Add modules for Accounts, Contacts, Potentials, Tasks, and Notes
Add parameters for Approvals and Workflow Triggers
Tweaks:
Convert to JSON for response handling and OOP for communication

= 1.4.1 =
2014.06.24 Escape attributes for pick list options to prevent comm failure when selections include special characters

= 1.4 =
2014.06.03 Add raw communication data to settings page to enable faster customer support

= 1.3.4 =
2014.05.19 Modify zoho crm scope to remove key from value

= 1.3.3 =

Update tags

= 1.3.2 =
Custom mapped field to allow dash 

= 1.3.1 =
Use User Analytics' field grabbing function created by Patrick Rauland instead of custom function originally used


= 1.3 = 
Add Custom Field Mapping

--data-processing.php
Add Custom Field Mapping option

Modify Annual Revenue validation to remove all non-numeric and conver to integer

Remove function that turns on error log writing

--field-registration.php
modify field mapping function to make it extendable by other plugins with custom fields
add custom mapping field



= 1.2 =
Initial communication with User Analytics

Add User Analytics fields 'country' 'region' 'postal_code' 'city' as
mappable fields

In field-registration.php, change mapped field values for dividers from "none" to "divider" and
add code to reset to "none" before processing. The resetting prevents
unwanted fields from being mapped.

In data-processing.php, add secondary check during mapping to ensure divider
fields don't get mapped


= 1.1 =
Modify log data status and debug information based on recent learning from Elicere support ticket

Add preg_replace code for Annual Revenue field to prevent comm failure - Zoho requires integer for Annual Revenue

Format documentation with html headers
Modify instructions for end user documentation


= 1.0 =
Changed the name of the plugin to fit in with the Ninja Forms standard: Ninja Forms - Zoho CRM.
Fixed the version number constant.
Changed the licensing option call to the correct format.
Add text-domain for all translations (ninja-forms-zoho-crm)
Add gettext function around 'Map this field to your Zoho CRM' - missing it prior
Remove .nff file from plugin - to be uploaded to NF site after approval
Remove authtoken test value from remarks
Add default .po and .mo to initiate translations