<?php
/* Template Name: Product Shop */
get_header(); ?>

<div class="flex gap-6 px-6 py-8">
  <?php get_template_part('filter-sidebar');?>

	<!-- Product Listing -->
	<main class="w-3/4">
		<div id="product-results" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<div class="lds-dual-ring black"></div>
		</div>
	</main>
</div>


<?php get_footer(); ?>
