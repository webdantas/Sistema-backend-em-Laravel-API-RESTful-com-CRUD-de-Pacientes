## CRUD-API api crud systen

This repo was coded using Laravel 8


 1. Clone this repo
 2. cd CRUD-API
 3. composer install
 4. php artisan key:generate
 5. php artisan migrate
 6. php artisan serve
 7. Create a database
 8. Edit .env.example file and change credentials for the DB section and save the file as **.env**
 9. Open Postman and import the **API-CRUD.postman_collection.json** file
 10. Register a user via Postman using the follow POST method ending point: **127.0.0.1:8000/api/register**
 11. Copy the access_token given in the result and add it to the e-mail and password to a POST method to the login ending point: **127.0.0.1:8000/api/login**
 12. With the GET method to **127.0.0.1:8000/api/profile** you can see your user information by entering the access_token
 13. Logout using the ending point **127.0.0.1:8000/api/logout**. You will receive a goodbye message and your access_token will be deleted as well.
 14. Login again and create a salary using the ending point: **127.0.0.1:8000/api/salaries** as a POST method. Send the **name** and **desc** of the salary.
 15. The same ending point **127.0.0.1:8000/api/salaries** as a GET method will fetch all the salaries created
 16. **127.0.0.1:8000/api/salaries/{id}** will GET single data
 17. A PUT method do the ending point **127.0.0.1:8000/api/salaries/1?name=Name of the salary EDITED&desc=This is a short description EDITED** will edit the salary with the **id = 1**
 18. And finally, using the DELETE method you can, of course, erase the salary **id = 1** pointing the Postman at **127.0.0.1:8000/api/salaries/1**

Thank You!
