<?php
/* Template for Product Category Archive */
get_header();

$term = get_queried_object(); // Get current taxonomy term
$excluded_taxonomy = $term->taxonomy; // Store the taxonomy being viewed
$term_count = $term->count; // Get the number of products in this term
set_query_var('excluded_taxonomy', $excluded_taxonomy);
?>

<div class="flex gap-6 px-6 py-8">
	<!-- Sidebar (Filters) with the current taxonomy removed -->
	<?php get_template_part('filter-sidebar'); ?>

	<!-- Main Content -->
	<main class="w-3/4" data-taxonomy="<?php echo esc_attr($term->taxonomy); ?>" data-term="<?php echo esc_attr($term->slug); ?>">
		<h1 class="text-2xl font-bold mb-4">
			Currently Viewing: <?php echo esc_html($excluded_taxonomy); ?> → <?php echo esc_html($term->name); ?> (<?php echo esc_html($term_count); ?>)
		</h1>
		<a href="<?php echo site_url('/shop/'); ?>" class="text-blue-500 underline">← Back to Shop</a>

		<div id="product-results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<div class="lds-dual-ring black"></div>
		</div>
	</main>
</div>

<?php get_footer(); ?>
