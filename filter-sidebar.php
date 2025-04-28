<?php
// filter-sidebar.php - Sidebar for filtering products
$excluded_taxonomy = get_query_var('excluded_taxonomy', '');
?>
<aside class="w-1/4 bg-gray-100 p-4 rounded-lg shadow">
	<!-- Selected Filters Section -->
	<div id="selected-filters" class="bg-white p-4 rounded-lg shadow mb-4 hidden">
		<h4 class="text-lg font-semibold border-b pb-2">You've selected</h4>
		<div id="selected-filters-list" class="mt-2 space-y-2"></div>
		<a href="#" id="clear-all-filters" class="text-red-500 font-semibold mt-2 inline-block">Clear all!</a>
	</div>

	<!-- Keyword Search -->
	<div class="filter-group mb-4">
		<h4 class="text-lg font-semibold">Keyword Search</h4>
		<div class="relative">
			<input type="text" id="keyword-search" name="keyword" placeholder="Search products..."
				   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300" />
			<span id="clear-keyword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer hidden">✖</span>
		</div>
	</div>

	<!-- Taxonomy Filters -->
	<form id="product-filter-form">
		<?php 
		$taxonomies = array('product_category', 'application', 'project_type', 'feature', 'usage');
		foreach ($taxonomies as $taxonomy) {
			if ($taxonomy === $excluded_taxonomy) continue; // Skip the currently viewed taxonomy
			
			$terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
			if (!empty($terms)) {
				echo '<div class="filter-group mb-4" data-taxonomy="' . esc_attr($taxonomy) . '">';
				echo '<h4 class="text-lg font-semibold border-b pb-2">' . esc_html(ucwords(str_replace('_', ' ', $taxonomy))) . '</h4>';
				foreach ($terms as $term) {
					echo '<label class="flex items-center justify-between w-full py-1">
							<div class="flex items-center space-x-3">
								<input type="checkbox" name="' . esc_attr($taxonomy) . '[]" value="' . esc_attr($term->slug) . '" 
									   class="form-checkbox text-blue-600 focus:ring-blue-500 w-5 h-5" />
								<span class="text-sm font-medium text-gray-700">' . esc_html($term->name) . '</span>
							</div>
							<span class="count text-gray-500 text-sm">(0)</span>
						  </label>';
				}
				echo '</div>';
			}
		}
		?>
		<button type="button" id="clear-filters"
				class="w-full bg-red-500 text-white py-2 px-4 rounded-lg mt-4 hover:bg-red-600">
			Clear Filters
		</button>
	</form>
</aside>
