const addCategoryButton = $("#add-category-btn");
const categoriesTable = $("#categories-table");

addCategoryButton.click(() => {
	initializeModal(createCategoryModal());
});

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

handleAjaxFormSubmit(
	"#category-form",
	handleCategorySuccess,
	handleCategoryError
);
handleAjaxFormSubmit(
	"#confirm-form",
	handleCategoryDeleteSuccess,
	handleCategoryDeleteError
);

function handleCategorySuccess(form, response) {
	if (response.success) {
		const item = $(`tr[data-id="${response.data.id}"]`);
		const newItem = createCategoryRow(response.data);
		if (item.length === 0) {
			const container = $("#categories-table tbody");
			const currentNumberOfItems = updateTotalNumberOfItems(1);
			if (currentNumberOfItems === 1) {
				container.html(newItem);
			} else {
				container.prepend(newItem);
			}
			initializeToast(
				`Category (id: ${response.data.id}, name: ${response.data.name}) successfully added.`
			);
		} else {
			item.replaceWith(newItem);
			initializeToast(
				`Category (id: ${response.data.id}, name: ${response.data.name}) successfully updated.`
			);
		}
		$("#category-modal").modal("hide");
	} else {
		resetErrors(form);
		markErrors(form, response.errors);
	}
}

function handleCategoryError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#category-modal").modal("hide");
	console.error(xhr);
}

function handleCategoryDeleteSuccess(form, response) {
	if (response.success) {
		const item = $(`tr[data-id="${response.data.id}"]`);
		const currentNumberOfItems = updateTotalNumberOfItems(-1);
		if (currentNumberOfItems === 0) {
			item.replaceWith(createEmptyRow("There are no categories."));
		} else {
			item.remove();
		}
		initializeToast(`Category (id: ${response.data.id}) successfully deleted.`);
	} else {
		initializeToast(
			`Category (id: ${response.data.id}) wasn't successfully deleted.`
		);
	}
	$("#confirm-modal").modal("hide");
}

function handleCategoryDeleteError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#confirm-modal").modal("hide");
	console.error(xhr);
}

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
