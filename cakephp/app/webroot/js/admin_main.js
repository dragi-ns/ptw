function createConfirmationModal(options) {
	return `
		<div id="confirm-modal" class="modal fade">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Confirmation</h5>
						<button type="button" class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="confirm-form" action="${options.submitUrl}" method="POST">
							<input type="hidden" name="data[_Token][key]" value="${csrfToken}" autocomplete="off"/>
						</form>
						<p>${options.message}</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-danger" form="confirm-form">Confirm</button>
					</div>
				</div>
			</div>
		</div>
	`;
}

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

function createCategoryModal(categoryData = null) {
	const submitUrl = `/admin/categories/${
		categoryData ? "edit/" + categoryData.id : "add"
	}`;

	return `
		<div id="category-modal" class="modal fade">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">${categoryData ? "Edit" : "Add"} Category</h5>
						<button type="button" class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="category-form" action="${submitUrl}" method="POST">
							<input type="hidden" name="data[_Token][key]" value="${csrfToken}" autocomplete="off"/>
							<div class="form-group required">
								<label for="name">Name</label>
								<input name="data[Category][name]"
									   class="form-control"
									   placeholder="Enter a name..."
									   autofocus="autofocus"
									   maxlength="32"
									   type="text"
									   id="name"
									   required="required"
									   autofocus="autofocus"
									   value="${categoryData ? categoryData["name"] : ""}"/>
							   	<div class="invalid-feedback"></div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="category-form">${
							categoryData ? "Edit" : "Add"
						}</button>
					</div>
				</div>
			</div>
		</div>
	`;
}

function createEmptyRow(message) {
	return `
		<tr>
			<td colspan="100%" class="text-center">${message}</td>
		</tr>
	`;
}

function resetErrors(form) {
	form.find(".form-group.error").each((_, formGroup) => {
		formGroup = $(formGroup);
		formGroup.removeClass("error");
		formGroup.find("input").removeClass("form-error");
		formGroup.find(".invalid-feedback").text("");
	});
}

function markErrors(form, errors) {
	for (const [fieldName, fieldErrors] of Object.entries(errors)) {
		const field = form.find(`[name$="[${fieldName}]"]`);
		field.addClass("form-error");
		field.closest(".form-group").addClass("error");
		field.siblings(".invalid-feedback").text(fieldErrors[0]);
	}
}
