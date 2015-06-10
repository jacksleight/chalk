# Chalk Changelog

### 0.4.0

* **Improvements**
	* Ability to create pages within structure
	* Improved design

### 0.3.8

* **Improvements**
	* Updated Coast to 0.4

### 0.3.7

* **Improvements**
	* Speed optimisations
	* Tweaked file name handling
* **Fixes**
	* Bug fixes

### 0.3.6

* **Improvements**
	* Include query string in frontend redirect
* **Fixes**
	* Moved Doctrine metadata parsing to ensure cached data works
	* Bug fixes

### 0.3.5

* **Fixes**
	* Bug fixes

### 0.3.4

* **Fixes**
	* Bug fixes

### 0.3.3

* **Fixes**
	* Bug fixes

### 0.3.2

* **Fixes**
	* Bug fixes

### 0.3.1

* **Improvements**
	* Support random sorting in repository query
* **Fixes**
	* Bug fixes

### 0.3.0

* **Features**
	* CLI tools for database management etc.
	* New Repository->slug() method
	* Support for additional caching drivers
	* Bulk content archive and delete options
	* Content pagination
	* Content blocks for site-wide chunks of content
	* Content protection for fixed developer content
	* Full node path is now avaliable by default in frontend requests
* **Improvements**
	* File upload name sanitization
	* Speed optimisations	
	* UI updates
	* Repository->id() now supports criteria
	* File upload sort order is now maintined
	* Use doctrine nameing strategy
	* Abiltiy to extend Core\File
	* Ability to remove and replace content classes
	* View Site button now opens current page
	* Trim empty trailing paragraphs from content
	* Frontend Publishable queries now set isPublished to true by default
* **Fixes**
	* Fix issue with node tree references
	* Fix deleting content carying ID through to index
	* Fix stackable first item issue

### 0.2.4

* **Improvements**
	* Ability to add multiple items to stackable blocks
* **Fixes**
	* Fix stackable first item issue

### 0.2.3

* **Fixes**
	* Fix issue with multiple levels of nested widgets and links

### 0.2.2

* **Improvements**
	* Link to download uploaded files
* **Fixes**
	* Fix colour of tick and dot in checkbox and radio controls
	* Fix links to content not added to structure
	* Change sortable UI to avoid glitches with other controls
	* Fix search icon
	* Fix node moving when a node has zero children

### 0.2.1

* **Fixes**
	* Fix incorrect url in user delete button
	* Fix error in source code editor when value is blank
	* Fix icon rendering method
	* Fix array syntax in node tree methods
	* Fix widget element ordering issue

### 0.2.0

* **Features**
	* Custom HTML for <head> and <body> sections
	* Forgotten password reset
	* Notifications
	* Sorting and removing items in stacked blocks
	* New source code editor
* **Improvements**
	* Speed optimisations
	* Updated design
	* Less glitchy structure reorganisation UI
	* General usability improvements
* Fixes

### 0.1.0

* Initial release