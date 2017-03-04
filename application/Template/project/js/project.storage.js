//TO WRITE STORAGE JSON OBJECT
/*
var storeData = {};//make object NOT array
storeData[language] = {};//make key as object NOT array
storeData[language][self.message] = row.value;//store data into object WHAT can accessible like array
Project.Session.set('name', storeData);//it will automatically make object/array to json
Project.Session.get('name');//it will automatically get from json as object/array
*/

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
			return false;

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
			return false;

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