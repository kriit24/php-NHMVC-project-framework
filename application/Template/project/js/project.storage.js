Project.Local = {

	storage: localStorage,

	set: function(name, value){

		if( typeof value == 'object' )
			var jsonValue = new Array(value);
		else
			var jsonValue = value;

		this.storage.setItem(name, JSON.stringify(jsonValue));
	},

	get: function(name){

		if( this.storage.getItem(name) == undefined )
			return;

		var ret = JSON.parse(this.storage.getItem(name));
		if( typeof ret == 'string' || typeof ret == 'number' || typeof ret == 'integer' )
			return ret;
		return ret[0];
	},

	remove: function(name){

		this.storage.removeItem(name);
	},

	clear: function(){

		this.storage.clear();
	}
};

Project.Session = {

	storage: sessionStorage,

	set: function(name, value){

		if( typeof value == 'object' )
			var jsonValue = new Array(value);
		else
			var jsonValue = value;

		this.storage.setItem(name, JSON.stringify(jsonValue));
	},

	get: function(name){

		if( this.storage.getItem(name) == undefined )
			return;

		var ret = JSON.parse(this.storage.getItem(name));
		if( typeof ret == 'string' || typeof ret == 'number' || typeof ret == 'integer' )
			return ret;
		return ret[0];
	},

	remove: function(name){

		this.storage.removeItem(name);
	},

	clear: function(){

		this.storage.clear();
	}
};