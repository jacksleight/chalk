/* Libraries */

//= require ../../bower_components/log/log.js
//= require ../../bower_components/mootools/Source/Core/Core.js
//= require ../../bower_components/mootools/Source/Types/Array.js
//= require ../../bower_components/mootools/Source/Types/String.js
//= require ../../bower_components/mootools/Source/Types/Number.js
//= require ../../bower_components/mootools/Source/Types/Function.js
//= require ../../bower_components/mootools/Source/Types/Object.js
//= require ../../bower_components/mootools/Source/Types/DOMEvent.js
//= require ../../bower_components/mootools/Source/Browser/Browser.js
//= require ../../bower_components/mootools/Source/Class/Class.js
//= require ../../bower_components/mootools/Source/Class/Class.Extras.js
//= require ../../bower_components/mootools/Source/Slick/Slick.Parser.js
//= require ../../bower_components/mootools/Source/Slick/Slick.Finder.js
//= require ../../bower_components/mootools/Source/Element/Element.js
//= require ../../bower_components/mootools/Source/Element/Element.Style.js
//= require ../../bower_components/mootools/Source/Element/Element.Event.js
//= require ../../bower_components/mootools/Source/Element/Element.Delegation.js
//= require ../../bower_components/mootools/Source/Element/Element.Dimensions.js
//= require ../../bower_components/mootools/Source/Fx/Fx.js
//= require ../../bower_components/mootools/Source/Fx/Fx.CSS.js
//= require ../../bower_components/mootools/Source/Fx/Fx.Tween.js
//= require ../../bower_components/mootools/Source/Fx/Fx.Morph.js
//= require ../../bower_components/mootools/Source/Fx/Fx.Transitions.js
//= require ../../bower_components/mootools/Source/Request/Request.js
//= require ../../bower_components/mootools/Source/Request/Request.HTML.js
//= require ../../bower_components/mootools/Source/Request/Request.JSON.js
//= require ../../bower_components/mootools/Source/Utilities/Cookie.js
//= require ../../bower_components/mootools/Source/Utilities/JSON.js
//= require ../../bower_components/mootools/Source/Utilities/DOMReady.js
//= require ../../bower_components/mootools/Source/Utilities/Swiff.js
//= require ../../bower_components/fastclick/lib/fastclick.js
//= require ../../bower_components/js-cookies/source/js-cookies.js

/* Initialize */

FastClick.attach(document.body);

new JS.Cookies({
	cookiePath:	App.options.base,
	policyUrl:	App.options.policy,
	message:	'<strong>We use cookies to give you the best possible experience on our web site.</strong> Please refer to our <a href="/privacy-policy">Privacy Policy</a> for more information.'
});