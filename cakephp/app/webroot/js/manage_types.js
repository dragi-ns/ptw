const addTypeButton = $("#add-type-btn");
const typesTable = $("#types-table");

addTypeButton.click(() => {
	initializeModal(createTypeModal());
});

typesTable.on("click", ".edit-type-btn", (event) => {
	const typeData = $(event.currentTarget).closest("tr").data("type");
	initializeModal(createTypeModal(typeData));
});
typesTable.on("click", ".delete-type-btn", (event) => {
	const typeData = $(event.currentTarget).closest("tr").data("type");
	initializeModal(
		createConfirmationModal({
			submitUrl: `/admin/types/delete/${typeData.id}`,
			message: `Are you sure you want to delete the type "${typeData.id} - ${typeData.name}"?`,
		})
	);
});

handleAjaxFormSubmit("#type-form", handleTypeSuccess, handleTypeError);
handleAjaxFormSubmit(
	"#confirm-form",
	handleTypeDeleteSuccess,
	handleTypeDeleteError
);

function handleTypeSuccess(form, response) {
	if (response.success) {
		const item = $(`tr[data-id="${response.data.id}"]`);
		const newItem = createTypeRow(response.data);
		if (item.length === 0) {
			const container = $("#types-table tbody");
			const currentNumberOfItems = updateTotalNumberOfItems(1);
			if (currentNumberOfItems === 1) {
				container.html(newItem);
			} else {
				container.prepend(newItem);
			}
			initializeToast(
				`Type (id: ${response.data.id}, name: ${response.data.name}) successfully added.`
			);
		} else {
			item.replaceWith(newItem);
			initializeToast(
				`Type (id: ${response.data.id}, name: ${response.data.name}) successfully updated.`
			);
		}
		$("#type-modal").modal("hide");
	} else {
		resetErrors(form);
		markErrors(form, response.errors);
	}
}

function handleTypeError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#type-modal").modal("hide");
	console.error(xhr);
}

function handleTypeDeleteSuccess(form, response) {
	if (response.success) {
		const item = $(`tr[data-id="${response.data.id}"]`);
		const currentNumberOfItems = updateTotalNumberOfItems(-1);
		if (currentNumberOfItems === 0) {
			item.replaceWith(createEmptyRow("There are no types."));
		} else {
			item.remove();
		}
		initializeToast(`Type (id: ${response.data.id}) successfully deleted.`);
	} else {
		initializeToast(
			`Type (id: ${response.data.id}) wasn't successfully deleted.`
		);
	}
	$("#confirm-modal").modal("hide");
}

function handleTypeDeleteError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#confirm-modal").modal("hide");
	console.error(xhr);
}

function createTypeRow(type) {
	return `
		<tr data-id="${type.id}" data-type='${JSON.stringify(type)}'>
			<td class="text-center">${type.id}</td>
			<td>${type.name}</td>
			<td class="text-center d-flex justify-content-center align-items-center cg-1">
				<button class="edit-type-btn btn btn-warning btn-sm">
					<span>
						<i class="bi bi-pencil-fill"></i>
					</span>
				</button>
				<button class="delete-type-btn btn btn-danger btn-sm">
					<span>
						<i class="bi bi-trash-fill"></i>
					</span>
				</button>
			</td>
		</tr>
	`;
}
