var clog = function(msg) {
	console.log( msg ? msg : '' );
}

$.fn.outerHTML = function() {
	return $('<div></div>').append( this.clone() ).html();
}

var pageAlert = function(msg) {
	clog('ALERT: ' + msg);
}

var getElemHtml = function(page) {

	return page.evaluate( function() {

		var match = $(sel);
		var elemHtml = [];

		match.each( function() {
			elemHtml.push( $(this).outerHTML() );
		});

		return elemHtml;
	});
}

var prettyPrintStats = function(allElemHtml) {

	var nElems = 0;
	var nPages = 0;

	for (url in allElemHtml) {
		var len = allElemHtml[url].length;
		for (var i = 0; i < len; ++i) {
			++nElems;
		}
		++nPages;
	}

	clog( 'Matched ' + nElems + ' elements on ' + nPages + ' pages' );
}

var prettyPrintUrlElems = function( url, allElemHtml )
{
	var len = allElemHtml[url].length;
	for (var i = 0; i < len; ++i) {
		clog( '  ' + (i+1) + '.  ' + allElemHtml[url][i]);
	}
}

var prettyPrint = function( allElemHtml ) {

	clog();
	prettyPrintStats( allElemHtml );

	for (url in allElemHtml) {
		clog();
		clog(url);
		clog();
		prettyPrintUrlElems( url, allElemHtml )
	}
	clog();
}

var createExterns = function ()
{


}

var writeFile = function( fName, data ) {
	var fs = require('fs');
	fullpath = fs.workingDirectory + fs.separator + fName;
	var dataFile = fs.open(fullpath, 'w');
	dataFile.write(data);
	dataFile.close();
}

function stripScripts(s) {
	var div = document.createElement('div');
	div.innerHTML = s;
	var scripts = div.getElementsByTagName('script');
	var i = scripts.length;
	while (i--) {
		scripts[i].parentNode.removeChild(scripts[i]);
	}
	return div.innerHTML;
}
