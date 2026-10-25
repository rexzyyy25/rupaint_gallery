CREATE DATABASE IF NOT EXISTS rupaint_gallery;
USE rupaint_gallery;

DROP FUNCTION IF EXISTS calculate_order_total;
DELIMITER //
CREATE FUNCTION calculate_order_total(p_order_id BIGINT)
RETURNS DECIMAL(15,2)
DETERMINISTIC
BEGIN
    DECLARE total_order DECIMAL(15,2);
    SELECT COALESCE(SUM(subtotal),0) INTO total_order FROM order_items WHERE order_id=p_order_id;
    RETURN total_order;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS get_order_detail;
DELIMITER //
CREATE PROCEDURE get_order_detail(IN p_order_id BIGINT)
BEGIN
    SELECT o.order_code,o.customer_name,o.customer_phone,o.status,
           oi.painting_id,oi.quantity,oi.price,oi.subtotal
    FROM orders o JOIN order_items oi ON o.id=oi.order_id
    WHERE o.id=p_order_id;
END//
DELIMITER ;

DROP TRIGGER IF EXISTS before_order_item_insert;
DELIMITER //
CREATE TRIGGER before_order_item_insert
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    SET NEW.subtotal=NEW.quantity*NEW.price;
END//
DELIMITER ;

DROP TRIGGER IF EXISTS before_order_item_update;
DELIMITER //
CREATE TRIGGER before_order_item_update
BEFORE UPDATE ON order_items
FOR EACH ROW
BEGIN
    SET NEW.subtotal=NEW.quantity*NEW.price;
END//
DELIMITER ;
