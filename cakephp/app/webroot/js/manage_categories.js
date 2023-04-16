const addCategoryButton = $("#add-category-btn");
addCategoryButton.click(() => {
	initializeModal(createCategoryModal());
});

const categoriesTable = $("#categories-table");
categoriesTable.on("click", ".edit-category-btn", (event) => {
	const categoryData = $(event.currentTarget).closest("tr").data("category");
	initializeModal(createCategoryModal(categoryData));
});
categoriesTable.on("click", ".delete-category-btn", (event) => {
	const categoryData = $(event.currentTarget).closest("tr").data("category");
	initializeModal(
		createConfirmationModal({
			submitUrl: `/admin/categories/delete/${categoryData.id}`,
			message: `Are you sure you want to delete the category "${categoryData.id} - ${categoryData.name}"?`,
		})
	);
});

$(document).on("submit", "#category-form", (event) => {
	event.preventDefault();
	const form = $(event.currentTarget);

	$.ajax({
		type: "POST",
		url: form.attr("action"),
		data: form.serialize(),
		success: (response) => {
			if (response.success) {
				const categoryRow = $(`tr[data-id="${response.category.id}"]`);
				if (categoryRow.length !== 0) {
					categoryRow.replaceWith(createCategoryRow(response.category));
					initializeToast(
						`Category "${response.category.id} - ${response.category.name}" was successfully updated!`
					);
				} else {
					const tableTbody = $("#categories-table tbody");
					const totalNumberOfCategoriesSpan = $("#total-number-of-items");
					const currentNumberOfCategories =
						Number(totalNumberOfCategoriesSpan.text().slice(1, -1)) + 1;
					totalNumberOfCategoriesSpan.text(`(${currentNumberOfCategories})`);
					if (currentNumberOfCategories === 1) {
						tableTbody.html(createCategoryRow(response.category));
					} else {
						tableTbody.prepend(createCategoryRow(response.category));
					}
					initializeToast(
						`Category "${response.category.id} - ${response.category.name}" was successfully added!`
					);
				}
				$("#category-modal").modal("hide");
			} else {
				resetErrors(form);
				markErrors(form, response.errors);
			}
		},
		error: (error) => {
			initializeToast(`Category wasn't successfully added/updated!`);
			$("#category-modal").modal("hide");
			console.error(error);
		},
	});
});
$(document).on("submit", "#confirm-form", (event) => {
	event.preventDefault();
	const form = $(event.currentTarget);

	$.ajax({
		type: "POST",
		url: form.attr("action"),
		data: form.serialize(),
		success: (response) => {
			if (response.success) {
				const categoryRow = $(`tr[data-id="${response.category_id}"]`);
				const totalNumberOfCategoriesSpan = $("#total-number-of-items");
				const currentNumberOfCategories =
					Number(totalNumberOfCategoriesSpan.text().slice(1, -1)) - 1;
				totalNumberOfCategoriesSpan.text(`(${currentNumberOfCategories})`);

				if (currentNumberOfCategories === 0) {
					categoryRow.replaceWith(createEmptyRow("There are no categories."));
				} else {
					categoryRow.remove();
				}
				initializeToast(`Category was successfully deleted!`);
			} else {
				initializeToast(`Category wasn't successfully deleted!`);
			}
		},
		error: (error) => {
			initializeToast(`Category wasn't successfully deleted!`);
			console.error(error);
		},
		complete: () => {
			$("#confirm-modal").modal("hide");
		},
	});
});

function createCategoryRow(category) {
	return `
		<tr data-id="${category.id}" data-category='${JSON.stringify(category)}'>
			<td class="text-center">${category.id}</td>
			<td>${category.name}</td>
			<td class="text-center d-flex justify-content-center align-items-center cg-1">
				<button class="edit-category-btn btn btn-warning btn-sm">
					<span>
						<i class="bi bi-pencil-fill"></i>
					</span>
				</button>
				<button class="delete-category-btn btn btn-danger btn-sm">
					<span>
						<i class="bi bi-trash-fill"></i>
					</span>
				</button>
			</td>
		</tr>
	`;
}
