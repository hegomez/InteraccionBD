var Usuario = require('./modelUsuarios.js')

module.exports.crearUsuarioDemo = function(callback){
	var arr = [{ user: 'he.gomez@hotmail.com', nombre: "HEINER GOMEZ VILLARREAL", password: "12345", fecha: "1989-03-23"}, { user: 'lorigut@hotmail.com', nombre: "LORAINE GUTIERREZ AVENDANIO", password: "54321", fecha: "1997-10-04"}, { user: 'benjigg@hotmail.com', nombre: "BENJAMIN GOMEZ GUTIERREZ", password: "13579", fecha: "2017-11-03"}];
	Usuario.insertMany(arr, function(error, docs) {
		if(error)
		{
			if(error.code == 11000)
			{
				callback("Los usuarios Fueron Creados Use sus credenciales")
			}
			else
			{
				callback(error.message)
			}
		}
		else
		{
			callback(null, "Los usuarios Fueron Creados Use sus credenciales")
		}
	});
}
