const addResourceButton = $("#add-resource-btn");
const cardsContainer = $("#cards-container");

addResourceButton.click(() => {
	initializeModal(createResourceModal());
});

cardsContainer.on("click", ".toggle-approved-btn", handleResourceApprove);
cardsContainer.on("click", ".edit-resource-btn", (event) => {
	const resourceData = $(event.currentTarget).closest(".card").data("resource");
	initializeModal(createResourceModal(resourceData));
});
cardsContainer.on("click", ".delete-resource-btn", (event) => {
	const resourceData = $(event.currentTarget).closest(".card").data("resource");
	initializeModal(
		createConfirmationModal({
			submitUrl: `/admin/resources/delete/${resourceData.id}`,
			message: `Are you sure you want to delete the resource "${resourceData.id} - ${resourceData.title}"?`,
		})
	);
});

$(document).on("click", "#add-type-btn", () => {
	initializeModal(createTypeModal());
});
$(document).on("click", "#add-category-btn", () => {
	initializeModal(createCategoryModal());
});

handleAjaxFormSubmit(
	"#resource-form",
	handleResourceSuccess,
	handleResourceError
);
handleAjaxFormSubmit(
	"#confirm-form",
	handleResourceDeleteSuccess,
	handleResourceDeleteError
);

handleAjaxFormSubmit("#type-form", handleTypeSuccess, handleTypeError);
handleAjaxFormSubmit(
	"#category-form",
	handleCategorySuccess,
	handleCategoryError
);

function handleResourceSuccess(form, response) {
	if (response.success) {
		const item = $(`div[data-id="${response.data.id}"]`);
		const newItem = createResourceCard(response.data);
		if (item.length === 0) {
			const container = $("#cards-container");
			const currentNumberOfItems = updateTotalNumberOfItems(1);
			if (currentNumberOfItems === 1) {
				container.html(newItem);
			} else {
				container.prepend(newItem);
			}
			initializeToast(
				`Resource (id: ${response.data.id}, title: ${response.data.title}) successfully added.`
			);
		} else {
			item.replaceWith(newItem);
			initializeToast(
				`Resource (id: ${response.data.id}, title: ${response.data.title}) successfully updated.`
			);
		}
		$("#resource-modal").modal("hide");
	} else {
		resetErrors(form);
		markErrors(form, response.errors);
	}
}

function handleResourceError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#resource-modal").modal("hide");
	console.error(xhr);
}

function handleResourceDeleteSuccess(form, response) {
	if (response.success) {
		const item = $(`div[data-id="${response.data.id}"]`);
		const currentNumberOfItems = updateTotalNumberOfItems(-1);
		if (currentNumberOfItems === 0) {
			item.replaceWith(createEmptyParagraph("There are no resources."));
		} else {
			item.remove();
		}
		initializeToast(`Resource (id: ${response.data.id}) successfully deleted.`);
	} else {
		initializeToast(
			`Resource (id: ${response.data.id}) wasn't successfully deleted.`
		);
	}
	$("#confirm-modal").modal("hide");
}

function handleResourceDeleteError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#confirm-modal").modal("hide");
	console.error(xhr);
}

function handleTypeSuccess(form, response) {
	if (response.success) {
		const typesSelect = $("select#type");
		typesSelect.prepend(
			`<option value="${response.data.id}">${response.data.name}</option>`
		);
		typesSelect.selectpicker("refresh");
		typesSelect.selectpicker("val", [+response.data.id]);
		TYPES.push({ id: +response.data.id, name: response.data.name });
		initializeToast(
			`Type (id: ${response.data.id}, name: ${response.data.name}) was successfully added!`
		);
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

function handleCategorySuccess(form, response) {
	if (response.success) {
		const categoriesSelect = $("select#category");
		categoriesSelect.prepend(
			`<option value="${response.data.id}">${response.data.name}</option>`
		);
		categoriesSelect.selectpicker("refresh");
		categoriesSelect.selectpicker("val", [
			...categoriesSelect.selectpicker("val"),
			+response.data.id,
		]);
		CATEGORIES.push({ id: +response.data.id, name: response.data.name });
		initializeToast(
			`Category (id: ${response.data.id}, name: ${response.data.name}) was successfully added!`
		);
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

function handleResourceApprove(event) {
	const resourceId = $(event.currentTarget).closest(".card").data("id");

	$.ajax({
		type: "POST",
		url: `/admin/resources/approve/${resourceId}`,
		data: `data%5B_Token%5D%5Bkey%5D=${csrfToken}`,
		success: (response) => {
			if (response.success) {
				$(event.currentTarget).html(
					response.data.approved
						? '<i class="bi bi-check2-all"></i>'
						: '<i class="bi bi-x-lg"></i>'
				);
				initializeToast(
					`Resource was successfully ${
						!response.data.approved ? "un" : ""
					}approved!`
				);
			} else {
				initializeToast(`Resource wasn't successfully approved!`);
			}
		},
		error: (xhr) => {
			initializeToast(
				`An error occurred (${xhr.responseJSON.message}). Please try again.`
			);
			console.error(xhr);
		},
	});
}

function createResourceModal(resourceData = null) {
	const submitUrl = `/admin/resources/${
		resourceData ? "edit/" + resourceData.id : "add"
	}`;

	return `
		<div id="resource-modal" class="modal fade">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">${resourceData ? "Edit" : "Add"} Resource</h5>
						<button type="button" class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="resource-form" action="${submitUrl}" method="POST">
							<input type="hidden" name="data[_Token][key]" value="${csrfToken}" autocomplete="off"/>
							${
								resourceData
									? `<input type="hidden" name="data[Resource][id]" value="${resourceData.id}" autocomplete="off"/>`
									: ""
							}
							<div class="form-group required">
								<label for="title">Title</label>
								<input name="data[Resource][title]"
									   class="form-control"
									   placeholder="Enter a title..."
									   autofocus="autofocus"
									   maxlength="64"
									   type="text"
									   id="title"
									   required="required"
									   autofocus="autofocus"
									   value="${resourceData ? resourceData["title"] : ""}"/>
							   	<div class="invalid-feedback"></div>
							</div>

							<div class="form-group required">
								<label for="type">Type</label>
								<select name="data[Resource][type_id]"
										id="type"
										class="selectpicker"
										data-width="100%"
										required="required">
									${TYPES.map((type) => {
										const isSelected =
											resourceData && resourceData.type.id == type.id;
										return `<option value="${type.id}"${
											isSelected ? "selected" : ""
										}>${type.name}</option>`;
									}).join("")}
								 </select>
							   	<div class="invalid-feedback"></div>
							   	<small class="form-text text-muted text-right">
							   		Don't see the type?
							   		<button type="button" id="add-type-btn" class="btn btn-sm btn-link">
										Add Type
							   		</button>
								</small>
							</div>

							<div class="form-group required">
								<input type="hidden" name="data[Resource][Category]" value="">
								<label for="category">Categories</label>
								<select name="data[Resource][Category][]"
										id="category"
										class="selectpicker"
										data-width="100%"
										data-live-search="1"
										multiple="multiple">
									${CATEGORIES.map((category) => {
										const isSelected =
											resourceData &&
											resourceData.categories.find(
												(selectedCategory) => selectedCategory.id == category.id
											);
										return `<option value="${category.id}"${
											isSelected ? "selected" : ""
										}>${category.name}</option>`;
									}).join("")}
								</select>
							   	<div class="invalid-feedback"></div>
								<small class="form-text text-muted text-right">
							   		Don't see the category?
							   		<button type="button" id="add-category-btn" class="btn btn-sm btn-link">
							   			Add Category
							   		</button>
								</small>
							</div>

							<div class="form-group required">
								<label for="category">Description</label>
								<textarea name="data[Resource][description]"
									   class="form-control"
									   placeholder="Enter a description..."
									   maxlength="512"
									   id="description"
									   required="required">${
												resourceData ? resourceData["description"] : ""
											}</textarea>
							   	<div class="invalid-feedback"></div>
							</div>

							<div class="form-group required">
								<label for="url">URL</label>
								<input name="data[Resource][url]"
									   class="form-control"
									   placeholder="Enter a url..."
									   maxlength="64"
									   type="text"
									   id="url"
									   required="required"
									   value="${resourceData ? resourceData["url"] : ""}"/>
							   	<div class="invalid-feedback"></div>
							</div>

							<div class="form-group form-check">
								<input type="hidden" name="data[Resource][approved]" value="0">
								<input type="checkbox" class="form-check-input" id="approved" name="data[Resource][approved]" value="1"${
									resourceData && resourceData.approved ? "checked" : ""
								}>
								<label class="form-check-label" for="approved">Mark the resource as approved</label>
						  	</div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="resource-form">${
							resourceData ? "Edit" : "Add"
						}</button>
					</div>
				</div>
			</div>
		</div>
	`;
}

function createResourceCard(resourceData) {
	return `
		<div data-id="${resourceData.id}"
			 data-resource='${JSON.stringify(resourceData)}'
 			 class="card bg-light">
			<div class="card-header d-flex flex-wrap align-items-center justify-content-between rg-2 cg-2">
				<div class="d-flex rg-2 cg-2">
					<p class="mb-0">#${resourceData.id}</p>
					<p class="mb-0">
						${resourceData.created}
						${
							resourceData.created !== resourceData.modified
								? `(<span class="font-italic">${resourceData.modified}</span>)`
								: ""
						}
					</p>
				</div>
				<div class="d-flex align-items-center cg-1">
					<button class="toggle-approved-btn btn btn-light btn-sm">
						<span>
							${
								resourceData.approved
									? '<i class="bi bi-check2-all"></i>'
									: '<i class="bi bi-x-lg"></i>'
							}
						</span>
					</button>
					<button class="edit-resource-btn btn btn-warning btn-sm">
						<span>
							<i class="bi bi-pencil-fill"></i>
						</span>
					</button>
					<button class="delete-resource-btn btn btn-danger btn-sm">
						<span>
							<i class="bi bi-trash-fill"></i>
						</span>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="d-flex flex-wrap justify-content-start align-items-center cg-1">
					<span class="badge badge-pill badge-primary">${resourceData.type.name}</span>
					${resourceData.categories
						.map(
							(category) =>
								`<span class="badge badge-pill badge-info">${category.name}</span>`
						)
						.join("")}
				</div>
				<h5 class="card-title mt-2">
					<a href="${resourceData.url}" target="_blank">
						${resourceData.title}
					</a>
				</h5>
				<p class="card-text">${resourceData.description}</p>
			</div>
		</div>
	`;
}
