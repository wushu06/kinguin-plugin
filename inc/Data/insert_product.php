<?php

/*function prod_a( $ID ){

	$product = new WC_Product_Variable($ID);
	$variations = $product->get_available_variations();
	var_dump($variations);
	$var_sku = array();
	foreach ($variations as $variation) {
		 $var_id[] = $variation['variation_id'];

	}
	return $var_id;
}*/

//add_action('init', 'insert_all');
/*
function insert_all()

{
	$json = file_get_contents(plugins_url().'/my-product-data.json'); // Get json from sample file
	$products_data = json_decode($json, true); // Decode it into an array


	insert_products($products_data);

}


function insert_products ($products)
{
	if (!empty($products)) // No point proceeding if there are no products
	{
		array_map('insert_product', $products); // Run 'insert_product' function from above for each product
	}
}







function insert_product ($product_data)
{




	// loop throught attributes and insert them
	foreach ($product_data['available_attributes'] as $attr_name )

	{
		foreach ($product_data['variations'] as $index=>$var)
		{
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




	// check if the post already exists
	$count = get_page_by_title($product_data['name'], OBJECT, 'product');
	if ( empty($count) ) {

		$post = array( // Set up the basic post data to insert for our product

			'post_author' => 1,
			'post_content' => $product_data['description'],
			'post_status' => 'publish',
			'post_title' => $product_data['name'],
			'post_parent' => '',
			'post_type' => 'product'
		);

		$post_id = wp_insert_post($post); // Insert the post returning the new post id

		if (!$post_id) // If there is no post id something has gone wrong so don't proceed
		{
			return false;
		}

		update_post_meta($post_id, '_sku', $product_data['sku']); // Set its SKU
		update_post_meta($post_id, '_visibility', 'visible'); // Set the product to visible, if not it won't show on the front end

		wp_set_object_terms($post_id, $product_data['categories'], 'product_cat'); // Set up its categories
		wp_set_object_terms($post_id, 'variable', 'product_type'); // Set it to a variable product type

		insert_product_attributes($post_id, $product_data['available_attributes'], $product_data['variations']); // Add attributes passing the new post id, attributes & variations
		insert_product_variations($post_id, $product_data['variations']); // Insert variations passing the new post id & variations
	}
}


function insert_product_attributes ($post_id, $available_attributes, $variations)
{
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
					$values[] = $variation['attributes'][$key];
				}
			}
		}

		// Essentially we want to end up with something like this for each attribute:
		// $values would contain: array('small', 'medium', 'medium', 'large');

		$values = array_unique($values); // Filter out duplicate values

		// Store the values to the attribute on the new post, for example without variables:
		// wp_set_object_terms(23, array('small', 'medium', 'large'), 'pa_size');
		wp_set_object_terms($post_id, $values, 'pa_' . $attribute);
	}

	$product_attributes_data = array(); // Setup array to hold our product attributes data

	foreach ($available_attributes as $attribute) // Loop round each attribute
	{
		$product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'

			'name'         => 'pa_'.$attribute,
			'value'        => '',
			'is_visible'   => '1',
			'is_variation' => '1',
			'is_taxonomy'  => '1'

		);
	}

	update_post_meta($post_id, '_product_attributes', $product_attributes_data); // Attach the above array to the new posts meta data key '_product_attributes'

}

function insert_product_variations ($post_id, $variations)
{
	foreach ($variations as $index => $variation)
	{
		$variation_post = array( // Setup the post data for the variation

			'post_title'  => 'Variation #'.$index.' of '.count($variations).' for product#'. $post_id,
			'post_name'   => 'product-'.$post_id.'-variation-'.$index,
			'post_status' => 'publish',
			'post_parent' => $post_id,
			'post_type'   => 'product_variation',

		);

		$variation_post_id = wp_insert_post($variation_post); // Insert the variation

		foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
		{
			$attribute_term = get_term_by('name', $value, 'pa_'.$attribute); // We need to insert the slug not the name into the variation post meta

			update_post_meta($variation_post_id, 'attribute_pa_'.$attribute, $attribute_term->slug);
			// Again without variables: update_post_meta(25, 'attribute_pa_size', 'small')
		}

		update_post_meta($variation_post_id, '_price', $variation['price']);
		update_post_meta($variation_post_id, '_regular_price', $variation['price']);
	}
}

*/



