/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * JMediaManager behavior for media component
 *
 * @package		Joomla.Extensions
 * @subpackage  Media
 * @since		1.5
 */
(function($) {
var MediaManager = this.MediaManager = {

	initialize: function()
	{
		this.folderframe	= $('#folderframe');
		this.folderpath		= $('#folderpath');

		this.updatepaths	= $('input.update-folder');

		this.frame		= window.frames['folderframe'];
		this.frameurl	= this.frame.location.href;

		this.setTreeviewState();		
	},

	submit: function(task)
	{
		form = window.frames['folderframe'].document.getElementById('mediamanager-form');
		form.task.value = task;
		form.action += ('&controller=' + task);
		if ($('#username').length) {
			form.username.value = $('#username').val();
			form.password.value = $('#password').val();
		}
		form.submit();
	},

	submitWithTargetPath: function(task)
	{
		form = window.frames['folderframe'].document.getElementById('mediamanager-form');
		form.task.value = task;
		form.action += ('&controller=' + task);
		if ($('#username').length) {
			form.username.value = $('#username').val();
			form.password.value = $('#password').val();
		}
		var inp = document.createElement("input");
	    inp.type = "hidden";
	    inp.name = "targetPath";
	    
	    var method = task.split('.')[1];
	    if (method == "copy") {
	    	inp.value = $('#copyTarget #folderlist').find(":selected").text();
	    } else if (method == "move") {
	    	inp.value = $('#moveTarget #folderlist').find(":selected").text();
	    }

	    form.appendChild(inp);
		form.submit();
	},

	onloadframe: function()
	{
		// Update the frame url
		this.frameurl = this.frame.location.href;

		var folder = this.getFolder();
		if (folder) {
			this.updatepaths.each(function(path, el){ el.value =folder; });
			this.folderpath.val(basepath+'/'+folder+'/');
//			node = this.tree.get('node_'+folder);
//			node.toggle(false, true);
		} else {
			this.updatepaths.each(function(path, el){ el.value = ''; });
			this.folderpath.val(basepath+'/');
//			node = this.tree.root;
		}
/*
		if (node) {
			this.tree.select(node, true);
		}
*/
		document.id(viewstyle).addClass('active');
/*
		a = this._getUriObject(document.id('uploadForm').getProperty('action'));
		q = new Hash(this._getQueryObject(a.query));
		q.set('folder', folder);
		var query = [];
		q.each(function(v, k){
			if (v != null) {
				this.push(k+'='+v);
			}
		}, query);
		a.query = query.join('&');

		if (a.port) {
			document.id('uploadForm').setProperty('action', a.scheme+'://'+a.domain+':'+a.port+a.path+'?'+a.query);
		} else {
			document.id('uploadForm').setProperty('action', a.scheme+'://'+a.domain+a.path+'?'+a.query);
		}*/
	},

	oncreatefolder: function()
	{
		if ($('#foldername').val().length) {
			$('#dirpath').val() = this.getFolder();
			Joomla.submitbutton('createfolder');
		}
	},

	setViewType: function(type)
	{
		$('#' + type).addClass('active');
		$('#' + viewstyle).removeClass('active');
		viewstyle = type;
		var folder = this.getFolder();
		this._setFrameUrl('index.php?option=com_media&controller=media.display.medialist&view=medialist&tmpl=component&folder='+folder+'&layout='+type);
	},

	refreshFrame: function()
	{
		this._setFrameUrl();
	},

	getFolder: function()
	{
		var url	 = this.frame.location.search.substring(1);
		var args	= this.parseQuery(url);

		if (args['folder'] == "undefined") {
			args['folder'] = "";
		}

		return args['folder'];
	},

	parseQuery: function(query)
	{
		var params = new Object();
		if (!query) {
			return params;
		}
		var pairs = query.split(/[;&]/);
		for ( var i = 0; i < pairs.length; i++ )
		{
			var KeyVal = pairs[i].split('=');
			if ( ! KeyVal || KeyVal.length != 2 ) {
				continue;
			}
			var key = unescape( KeyVal[0] );
			var val = unescape( KeyVal[1] ).replace(/\+ /g, ' ');
			params[key] = val;
	   }
	   return params;
	},

	_setFrameUrl: function(url)
	{
		if (url != null) {
			this.frameurl = url;
		}
		this.frame.location.href = this.frameurl;
	},

	_getQueryObject: function(q) {
		var vars = q.split(/[&;]/);
		var rs = {};
		if (vars.length) vars.forEach(function(val) {
			var keys = val.split('=');
			if (keys.length && keys.length == 2) rs[encodeURIComponent(keys[0])] = encodeURIComponent(keys[1]);
		});
		return rs;
	},

	_getUriObject: function(u){
		var bitsAssociate = {}, bits = u.match(/^(?:([^:\/?#.]+):)?(?:\/\/)?(([^:\/?#]*)(?::(\d*))?)((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[\?#]|$)))*\/?)?([^?#\/]*))?(?:\?([^#]*))?(?:#(.*))?/);
		['uri', 'scheme', 'authority', 'domain', 'port', 'path', 'directory', 'file', 'query', 'fragment'].forEach(function(key, index){
		    bitsAssociate[key] = bits[index];
		});

		return (bits)
			? bitsAssociate
			: null;
	},

	setTreeviewState: function(){
		// Load the value from localStorage
		if (typeof(Storage) !== "undefined")
		{
			var $visible = localStorage.getItem('jsidebar');
		}

		// Need to convert the value to a boolean
		$visible = ($visible == 'true') ? true : false;

		// Toggle according to j-sidebar class status or storage saved status
		var classStatus = jQuery('#j-sidebar-container').attr('class');
		if(classStatus.contains('j-toggle-hidden') || $visible)
		{
			jQuery('#treeview').attr('hidden', true);
		}
		else
		{
			jQuery('#treeview').attr('hidden', false);
		}
	}
};
})(jQuery);

jQuery(function(){
	// Added to populate data on iframe load
	MediaManager.initialize();
	MediaManager.trace = 'start';
	document.updateUploader = function() { MediaManager.onloadframe(); };
	MediaManager.onloadframe();
});
