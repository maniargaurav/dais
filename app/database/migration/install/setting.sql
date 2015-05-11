-- --------------------------------------------------------

--
-- Table structure for table `dais_setting`
--

DROP TABLE IF EXISTS `dais_setting`;
CREATE TABLE IF NOT EXISTS `dais_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `section` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `item` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `serialized` tinyint(1) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14414 ;

--
-- Dumping data for table `dais_setting`
--

INSERT INTO `dais_setting` (`setting_id`, `store_id`, `section`, `item`, `data`, `serialized`) VALUES
(3743, 0, 'free_checkout', 'free_checkout_order_status_id', '1', 0),
(3744, 0, 'free_checkout', 'free_checkout_status', '0', 0),
(3745, 0, 'free_checkout', 'free_checkout_sort_order', '1', 0),
(3868, 0, 'sub_total', 'sub_total_status', '1', 0),
(3869, 0, 'sub_total', 'sub_total_sort_order', '1', 0),
(3872, 0, 'subtotal', 'subtotal_status', '1', 0),
(3873, 0, 'subtotal', 'subtotal_sort_order', '1', 0),
(4012, 0, 'blog_category', 'blog_category_widget', 'a:1:{i:0;a:4:{s:9:"layout_id";s:1:"6";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(4019, 0, 'blog_featured', 'post', '', 0),
(4020, 0, 'blog_featured', 'blog_featured_post', '', 0),
(4288, 0, 'postwall_widget', 'postwall_widget', 'a:1:{i:0;a:10:{s:5:"limit";s:2:"12";s:4:"span";s:1:"4";s:6:"height";s:0:"";s:9:"post_type";s:6:"latest";s:11:"description";s:1:"1";s:6:"button";s:1:"1";s:9:"layout_id";s:2:"14";s:8:"position";s:11:"content_top";s:6:"status";s:1:"0";s:10:"sort_order";s:1:"1";}}', 1),
(9740, 0, 'banner', 'banner_widget', 'a:1:{i:0;a:7:{s:9:"banner_id";s:1:"6";s:5:"width";s:3:"267";s:6:"height";s:3:"267";s:9:"layout_id";s:1:"3";s:8:"position";s:11:"column_left";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}}', 1),
(9741, 0, 'blogcategory', 'blogcategory_widget', 'a:4:{i:0;a:4:{s:9:"layout_id";s:2:"15";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}i:1;a:4:{s:9:"layout_id";s:2:"14";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}i:2;a:4:{s:9:"layout_id";s:2:"16";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}i:3;a:4:{s:9:"layout_id";s:2:"17";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}}', 1),
(9742, 0, 'blogfeatured', 'post', '', 0),
(9743, 0, 'blogfeatured', 'blogfeatured_post', '1', 0),
(9744, 0, 'blogfeatured', 'blogfeatured_widget', 'a:4:{i:0;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"15";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}i:1;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"14";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}i:2;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"16";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}i:3;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"17";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}}', 1),
(9745, 0, 'bloghottopics', 'bloghottopics_widget', 'a:4:{i:0;a:5:{s:5:"limit";s:1:"5";s:9:"layout_id";s:2:"15";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"4";}i:1;a:5:{s:5:"limit";s:1:"5";s:9:"layout_id";s:2:"14";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"4";}i:2;a:5:{s:5:"limit";s:1:"5";s:9:"layout_id";s:2:"16";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"4";}i:3;a:5:{s:5:"limit";s:1:"5";s:9:"layout_id";s:2:"17";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"4";}}', 1),
(9746, 0, 'bloglatest', 'bloglatest_widget', 'a:4:{i:0;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"15";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"5";}i:1;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"14";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"5";}i:2;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"16";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"5";}i:3;a:7:{s:5:"limit";s:1:"5";s:11:"image_width";s:2:"40";s:12:"image_height";s:2:"30";s:9:"layout_id";s:2:"17";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"5";}}', 1),
(9747, 0, 'blogsearch', 'blogsearch_widget', 'a:4:{i:0;a:4:{s:9:"layout_id";s:2:"15";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:1;a:4:{s:9:"layout_id";s:2:"14";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:2;a:4:{s:9:"layout_id";s:2:"16";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:3;a:4:{s:9:"layout_id";s:2:"17";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(9748, 0, 'carousel', 'carousel_widget', 'a:2:{i:0;a:9:{s:9:"banner_id";s:1:"8";s:5:"limit";s:1:"5";s:6:"scroll";s:1:"2";s:5:"width";s:2:"80";s:6:"height";s:2:"80";s:9:"layout_id";s:1:"2";s:8:"position";s:10:"pre_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}i:1;a:9:{s:9:"banner_id";s:1:"8";s:5:"limit";s:1:"5";s:6:"scroll";s:1:"3";s:5:"width";s:2:"80";s:6:"height";s:2:"80";s:9:"layout_id";s:2:"14";s:8:"position";s:10:"pre_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(9749, 0, 'category', 'category_widget', 'a:4:{i:0;a:4:{s:9:"layout_id";s:1:"4";s:8:"position";s:11:"column_left";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:1;a:4:{s:9:"layout_id";s:1:"5";s:8:"position";s:11:"column_left";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:2;a:4:{s:9:"layout_id";s:1:"3";s:8:"position";s:11:"column_left";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:3;a:4:{s:9:"layout_id";s:2:"12";s:8:"position";s:11:"column_left";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(9750, 0, 'featured', 'product', '', 0),
(9751, 0, 'featured', 'featured_product', '43,40,42,49,46,47,28', 0),
(9752, 0, 'featured', 'featured_widget', 'a:1:{i:0;a:7:{s:5:"limit";s:1:"6";s:11:"image_width";s:3:"191";s:12:"image_height";s:3:"180";s:9:"layout_id";s:1:"2";s:8:"position";s:14:"content_bottom";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(9754, 0, 'masonry_widget', 'masonry_widget', 'a:1:{i:0;a:10:{s:5:"limit";s:2:"12";s:4:"span";s:1:"2";s:6:"height";s:0:"";s:12:"product_type";s:6:"latest";s:11:"description";s:1:"1";s:6:"button";s:1:"1";s:9:"layout_id";s:1:"2";s:8:"position";s:11:"content_top";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}}', 1),
(9755, 0, 'page', 'page_widget', 'a:1:{i:0;a:4:{s:9:"layout_id";s:2:"11";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(9771, 0, 'headermenu', 'headermenu_widget', 'a:3:{i:0;a:5:{s:7:"menu_id";s:2:"17";s:9:"layout_id";s:1:"1";s:8:"position";s:11:"shop_header";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:1;a:5:{s:7:"menu_id";s:1:"4";s:9:"layout_id";s:1:"1";s:8:"position";s:14:"content_header";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:2;a:5:{s:7:"menu_id";s:1:"3";s:9:"layout_id";s:1:"1";s:8:"position";s:14:"content_header";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}}', 1),
(9785, 0, 'sidebarmenu', 'sidebarmenu_widget', 'a:3:{i:0;a:5:{s:7:"menu_id";s:2:"14";s:9:"layout_id";s:1:"3";s:8:"position";s:11:"column_left";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}i:1;a:5:{s:7:"menu_id";s:2:"15";s:9:"layout_id";s:2:"14";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"4";}i:2;a:5:{s:7:"menu_id";s:2:"16";s:9:"layout_id";s:2:"14";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}}', 1),
(9786, 0, 'footerblocks', 'footerblocks_widget', 'a:8:{i:0;a:5:{s:7:"menu_id";s:1:"5";s:9:"layout_id";s:1:"1";s:8:"position";s:11:"shop_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:1;a:5:{s:7:"menu_id";s:1:"7";s:9:"layout_id";s:1:"1";s:8:"position";s:11:"shop_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}i:2;a:5:{s:7:"menu_id";s:1:"8";s:9:"layout_id";s:1:"1";s:8:"position";s:11:"shop_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}i:3;a:5:{s:7:"menu_id";s:2:"13";s:9:"layout_id";s:1:"1";s:8:"position";s:11:"shop_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"4";}i:4;a:5:{s:7:"menu_id";s:2:"10";s:9:"layout_id";s:1:"1";s:8:"position";s:14:"content_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:5;a:5:{s:7:"menu_id";s:1:"8";s:9:"layout_id";s:1:"1";s:8:"position";s:14:"content_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}i:6;a:5:{s:7:"menu_id";s:1:"5";s:9:"layout_id";s:1:"1";s:8:"position";s:14:"content_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}i:7;a:5:{s:7:"menu_id";s:2:"16";s:9:"layout_id";s:1:"1";s:8:"position";s:14:"content_footer";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"4";}}', 1),
(9790, 0, 'slideshow', 'slideshow_widget', 'a:2:{i:0;a:7:{s:9:"banner_id";s:1:"9";s:5:"width";s:4:"1170";s:6:"height";s:3:"340";s:9:"layout_id";s:1:"2";s:8:"position";s:11:"post_header";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:1;a:7:{s:9:"banner_id";s:1:"9";s:5:"width";s:4:"1170";s:6:"height";s:3:"340";s:9:"layout_id";s:2:"14";s:8:"position";s:11:"post_header";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(11053, 0, 'account', 'account_widget', 'a:2:{i:0;a:4:{s:9:"layout_id";s:1:"6";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:1;a:4:{s:9:"layout_id";s:2:"18";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', 1),
(12547, 0, 'git', 'git_provider', '2', 0),
(12548, 0, 'git', 'git_url', 'git@github.com:oculusxms/dais.git', 0),
(12549, 0, 'git', 'git_branch', 'master', 0),
(12550, 0, 'git', 'git_status', '1', 0),
(12698, 0, 'coupon', 'coupon_status', '1', 0),
(12699, 0, 'coupon', 'coupon_sort_order', '2', 0),
(12700, 0, 'credit', 'credit_status', '1', 0),
(12701, 0, 'credit', 'credit_sort_order', '3', 0),
(12702, 0, 'handling', 'handling_total', '', 0),
(12703, 0, 'handling', 'handling_fee', '', 0),
(12704, 0, 'handling', 'handling_tax_class_id', '0', 0),
(12705, 0, 'handling', 'handling_status', '0', 0),
(12706, 0, 'handling', 'handling_sort_order', '5', 0),
(12707, 0, 'loworderfee', 'loworderfee_total', '', 0),
(12708, 0, 'loworderfee', 'loworderfee_fee', '', 0),
(12709, 0, 'loworderfee', 'loworderfee_tax_class_id', '0', 0),
(12710, 0, 'loworderfee', 'loworderfee_status', '0', 0),
(12711, 0, 'loworderfee', 'loworderfee_sort_order', '6', 0),
(12712, 0, 'reward', 'reward_status', '1', 0),
(12713, 0, 'reward', 'reward_sort_order', '9', 0),
(12714, 0, 'shipping', 'shipping_estimator', '1', 0),
(12715, 0, 'shipping', 'shipping_status', '1', 0),
(12716, 0, 'shipping', 'shipping_sort_order', '7', 0),
(12717, 0, 'tax', 'tax_status', '1', 0),
(12718, 0, 'tax', 'tax_sort_order', '4', 0),
(12719, 0, 'total', 'total_status', '1', 0),
(12720, 0, 'total', 'total_sort_order', '10', 0),
(14013, 0, 'giftcard', 'giftcard_status', '1', 0),
(14014, 0, 'giftcard', 'giftcard_sort_order', '8', 0),
(14281, 0, 'config', 'config_name', 'Your Site', 0),
(14282, 0, 'config', 'config_owner', 'Your Name', 0),
(14283, 0, 'config', 'config_address', '77 Massachusetts Ave,\r\nCambridge, MA 02139', 0),
(14284, 0, 'config', 'config_email', 'info@dais.io', 0),
(14285, 0, 'config', 'config_telephone', '(123) 456-7890', 0),
(14286, 0, 'config', 'config_default_visibility', '1', 0),
(14287, 0, 'config', 'config_free_customer', '2', 0),
(14288, 0, 'config', 'config_top_customer', '4', 0),
(14289, 0, 'config', 'config_site_style', 'content', 0),
(14290, 0, 'config', 'config_home_page', '0', 0),
(14291, 0, 'config', 'config_title', 'Your Store', 0),
(14292, 0, 'config', 'config_meta_description', 'My Store', 0),
(14293, 0, 'config', 'config_theme', 'ghost', 0),
(14294, 0, 'config', 'config_admin_theme', 'bs3', 0),
(14295, 0, 'config', 'config_layout_id', '2', 0),
(14296, 0, 'config', 'config_country_id', '223', 0),
(14297, 0, 'config', 'config_zone_id', '3616', 0),
(14298, 0, 'config', 'config_language', 'en', 0),
(14299, 0, 'config', 'config_admin_language', 'en', 0),
(14300, 0, 'config', 'config_currency', 'USD', 0),
(14301, 0, 'config', 'config_currency_auto', '1', 0),
(14302, 0, 'config', 'config_length_class_id', '3', 0),
(14303, 0, 'config', 'config_weight_class_id', '5', 0),
(14304, 0, 'config', 'config_catalog_limit', '16', 0),
(14305, 0, 'config', 'config_admin_limit', '10', 0),
(14306, 0, 'config', 'config_product_count', '0', 0),
(14307, 0, 'config', 'config_review_status', '1', 0),
(14308, 0, 'config', 'config_review_logged', '0', 0),
(14309, 0, 'config', 'config_download', '0', 0),
(14310, 0, 'config', 'blog_posted_by', 'user_name', 0),
(14311, 0, 'config', 'blog_comment_status', '1', 0),
(14312, 0, 'config', 'blog_comment_logged', '0', 0),
(14313, 0, 'config', 'blog_comment_require_approve', '1', 0),
(14314, 0, 'config', 'blog_admin_group_id', '1', 0),
(14315, 0, 'config', 'blog_image_thumb_width', '200', 0),
(14316, 0, 'config', 'blog_image_thumb_height', '200', 0),
(14317, 0, 'config', 'blog_image_popup_width', '600', 0),
(14318, 0, 'config', 'blog_image_popup_height', '600', 0),
(14319, 0, 'config', 'blog_image_post_width', '900', 0),
(14320, 0, 'config', 'blog_image_post_height', '300', 0),
(14321, 0, 'config', 'blog_image_additional_width', '130', 0),
(14322, 0, 'config', 'blog_image_additional_height', '130', 0),
(14323, 0, 'config', 'blog_image_related_width', '200', 0),
(14324, 0, 'config', 'blog_image_related_height', '200', 0),
(14325, 0, 'config', 'config_giftcard_min', '25.00', 0),
(14326, 0, 'config', 'config_giftcard_max', '1000.00', 0),
(14327, 0, 'config', 'config_tax', '0', 0),
(14328, 0, 'config', 'config_vat', '0', 0),
(14329, 0, 'config', 'config_tax_default', '', 0),
(14330, 0, 'config', 'config_tax_customer', 'shipping', 0),
(14331, 0, 'config', 'config_customer_online', '0', 0),
(14332, 0, 'config', 'config_customer_group_id', '2', 0),
(14333, 0, 'config', 'config_customer_group_display', 'a:1:{i:0;s:1:"2";}', 1),
(14334, 0, 'config', 'config_customer_price', '0', 0),
(14335, 0, 'config', 'config_account_id', '3', 0),
(14336, 0, 'config', 'config_cart_weight', '1', 0),
(14337, 0, 'config', 'config_guest_checkout', '1', 0),
(14338, 0, 'config', 'config_checkout_id', '5', 0),
(14339, 0, 'config', 'config_order_edit', '100', 0),
(14340, 0, 'config', 'config_invoice_prefix', 'INV-2013-00', 0),
(14341, 0, 'config', 'config_order_status_id', '2', 0),
(14342, 0, 'config', 'config_complete_status_id', '5', 0),
(14343, 0, 'config', 'config_stock_display', '1', 0),
(14344, 0, 'config', 'config_stock_warning', '0', 0),
(14345, 0, 'config', 'config_stock_checkout', '0', 0),
(14346, 0, 'config', 'config_stock_status_id', '5', 0),
(14347, 0, 'config', 'config_affiliate_allowed', '1', 0),
(14348, 0, 'config', 'config_affiliate_terms', '8', 0),
(14349, 0, 'config', 'config_commission', '10', 0),
(14350, 0, 'config', 'config_return_id', '7', 0),
(14351, 0, 'config', 'config_return_status_id', '2', 0),
(14352, 0, 'config', 'config_logo', 'data/logo.png', 0),
(14353, 0, 'config', 'config_icon', 'data/favicon.png', 0),
(14354, 0, 'config', 'config_image_category_width', '180', 0),
(14355, 0, 'config', 'config_image_category_height', '180', 0),
(14356, 0, 'config', 'config_image_thumb_width', '451', 0),
(14357, 0, 'config', 'config_image_thumb_height', '451', 0),
(14358, 0, 'config', 'config_image_popup_width', '600', 0),
(14359, 0, 'config', 'config_image_popup_height', '600', 0),
(14360, 0, 'config', 'config_image_product_width', '213', 0),
(14361, 0, 'config', 'config_image_product_height', '213', 0),
(14362, 0, 'config', 'config_image_additional_width', '88', 0),
(14363, 0, 'config', 'config_image_additional_height', '88', 0),
(14364, 0, 'config', 'config_image_related_width', '180', 0),
(14365, 0, 'config', 'config_image_related_height', '180', 0),
(14366, 0, 'config', 'config_image_compare_width', '140', 0),
(14367, 0, 'config', 'config_image_compare_height', '140', 0),
(14368, 0, 'config', 'config_image_wishlist_width', '70', 0),
(14369, 0, 'config', 'config_image_wishlist_height', '70', 0),
(14370, 0, 'config', 'config_image_cart_width', '60', 0),
(14371, 0, 'config', 'config_image_cart_height', '60', 0),
(14372, 0, 'config', 'config_ftp_host', '', 0),
(14373, 0, 'config', 'config_ftp_port', '', 0),
(14374, 0, 'config', 'config_ftp_username', '', 0),
(14375, 0, 'config', 'config_ftp_password', '', 0),
(14376, 0, 'config', 'config_ftp_root', '', 0),
(14377, 0, 'config', 'config_ftp_status', '0', 0),
(14378, 0, 'config', 'config_mail_protocol', '', 0),
(14379, 0, 'config', 'config_mail_parameter', '', 0),
(14380, 0, 'config', 'config_smtp_host', '', 0),
(14381, 0, 'config', 'config_smtp_username', '', 0),
(14382, 0, 'config', 'config_smtp_password', '', 0),
(14383, 0, 'config', 'config_smtp_port', '', 0),
(14384, 0, 'config', 'config_smtp_timeout', '', 0),
(14385, 0, 'config', 'config_admin_email_user', '1', 0),
(14386, 0, 'config', 'config_html_signature', '&lt;p style=&quot;margin-top: 0;color: #212425;font-family: sans-serif;font-size: 16px;line-height: 24px;margin-bottom: 24px&quot;&gt;\r\n &lt;em&gt;\r\n    Thanks so much,\r\n &lt;/em&gt;\r\n&lt;/p&gt;\r\n&lt;p style=&quot;margin-top: 0;color: #212425;font-family: sans-serif;font-size: 16px;line-height: 24px;margin-bottom: 24px&quot;&gt;\r\n &lt;em&gt;\r\n    !store_name! Administration\r\n   &lt;br&gt;\r\n    &lt;a href=&quot;!store_url!&quot; target=&quot;_blank&quot;&gt;\r\n      !store_url!\r\n   &lt;/a&gt;\r\n  &lt;/em&gt;\r\n&lt;/p&gt;', 0),
(14387, 0, 'config', 'config_text_signature', 'Thanks so much,\r\n\r\n!store_name! Administration\r\n!store_url!', 0),
(14388, 0, 'config', 'config_mail_twitter', 'TwitterHandle', 0),
(14389, 0, 'config', 'config_mail_facebook', 'FacebookPage', 0),
(14390, 0, 'config', 'config_alert_mail', '0', 0),
(14391, 0, 'config', 'config_account_mail', '0', 0),
(14392, 0, 'config', 'config_alert_emails', '', 0),
(14393, 0, 'config', 'config_fraud_detection', '0', 0),
(14394, 0, 'config', 'config_fraud_key', '', 0),
(14395, 0, 'config', 'config_fraud_score', '', 0),
(14396, 0, 'config', 'config_fraud_status_id', '7', 0),
(14397, 0, 'config', 'config_secure', '0', 0),
(14398, 0, 'config', 'config_shared', '0', 0),
(14399, 0, 'config', 'config_top_level', '0', 0),
(14400, 0, 'config', 'config_ucfirst', '1', 0),
(14401, 0, 'config', 'config_robots', 'abot\r\ndbot\r\nebot\r\nhbot\r\nkbot\r\nlbot\r\nmbot\r\nnbot\r\nobot\r\npbot\r\nrbot\r\nsbot\r\ntbot\r\nvbot\r\nybot\r\nzbot\r\nbot.\r\nbot/\r\n_bot\r\n.bot\r\n/bot\r\n-bot\r\n:bot\r\n(bot\r\ncrawl\r\nslurp\r\nspider\r\nseek\r\naccoona\r\nacoon\r\nadressendeutschland\r\nah-ha.com\r\nahoy\r\naltavista\r\nananzi\r\nanthill\r\nappie\r\narachnophilia\r\narale\r\naraneo\r\naranha\r\narchitext\r\naretha\r\narks\r\nasterias\r\natlocal\r\natn\r\natomz\r\naugurfind\r\nbackrub\r\nbannana_bot\r\nbaypup\r\nbdfetch\r\nbig brother\r\nbiglotron\r\nbjaaland\r\nblackwidow\r\nblaiz\r\nblog\r\nblo.\r\nbloodhound\r\nboitho\r\nbooch\r\nbradley\r\nbutterfly\r\ncalif\r\ncassandra\r\nccubee\r\ncfetch\r\ncharlotte\r\nchurl\r\ncienciaficcion\r\ncmc\r\ncollective\r\ncomagent\r\ncombine\r\ncomputingsite\r\ncsci\r\ncurl\r\ncusco\r\ndaumoa\r\ndeepindex\r\ndelorie\r\ndepspid\r\ndeweb\r\ndie blinde kuh\r\ndigger\r\nditto\r\ndmoz\r\ndocomo\r\ndownload express\r\ndtaagent\r\ndwcp\r\nebiness\r\nebingbong\r\ne-collector\r\nejupiter\r\nemacs-w3 search engine\r\nesther\r\nevliya celebi\r\nezresult\r\nfalcon\r\nfelix ide\r\nferret\r\nfetchrover\r\nfido\r\nfindlinks\r\nfireball\r\nfish search\r\nfouineur\r\nfunnelweb\r\ngazz\r\ngcreep\r\ngenieknows\r\ngetterroboplus\r\ngeturl\r\nglx\r\ngoforit\r\ngolem\r\ngrabber\r\ngrapnel\r\ngralon\r\ngriffon\r\ngromit\r\ngrub\r\ngulliver\r\nhamahakki\r\nharvest\r\nhavindex\r\nhelix\r\nheritrix\r\nhku www octopus\r\nhomerweb\r\nhtdig\r\nhtml index\r\nhtml_analyzer\r\nhtmlgobble\r\nhubater\r\nhyper-decontextualizer\r\nia_archiver\r\nibm_planetwide\r\nichiro\r\niconsurf\r\niltrovatore\r\nimage.kapsi.net\r\nimagelock\r\nincywincy\r\nindexer\r\ninfobee\r\ninformant\r\ningrid\r\ninktomisearch.com\r\ninspector web\r\nintelliagent\r\ninternet shinchakubin\r\nip3000\r\niron33\r\nisraeli-search\r\nivia\r\njack\r\njakarta\r\njavabee\r\njetbot\r\njumpstation\r\nkatipo\r\nkdd-explorer\r\nkilroy\r\nknowledge\r\nkototoi\r\nkretrieve\r\nlabelgrabber\r\nlachesis\r\nlarbin\r\nlegs\r\nlibwww\r\nlinkalarm\r\nlink validator\r\nlinkscan\r\nlockon\r\nlwp\r\nlycos\r\nmagpie\r\nmantraagent\r\nmapoftheinternet\r\nmarvin/\r\nmattie\r\nmediafox\r\nmediapartners\r\nmercator\r\nmerzscope\r\nmicrosoft url control\r\nminirank\r\nmiva\r\nmj12\r\nmnogosearch\r\nmoget\r\nmonster\r\nmoose\r\nmotor\r\nmultitext\r\nmuncher\r\nmuscatferret\r\nmwd.search\r\nmyweb\r\nnajdi\r\nnameprotect\r\nnationaldirectory\r\nnazilla\r\nncsa beta\r\nnec-meshexplorer\r\nnederland.zoek\r\nnetcarta webmap engine\r\nnetmechanic\r\nnetresearchserver\r\nnetscoop\r\nnewscan-online\r\nnhse\r\nnokia6682/\r\nnomad\r\nnoyona\r\nnutch\r\nnzexplorer\r\nobjectssearch\r\noccam\r\nomni\r\nopen text\r\nopenfind\r\nopenintelligencedata\r\norb search\r\nosis-project\r\npack rat\r\npageboy\r\npagebull\r\npage_verifier\r\npanscient\r\nparasite\r\npartnersite\r\npatric\r\npear.\r\npegasus\r\nperegrinator\r\npgp key agent\r\nphantom\r\nphpdig\r\npicosearch\r\npiltdownman\r\npimptrain\r\npinpoint\r\npioneer\r\npiranha\r\nplumtreewebaccessor\r\npogodak\r\npoirot\r\npompos\r\npoppelsdorf\r\npoppi\r\npopular iconoclast\r\npsycheclone\r\npublisher\r\npython\r\nrambler\r\nraven search\r\nroach\r\nroad runner\r\nroadhouse\r\nrobbie\r\nrobofox\r\nrobozilla\r\nrules\r\nsalty\r\nsbider\r\nscooter\r\nscoutjet\r\nscrubby\r\nsearch.\r\nsearchprocess\r\nsemanticdiscovery\r\nsenrigan\r\nsg-scout\r\nshai''hulud\r\nshark\r\nshopwiki\r\nsidewinder\r\nsift\r\nsilk\r\nsimmany\r\nsite searcher\r\nsite valet\r\nsitetech-rover\r\nskymob.com\r\nsleek\r\nsmartwit\r\nsna-\r\nsnappy\r\nsnooper\r\nsohu\r\nspeedfind\r\nsphere\r\nsphider\r\nspinner\r\nspyder\r\nsteeler/\r\nsuke\r\nsuntek\r\nsupersnooper\r\nsurfnomore\r\nsven\r\nsygol\r\nszukacz\r\ntach black widow\r\ntarantula\r\ntempleton\r\n/teoma\r\nt-h-u-n-d-e-r-s-t-o-n-e\r\ntheophrastus\r\ntitan\r\ntitin\r\ntkwww\r\ntoutatis\r\nt-rex\r\ntutorgig\r\ntwiceler\r\ntwisted\r\nucsd\r\nudmsearch\r\nurl check\r\nupdated\r\nvagabondo\r\nvalkyrie\r\nverticrawl\r\nvictoria\r\nvision-search\r\nvolcano\r\nvoyager/\r\nvoyager-hc\r\nw3c_validator\r\nw3m2\r\nw3mir\r\nwalker\r\nwallpaper\r\nwanderer\r\nwauuu\r\nwavefire\r\nweb core\r\nweb hopper\r\nweb wombat\r\nwebbandit\r\nwebcatcher\r\nwebcopy\r\nwebfoot\r\nweblayers\r\nweblinker\r\nweblog monitor\r\nwebmirror\r\nwebmonkey\r\nwebquest\r\nwebreaper\r\nwebsitepulse\r\nwebsnarf\r\nwebstolperer\r\nwebvac\r\nwebwalk\r\nwebwatch\r\nwebwombat\r\nwebzinger\r\nwhizbang\r\nwhowhere\r\nwild ferret\r\nworldlight\r\nwwwc\r\nwwwster\r\nxenu\r\nxget\r\nxift\r\nxirq\r\nyandex\r\nyanga\r\nyeti\r\nyodao\r\nzao\r\nzippp\r\nzyborg', 0),
(14402, 0, 'config', 'config_file_extension_allowed', 'txt\r\npng\r\njpe\r\njpeg\r\njpg\r\ngif\r\nbmp\r\nico\r\ntiff\r\ntif\r\nsvg\r\nsvgz\r\nzip\r\nrar\r\nmsi\r\ncab\r\nmp3\r\nqt\r\nmov\r\npdf\r\npsd\r\nai\r\neps\r\nps\r\ndoc\r\nrtf\r\nxls\r\nppt\r\nodt\r\nods', 0),
(14403, 0, 'config', 'config_file_mime_allowed', 'text/plain\r\nimage/png\r\nimage/jpeg\r\nimage/jpeg\r\nimage/jpeg\r\nimage/gif\r\nimage/bmp\r\nimage/vnd.microsoft.icon\r\nimage/tiff\r\nimage/tiff\r\nimage/svg+xml\r\nimage/svg+xml\r\napplication/zip\r\napplication/x-rar-compressed\r\napplication/x-msdownload\r\napplication/vnd.ms-cab-compressed\r\naudio/mpeg\r\nvideo/quicktime\r\nvideo/quicktime\r\napplication/pdf\r\nimage/vnd.adobe.photoshop\r\napplication/postscript\r\napplication/postscript\r\napplication/postscript\r\napplication/msword\r\napplication/rtf\r\napplication/vnd.ms-excel\r\napplication/vnd.ms-powerpoint\r\napplication/vnd.oasis.opendocument.text\r\napplication/vnd.oasis.opendocument.spreadsheet', 0),
(14404, 0, 'config', 'config_maintenance', '0', 0),
(14405, 0, 'config', 'config_password', '1', 0),
(14406, 0, 'config', 'config_encryption', 'c3f29e0a7456c5f6509735bdf122561f', 0),
(14407, 0, 'config', 'config_compression', '', 0),
(14408, 0, 'config', 'config_error_display', '1', 0),
(14409, 0, 'config', 'config_error_log', '1', 0),
(14410, 0, 'config', 'config_error_filename', 'error.txt', 0),
(14411, 0, 'config', 'config_google_analytics', '', 0),
(14412, 0, 'config', 'config_cache_type_id', 'file', 0),
(14413, 0, 'config', 'config_cache_status', '0', 0);