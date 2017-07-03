# my_api

How to use my_api :

1) Fill config.json.tmp and rename it to config.json

2) If you wish to add more Entities, add them in the $entities array in the requireEntities method from api.php. Make sure their methods are compatible with the Entity parent class (and extend it).

3) All entities name must respect some norm : The first letter is uppercase and the name is the singular of the table name
	For instance :
		'User.php' is the class name
		'users' is the table name
		
All commits messages are inexistants since i'm mostly working on my school git server.
