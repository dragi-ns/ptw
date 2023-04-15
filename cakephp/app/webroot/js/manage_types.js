const addTypeButton = $("#add-type-btn");
addTypeButton.click(() => {
	initializeModal(createTypeModal());
});

const typesTable = $("#types-table");
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

$(document).on("submit", "#type-form", (event) => {
	event.preventDefault();
	const form = $(event.currentTarget);

	$.ajax({
		type: "POST",
		url: form.attr("action"),
		data: form.serialize(),
		success: (response) => {
			console.log(response);
			if (response.success) {
				const typeRow = $(`tr[data-id="${response.type.id}"]`);
				if (typeRow.length !== 0) {
					typeRow.replaceWith(createTypeRow(response.type));
					initializeToast(
						`Type "${response.type.id} - ${response.type.name}" was successfully updated!`
					);
				} else {
					const tableTbody = $("#types-table tbody");
					const totalNumberOfTypesSpan = $("#total-number-of-items");
					const currentNumberOfTypes =
						Number(totalNumberOfTypesSpan.text().slice(1, -1)) + 1;
					totalNumberOfTypesSpan.text(`(${currentNumberOfTypes})`);
					if (currentNumberOfTypes === 1) {
						tableTbody.html(createTypeRow(response.type));
					} else {
						tableTbody.prepend(createTypeRow(response.type));
					}
					initializeToast(
						`Type "${response.type.id} - ${response.type.name}" was successfully added!`
					);
				}
				$("#type-modal").modal("hide");
			} else {
				resetErrors(form);
				markErrors(form, response.errors);
			}
		},
		error: (error) => {
			initializeToast(`Type wasn't successfully added/updated!`);
			$("#type-modal").modal("hide");
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
				const typeRow = $(`tr[data-id="${response.type_id}"]`);
				const totalNumberOfTypesSpan = $("#total-number-of-items");
				const currentNumberOfTypes =
					Number(totalNumberOfTypesSpan.text().slice(1, -1)) - 1;
				totalNumberOfTypesSpan.text(`(${currentNumberOfTypes})`);

				if (currentNumberOfTypes === 0) {
					typeRow.replaceWith(createEmptyRow("There are no types."));
				} else {
					typeRow.remove();
				}
				initializeToast(`Type was successfully deleted!`);
			} else {
				initializeToast(`Type wasn't successfully deleted!`);
			}
		},
		error: (error) => {
			initializeToast(`Type wasn't successfully deleted!`);
			console.error(error);
		},
		complete: () => {
			$("#confirm-modal").modal("hide");
		},
	});
});

function createTypeModal(typeData = null) {
	const submitUrl = `/admin/types/${typeData ? "edit/" + typeData.id : "add"}`;

	return `
		<div id="type-modal" class="modal fade">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">${typeData ? "Edit" : "Add"} Type</h5>
						<button type="button" class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="type-form" action="${submitUrl}" method="POST">
							<input type="hidden" name="data[_Token][key]" value="${csrfToken}" autocomplete="off"/>
							<div class="form-group required">
								<label for="name">Name</label>
								<input name="data[Type][name]"
									   class="form-control"
									   placeholder="Enter a name..."
									   autofocus="autofocus"
									   maxlength="32"
									   type="text"
									   id="name"
									   required="required"
									   autofocus="autofocus"
									   value="${typeData ? typeData["name"] : ""}"/>
							   	<div class="invalid-feedback"></div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="type-form">${
							typeData ? "Edit" : "Add"
						}</button>
					</div>
				</div>
			</div>
		</div>
	`;
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
