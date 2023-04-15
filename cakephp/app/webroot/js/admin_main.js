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
