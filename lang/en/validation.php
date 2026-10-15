<?php

/*
Validation messages in English (site displayed with the "EN" button)
Same friendly tone as the French version (lang/fr/validation.php).
 Messages that are not listed here fall back to Laravel's default English messages.
*/

return [

    'accepted' => 'Please tick the box: :attribute.',
    'after_or_equal' => ':Attribute must be on or after :date.',
    'array' => 'The value given for :attribute is not valid.',
    'before_or_equal' => ':Attribute must be on or before :date.',
    'boolean' => 'The value given for :attribute is not valid.',
    'confirmed' => 'The confirmation of :attribute does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'Please enter a valid date for :attribute.',
    'date_format' => 'Please enter :attribute in the correct format.',
    'different' => ':Attribute must be different from :other.',
    'email' => 'Please enter a valid email address (e.g. firstname.lastname@example.com).',
    'file' => ':Attribute must be a file.',
    'image' => ':Attribute must be an image.',
    'in' => 'The option chosen for :attribute is not valid.',
    'integer' => ':Attribute must be a whole number.',
    'max' => [
        'array' => ':Attribute may not contain more than :max items.',
        'file' => ':Attribute may not be larger than :max KB.',
        'numeric' => ':Attribute may not be greater than :max.',
        'string' => ':Attribute may not be longer than :max characters.',
    ],
    'mimes' => 'File format not accepted for :attribute (accepted formats: :values).',
    'mimetypes' => 'File format not accepted for :attribute (accepted formats: :values).',
    'min' => [
        'array' => 'Please select at least :min item(s) for :attribute.',
        'file' => ':Attribute must be at least :min KB.',
        'numeric' => ':Attribute must be at least :min.',
        'string' => ':Attribute must contain at least :min characters.',
    ],
    'not_in' => 'The option chosen for :attribute is not valid.',
    'numeric' => ':Attribute must be a number.',
    'regex' => 'The format of :attribute is not valid.',
    'required' => 'Please fill in :attribute.',
    'required_if' => 'Please fill in :attribute.',
    'required_unless' => 'Please fill in :attribute.',
    'required_with' => 'Please fill in :attribute.',
    'string' => 'The value given for :attribute is not valid.',
    'unique' => ':Attribute is already in use.',
    'uploaded' => 'The file could not be uploaded: it may be too large.',

    /*
    Field names
    Replace ":attribute" in the messages (e.g. "Please fill in your phone number.").
    */

    'attributes' => [
        // Common fields
        'email' => 'your email address',
        'message' => 'your message',
        'rgpd_consent' => 'consent to the processing of your data',

        // Contact
        'fullname' => 'your full name',
        'phone' => 'your phone number',
        'subject' => 'the subject of your enquiry',

        // Company quote
        'company_name' => 'the company name',
        'sector' => 'the business sector',
        'employees_count' => 'the number of employees',
        'berceaux_count' => 'the number of places',
        'contact_name' => 'the contact name',
        'contact_role' => 'the contact\'s job title',
        'contact_email' => 'the business email',
        'contact_tel' => 'the phone number',

        // Job application
        'nom' => 'your last name',
        'prenom' => 'your first name',
        'tel' => 'your phone number',
        'poste' => 'the position you are applying for',
        'documents' => 'your documents',
        'documents.*' => 'each document',

        // Pre-registration
        'care_type' => 'the type of childcare',
        'care_days' => 'the days of attendance',
        'care_days.*' => 'a day of attendance',
        'special_needs' => 'specific needs',
        'special_needs_details' => 'details of specific needs',
        'p1_civilite' => 'your title',
        'p1_fixe' => 'your landline number',
        'p1_profession' => 'your occupation',
        'p1_employeur' => 'your employer',
        'p1_cp' => 'your postcode',
        'p1_ville' => 'your town',
        'p1_adresse' => 'your address',
        'single_parent' => 'your family situation',
        'p2_civilite' => 'the second parent\'s title',
        'p2_nom' => 'the second parent\'s last name',
        'p2_prenom' => 'the second parent\'s first name',
        'p2_email' => 'the second parent\'s email',
        'p2_tel' => 'the second parent\'s phone number',
        'p2_profession' => 'the second parent\'s occupation',
        'p2_employeur' => 'the second parent\'s employer',
        'newsletter_opt_in' => 'news sign-up',
    ],

];
