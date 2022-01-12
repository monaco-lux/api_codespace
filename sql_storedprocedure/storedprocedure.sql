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
    END IF
  END$$
