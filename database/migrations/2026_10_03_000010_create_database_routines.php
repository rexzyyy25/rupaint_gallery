<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') return;
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_order_total');
        DB::unprepared("CREATE FUNCTION calculate_order_total(p_order_id BIGINT) RETURNS DECIMAL(15,2) DETERMINISTIC READS SQL DATA BEGIN DECLARE total_order DECIMAL(15,2); SELECT COALESCE(SUM(subtotal),0) INTO total_order FROM order_items WHERE order_id=p_order_id; RETURN total_order; END");
        DB::unprepared('DROP PROCEDURE IF EXISTS get_order_detail');
        DB::unprepared("CREATE PROCEDURE get_order_detail(IN p_order_id BIGINT) BEGIN SELECT o.order_code,o.customer_name,o.customer_phone,o.status,oi.painting_id,oi.quantity,oi.price,oi.subtotal FROM orders o JOIN order_items oi ON o.id=oi.order_id WHERE o.id=p_order_id; END");
        DB::unprepared('DROP TRIGGER IF EXISTS before_order_item_insert');
        DB::unprepared("CREATE TRIGGER before_order_item_insert BEFORE INSERT ON order_items FOR EACH ROW SET NEW.subtotal=NEW.quantity*NEW.price");
        DB::unprepared('DROP TRIGGER IF EXISTS before_order_item_update');
        DB::unprepared("CREATE TRIGGER before_order_item_update BEFORE UPDATE ON order_items FOR EACH ROW SET NEW.subtotal=NEW.quantity*NEW.price");
    }
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') return;
        DB::unprepared('DROP TRIGGER IF EXISTS before_order_item_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS before_order_item_update');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_order_total');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_order_detail');
    }
};
