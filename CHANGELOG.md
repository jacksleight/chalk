# Chalk Changelog

### 0.1.0

* Initial release

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

### 0.2.1

* **Fixes**
	* Fix incorrect url in user delete button
	* Fix error in source code editor when value is blank
	* Fix icon rendering method
	* Fix array syntax in node tree methods
	* Fix widget element ordering issue

### 0.2.2

* **Improvements**
	* Link to download uploaded files
* **Fixes**
	* Fix colour of tick and dot in checkbox and radio controls
	* Fix links to content not added to structure
	* Change sortable UI to avoid glitches with other controls
	* Fix search icon
	* Fix node moving when a node has zero children

### 0.2.3

* **Fixes**
	* Fix issue with multiple levels of nested widgets and links

### 0.2.4

* **Improvements**
	* Ability to add multiple items to stackable blocks
* **Fixes**
	* Fix stackable first item issue

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

### 0.3.1

* **Improvements**
	* Support random sorting in repository query
* **Fixes**
	* Bug fixes

### 0.3.2

* **Fixes**
	* Bug fixes

### 0.3.3

* **Fixes**
	* Bug fixes

### 0.3.4

* **Fixes**
	* Bug fixes

### 0.3.5

* **Fixes**
	* Bug fixes

### 0.3.6

* **Improvements**
	* Include query string in frontend redirect
* **Fixes**
	* Moved Doctrine metadata parsing to ensure cached data works
	* Bug fixes

### 0.3.7

* **Improvements**
	* Speed optimisations
	* Tweaked file name handling
* **Fixes**
	* Bug fixes

### 0.3.8

* **Improvements**
	* Updated Coast to 0.4

### 0.5.0

* **Features**
	* Replace existing files
	* Preview draft content (when logged in)
	* Open current content directly from CMS
	* Bulk publish/archive/restore/delete
	* Use local timezone for date/time inputs
	* Basic content tagging
	* Filtering content by subtype
	* Create child and sibling pages from within structure tab
	* Login form remembers requested URL
	* Full internal linking support for all types of content 
	* Control sorting of items in content lists
	* Quick add URLs
	* Full editor widget previews
	* Structure path prefixes
* **Improvements**
	* New content browser for selecting files/pages/links etc.
	* Update and improved UI throughout
	* Speed improvements
* **Fixes**
	* Bug fixes

### 0.5.1

* **Features**
	* Ability to retain whitespace in HTML parser
	* Option to set maximum file upload size
* **Improvements**
	* Session keep-alive while the browser window is open
* **Fixes**
	* Bug fixes

### 0.5.2

* **Fixes**
	* Bug fixes

### 0.5.3

* **Fixes**
	* Bug fixes

### 0.5.4

* **Improvements**
	* Modal window and notification changes
* **Fixes**
	* Bug fixes

### 0.5.5

* **Improvements**
	* Fetch related contet tags repo method
	* Frontend timezone method
	* Styling tweaks
	* Sitemap module support
	* Dynamic robots.txt support
	* Module related enhancements
* **Fixes**
	* Bug fixes

### 0.5.6

* **Improvements**
	* Add file list date filter
	* Layout tweaks
* **Fixes**
	* Bug fixes

### 0.5.7

* **Fixes**
	* Bug fixes

### 0.5.8

* **Fixes**
	* Bug fixes

### 0.5.9

* **Fixes**
	* Bug fixes

### 0.5.10

* **Fixes**
	* Bug fixes

### 0.5.11

* **Improvements**
	* Support additional cache drivers

### 0.5.12

* **Improvements**
	* SVG image previews
	* New date picker
* **Fixes**
	* Bug fixes

### 0.5.13

* **Fixes**
	* Bug fixes

### 0.5.14

* **Fixes**
	* Bug fixes

### 0.6.0

* **Improvements**
	* CLI scripts per module
	* CLI non-interactive mode
	* CLI arguments/params/flags
	* Migrations support
	* Repository INDEXBY support
	* Settings table
	* Ability to confifure mail driver
	* Customisable widget form/render view paths
	* Customisable widget render params
* **Fixes**
	* Bug fixes

### 0.6.1

* **Fixes**
	* Bug fixes