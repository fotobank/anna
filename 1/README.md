Class that generates HTML forms supporting:

- Multiple inputs may be interconnected in such way that client side events that occur on one input can trigger actions on the context of other inputs. Developers may use input interconnection support without writing Javascript code.
- Can be extended with new types of input controls plug-in classes.
- Custom input plug-in classes can be used to support for handling client site events on the server side without submitting the form or redrawing the whole form page
- Some control plug-in classes are made available:
* AJAX based form submission (without reloading the whole page)
* Auto-complete text inputs
* Select a location on a map using Google Maps API
* Calendar date input
* CAPTCHA test to prevent automated access by robots
* Linked select input to switch select options when the value of another input changes. An unlimited number of selected can be linked in cascade. Additional plug-in subclasses are provided to retrive option groups from a MySQL database or many other SQL databases using the Metabase PEAR::MDB2 PHP database abstraction layer APIs 
* Manage animations that apply visual effects to the page form elements, like: fade-in, fade-out, show, hide, update content, etc..
- XHTML compliant output.
- Load submitted form field values even with register_globals option Off and strip slashes when magic_quotes_gpc option is On.
- Keyboard navigation support:
* Attachment of labels with activation keys to each form field.
* Tab navigation order index.
- Built-in server side (PHP based) and client side (Javascript 1.0 or better) field validation for:
* E-mail address
* Credit card numbers (Visa, Mastercard, American Express, Discover, Diners Club, Carte Blanche, enRoute, JCB, any of these or even determined by a select field).
* Regular expressions.
* Field not empty.
* Field equal to another (useful for password confirmation fields).
* Field different from another (useful for reminder fields that must not be equal to the actual password).
* As set (for check boxes, radio buttons and select multiple fields).
* As integer number (with range limitation).
* As floating point number (with range limitation).
* Programmer defined client and server validation functions.
- Highlight invalid fields rendering them distinct CSS styles
- Security attack prevention by optionally discarding invalid values passed in fields that could not be edited by users but may be spoofed by attackers. 
- Option to define a value that, when used in a field, it is accepted without performing any of the validations defined for the field.
- Ability to stop the user from submiting a form more than once inadvertdly.
- Sub form validation (validate only smaller set of field depending on the submit button that was used).
- Composition and generation of the form HTML output with fields displayed as fully accessible or in read-only mode.
- Generation of Javascript functions (useful to set to the page ONLOAD event):
* Set the input focus to a field.
* Select the text of a field.
* Set the input focus and select the text of a field.
* Enable and disable input fields
- Automatic capitalization of the text of a field:
* Upper case.
* Lower case.
* Word initials
- Replacement of text field expressions to perform adjustments like trimming whitespace or auto-complete values based on rules defined by regular expressions
- Compose forms with templates using plain HTML files with embedded PHP code or using the Smarty template engine with a supplied pre-filter plugin
- Etc.