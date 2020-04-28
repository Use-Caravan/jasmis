-----------------------------------------------------------------------------------
-- Migration Status
-- Live - No
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `corporate_voucher_file` (
  `corporate_voucher_file_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `corporate_voucher_file_key` VARCHAR(32) NULL DEFAULT NULL,
  `order_id` BIGINT(20) NULL DEFAULT NULL,
  `user_corporate_id` BIGINT(10) NULL DEFAULT NULL,
  `file_path` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`corporate_voucher_file_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `user_corporate` 
ADD COLUMN `company_logo` VARCHAR(255) NULL DEFAULT NULL AFTER `contact_name`

-----------------------------------------------------------------------------------



-----------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `order` 
ADD COLUMN `claim_corporate_offer_booking` TINYINT(1) NULL DEFAULT NULL AFTER `user_corporate_id`,
ADD COLUMN `corporate_voucher_code` VARCHAR(32) NULL DEFAULT NULL AFTER `claim_corporate_offer_booking`;


ALTER TABLE `user_corporate` 
CHANGE COLUMN `status` `status` TINYINT(1) NULL DEFAULT NULL AFTER `is_booked`,
CHANGE COLUMN `updated_at` `updated_at` DATETIME NULL DEFAULT NULL AFTER `status`,
ADD COLUMN `order_id` BIGINT(20) NULL DEFAULT NULL AFTER `user_corporate_key`;

-----------------------------------------------------------------------------------

-----------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

/** This one have to update in customer live db **/

ALTER TABLE `order` 
ADD COLUMN `user_corporate_id` BIGINT(20) NULL DEFAULT NULL AFTER `order_booked_by`;

CREATE TABLE IF NOT EXISTS `corporate_voucher_item` (
  `corporate_voucher_item_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `corporate_voucher_id` BIGINT(20) NULL DEFAULT NULL,
  `order_item_id` BIGINT(20) NULL DEFAULT NULL,
  `quantity` INT(11) NULL DEFAULT NULL,
  `is_claimed` TINYINT(1) NULL DEFAULT 0 COMMENT '1 - Claimed\n0 - Not Claimed\n',
  `claimed_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`corporate_voucher_item_id`),
  INDEX `fk_corporate_voucher_item_1_idx` (`corporate_voucher_id` ASC),
  INDEX `fk_corporate_voucher_item_2_idx` (`order_item_id` ASC),
  CONSTRAINT `fk_corporate_voucher_item_1`
    FOREIGN KEY (`corporate_voucher_id`)
    REFERENCES `corporate_voucher` (`corporate_voucher_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_corporate_voucher_item_2`
    FOREIGN KEY (`order_item_id`)
    REFERENCES `order_item` (`order_item_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1


CREATE TABLE IF NOT EXISTS `corporate_voucher` (
  `corporate_voucher_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `corporate_voucher_key` VARCHAR(32) NULL DEFAULT NULL,
  `voucher_number` VARCHAR(32) NULL DEFAULT NULL,
  `order_id` BIGINT(20) NULL DEFAULT NULL,
  `user_corporate_id` BIGINT(20) NULL DEFAULT NULL,
  `is_claimed` TINYINT(1) NULL DEFAULT 0 COMMENT '1 - Claimed\n0 - No Claimed\n\n',
  `claimed_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`corporate_voucher_id`),
  INDEX `fk_corporate_vouchers_1_idx` (`order_id` ASC),
  INDEX `fk_corporate_vouchers_2_idx` (`user_corporate_id` ASC),
  CONSTRAINT `fk_corporate_vouchers_1`
    FOREIGN KEY (`order_id`)
    REFERENCES `order` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_corporate_vouchers_2`
    FOREIGN KEY (`user_corporate_id`)
    REFERENCES `user_corporate` (`user_corporate_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


ALTER TABLE `order` 
CHANGE COLUMN `payment_type` `payment_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Online\n2 - COD\n3 - Wallet\n5 - Corporate Online\n6 - Corporate Payment Credit\n7 - Corporate Payment LPO' 


ALTER TABLE `order` 
ADD COLUMN `order_booked_by` TINYINT(1) NULL DEFAULT 1 COMMENT '1 - Customer\n2 - Corporates' AFTER `order_number`


ALTER TABLE `corporate_offer` 
ADD COLUMN `start_datetime` DATETIME NULL DEFAULT NULL AFTER `offer_value`,
ADD COLUMN `end_datetime` DATETIME NULL DEFAULT NULL AFTER `start_datetime`

ALTER TABLE `user` 
ADD COLUMN `user_type` TINYINT(1) NULL DEFAULT 1 COMMENT '1 - Customer\n2 - Corporates' AFTER `user_key`;

CREATE TABLE IF NOT EXISTS `user_corporate` (
  `user_corporate_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_corporate_key` VARCHAR(32) NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT NULL,
  `corporate_name` VARCHAR(245) NULL DEFAULT NULL,
  `contact_name` VARCHAR(245) NULL DEFAULT NULL,
  `office_email` VARCHAR(245) NULL DEFAULT NULL,
  `mobile_number` VARCHAR(20) NULL DEFAULT NULL,
  `contact_address` TEXT NULL DEFAULT NULL,
  `voucher_description` TEXT NULL DEFAULT NULL,
  `is_booked` TINYINT(1) NULL DEFAULT 0 COMMENT '1 - Booked\n0 - Not Booked ',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`user_corporate_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1

CREATE TABLE IF NOT EXISTS `corporate_offer` (
  `corporate_offer_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `corporate_offer_key` VARCHAR(32) NULL DEFAULT NULL,
  `offer_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Qty Based\n2 - Price Based',
  `offer_level` INT(11) NULL DEFAULT NULL,
  `offer_value` DOUBLE(10,2) NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`corporate_offer_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE TABLE IF NOT EXISTS `corporate_offer_lang` (
  `corporate_offer_lang_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `corporate_offer_id` BIGINT(20) NULL DEFAULT NULL,
  `language_code` VARCHAR(8) NULL DEFAULT NULL,
  `offer_name` VARCHAR(255) NULL DEFAULT NULL,
  `offer_description` VARCHAR(245) NULL DEFAULT NULL,
  `offer_banner` VARCHAR(245) NULL DEFAULT NULL,
  PRIMARY KEY (`corporate_offer_lang_id`),
  INDEX `fk_corporate_offer_lang_1_idx` (`corporate_offer_id` ASC),
  CONSTRAINT `fk_corporate_offer_lang_1`
    FOREIGN KEY (`corporate_offer_id`)
    REFERENCES `corporate_offer` (`corporate_offer_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-----------------------------------------------------------------------------------

-----------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `branch_user` 
ADD COLUMN `default_language` VARCHAR(8) NULL DEFAULT NULL AFTER `remember_token`;

ALTER TABLE `vendor`
ADD COLUMN `default_language` VARCHAR(8) NULL DEFAULT NULL AFTER `device_token`;

ALTER TABLE `language` 
ADD COLUMN `is_default` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Yes\n0 - No' AFTER `status`;

-----------------------------------------------------------------------------------


-----------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind


CREATE TABLE IF NOT EXISTS `user_loyalty_credit` (
  `user_loyalty_credit_id` bigint(20) NOT NULL,
  `user_loyalty_credit_key` varchar(32) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `loyalty_point_id` bigint(20) DEFAULT NULL,
  `order_amount` double DEFAULT NULL,
  `loyalty_point` int(11) DEFAULT NULL,
  `transaction_for` tinyint(1) DEFAULT NULL COMMENT '1 - Credit point for place order \n2 - Debit point for redeem point to wallet',
  `status` tinyint(1) DEFAULT NULL,
  `previous_user_point` int(11) DEFAULT NULL,
  `current_user_point` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `loyalty_level` 
ADD COLUMN `redeem_amount_per_point` DOUBLE NULL DEFAULT NULL AFTER `to_point`;

ALTER TABLE `transaction` 
CHANGE COLUMN `transaction_for` `transaction_for` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Online Payment Order \n2 - Add to Wallet\n3 - Points Redeem\n4 - Wallet Payment Order';

ALTER TABLE `order` 
CHANGE COLUMN `payment_reponse` `transaction_id` BIGINT(20) NULL DEFAULT NULL AFTER `deliveryboy_key`


ALTER TABLE `payment_gateway` 
ADD COLUMN `response_received_data` TEXT NULL DEFAULT NULL AFTER `received_data`;

ALTER TABLE `transaction` 
CHANGE COLUMN `transaction_number` `transaction_number` VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE `payment_gateway` 
CHANGE COLUMN `payment_gateway_id` `payment_gateway_id` BIGINT(20) NOT NULL AUTO_INCREMENT;


ALTER TABLE `transaction` 
CHANGE COLUMN `reference_id` `payment_gateway_id` BIGINT(20) NULL DEFAULT NULL;

ALTER TABLE `payment_gateway` 
CHANGE COLUMN `transaction_id` `gateway_url` TEXT NULL DEFAULT NULL;


CREATE TABLE IF NOT EXISTS `payment_gateway` (
  `payment_gateway_id` BIGINT(20) NOT NULL,
  `transaction_id` BIGINT(20) NULL DEFAULT NULL,
  `sent_data` TEXT NULL DEFAULT NULL,
  `received_data` TEXT NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Success\n2 - Failiur',
  PRIMARY KEY (`payment_gateway_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8

-----------------------------------------------------------------------------------


-----------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `banner` 
ADD COLUMN `is_home_banner` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Yes \n0 - No' AFTER `banner_file`;
-----------------------------------------------------------------------------------

-----------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `order` 
CHANGE COLUMN `vendor_commission` `vendor_commission` DOUBLE NULL DEFAULT NULL;

ALTER TABLE `order` 
ADD COLUMN `vendor_commission` INT(11) NULL DEFAULT NULL AFTER `order_payment_id`,
ADD COLUMN `vendor_profit` DOUBLE NULL DEFAULT NULL AFTER `vendor_commission`,
ADD COLUMN `admin_profit` DOUBLE NULL DEFAULT NULL AFTER `vendor_profit`,
ADD COLUMN `vendor_payment_status` TINYINT(1) NULL DEFAULT NULL COMMENT '0 - Pending\n1 - Paid\n2 - Cancelled ' AFTER `admin_profit`;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `offer` 
ADD COLUMN `status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Active\n0 - Inactive' AFTER `display_in_home`;


CREATE TABLE IF NOT EXISTS `offer` (
  `offer_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `offer_key` VARCHAR(32) NULL DEFAULT NULL,
  `vendor_id` BIGINT(20) NULL DEFAULT NULL,
  `branch_id` BIGINT(20) NULL DEFAULT NULL,
  `offer_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Flat\n2 - Percentage\n',
  `offer_value` DOUBLE NULL DEFAULT NULL,
  `start_datetime` DATETIME NULL DEFAULT NULL,
  `end_datetime` DATETIME NULL DEFAULT NULL,
  `display_in_home` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Yes\n2 - No',
  `created_at` VARCHAR(45) NULL DEFAULT NULL,
  `updated_at` VARCHAR(45) NULL DEFAULT NULL,
  `deleted_at` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`offer_id`),
  INDEX `fk_offer_1_idx` (`vendor_id` ASC),
  INDEX `fk_offer_2_idx` (`branch_id` ASC),
  CONSTRAINT `fk_offer_1`
    FOREIGN KEY (`vendor_id`)
    REFERENCES `vendor` (`vendor_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_offer_2`
    FOREIGN KEY (`branch_id`)
    REFERENCES `branch` (`branch_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


CREATE TABLE IF NOT EXISTS `offer_lang` (
  `offer_lang_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `offer_id` BIGINT(20) NULL DEFAULT NULL,
  `language_code` VARCHAR(8) NULL DEFAULT NULL,
  `offer_name` TEXT NULL DEFAULT NULL,
  `offer_banner` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`offer_lang_id`),
  INDEX `fk_offer_lang_1_idx` (`offer_id` ASC),
  CONSTRAINT `fk_offer_lang_1`
    FOREIGN KEY (`offer_id`)
    REFERENCES `offer` (`offer_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8


CREATE TABLE IF NOT EXISTS `offer_item` (
  `offer_item_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `offer_id` BIGINT(20) NULL DEFAULT NULL,
  `item_id` BIGINT(20) NULL DEFAULT NULL,
  PRIMARY KEY (`offer_item_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


ALTER TABLE `offer_item` 
ADD INDEX `fk_offer_item_1_idx` (`offer_id` ASC),
ADD INDEX `fk_offer_item_2_idx` (`item_id` ASC)
ALTER TABLE `offer_item` 
ADD CONSTRAINT `fk_offer_item_1`
  FOREIGN KEY (`offer_id`)
  REFERENCES `offer` (`offer_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_offer_item_2`
  FOREIGN KEY (`item_id`)
  REFERENCES `item` (`item_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;



------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `ingredient_group` 
ADD COLUMN `vendor_id` BIGINT(20) NULL DEFAULT NULL AFTER `ingredient_type`;
ADD INDEX `fk_ingredient_group_1_idx` (`vendor_id` ASC)
ALTER TABLE `ingredient_group` 
ADD CONSTRAINT `fk_ingredient_group_1`
  FOREIGN KEY (`vendor_id`)
  REFERENCES `vendor` (`vendor_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `branch_user` 
CHANGE COLUMN `branch_user_id` `branch_user_id` BIGINT(20) NULL DEFAULT NULL AUTO_INCREMENT ,
ADD COLUMN `vendor_id` BIGINT(20) NULL DEFAULT NULL AFTER `branch_id`;


ALTER TABLE `ingredient` 
ADD COLUMN `vendor_id` BIGINT(20) NULL DEFAULT NULL AFTER `ingredient_key`;


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `branch_user` 
CHANGE COLUMN `branch_user_id` `branch_user_id` BIGINT(20) NULL DEFAULT NULL AUTO_INCREMENT ,
ADD COLUMN `remember_token` VARCHAR(100) NULL DEFAULT NULL AFTER `password`;


ALTER TABLE `order` 
CHANGE COLUMN `order_status` `order_status` TINYINT(1) NULL DEFAULT 0 COMMENT '0 - Pending\n1 - Approved\n2 - Rejected\n3 - Preparing\n4 - Driver Accepted\n5 - Ready for Pickup\n6 - Driver Picked Up\n7 - Delivered\n8 - Completed\n9 - Assign to Driver\n10 - Order on the way\n11 - Order driver delivered\n12 - Order driver requested\n13 - Order driver rejected';

ALTER TABLE `order` 
ADD COLUMN `deliveryboy_key` VARCHAR(100) NULL DEFAULT NULL AFTER `order_status`;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `vendor` 
ADD COLUMN `remember_token` VARCHAR(100) NULL DEFAULT NULL AFTER `password`;
------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `role` 
CHANGE COLUMN `user_type` `user_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Admin\n2 - Vendor\n3 - Outlet';


ALTER TABLE `role` 
ADD COLUMN `user_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Vendor\n2 - Outlet' AFTER `role_name`;

ALTER TABLE `order` 
CHANGE COLUMN `order_status` `order_status` TINYINT(1) NULL DEFAULT 0 COMMENT '0 - Pending\n1 - Approved\n2 - Rejected\n3 - Preparing\n4 - Driver Accepted\n5 - Ready for Pickup\n6 - Driver Picked Up\n7 - Delivered\n8 - Completed\n9 - Assign to Driver';


ALTER TABLE `order` 
ADD COLUMN `order_refkey` VARCHAR(255) NULL DEFAULT NULL AFTER `order_key`;

ALTER TABLE `order_item` 
ADD COLUMN `item_instruction` TEXT NULL DEFAULT NULL AFTER `item_subtotal`;

ALTER TABLE `cart_item` 
ADD COLUMN `item_instruction` TEXT NULL DEFAULT NULL AFTER `ingredients`;

ALTER TABLE `branch_user` 
CHANGE COLUMN `branch_user_id` `branch_user_id` BIGINT(20) NULL DEFAULT NULL AUTO_INCREMENT ,
ADD INDEX `fk_branch_user_1_idx` (`branch_id` ASC)
ALTER TABLE `branch_user` 
ADD CONSTRAINT `fk_branch_user_1`
  FOREIGN KEY (`branch_id`)
  REFERENCES `branch` (`branch_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;
------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind


CREATE TABLE IF NOT EXISTS `branch_user` (
  `branch_user_id` BIGINT(20) NULL DEFAULT NULL AUTO_INCREMENT,
  `branch_user_key` VARCHAR(32) NULL DEFAULT NULL,
  `branch_id` BIGINT(20) NULL DEFAULT NULL,
  `username` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `phone_number` VARCHAR(20) NULL DEFAULT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `device_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Web\n2 - Android\n3 - IOS\n4 - Windows',
  `device_token` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`branch_user_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `order` 
ADD COLUMN `order_reject_reason` VARCHAR(255) NULL DEFAULT NULL AFTER `order_rejected_datetime`;

ALTER TABLE `order` 
CHANGE COLUMN `order_status` `order_status` TINYINT(1) NULL DEFAULT 0 COMMENT '0 - Pending\n1 - Approved\n2 - Rejected\n3 - Preparing\n4 - Driver Accepted\n5 - Ready for Pickup\n6 - Driver Picked Up\n7 - Delivered\n8 - Completed';


------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind


ALTER TABLE `vendor` ADD `device_type` TINYINT(1) NOT NULL COMMENT '1 - Web 2 - Android 3 - IOS 4 - Windows' AFTER `payment_option`, ADD `device_token` VARCHAR(255) NOT NULL AFTER `device_type`;


ALTER TABLE `order` 
DROP COLUMN `order_processing_status`,
CHANGE COLUMN `order_approval_status` `order_status` TINYINT(1) NULL DEFAULT 0 COMMENT '0 - Pending\n1 - Approved\n2 - Rejected\n3 - Preparing\n4 - Driver Accepted\n5 - Driver Picked Up\n6 - Delivered\n7 - Completed';



ALTER TABLE `order` 
ADD COLUMN `delivery_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - ASAP\n2 - Pre Order' AFTER `order_type`,
ADD COLUMN `delivery_datetime` DATETIME NULL DEFAULT NULL AFTER `delivery_distance`;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `vendor_password_reset` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `order_item_ingredient_group` 
ADD COLUMN `ingredient_group_id` BIGINT(20) NULL DEFAULT NULL AFTER `order_item_id`;
ALTER TABLE `order_ingredient` 
ADD COLUMN `ingredient_id` BIGINT(20) NULL DEFAULT NULL AFTER `order_item_ingredient_group_id`;
ALTER TABLE `order` 
ADD COLUMN `cart_id` BIGINT(20) NULL DEFAULT NULL AFTER `user_address_id`;
ALTER TABLE `cms` 
ADD COLUMN `position` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Terms & condition\n2 - Privacy Policy\n3 - Others' AFTER `sort_no`;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `branch` 
CHANGE COLUMN `order_type` `order_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Delivery\n2 - Pickup & DineIn\n3 - Both';

ALTER TABLE `vendor` 
CHANGE COLUMN `payment_option` `payment_option` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Online\n2 - COD\n3 - Wallet\n4 - All';

ALTER TABLE `vendor` 
CHANGE COLUMN `payment_option` `payment_option` VARCHAR(10) NULL DEFAULT NULL COMMENT '1 - Online\n2 - COD\n3 - Wallet\n4 - All';

ALTER TABLE `delivery_area` 
CHANGE COLUMN `zone_radius` `zone_radius` DOUBLE(20,20) NULL DEFAULT NULL;



------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `loyalty_point` (
  `loyalty_point_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `loyalty_point_key` VARCHAR(32) NULL DEFAULT NULL,
  `from_amount` DOUBLE NULL DEFAULT NULL,
  `to_amount` DOUBLE NULL DEFAULT NULL,
  `point` INT(11) NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Active\n0 - Inactive',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`loyalty_point_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;
------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - No
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `cart` 
DROP COLUMN `items`;

CREATE TABLE IF NOT EXISTS `cart_item` (
  `cart_item_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `cart_item_key` VARCHAR(32) NULL DEFAULT NULL,
  `cart_id` BIGINT(20) NULL DEFAULT NULL,
  `item_id` VARCHAR(45) NULL DEFAULT NULL,
  `is_ingredient` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Ingredient Added\n0 - No ingredient',
  `ingredients` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`cart_item_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

ALTER TABLE `cart_item` 
ADD COLUMN `quantity` INT(11) NULL DEFAULT NULL AFTER `item_id`;



------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `cart_key` VARCHAR(32) NULL DEFAULT NULL,
  `user_id` VARCHAR(45) NULL DEFAULT NULL,
  `branch_id` BIGINT(20) NULL DEFAULT NULL,
  `items` LONGTEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`cart_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;
------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `transaction` (
  `transaction_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `transaction_key` VARCHAR(32) NULL DEFAULT NULL,
  `reference_id` VARCHAR(45) NULL DEFAULT NULL,
  `user_id` BIGINT(20) NULL DEFAULT NULL,
  `transaction_for` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Order\n2 - Add to Wallet\n3 - Points Redeem',
  `transaction_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Credit\n2 - Debit\n',
  `transaction_number` VARCHAR(100) NULL DEFAULT NULL,
  `amount` DOUBLE NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Pending\n2 - Success\n3 - Cancelled\n4 - Failed',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`transaction_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `user` 
ADD COLUMN `wallet_amount` DOUBLE NULL DEFAULT NULL AFTER `default_language`,
ADD COLUMN `loyalty_points` INT(11) NULL DEFAULT NULL AFTER `wallet_amount`;

------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `user` 
CHANGE COLUMN `otp_verfied` `otp_verified` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Yes';
------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `branch_review` (
  `branch_review_id` bigint(20) NOT NULL,
  `branch_review_key` varchar(32) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `vendor_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `review` text,
  `status` tinyint(4) DEFAULT NULL COMMENT '1 - Active\n2 - Inactive',
  `approved_status` tinyint(4) DEFAULT NULL COMMENT '1 - Approved\n2 - Disapproved',
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_wishlist` (
  `user_wishlist_id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '1 - Active\n2 - Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `user_address` (
  `user_address_id` bigint(20) NOT NULL,
  `user_address_key` varchar(32) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `address_type_id` bigint(20) DEFAULT NULL,
  `country_id` bigint(20) DEFAULT NULL,
  `city_id` bigint(20) DEFAULT NULL,
  `area_id` bigint(20) DEFAULT NULL,
  `latitude` text,
  `longitude` text,
  `address_line_one` text,
  `address_line_two` text,
  `landmark` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL COMMENT '1 - Active\n0 - Inactive',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `country` 
ADD COLUMN `country_code` VARCHAR(8) NULL DEFAULT NULL AFTER `country_key`;

------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `delivery_charge` (
  `delivery_charge_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `delivery_charge_key` VARCHAR(32) NULL DEFAULT NULL,
  `from_km` DOUBLE NULL DEFAULT NULL,
  `to_km` DOUBLE NULL DEFAULT NULL,
  `status` DATETIME NULL DEFAULT NULL COMMENT '1 - Active\n0 - Inactive',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`delivery_charge_id`));

ALTER TABLE `delivery_charge`
ADD COLUMN `price` DOUBLE NULL DEFAULT NULL AFTER `to_km`;

ALTER TABLE `delivery_charge` 
CHANGE COLUMN `status` `status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Active\n0 - Inactive';


------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `loyalty_level` (
  `loyalty_level_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `loyalty_level_key` VARCHAR(32) NULL DEFAULT NULL,
  `from_point` INT(11) NULL DEFAULT NULL,
  `to_point` INT(11) NULL DEFAULT NULL,
  `status` TINYINT(20) NULL DEFAULT NULL COMMENT '1 - Active\n0 - Inactive',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`loyalty_level_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


CREATE TABLE IF NOT EXISTS `loyalty_level_lang` (
  `loyalty_level_lang_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `loyalty_level_id` BIGINT(20) NULL DEFAULT NULL,
  `language_code` VARCHAR(8) NULL DEFAULT NULL,
  `loyalty_level_name` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`loyalty_level_lang_id`),
  INDEX `fk_loyalty_level_lang_1_idx` (`loyalty_level_id` ASC),
  CONSTRAINT `fk_loyalty_level_lang_1`
    FOREIGN KEY (`loyalty_level_id`)
    REFERENCES `loyalty_level` (`loyalty_level_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `branch` 
CHANGE COLUMN `availability_status` `availability_status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Open\n2 - Closed\n3 - Busy\n4 - Out of service';

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `branch_lang` 
ADD COLUMN `branch_address` TEXT NULL DEFAULT NULL AFTER `branch_logo`;

ALTER TABLE `vendor_lang` 
ADD COLUMN `vendor_address` TEXT NULL DEFAULT NULL AFTER `vendor_description`;

------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `branch_timeslot` 
CHANGE COLUMN `day_no` `day_no` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Monday\n2 - Tuesday\n3 - Wednesday\n4 - Thursday\n5 - Friday\n6 - Saturday  \n7 - Sunday\n'

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `vendor` 
ADD COLUMN `color_code` VARCHAR(45) NULL DEFAULT NULL AFTER `commission`

------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `voucher` 
ADD COLUMN `limit_of_use` INT(11) NULL DEFAULT 0 COMMENT '0 -> Unlimited' AFTER `discount_type`;

------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `voucher_beneficiary` 
CHANGE COLUMN `apply_promo_for` `beneficiary_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Shop\n2 - User';



------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `voucher_usage` (
  `voucher_usage_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `voucher_usage_key` VARCHAR(32) NULL DEFAULT NULL,
  `voucher_id` BIGINT(20) NULL DEFAULT NULL,
  `beneficiary_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Shop\n2 - User',
  `beneficiary_id` BIGINT(20) NULL DEFAULT NULL,
  `used_date` DATETIME NULL DEFAULT NULL,
  `order_id` BIGINT(20) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`voucher_usage_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `cms` 
ADD COLUMN `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `cms_key`;

------------------------------------------------------------------------------------

------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `order` (
  `order_id` bigint(20) NOT NULL,
  `order_key` varchar(32) DEFAULT NULL,
  `order_number` varchar(32) DEFAULT NULL,
  `vendor_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_address_id` bigint(20) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_phone_number` varchar(20) DEFAULT NULL,
  `order_datetime` datetime DEFAULT NULL,
  `order_type` tinyint(1) DEFAULT NULL COMMENT '1 - Delivery\n2 - Pickup',
  `payment_type` tinyint(1) DEFAULT NULL COMMENT '1 - Online\n2 - COD\n3 - Wallet',
  `item_total` double DEFAULT NULL,
  `delivery_fee` double DEFAULT NULL,
  `delivery_distance` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `tax_percent` int(11) DEFAULT NULL,
  `service_tax` double DEFAULT NULL,
  `service_tax_percent` int(11) DEFAULT NULL,
  `voucher_id` bigint(20) DEFAULT NULL,
  `voucher_offer_value` double DEFAULT NULL,
  `order_total` double DEFAULT NULL,
  `order_message` text,
  `status` tinyint(1) DEFAULT NULL COMMENT '1 - Active\n0 - Inactive',
  `order_approval_status` tinyint(1) DEFAULT '0' COMMENT '0 - Pending\n1 - Approved\n2 - Rejected',
  `payment_status` tinyint(1) DEFAULT '0' COMMENT '0 - Pending\n1 - Success\n2 - Failiur\n',
  `payment_reponse` text,
  `order_approved_datetime` datetime DEFAULT NULL,
  `order_rejected_datetime` datetime DEFAULT NULL,
  `order_payment_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `order_ingredient` (
  `order_ingredient_id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `order_item_id` bigint(20) DEFAULT NULL,
  `order_item_ingredient_group_id` bigint(20) DEFAULT NULL,
  `ingredient_price` double DEFAULT NULL,
  `ingredient_quanitity` int(11) DEFAULT NULL,
  `ingredient_subtotal` double DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `order_ingredient_lang` (
  `order_ingredient_lang_id` bigint(20) NOT NULL,
  `order_ingredient_id` bigint(20) DEFAULT NULL,
  `language_code` varchar(8) DEFAULT NULL,
  `ingredient_name` text CHARACTER SET utf8
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `order_item` (
  `order_item_id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `base_price` double DEFAULT NULL,
  `item_quantity` int(11) DEFAULT NULL,
  `item_total_price` double DEFAULT NULL COMMENT 'Without Ingredient Cost\n',
  `item_subtotal` double DEFAULT NULL COMMENT 'With Ingredients cost'
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `order_item_lang` (
  `order_item_lang_id` bigint(20) NOT NULL,
  `order_item_id` bigint(20) DEFAULT NULL,
  `language_code` varchar(8) DEFAULT NULL,
  `item_name` text,
  `item_description` text,
  `item_image_path` text
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `order_item_ingredient_group` (
  `order_item_ingredient_group_id` bigint(20) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `order_item_id` bigint(20) DEFAULT NULL,
  `ingredient_type` varchar(45) DEFAULT NULL COMMENT '1 - Modifier\n2 - Subcourse',
  `minimum` varchar(45) DEFAULT NULL,
  `maximum` varchar(45) DEFAULT NULL,
  `ingredient_group_subtotal` double DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `order_item_ingredient_group_lang` (
  `order_item_ingredient_group_lang_id` bigint(20) NOT NULL,
  `order_item_ingredient_group_id` bigint(20) DEFAULT NULL,
  `language_code` varchar(8) DEFAULT NULL,
  `group_name` text
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;


ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_order_1_idx` (`vendor_id`),
  ADD KEY `fk_order_2_idx` (`branch_id`),
  ADD KEY `fk_order_3_idx` (`user_id`),
  ADD KEY `fk_order_4_idx` (`voucher_id`);

--
-- Indexes for table `order_ingredient`
--
ALTER TABLE `order_ingredient`
  ADD PRIMARY KEY (`order_ingredient_id`),
  ADD KEY `fk_order_ingredient_1_idx` (`order_id`),
  ADD KEY `fk_order_ingredient_2_idx` (`order_item_id`),
  ADD KEY `fk_order_ingredient_3_idx` (`order_item_ingredient_group_id`);

--
-- Indexes for table `order_ingredient_lang`
--
ALTER TABLE `order_ingredient_lang`
  ADD PRIMARY KEY (`order_ingredient_lang_id`),
  ADD KEY `fk_order_ingredient_lang_1_idx` (`order_ingredient_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_order_item_2_idx` (`item_id`),
  ADD KEY `fk_order_item_1_idx` (`order_id`);

--
-- Indexes for table `order_item_ingredient_group`
--
ALTER TABLE `order_item_ingredient_group`
  ADD PRIMARY KEY (`order_item_ingredient_group_id`),
  ADD KEY `fk_order_item_ingredient_group_1_idx` (`order_item_id`),
  ADD KEY `fk_order_item_ingredient_group_2_idx` (`order_id`);

--
-- Indexes for table `order_item_ingredient_group_lang`
--
ALTER TABLE `order_item_ingredient_group_lang`
  ADD PRIMARY KEY (`order_item_ingredient_group_lang_id`),
  ADD KEY `fk_order_item_ingredient_group_lang_1_idx` (`order_item_ingredient_group_id`);

--
-- Indexes for table `order_item_lang`
--
ALTER TABLE `order_item_lang`
  ADD PRIMARY KEY (`order_item_lang_id`),
  ADD KEY `fk_order_item_lang_1_idx` (`order_item_id`);

------------------------------------------------------------------------------------



------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

ALTER TABLE `user` 
ADD COLUMN `login_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - App,\n2 - G+\n3 - FB\n' AFTER `profile_image`,
ADD COLUMN `social_token` VARCHAR(255) NULL DEFAULT NULL AFTER `login_type`,
ADD COLUMN `otp_verified_at` DATETIME NULL DEFAULT NULL AFTER `email_verified_at`,
ADD COLUMN `device_type` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Web\n2 - Android\n3 - IOS\n4 - Windows' AFTER `remember_token`;

ALTER TABLE `user` 
ADD COLUMN `email_verified` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Yes' AFTER `social_token`,
ADD COLUMN `otp_verfied` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Yes' AFTER `email_verified_at`;


------------------------------------------------------------------------------------


------------------------------------------------------------------------------------
-- Migration Status
-- Live - Yes
-- Local Server - Yes
-- @author: Aravind

CREATE TABLE IF NOT EXISTS `otp_temp` (
  `otp_temp_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `otp_temp_key` VARCHAR(32) NULL DEFAULT NULL,
  `phone_number` VARCHAR(20) NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `user_details` TEXT NULL DEFAULT NULL,
  `otp` VARCHAR(8) NULL DEFAULT NULL,
  `otp_purpose` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Create Account\n2 - Forget Password\n3 - Change Mobile Number\n4 - Place order',
  `status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Verified\n2 - ',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`otp_temp_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


ALTER TABLE `otp_temp` 
DROP COLUMN `user_details`,
DROP COLUMN `email`,
CHANGE COLUMN `phone_number` `user_id` BIGINT(20) NULL DEFAULT NULL;

ALTER TABLE `otp_temp` 
CHANGE COLUMN `status` `status` TINYINT(1) NULL DEFAULT NULL COMMENT '1 - Verified\n2 - Unverified';

------------------------------------------------------------------------------------
