## CRUD-API api crud systen

This repo was coded using Laravel 8


 1.  Clone this repo
 2.  cd CRUD-API
 3.  composer install
 4.  php artisan key:generate
 5.  php artisan migrate
 6.  php artisan serve
 7.  Create a database
 8.  Edit .env.example file and change credentials for the DB section and save the file as **.env**
 9.  Register a user via Postman using the follow POST method ending point: **127.0.0.1:8000/api/register**
 11. Copy the access_token given in the result and add it to the e-mail and password to a POST method to the login ending point: **127.0.0.1:8000/api/login**
 12. With the GET method to **127.0.0.1:8000/api/profile** you can see your user information by entering the access_token
 13. Logout using the ending point **127.0.0.1:8000/api/logout**. You will receive a goodbye message and your access_token will be deleted as well.
 14. Login again and create a pacientes using the ending point: **127.0.0.1:8000/api/pacientes** as a POST method.
 15. The same ending point **127.0.0.1:8000/api/pacientes** as a GET method will fetch all the pacientes created
 16. **127.0.0.1:8000/api/pacientes/{id}** will GET single data
 17. A PUT method do the ending point **127.0.0.1:8000/api/pacientes/1?name=Name of the pacientes EDITED&desc=This is a short description EDITED** will edit the pacientes with the **id = 1**
 18. And finally, using the DELETE method you can, of course, erase the pacientes **id = 1** pointing the Postman at **127.0.0.1:8000/api/pacientes/1**
 19. Just send a GET request to **/api/pacientes/search** with the desired name and/or cpf parameters. For example, to search for patients with the name "João da Silva", send a GET request to **//api/pacientes/search??name=Joao%20da%20Silva**. To search for patients with CPF **"12345678900"**, send a GET request to **/api/patients/search?cpf=12345678900**.
 20. For Docker Version:
     1.docker-compose up -d
     (this command start up the Docker)
     2.docker-compose exec app bash
     (use this command to run the next command)
     3.composer install
     (this command install all dependencies)
     4.php artisan key:generate
     (this command generate a key)
     5.Change the URL in the POSTMAN to localhost:8989/api/

Thank You!
