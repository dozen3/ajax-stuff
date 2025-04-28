jQuery(document).ready(function($) {

	function updateFilters() {
		var selectedFilters = {};
		var keyword = $("#keyword-search").val();

		// Get the currently viewed taxonomy and term from the page
		var currentTaxonomy = $("main").data("taxonomy"); // Stored in the <main> tag
		var currentTerm = $("main").data("term");

		$("#product-filter-form .filter-group").each(function() {
			var taxonomy = $(this).data("taxonomy");
			var checked = $(this).find("input:checked");
			if (checked.length) {
				selectedFilters[taxonomy] = [];
				checked.each(function() {
					selectedFilters[taxonomy].push($(this).val());
				});
			}
		});

		// Ensure filters apply only within the currently viewed taxonomy term
		if (currentTaxonomy && currentTerm) {
			selectedFilters[currentTaxonomy] = [currentTerm];
		}
		// **Smooth Scroll to Top of Product Results**
		var productResults = $("#product-results");
		var productOffset = productResults.offset().top; // Get top position of product list
		var scrollTop = $(window).scrollTop(); // Get current scroll position
		
		if (scrollTop > productOffset - 50) { // If user has scrolled past product list
			$("html, body").animate({ scrollTop: productOffset - 20 }, 600); // Smooth scroll up
		}

		$.ajax({
			type: "POST",
			url: ajax_vars.ajaxurl,
			data: {
				action: "filter_products",
				keyword: keyword,
				...selectedFilters
			},
			beforeSend: function() {
				$("#product-results").html("<div class='lds-dual-ring black'></div>");
			},
			success: function(response) {
				var data = JSON.parse(response);
				$("#product-results").html(data.products);
				
				$(".filter-group").each(function() {
					var taxonomy = $(this).data("taxonomy");
					
					// Ensure taxonomy exists in counts
					if (data.counts[taxonomy]) {
						$(this).find("label").each(function() {
							var checkbox = $(this).find("input");
							var termSlug = checkbox.val().trim();
							var count = data.counts[taxonomy][termSlug] !== undefined ? data.counts[taxonomy][termSlug] : 0;
							
							// Update the count UI
							$(this).find(".count").text("(" + count + ")");
							
							// Disable the checkbox if count is 0
							if (count === 0) {
								checkbox.prop("disabled", true);
							} else {
								checkbox.prop("disabled", false);
							}
						});
					} else {
						console.log("No counts found for taxonomy:", taxonomy);
					}
				});
				
				updateSelectedFilters();
			}
		});
	}

	function updateSelectedFilters() {
		var selectedHTML = "";
		var hasFilters = false;

		$("#product-filter-form .filter-group").each(function() {
			var taxonomy = $(this).data("taxonomy");
			var taxonomyLabel = $(this).find("h4").text();
			var checked = $(this).find("input:checked");

			if (checked.length) {
				hasFilters = true;
				selectedHTML += "<div class='selected-filter' data-taxonomy='" + taxonomy + "'>";
				selectedHTML += "<strong>" + taxonomyLabel + "</strong>";

				checked.each(function() {
					var filterText = $(this).parent().clone().children(".count").remove().end().text().trim();
					selectedHTML += "<div class='selected-filter-item' data-value='" + $(this).val() + "'>";
					selectedHTML += "❌ " + filterText;
					selectedHTML += "</div>";
				});

				selectedHTML += "</div>";
			}
		});

		if (hasFilters) {
			$("#selected-filters-list").html(selectedHTML);
			$("#selected-filters").show();
		} else {
			$("#selected-filters").hide();
		}
	}

	// Load all products on page load
	updateFilters();

	// Filter when checkboxes change
	$("#product-filter-form input[type='checkbox']").change(updateFilters);
	$("#keyword-search").on("input", updateFilters);

	// Remove individual filter
	$(document).on("click", ".selected-filter-item", function() {
		var taxonomy = $(this).closest(".selected-filter").data("taxonomy");
		var value = $(this).data("value");
		$(".filter-group[data-taxonomy='" + taxonomy + "'] input[value='" + value + "']").prop("checked", false);
		updateFilters();
	});

	// Clear just the checkboxes
	$("#clear-all-filters").click(function(e) {
		e.preventDefault();
		$("#product-filter-form input[type='checkbox']").prop("checked", false);
		updateFilters();
	});

	// Clear all filters bottom button
	$("#clear-filters").click(function (e) {
		e.preventDefault();
		$("#keyword-search").val("");
		$("#clear-keyword").hide();
		$("#product-filter-form input[type='checkbox']").prop("checked", false);
		$("#selected-filters").hide();
		updateFilters();
	});

	const keywordInput = $("#keyword-search");
	const clearKeywordBtn = $("#clear-keyword");

	// Show 'X' when user types in keyword input
	keywordInput.on("input", function () {
		clearKeywordBtn.toggle($(this).val().length > 0);
	});

	// Clear keyword input when 'X' is clicked
	clearKeywordBtn.on("click", function () {
		keywordInput.val("");
		$(this).hide();
		updateFilters();
	});

});
