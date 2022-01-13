# api_codespace
## Slim API Framework implementation
This project was all about creating APIs and manoevuring in the backend. As part of the project I had to create the following endpoints:
* Login Endpoint that can accept POST/PATCH/GET;
* Carts endpoint that can accept POST/PATCH/DELETE and that has a limit of 20 items;
*  Categories endpoint that can accept a GET and has a limit of 10 categories;
* Products endpoint that has 100 products and can accept GET/POST/PATCH/DELETE;
* Wishlist endpoint that can accept GET/POST/DELETE/PATCH.

I implement the above using the SLIM api framework which eased the creation of APIs along with a MySQL database.

## Data base structure
For my database I set up a few individual databases:
* login_endpoint;
* product_endpoint;
* cart_endpoint;
* wishlist_endpoint;
* categories_endpoint.

The intention of each of these databases was to hold data for the endpoints so that a front end system could interact with them.

## File layout
I have laid out my file structure in the following way:
* public-> this is where index.php is located
* config-> this is where the database connection file class is located
* routes-> these are the endpoint indicators where the API's will be found.

### Login Endpoint
The login endpoint can accept the following interactions:
* localhost/api/login/username/password/token -> the incorporation of all of these ensures a rudimentary, if unsafe login system. It will return values which can be checked against (GET); 
* localhost/api/login/add -> this accepts a json file with the values username, password and token. It can be used to create a new login entry in the database (POST);
* localhost/api/login/patchuser -> this can be used to update a username. It uses a token as a checking method (as the user would presumably be logged in). It accepts its data in the form of a JSON post (PATCH);
* localhost/api/login/patchpass -> this can be used to update a user's password. It once again accepts a token as a validation method. (PATCH)

### Product Endpoint
The product endpoint can accept the following interactions:
* localhost/api/products/all -> list all products and their characteristics as contained in the database (GET);
* localhost/api/poducts/{id} -> get a single product from the product database (GET);
* localhost/api/products/add -> add a new product. Accepts name, description, image, price, category_id via a JSON file (POST);
* localhost/api/products/delete/{id} -> delete a product based on id (DELETE).
I have purposefull not added an update endpoint. From my point of view this would be easier to update on the back end than through a graphical interface.

### Wishlist Endpoint
The wishlist endpoint can accept the following interactions:
* localhost/api/wishlist/{id} -> get the name and price of a wishlist item based on the user's id (GET);
* localhost/api/wishlist/add -> add a new item to the wishlist. Accepts product_id and user_id in the form of a JSON (POST);
* localhost/api/wishlist/delete/{id}/{product_id} -> delete a item based on the user id and the product id (DELETE).

### Cart Endpoint
The cart endpoint can accept the following interactions:
* localhost/api/cart/{id} -> get the name, image, price and user id from a cart based on the user id (GET);
* localhost/api/cart/add -> accepts a JSON file containing product_id and user_id. I made use of a MySQL stored procedure to limit the amount of items that could be added to the cart to 20 (POST);
Example:
```
   DELIMITER $$

CREATE PROCEDURE cart_updater(
    IN Aproduct_id INT,
    IN Auser_id INT
  )
  BEGIN
    SET @return = 1;
    SET @recCount = (select count(user_id) from cart_endpoint WHERE user_id = Auser_id);
    If @recCount <= 20 THEN
    INSERT INTO cart_endpoint (product_id,user_id,add_remove) VALUES (AProduct_id, Auser_id, 'add');
    ELSE
    SET @return = "Cart limit reached, remove items";
    SELECT @return;
    END IF;
  END $$
```
* localhost/api/cart/delete/{product_id}/{user_id} -> delete a product from the cart based on its product id and the user id (DELETE).

## Closing Thoughts
This was a cool project and overall it taught me a lot about database work. I'll look forward to using it in the near future for my own personal projects.
