Project.Language = function( message ){

	return {

		'message' : message,
		'value' : message,

		load : function(){

			if( typeof language == 'undefined' ){

				alert("Set language var language = '<?=_LANG;?>';");
				return;
			}

			if( typeof Project.Session == 'undefined' ){

				alert('Enable Project.Session library');
				return;
			}

			this.getFromSession();
			return this.value;
		},
		
		getFromSession : function(){

			var self = this;
			var jsonData = Project.Session.get('language');

			if( jsonData == undefined || jsonData[language] == undefined ){

				self.storeToSession();
				return;
			}
			if( jsonData[language][self.message] == undefined ){

				self.storeToSession();
				return;
			}

			self.value = jsonData[language][self.message];
		},
		
		storeToSession : function(){

			var self = this;

			$.get('/Model/Translate/GetJsonTranslate/?translate=' + encodeURIComponent(self.message), function(data){

				$.canJSON( data, function(row){

					var jsonData = Project.Session.get('language');
					var storeData = {};
					storeData[language] = {};
					storeData[language][self.message] = row.value;

					if( typeof jsonData == 'undefined' || typeof jsonData == 'boolean' ){

						Project.Session.set('language', storeData);
					}
					else{

						var storedData = jsonData;
						storedData[language][self.message] = row.value;

						Project.Session.set('language', storedData);
					}
					self.value = row.value;
				} );
			});
		}

	}.load();
};

$(document).ready(function(){

	$.extend( {language : Project.Language} );
});