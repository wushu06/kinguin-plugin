<?php

namespace Inc\Data\WP;

use Inc\Base\BaseController;

class InsertProducts extends BaseController
{

	public $msg = array();

	public function insert_all($product_data)
	{

		return  $this->insert_products($product_data);

	}


	public function insert_products($products)
	{
		if (!empty($products)) // No point proceeding if there are no products
		{
			return array_map(array($this, 'insert_product'), $products); // Run 'insert_product' public function from above for each product
		}
	}


	public function insert_product($product_data)
	{
		global $wpdb;


		// check if the post already exists
		//$count = get_page_by_title($product_data['title'], OBJECT, 'product');
		$post_name = $product_data['name'];
		$dbpost = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_name = %s ", $post_name));

		$postdate = date("Y-m-d H:i:s");


		if ($dbpost >= 1) {


			$count = get_page_by_title($product_data['title'], OBJECT, 'product');
			$post_id = $count->ID;

			$count = $wpdb->get_results($wpdb->prepare( "SELECT ID FROM wp_posts WHERE post_name = %s" , $post_name));

			$id = json_decode(json_encode($count ), true);
			$post_id = $id[0]['ID'];


			//echo $product_data['title'].'update';

			/*	$wpdb->update(
					$wpdb->posts,
					array(
						'post_title' => $product_data['title'],
						'post_name' => $product_data['name'],
						'post_status' => 'publish',
						'post_type' => 'product',
						'post_date' => $postdate
					),
					array(
						'ID' => $post_id
					),

					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',


					)
				);

				wp_set_object_terms($post_id, $product_data['categories']['product_cat'], 'product_cat'); // Set up its categories
				wp_set_object_terms($post_id, $product_data['categories']['brands'], 'brands'); // Set up its categories


				$sub_cat = $product_data['categories']['subgroup'];
				if ($sub_cat) {
					// insert sub categories

					$parent_term = term_exists($product_data['categories']['product_cat'], 'product_cat'); // array is returned if taxonomy is given
					$parent_term_id = $parent_term['term_id'];

					$sub_cat_exist = term_exists($sub_cat, 'product_cat', $parent_term_id);


					if ($sub_cat_exist) {
						wp_set_post_terms($post_id, $sub_cat_exist['term_id'], 'product_cat');

					} else {
						// get numeric term id

						$child_term = wp_insert_term(
							$sub_cat,   // the term
							'product_cat', // the taxonomy
							array(
								'parent' => $parent_term_id,
							)
						);

						$sub_id = $child_term['term_id'];

						wp_set_post_terms($post_id, $sub_id, 'product_cat');
					}


				}

				update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
				update_post_meta($post_id, '_visibility', 'visible'); // Set the product to visible, if not it won't show on the front end*/

			foreach ($product_data['available_attributes'] as $attr_name) {

				foreach ($product_data['variations'] as $index => $var) {
					$attr_value = $var['attributes'][$attr_name];

				}
				//inserting attr
				global $wpdb;
				$attribute = array('attribute_name' => $attr_name, 'attribute_label' => $attr_name, 'attribute_type' => 'select', 'attribute_orderby' => 'menu_order', 'attribute_public' => 0);
				if (empty($attribute['attribute_type'])) {
					$attribute['attribute_type'] = 'text';
				}
				if (empty($attribute['attribute_orderby'])) {
					$attribute['attribute_orderby'] = 'menu_order';
				}
				if (empty($attribute['attribute_public'])) {
					$attribute['attribute_public'] = 0;
				}
				$name = $attribute['attribute_name'];
				$results = $wpdb->query("SELECT * FROM wp_woocommerce_attribute_taxonomies WHERE  attribute_name= '{$name}'");

				if ($results == false) {

					$wpdb->insert($wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute);

					do_action('woocommerce_attribute_added', $wpdb->insert_id, $attribute);
					delete_transient('wc_attribute_taxonomies');
				}

				add_action('admin_init', 'flush_rewrite_rules');

			}

			$this->msg['status'] = 'updated';
			$this->msg['post_name'] = $product_data['name'];

			wp_set_object_terms($post_id, $product_data['categories']['brands'], 'brands'); // Set up its categories

			wp_set_object_terms($post_id, $product_data['type'], 'product_type'); // Set it to a variable product type
			if($product_data['type'] == 'simple') {
				update_post_meta($post_id, '_price', $product_data['simple_price']);
				update_post_meta($post_id, '_regular_price', $product_data['simple_price']);
				update_post_meta($post_id, '_stock', $product_data['simple_stock']);
				update_post_meta($post_id, '_sku', $product_data['sku']);
				if ($product_data['simple_stock'] != 0) {
					update_post_meta($post_id, '_stock_status', 'instock');
					update_post_meta($post_id, '_manage_stock', 'yes');
				}else{
					update_post_meta($post_id, '_stock_status', 'outofstock');
				}
			}else {
				//update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
				$this->insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations'], $product_data['brands_attr']); // Add attributes passing the new post id, attributes &
				// variations
				return $this->insert_product_variations($post_id, $product_data['variations'], $product_data['sku']); // Insert variations passing the new post id & variations*/
			}



		} else {
			//echo $product_data['title'].'insert <hr>';


			//1- insert attributes

			// loop throught attributes and insert them
			foreach ($product_data['available_attributes'] as $attr_name) {
				foreach ($product_data['variations'] as $index => $var) {
					$attr_value = $var['attributes'][$attr_name];

				}
				//inserting attr
				global $wpdb;
				$attribute = array('attribute_name' => $attr_name, 'attribute_label' => $attr_name, 'attribute_type' => 'select', 'attribute_orderby' => 'menu_order', 'attribute_public' => 0);
				if (empty($attribute['attribute_type'])) {
					$attribute['attribute_type'] = 'text';
				}
				if (empty($attribute['attribute_orderby'])) {
					$attribute['attribute_orderby'] = 'menu_order';
				}
				if (empty($attribute['attribute_public'])) {
					$attribute['attribute_public'] = 0;
				}
				$name = $attribute['attribute_name'];
				$results = $wpdb->query("SELECT * FROM wp_woocommerce_attribute_taxonomies WHERE  attribute_name= '{$name}'");

				if ($results == false) {

					$wpdb->insert($wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute);

					do_action('woocommerce_attribute_added', $wpdb->insert_id, $attribute);
					delete_transient('wc_attribute_taxonomies');
				}

				add_action('admin_init', 'flush_rewrite_rules');

			}


			// 2- insert post
			if($product_data['short_description'] == null){
				$product_data['short_description']  = '';
			}


			$wpdb->insert(
				$wpdb->posts,
				array(
					'post_title' => $product_data['title'],
					'post_name' => $product_data['name'],
					'post_status' => 'draft',
					'post_type' => 'product',
					'post_date' => $postdate
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',

				)
			);
			$post_id = $wpdb->insert_id;

			if (!$post_id) // If there is no post id something has gone wrong so don't proceed
			{
				return false;
			}

			//update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
			update_post_meta($post_id, '_visibility', 'visible'); // Set the product to visible, if not it won't show on the front end

			wp_set_object_terms($post_id, $product_data['categories']['product_cat'], 'product_cat'); // Set up its categories
			wp_set_object_terms($post_id, $product_data['categories']['brands'], 'brands'); // Set up its categories
			$sub_cat = $product_data['categories']['subgroup'];
			if ($sub_cat) {
				// insert sub categories
				$sub_cat_exist = term_exists($sub_cat, 'product_cat');


				if ($sub_cat_exist) {
					wp_set_post_terms($post_id, $sub_cat_exist['term_id'], 'product_cat');

				} else {
					$parent_term = term_exists($product_data['categories']['product_cat'], 'product_cat'); // array is returned if taxonomy is given
					$parent_term_id = $parent_term['term_id'];         // get numeric term id

					$child_term = wp_insert_term(
						$sub_cat,   // the term
						'product_cat', // the taxonomy
						array(
							'parent' => $parent_term_id,
						)
					);

					$sub_id = $child_term['term_id'];

					wp_set_post_terms($post_id, $sub_id, 'product_cat');
				}

			}

			$this->msg['status'] = 'inserted';
			$this->msg['post_name'] = $product_data['name'];


			if($product_data['type'] == 'simple') {
				update_post_meta($post_id, '_price', $product_data['simple_price']);
				update_post_meta($post_id, '_regular_price', $product_data['simple_price']);
				update_post_meta($post_id, '_stock', $product_data['simple_stock']);
				update_post_meta($post_id, '_sku', $product_data['sku']);
				if ($product_data['simple_stock'] != 0) {
					update_post_meta($post_id, '_stock_status', 'instock');
					update_post_meta($post_id, '_manage_stock', 'yes');
				}else{
					update_post_meta($post_id, '_stock_status', 'outofstock');
				}
			}else {
				// insert variable posts
				$this->insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations'], $product_data['brands_attr']); // Add attributes passing the new post id, attributes & variations
				return $this->insert_product_variations($post_id, $product_data['variations'], $product_data['sku']); // Insert variations passing the new post id & variations*/
			}


		}
		return $this->msg;
	}


	public function insert_product_attributes($post_id, $available_attributes, $variations, $brand_attr)
	{
		$product_attributes_data = array(); // Setup array to hold our product attributes data

		foreach ($available_attributes as $attribute) // Loop round each attribute
		{


			$product_attributes_data['pa_' . $attribute] = array( // Set this attributes array to a key to using the prefix 'pa'

				'name' => 'pa_' . $attribute, // insert size, color, hand
				'value' => '',
				'is_visible' => '1',
				'is_variation' => '1',
				'is_taxonomy' => '1'

			);

			//echo $post_id.'pa_'.$attribute.'<hr>';
		}
		$product_attributes_data['pa_brand'] = array( // Set this attributes array to a key to using the prefix 'pa'

			'name' => 'pa_brand', // insert size, color, hand
			'value' => '',
			'is_visible' => '1',
			'is_taxonomy' => '1'

		);


		update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'


		foreach ($available_attributes as $attribute) // Go through each attribute
		{
			$values = array(); // Set up an array to store the current attributes values.

			foreach ($variations as $variation) // Loop each variation in the file
			{
				$attribute_keys = array_keys($variation['attributes']); // Get the keys for the current variations attributes

				foreach ($attribute_keys as $key) // Loop through each key
				{
					if ($key === $attribute) // If this attributes key is the top level attribute add the value to the $values array
					{
						if ($variation['attributes'][$key] != '') {

							switch ($variation['attributes'][$key]) {
								case 'Wh':
									$variation['attributes'][$key] = 'White';
									break;
								case "Bk":
									$variation['attributes'][$key] = 'Black';
									break;

							}

							$values[] = $variation['attributes'][$key];
						}
					}
				}
			}

			// Essentially we want to end up with something like this for each attribute:
			// $values would contain: array('small', 'medium', 'medium', 'large');

			$values = array_unique($values); // Filter out duplicate values

			// Store the values to the attribute on the new post, for example without variables:
			// wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');

			if (!empty($values)) {

				wp_set_object_terms($post_id, $values, 'pa_' . $attribute, true);
				wp_set_object_terms($post_id, array($brand_attr), 'pa_brand', true);
			}
		}


	}


	public function insert_product_variations($post_id, $variations, $product_sku)
	{
		global $wpdb;


		foreach ($variations as $index => $variation) {


			$post_name = 'product-' . $post_id . '-variation-' . $index;
			$variation_post = array( // Setup the post data for the variation
				//'ID'=> 6595,
				'post_title' => 'Variation  for product#' . $post_id,
				'post_name' => $post_name,
				'post_status' => 'publish',
				'post_parent' => $post_id,
				'post_type' => 'product_variation',

			);


			$dbpost = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ", strtolower($post_name)));

			$this->msg['variation_post'] = $post_name;

			if ($dbpost) {

				$variation_id = array();
				$variation_id['ID'] = intval($dbpost);
				$variation_post = $variation_id + $variation_post;
				$variation_post_id = wp_update_post($variation_post); // Insert the variation
				// echo 'update id'.$variation_post_id;

				$this->msg['variation_status'] = 'updated ID: '.$variation_post_id;


			} else {
				$variation_post_id = wp_insert_post($variation_post); // Insert the variation;
				//	echo 'insert'.intval($variation_post_id);
				$this->msg['variation_status'] = 'inserted';


			}


			foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
			{

				if ($value != '') {

					switch ($value) {
						case 'Wh':
							$value = 'White';
							break;
						case "Bk":
							$value = 'Black';
							break;

					}

					@$attribute_term = get_term_by('name', $value, 'pa_' . $attribute); // We need to insert the slug not the name into the variation post meta

					if ($attribute_term->slug !=='') {
                       // echo  'id '.$variation_post_id.' val '.$value . 'attr ' . $attribute . ' slug ' . $attribute_term->slug . '<hr>';

                        update_post_meta($variation_post_id, 'attribute_pa_' . $attribute, $attribute_term->slug);
                        // Again without variables: update_post_meta(25, 'attribute_pa_size', 'small')
                    }
				}

			}

			update_post_meta($variation_post_id, '_sku', $product_sku);
			update_post_meta($variation_post_id, '_price', $variation['price']);
			update_post_meta($variation_post_id, '_regular_price', $variation['price']);
			update_post_meta($variation_post_id, '_stock', $variation['stock']);
			if ($variation['stock'] != 0) {
				update_post_meta($variation_post_id, '_stock_status', 'instock');
				update_post_meta($variation_post_id, '_manage_stock', 'yes');

				$this->msg['variation_stock'] = $variation['stock'];
			}else {
				update_post_meta($variation_post_id, '_stock_status', 'outofstock');
				$this->msg['variation_stock'] = 0;
			}
		}
		return $this->msg;

	}

	

}