QUERY select value from sessions where sesskey = '98345a3dd307b696a40f6038941db363' and expiry > '1289908353'
RESULT Resource id #17 
QUERY select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from currencies
RESULT Resource id #21 
QUERY select languages_id, name, code, image, directory from languages order by sort_order
RESULT Resource id #27 
QUERY delete from whos_online where time_last_click < '1289907453'
RESULT 1 
QUERY select count(*) as count from whos_online where session_id = '98345a3dd307b696a40f6038941db363'
RESULT Resource id #40 
QUERY insert into whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('0', 'Guest', '98345a3dd307b696a40f6038941db363', '91.211.16.126', '1289908353', '1289908353', '/index.php?cookies=1')
RESULT 1 
QUERY select banners_id, date_scheduled from banners where date_scheduled != ''
RESULT Resource id #50 
QUERY select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from banners b, banners_history bh where b.status = '1' and b.banners_id = bh.banners_id group by b.banners_id
RESULT Resource id #53 
QUERY select specials_id from specials where status = '1' and now() >= expires_date and expires_date > 0
RESULT Resource id #57 
QUERY select * from supertracker where browser_string ='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)' and ip_address like '91.211%' and last_click > '2010-11-16 11:22:33'
RESULT Resource id #64 
QUERY INSERT INTO `supertracker` (`ip_address`, `browser_string`, `country_code`, `country_name`, `referrer`,`referrer_query_string`,`landing_page`,`exit_page`,`time_arrived`,`last_click`) VALUES ('91.211.16.126','Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)','ua', 'Ukraine', '', '','/index.php?cookies=1','/index.php','2010-11-16 11:52:33','2010-11-16 11:52:33')
RESULT 1 
QUERY select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='1' order by sort_order, cd.categories_name
RESULT Resource id #91 
QUERY select count(*) as count from categories where parent_id = '22'
RESULT Resource id #95 
QUERY select count(*) as count from categories where parent_id = '23'
RESULT Resource id #99 
QUERY select count(*) as count from categories where parent_id = '40'
RESULT Resource id #103 
QUERY select count(*) as count from categories where parent_id = '24'
RESULT Resource id #107 
QUERY select count(*) as count from categories where parent_id = '26'
RESULT Resource id #111 
QUERY select count(*) as count from categories where parent_id = '28'
RESULT Resource id #115 
QUERY select count(*) as count from categories where parent_id = '39'
RESULT Resource id #119 
QUERY select count(*) as count from categories where parent_id = '27'
RESULT Resource id #123 
QUERY select count(*) as count from categories where parent_id = '25'
RESULT Resource id #127 
QUERY select count(*) as count from categories where parent_id = '29'
RESULT Resource id #131 
QUERY select count(*) as count from categories where parent_id = '30'
RESULT Resource id #135 
QUERY select count(*) as count from categories where parent_id = '31'
RESULT Resource id #139 
QUERY select count(*) as count from categories where parent_id = '43'
RESULT Resource id #143 
QUERY select count(*) as count from categories where parent_id = '38'
RESULT Resource id #147 
QUERY select count(*) as count from categories where parent_id = '33'
RESULT Resource id #151 
QUERY select count(*) as count from categories where parent_id = '34'
RESULT Resource id #155 
QUERY select count(*) as count from categories where parent_id = '41'
RESULT Resource id #159 
QUERY select count(*) as count from categories where parent_id = '32'
RESULT Resource id #163 
QUERY select count(*) as count from categories where parent_id = '35'
RESULT Resource id #167 
QUERY select count(*) as count from categories where parent_id = '36'
RESULT Resource id #171 
QUERY select count(*) as count from categories where parent_id = '37'
RESULT Resource id #175 
QUERY select count(*) as count from categories where parent_id = '42'
RESULT Resource id #179 
QUERY select distinct p.products_id, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from products p, products_description pd, products_to_categories p2c, categories c where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '0' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit 10
RESULT Resource id #186 
QUERY select specials_new_products_price from specials where products_id = '324' and status
RESULT Resource id #194 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #197 
QUERY select specials_new_products_price from specials where products_id = '229' and status
RESULT Resource id #204 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #207 
QUERY select specials_new_products_price from specials where products_id = '159' and status
RESULT Resource id #214 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #217 
QUERY select specials_new_products_price from specials where products_id = '212' and status
RESULT Resource id #224 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #227 
QUERY select specials_new_products_price from specials where products_id = '213' and status
RESULT Resource id #234 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #237 
QUERY select specials_new_products_price from specials where products_id = '211' and status
RESULT Resource id #244 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #247 
QUERY select specials_new_products_price from specials where products_id = '241' and status
RESULT Resource id #254 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #257 
QUERY select specials_new_products_price from specials where products_id = '175' and status
RESULT Resource id #264 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #267 
QUERY select specials_new_products_price from specials where products_id = '215' and status
RESULT Resource id #274 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #277 
QUERY select specials_new_products_price from specials where products_id = '205' and status
RESULT Resource id #284 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #287 
QUERY select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '1' order by p.products_date_added desc limit 12
RESULT Resource id #294 
QUERY select products_description, products_id from products_description where products_id = '176' and language_id = '1'
RESULT Resource id #297 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #302 
QUERY select products_description, products_id from products_description where products_id = '329' and language_id = '1'
RESULT Resource id #306 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #311 
QUERY select products_description, products_id from products_description where products_id = '328' and language_id = '1'
RESULT Resource id #315 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #320 
QUERY select products_description, products_id from products_description where products_id = '175' and language_id = '1'
RESULT Resource id #324 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #327 
QUERY select products_description, products_id from products_description where products_id = '174' and language_id = '1'
RESULT Resource id #331 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #336 
QUERY select products_description, products_id from products_description where products_id = '144' and language_id = '1'
RESULT Resource id #340 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #345 
QUERY select products_description, products_id from products_description where products_id = '220' and language_id = '1'
RESULT Resource id #349 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #354 
QUERY select products_description, products_id from products_description where products_id = '327' and language_id = '1'
RESULT Resource id #358 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #363 
QUERY select products_description, products_id from products_description where products_id = '128' and language_id = '1'
RESULT Resource id #367 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #372 
QUERY select products_description, products_id from products_description where products_id = '326' and language_id = '1'
RESULT Resource id #376 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #381 
QUERY select products_description, products_id from products_description where products_id = '237' and language_id = '1'
RESULT Resource id #385 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #390 
QUERY select products_description, products_id from products_description where products_id = '239' and language_id = '1'
RESULT Resource id #394 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #399 
QUERY select p.products_id, pd.products_name, products_date_available as date_expected from products p, products_description pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '1' order by date_expected asc limit 10
RESULT Resource id #404 
QUERY select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from products p, products_description pd, specials s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '1' and s.status = '1' order by s.specials_date_added desc limit 10
RESULT Resource id #409 
QUERY select manufacturers_name, manufacturers_id, manufacturers_image from manufacturers where manufacturers_image  not like '' order by manufacturers_name
RESULT Resource id #415 
QUERY select startdate, counter from counter
RESULT Resource id #420 
QUERY update counter set counter = '47082'
RESULT 1 
QUERY select banners_id, banners_title, banners_image, banners_html_text from banners where status = '1' and banners_group = '468x50'
RESULT Resource id #425 
QUERY select count(*) as total from sessions where sesskey = '98345a3dd307b696a40f6038941db363'
RESULT Resource id #430 
QUERY insert into sessions values ('98345a3dd307b696a40f6038941db363', '1289909793', 'cart|O:12:\"shoppingCart\":4:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:12:\"content_type\";b:0;}language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:0:{}s:8:\"snapshot\";a:0:{}}')
RESULT 1 
16/11/2010 11:52:33 - /index.php?cookies=1 (0.093s)
QUERY select value from sessions where sesskey = '1bba2e819e38834b1be47972a48f304b' and expiry > '1289908356'
RESULT Resource id #17 
QUERY select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from currencies
RESULT Resource id #21 
QUERY select languages_id, name, code, image, directory from languages order by sort_order
RESULT Resource id #27 
QUERY delete from whos_online where time_last_click < '1289907456'
RESULT 1 
QUERY select count(*) as count from whos_online where session_id = '1bba2e819e38834b1be47972a48f304b'
RESULT Resource id #40 
QUERY insert into whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('0', 'Guest', '1bba2e819e38834b1be47972a48f304b', '91.211.16.126', '1289908356', '1289908356', '/index.php?cookies=1')
RESULT 1 
QUERY select banners_id, date_scheduled from banners where date_scheduled != ''
RESULT Resource id #50 
QUERY select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from banners b, banners_history bh where b.status = '1' and b.banners_id = bh.banners_id group by b.banners_id
RESULT Resource id #53 
QUERY select specials_id from specials where status = '1' and now() >= expires_date and expires_date > 0
RESULT Resource id #57 
QUERY select * from supertracker where browser_string ='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)' and ip_address like '91.211%' and last_click > '2010-11-16 11:22:36'
RESULT Resource id #64 
QUERY UPDATE supertracker set last_click='2010-11-16 11:52:36', exit_page='/index.php', num_clicks='2', added_cart='false', categories_viewed='b:0;', products_viewed='', customer_id='0', completed_purchase='false', cart_contents='', cart_total = '0', order_id = '0' where tracking_id='7234'
RESULT 1 
QUERY select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='1' order by sort_order, cd.categories_name
RESULT Resource id #89 
QUERY select count(*) as count from categories where parent_id = '22'
RESULT Resource id #93 
QUERY select count(*) as count from categories where parent_id = '23'
RESULT Resource id #97 
QUERY select count(*) as count from categories where parent_id = '40'
RESULT Resource id #101 
QUERY select count(*) as count from categories where parent_id = '24'
RESULT Resource id #105 
QUERY select count(*) as count from categories where parent_id = '26'
RESULT Resource id #109 
QUERY select count(*) as count from categories where parent_id = '28'
RESULT Resource id #113 
QUERY select count(*) as count from categories where parent_id = '39'
RESULT Resource id #117 
QUERY select count(*) as count from categories where parent_id = '27'
RESULT Resource id #121 
QUERY select count(*) as count from categories where parent_id = '25'
RESULT Resource id #125 
QUERY select count(*) as count from categories where parent_id = '29'
RESULT Resource id #129 
QUERY select count(*) as count from categories where parent_id = '30'
RESULT Resource id #133 
QUERY select count(*) as count from categories where parent_id = '31'
RESULT Resource id #137 
QUERY select count(*) as count from categories where parent_id = '43'
RESULT Resource id #141 
QUERY select count(*) as count from categories where parent_id = '38'
RESULT Resource id #145 
QUERY select count(*) as count from categories where parent_id = '33'
RESULT Resource id #149 
QUERY select count(*) as count from categories where parent_id = '34'
RESULT Resource id #153 
QUERY select count(*) as count from categories where parent_id = '41'
RESULT Resource id #157 
QUERY select count(*) as count from categories where parent_id = '32'
RESULT Resource id #161 
QUERY select count(*) as count from categories where parent_id = '35'
RESULT Resource id #165 
QUERY select count(*) as count from categories where parent_id = '36'
RESULT Resource id #169 
QUERY select count(*) as count from categories where parent_id = '37'
RESULT Resource id #173 
QUERY select count(*) as count from categories where parent_id = '42'
RESULT Resource id #177 
QUERY select distinct p.products_id, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from products p, products_description pd, products_to_categories p2c, categories c where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '0' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit 10
RESULT Resource id #184 
QUERY select specials_new_products_price from specials where products_id = '324' and status
RESULT Resource id #192 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #195 
QUERY select specials_new_products_price from specials where products_id = '229' and status
RESULT Resource id #202 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #205 
QUERY select specials_new_products_price from specials where products_id = '159' and status
RESULT Resource id #212 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #215 
QUERY select specials_new_products_price from specials where products_id = '212' and status
RESULT Resource id #222 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #225 
QUERY select specials_new_products_price from specials where products_id = '213' and status
RESULT Resource id #232 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #235 
QUERY select specials_new_products_price from specials where products_id = '211' and status
RESULT Resource id #242 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #245 
QUERY select specials_new_products_price from specials where products_id = '241' and status
RESULT Resource id #252 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #255 
QUERY select specials_new_products_price from specials where products_id = '175' and status
RESULT Resource id #262 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #265 
QUERY select specials_new_products_price from specials where products_id = '215' and status
RESULT Resource id #272 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #275 
QUERY select specials_new_products_price from specials where products_id = '205' and status
RESULT Resource id #282 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #285 
QUERY select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '1' order by p.products_date_added desc limit 12
RESULT Resource id #292 
QUERY select products_description, products_id from products_description where products_id = '176' and language_id = '1'
RESULT Resource id #295 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #300 
QUERY select products_description, products_id from products_description where products_id = '329' and language_id = '1'
RESULT Resource id #304 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #309 
QUERY select products_description, products_id from products_description where products_id = '328' and language_id = '1'
RESULT Resource id #313 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #318 
QUERY select products_description, products_id from products_description where products_id = '175' and language_id = '1'
RESULT Resource id #322 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #325 
QUERY select products_description, products_id from products_description where products_id = '174' and language_id = '1'
RESULT Resource id #329 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #334 
QUERY select products_description, products_id from products_description where products_id = '144' and language_id = '1'
RESULT Resource id #338 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #343 
QUERY select products_description, products_id from products_description where products_id = '220' and language_id = '1'
RESULT Resource id #347 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #352 
QUERY select products_description, products_id from products_description where products_id = '327' and language_id = '1'
RESULT Resource id #356 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #361 
QUERY select products_description, products_id from products_description where products_id = '128' and language_id = '1'
RESULT Resource id #365 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #370 
QUERY select products_description, products_id from products_description where products_id = '326' and language_id = '1'
RESULT Resource id #374 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #379 
QUERY select products_description, products_id from products_description where products_id = '237' and language_id = '1'
RESULT Resource id #383 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #388 
QUERY select products_description, products_id from products_description where products_id = '239' and language_id = '1'
RESULT Resource id #392 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #397 
QUERY select p.products_id, pd.products_name, products_date_available as date_expected from products p, products_description pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '1' order by date_expected asc limit 10
RESULT Resource id #402 
QUERY select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from products p, products_description pd, specials s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '1' and s.status = '1' order by s.specials_date_added desc limit 10
RESULT Resource id #407 
QUERY select manufacturers_name, manufacturers_id, manufacturers_image from manufacturers where manufacturers_image  not like '' order by manufacturers_name
RESULT Resource id #413 
QUERY select startdate, counter from counter
RESULT Resource id #418 
QUERY update counter set counter = '47083'
RESULT 1 
QUERY select banners_id, banners_title, banners_image, banners_html_text from banners where status = '1' and banners_group = '468x50'
RESULT Resource id #423 
QUERY select count(*) as total from sessions where sesskey = '1bba2e819e38834b1be47972a48f304b'
RESULT Resource id #428 
QUERY insert into sessions values ('1bba2e819e38834b1be47972a48f304b', '1289909796', 'cart|O:12:\"shoppingCart\":4:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:12:\"content_type\";b:0;}language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:0:{}s:8:\"snapshot\";a:0:{}}')
RESULT 1 
16/11/2010 11:52:36 - /index.php?cookies=1 (0.098s)
QUERY select value from sessions where sesskey = '64422d3891bad9705181fab71db8c498' and expiry > '1289908361'
RESULT Resource id #17 
QUERY select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from currencies
RESULT Resource id #21 
QUERY select languages_id, name, code, image, directory from languages order by sort_order
RESULT Resource id #27 
QUERY delete from whos_online where time_last_click < '1289907461'
RESULT 1 
QUERY select count(*) as count from whos_online where session_id = '64422d3891bad9705181fab71db8c498'
RESULT Resource id #40 
QUERY insert into whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('0', 'Guest', '64422d3891bad9705181fab71db8c498', '91.211.16.126', '1289908361', '1289908361', '/index.php?cookies=1')
RESULT 1 
QUERY select banners_id, date_scheduled from banners where date_scheduled != ''
RESULT Resource id #50 
QUERY select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from banners b, banners_history bh where b.status = '1' and b.banners_id = bh.banners_id group by b.banners_id
RESULT Resource id #53 
QUERY select specials_id from specials where status = '1' and now() >= expires_date and expires_date > 0
RESULT Resource id #57 
QUERY select * from supertracker where browser_string ='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)' and ip_address like '91.211%' and last_click > '2010-11-16 11:22:41'
RESULT Resource id #64 
QUERY UPDATE supertracker set last_click='2010-11-16 11:52:41', exit_page='/index.php', num_clicks='3', added_cart='false', categories_viewed='b:0;', products_viewed='', customer_id='0', completed_purchase='false', cart_contents='', cart_total = '0', order_id = '0' where tracking_id='7234'
RESULT 1 
QUERY select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='1' order by sort_order, cd.categories_name
RESULT Resource id #89 
QUERY select count(*) as count from categories where parent_id = '22'
RESULT Resource id #93 
QUERY select count(*) as count from categories where parent_id = '23'
RESULT Resource id #97 
QUERY select count(*) as count from categories where parent_id = '40'
RESULT Resource id #101 
QUERY select count(*) as count from categories where parent_id = '24'
RESULT Resource id #105 
QUERY select count(*) as count from categories where parent_id = '26'
RESULT Resource id #109 
QUERY select count(*) as count from categories where parent_id = '28'
RESULT Resource id #113 
QUERY select count(*) as count from categories where parent_id = '39'
RESULT Resource id #117 
QUERY select count(*) as count from categories where parent_id = '27'
RESULT Resource id #121 
QUERY select count(*) as count from categories where parent_id = '25'
RESULT Resource id #125 
QUERY select count(*) as count from categories where parent_id = '29'
RESULT Resource id #129 
QUERY select count(*) as count from categories where parent_id = '30'
RESULT Resource id #133 
QUERY select count(*) as count from categories where parent_id = '31'
RESULT Resource id #137 
QUERY select count(*) as count from categories where parent_id = '43'
RESULT Resource id #141 
QUERY select count(*) as count from categories where parent_id = '38'
RESULT Resource id #145 
QUERY select count(*) as count from categories where parent_id = '33'
RESULT Resource id #149 
QUERY select count(*) as count from categories where parent_id = '34'
RESULT Resource id #153 
QUERY select count(*) as count from categories where parent_id = '41'
RESULT Resource id #157 
QUERY select count(*) as count from categories where parent_id = '32'
RESULT Resource id #161 
QUERY select count(*) as count from categories where parent_id = '35'
RESULT Resource id #165 
QUERY select count(*) as count from categories where parent_id = '36'
RESULT Resource id #169 
QUERY select count(*) as count from categories where parent_id = '37'
RESULT Resource id #173 
QUERY select count(*) as count from categories where parent_id = '42'
RESULT Resource id #177 
QUERY select distinct p.products_id, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from products p, products_description pd, products_to_categories p2c, categories c where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '0' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit 10
RESULT Resource id #184 
QUERY select specials_new_products_price from specials where products_id = '324' and status
RESULT Resource id #192 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #195 
QUERY select specials_new_products_price from specials where products_id = '229' and status
RESULT Resource id #202 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #205 
QUERY select specials_new_products_price from specials where products_id = '159' and status
RESULT Resource id #212 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #215 
QUERY select specials_new_products_price from specials where products_id = '212' and status
RESULT Resource id #222 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #225 
QUERY select specials_new_products_price from specials where products_id = '213' and status
RESULT Resource id #232 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #235 
QUERY select specials_new_products_price from specials where products_id = '211' and status
RESULT Resource id #242 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #245 
QUERY select specials_new_products_price from specials where products_id = '241' and status
RESULT Resource id #252 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #255 
QUERY select specials_new_products_price from specials where products_id = '175' and status
RESULT Resource id #262 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #265 
QUERY select specials_new_products_price from specials where products_id = '215' and status
RESULT Resource id #272 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #275 
QUERY select specials_new_products_price from specials where products_id = '205' and status
RESULT Resource id #282 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #285 
QUERY select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '1' order by p.products_date_added desc limit 12
RESULT Resource id #292 
QUERY select products_description, products_id from products_description where products_id = '176' and language_id = '1'
RESULT Resource id #295 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #300 
QUERY select products_description, products_id from products_description where products_id = '329' and language_id = '1'
RESULT Resource id #304 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #309 
QUERY select products_description, products_id from products_description where products_id = '328' and language_id = '1'
RESULT Resource id #313 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #318 
QUERY select products_description, products_id from products_description where products_id = '175' and language_id = '1'
RESULT Resource id #322 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #325 
QUERY select products_description, products_id from products_description where products_id = '174' and language_id = '1'
RESULT Resource id #329 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #334 
QUERY select products_description, products_id from products_description where products_id = '144' and language_id = '1'
RESULT Resource id #338 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #343 
QUERY select products_description, products_id from products_description where products_id = '220' and language_id = '1'
RESULT Resource id #347 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #352 
QUERY select products_description, products_id from products_description where products_id = '327' and language_id = '1'
RESULT Resource id #356 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #361 
QUERY select products_description, products_id from products_description where products_id = '128' and language_id = '1'
RESULT Resource id #365 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #370 
QUERY select products_description, products_id from products_description where products_id = '326' and language_id = '1'
RESULT Resource id #374 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #379 
QUERY select products_description, products_id from products_description where products_id = '237' and language_id = '1'
RESULT Resource id #383 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #388 
QUERY select products_description, products_id from products_description where products_id = '239' and language_id = '1'
RESULT Resource id #392 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #397 
QUERY select p.products_id, pd.products_name, products_date_available as date_expected from products p, products_description pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '1' order by date_expected asc limit 10
RESULT Resource id #402 
QUERY select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from products p, products_description pd, specials s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '1' and s.status = '1' order by s.specials_date_added desc limit 10
RESULT Resource id #407 
QUERY select manufacturers_name, manufacturers_id, manufacturers_image from manufacturers where manufacturers_image  not like '' order by manufacturers_name
RESULT Resource id #413 
QUERY select startdate, counter from counter
RESULT Resource id #418 
QUERY update counter set counter = '47084'
RESULT 1 
QUERY select banners_id, banners_title, banners_image, banners_html_text from banners where status = '1' and banners_group = '468x50'
RESULT Resource id #423 
QUERY select count(*) as total from sessions where sesskey = '64422d3891bad9705181fab71db8c498'
RESULT Resource id #428 
QUERY insert into sessions values ('64422d3891bad9705181fab71db8c498', '1289909801', 'cart|O:12:\"shoppingCart\":4:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:12:\"content_type\";b:0;}language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:0:{}s:8:\"snapshot\";a:0:{}}')
RESULT 1 
16/11/2010 11:52:41 - /index.php?cookies=1 (0.094s)
QUERY select value from sessions where sesskey = 'ef35bd02e34fdb90b340500867969d5b' and expiry > '1289908373'
RESULT Resource id #17 
QUERY select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from currencies
RESULT Resource id #21 
QUERY select languages_id, name, code, image, directory from languages order by sort_order
RESULT Resource id #27 
QUERY delete from whos_online where time_last_click < '1289907473'
RESULT 1 
QUERY select count(*) as count from whos_online where session_id = 'ef35bd02e34fdb90b340500867969d5b'
RESULT Resource id #40 
QUERY insert into whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('0', 'Guest', 'ef35bd02e34fdb90b340500867969d5b', '91.211.16.126', '1289908373', '1289908373', '/index.php?cookies=1')
RESULT 1 
QUERY select banners_id, date_scheduled from banners where date_scheduled != ''
RESULT Resource id #50 
QUERY select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from banners b, banners_history bh where b.status = '1' and b.banners_id = bh.banners_id group by b.banners_id
RESULT Resource id #53 
QUERY select specials_id from specials where status = '1' and now() >= expires_date and expires_date > 0
RESULT Resource id #57 
QUERY select * from supertracker where browser_string ='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)' and ip_address like '91.211%' and last_click > '2010-11-16 11:22:53'
RESULT Resource id #64 
QUERY UPDATE supertracker set last_click='2010-11-16 11:52:53', exit_page='/index.php', num_clicks='4', added_cart='false', categories_viewed='b:0;', products_viewed='', customer_id='0', completed_purchase='false', cart_contents='', cart_total = '0', order_id = '0' where tracking_id='7234'
RESULT 1 
QUERY select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='1' order by sort_order, cd.categories_name
RESULT Resource id #89 
QUERY select count(*) as count from categories where parent_id = '22'
RESULT Resource id #93 
QUERY select count(*) as count from categories where parent_id = '23'
RESULT Resource id #97 
QUERY select count(*) as count from categories where parent_id = '40'
RESULT Resource id #101 
QUERY select count(*) as count from categories where parent_id = '24'
RESULT Resource id #105 
QUERY select count(*) as count from categories where parent_id = '26'
RESULT Resource id #109 
QUERY select count(*) as count from categories where parent_id = '28'
RESULT Resource id #113 
QUERY select count(*) as count from categories where parent_id = '39'
RESULT Resource id #117 
QUERY select count(*) as count from categories where parent_id = '27'
RESULT Resource id #121 
QUERY select count(*) as count from categories where parent_id = '25'
RESULT Resource id #125 
QUERY select count(*) as count from categories where parent_id = '29'
RESULT Resource id #129 
QUERY select count(*) as count from categories where parent_id = '30'
RESULT Resource id #133 
QUERY select count(*) as count from categories where parent_id = '31'
RESULT Resource id #137 
QUERY select count(*) as count from categories where parent_id = '43'
RESULT Resource id #141 
QUERY select count(*) as count from categories where parent_id = '38'
RESULT Resource id #145 
QUERY select count(*) as count from categories where parent_id = '33'
RESULT Resource id #149 
QUERY select count(*) as count from categories where parent_id = '34'
RESULT Resource id #153 
QUERY select count(*) as count from categories where parent_id = '41'
RESULT Resource id #157 
QUERY select count(*) as count from categories where parent_id = '32'
RESULT Resource id #161 
QUERY select count(*) as count from categories where parent_id = '35'
RESULT Resource id #165 
QUERY select count(*) as count from categories where parent_id = '36'
RESULT Resource id #169 
QUERY select count(*) as count from categories where parent_id = '37'
RESULT Resource id #173 
QUERY select count(*) as count from categories where parent_id = '42'
RESULT Resource id #177 
QUERY select distinct p.products_id, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from products p, products_description pd, products_to_categories p2c, categories c where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '0' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit 10
RESULT Resource id #184 
QUERY select specials_new_products_price from specials where products_id = '324' and status
RESULT Resource id #192 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #195 
QUERY select specials_new_products_price from specials where products_id = '229' and status
RESULT Resource id #202 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #205 
QUERY select specials_new_products_price from specials where products_id = '159' and status
RESULT Resource id #212 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #215 
QUERY select specials_new_products_price from specials where products_id = '212' and status
RESULT Resource id #222 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #225 
QUERY select specials_new_products_price from specials where products_id = '213' and status
RESULT Resource id #232 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #235 
QUERY select specials_new_products_price from specials where products_id = '211' and status
RESULT Resource id #242 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #245 
QUERY select specials_new_products_price from specials where products_id = '241' and status
RESULT Resource id #252 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #255 
QUERY select specials_new_products_price from specials where products_id = '175' and status
RESULT Resource id #262 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #265 
QUERY select specials_new_products_price from specials where products_id = '215' and status
RESULT Resource id #272 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #275 
QUERY select specials_new_products_price from specials where products_id = '205' and status
RESULT Resource id #282 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #285 
QUERY select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '1' order by p.products_date_added desc limit 12
RESULT Resource id #292 
QUERY select products_description, products_id from products_description where products_id = '176' and language_id = '1'
RESULT Resource id #295 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #300 
QUERY select products_description, products_id from products_description where products_id = '329' and language_id = '1'
RESULT Resource id #304 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #309 
QUERY select products_description, products_id from products_description where products_id = '328' and language_id = '1'
RESULT Resource id #313 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #318 
QUERY select products_description, products_id from products_description where products_id = '175' and language_id = '1'
RESULT Resource id #322 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #325 
QUERY select products_description, products_id from products_description where products_id = '174' and language_id = '1'
RESULT Resource id #329 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #334 
QUERY select products_description, products_id from products_description where products_id = '144' and language_id = '1'
RESULT Resource id #338 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #343 
QUERY select products_description, products_id from products_description where products_id = '220' and language_id = '1'
RESULT Resource id #347 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #352 
QUERY select products_description, products_id from products_description where products_id = '327' and language_id = '1'
RESULT Resource id #356 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #361 
QUERY select products_description, products_id from products_description where products_id = '128' and language_id = '1'
RESULT Resource id #365 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #370 
QUERY select products_description, products_id from products_description where products_id = '326' and language_id = '1'
RESULT Resource id #374 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #379 
QUERY select products_description, products_id from products_description where products_id = '237' and language_id = '1'
RESULT Resource id #383 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #388 
QUERY select products_description, products_id from products_description where products_id = '239' and language_id = '1'
RESULT Resource id #392 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #397 
QUERY select p.products_id, pd.products_name, products_date_available as date_expected from products p, products_description pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '1' order by date_expected asc limit 10
RESULT Resource id #402 
QUERY select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from products p, products_description pd, specials s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '1' and s.status = '1' order by s.specials_date_added desc limit 10
RESULT Resource id #407 
QUERY select manufacturers_name, manufacturers_id, manufacturers_image from manufacturers where manufacturers_image  not like '' order by manufacturers_name
RESULT Resource id #413 
QUERY select startdate, counter from counter
RESULT Resource id #418 
QUERY update counter set counter = '47085'
RESULT 1 
QUERY select banners_id, banners_title, banners_image, banners_html_text from banners where status = '1' and banners_group = '468x50'
RESULT Resource id #423 
QUERY select count(*) as total from sessions where sesskey = 'ef35bd02e34fdb90b340500867969d5b'
RESULT Resource id #428 
QUERY insert into sessions values ('ef35bd02e34fdb90b340500867969d5b', '1289909813', 'cart|O:12:\"shoppingCart\":4:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:12:\"content_type\";b:0;}language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:0:{}s:8:\"snapshot\";a:0:{}}')
RESULT 1 
16/11/2010 11:52:53 - /index.php?cookies=1 (0.095s)
QUERY select value from sessions where sesskey = '60b6dbfd980f0bbad0eb580c21e3c2cb' and expiry > '1289908381'
RESULT Resource id #17 
QUERY select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from currencies
RESULT Resource id #21 
QUERY select languages_id, name, code, image, directory from languages order by sort_order
RESULT Resource id #27 
QUERY delete from whos_online where time_last_click < '1289907481'
RESULT 1 
QUERY select count(*) as count from whos_online where session_id = '60b6dbfd980f0bbad0eb580c21e3c2cb'
RESULT Resource id #40 
QUERY insert into whos_online (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('0', 'Guest', '60b6dbfd980f0bbad0eb580c21e3c2cb', '91.211.16.126', '1289908381', '1289908381', '/index.php?cookies=1')
RESULT 1 
QUERY select banners_id, date_scheduled from banners where date_scheduled != ''
RESULT Resource id #50 
QUERY select b.banners_id, b.expires_date, b.expires_impressions, sum(bh.banners_shown) as banners_shown from banners b, banners_history bh where b.status = '1' and b.banners_id = bh.banners_id group by b.banners_id
RESULT Resource id #53 
QUERY select specials_id from specials where status = '1' and now() >= expires_date and expires_date > 0
RESULT Resource id #57 
QUERY select * from supertracker where browser_string ='Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; WebMoney Advisor; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)' and ip_address like '91.211%' and last_click > '2010-11-16 11:23:01'
RESULT Resource id #64 
QUERY UPDATE supertracker set last_click='2010-11-16 11:53:01', exit_page='/index.php', num_clicks='5', added_cart='false', categories_viewed='b:0;', products_viewed='', customer_id='0', completed_purchase='false', cart_contents='', cart_total = '0', order_id = '0' where tracking_id='7234'
RESULT 1 
QUERY select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='1' order by sort_order, cd.categories_name
RESULT Resource id #89 
QUERY select count(*) as count from categories where parent_id = '22'
RESULT Resource id #93 
QUERY select count(*) as count from categories where parent_id = '23'
RESULT Resource id #97 
QUERY select count(*) as count from categories where parent_id = '40'
RESULT Resource id #101 
QUERY select count(*) as count from categories where parent_id = '24'
RESULT Resource id #105 
QUERY select count(*) as count from categories where parent_id = '26'
RESULT Resource id #109 
QUERY select count(*) as count from categories where parent_id = '28'
RESULT Resource id #113 
QUERY select count(*) as count from categories where parent_id = '39'
RESULT Resource id #117 
QUERY select count(*) as count from categories where parent_id = '27'
RESULT Resource id #121 
QUERY select count(*) as count from categories where parent_id = '25'
RESULT Resource id #125 
QUERY select count(*) as count from categories where parent_id = '29'
RESULT Resource id #129 
QUERY select count(*) as count from categories where parent_id = '30'
RESULT Resource id #133 
QUERY select count(*) as count from categories where parent_id = '31'
RESULT Resource id #137 
QUERY select count(*) as count from categories where parent_id = '43'
RESULT Resource id #141 
QUERY select count(*) as count from categories where parent_id = '38'
RESULT Resource id #145 
QUERY select count(*) as count from categories where parent_id = '33'
RESULT Resource id #149 
QUERY select count(*) as count from categories where parent_id = '34'
RESULT Resource id #153 
QUERY select count(*) as count from categories where parent_id = '41'
RESULT Resource id #157 
QUERY select count(*) as count from categories where parent_id = '32'
RESULT Resource id #161 
QUERY select count(*) as count from categories where parent_id = '35'
RESULT Resource id #165 
QUERY select count(*) as count from categories where parent_id = '36'
RESULT Resource id #169 
QUERY select count(*) as count from categories where parent_id = '37'
RESULT Resource id #173 
QUERY select count(*) as count from categories where parent_id = '42'
RESULT Resource id #177 
QUERY select distinct p.products_id, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from products p, products_description pd, products_to_categories p2c, categories c where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '0' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit 10
RESULT Resource id #184 
QUERY select specials_new_products_price from specials where products_id = '324' and status
RESULT Resource id #192 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #195 
QUERY select specials_new_products_price from specials where products_id = '229' and status
RESULT Resource id #202 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #205 
QUERY select specials_new_products_price from specials where products_id = '159' and status
RESULT Resource id #212 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #215 
QUERY select specials_new_products_price from specials where products_id = '212' and status
RESULT Resource id #222 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #225 
QUERY select specials_new_products_price from specials where products_id = '213' and status
RESULT Resource id #232 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #235 
QUERY select specials_new_products_price from specials where products_id = '211' and status
RESULT Resource id #242 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #245 
QUERY select specials_new_products_price from specials where products_id = '241' and status
RESULT Resource id #252 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #255 
QUERY select specials_new_products_price from specials where products_id = '175' and status
RESULT Resource id #262 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #265 
QUERY select specials_new_products_price from specials where products_id = '215' and status
RESULT Resource id #272 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #275 
QUERY select specials_new_products_price from specials where products_id = '205' and status
RESULT Resource id #282 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #285 
QUERY select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '1' order by p.products_date_added desc limit 12
RESULT Resource id #292 
QUERY select products_description, products_id from products_description where products_id = '176' and language_id = '1'
RESULT Resource id #295 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #300 
QUERY select products_description, products_id from products_description where products_id = '329' and language_id = '1'
RESULT Resource id #304 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #309 
QUERY select products_description, products_id from products_description where products_id = '328' and language_id = '1'
RESULT Resource id #313 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #318 
QUERY select products_description, products_id from products_description where products_id = '175' and language_id = '1'
RESULT Resource id #322 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #325 
QUERY select products_description, products_id from products_description where products_id = '174' and language_id = '1'
RESULT Resource id #329 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #334 
QUERY select products_description, products_id from products_description where products_id = '144' and language_id = '1'
RESULT Resource id #338 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #343 
QUERY select products_description, products_id from products_description where products_id = '220' and language_id = '1'
RESULT Resource id #347 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #352 
QUERY select products_description, products_id from products_description where products_id = '327' and language_id = '1'
RESULT Resource id #356 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #361 
QUERY select products_description, products_id from products_description where products_id = '128' and language_id = '1'
RESULT Resource id #365 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #370 
QUERY select products_description, products_id from products_description where products_id = '326' and language_id = '1'
RESULT Resource id #374 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #379 
QUERY select products_description, products_id from products_description where products_id = '237' and language_id = '1'
RESULT Resource id #383 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #388 
QUERY select products_description, products_id from products_description where products_id = '239' and language_id = '1'
RESULT Resource id #392 
QUERY select sum(tax_rate) as tax_rate from tax_rates tr left join zones_to_geo_zones za on (tr.tax_zone_id = za.geo_zone_id) left join geo_zones tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '222') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '0') and tr.tax_class_id = '0' group by tr.tax_priority
RESULT Resource id #397 
QUERY select p.products_id, pd.products_name, products_date_available as date_expected from products p, products_description pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '1' order by date_expected asc limit 10
RESULT Resource id #402 
QUERY select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from products p, products_description pd, specials s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '1' and s.status = '1' order by s.specials_date_added desc limit 10
RESULT Resource id #407 
QUERY select manufacturers_name, manufacturers_id, manufacturers_image from manufacturers where manufacturers_image  not like '' order by manufacturers_name
RESULT Resource id #413 
QUERY select startdate, counter from counter
RESULT Resource id #418 
QUERY update counter set counter = '47086'
RESULT 1 
QUERY select banners_id, banners_title, banners_image, banners_html_text from banners where status = '1' and banners_group = '468x50'
RESULT Resource id #423 
QUERY select count(*) as total from sessions where sesskey = '60b6dbfd980f0bbad0eb580c21e3c2cb'
RESULT Resource id #428 
QUERY insert into sessions values ('60b6dbfd980f0bbad0eb580c21e3c2cb', '1289909821', 'cart|O:12:\"shoppingCart\":4:{s:8:\"contents\";a:0:{}s:5:\"total\";i:0;s:6:\"weight\";i:0;s:12:\"content_type\";b:0;}language|s:7:\"english\";languages_id|s:1:\"1\";currency|s:3:\"GBP\";navigation|O:17:\"navigationHistory\":2:{s:4:\"path\";a:0:{}s:8:\"snapshot\";a:0:{}}')
RESULT 1 
16/11/2010 11:53:01 - /index.php?cookies=1 (0.102s)
