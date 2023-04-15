function createToast(message) {
	return `
		<div class="toast hide" data-delay="2000">
			<div class="toast-header">
				<strong class="mr-auto">Info</strong>
				<button type="button" class="ml-2 mb-1 close" data-dismiss="toast">
					<span>&times;</span>
				</button>
			</div>
			<div class="toast-body">${message}</div>
		</div>
	`;
}

function initializeToast(message) {
	const toast = $(createToast(message));
	$("#toasts-container").append(toast);
	toast.toast("show");
	toast.on("hidden.bs.toast", () => {
		toast.remove();
	});
}

function initializeModal(modal) {
	modal = $(modal);
	$("body").append(modal);
	modal.modal("show");
	modal.on("shown.bs.modal", () => {
		$("[autofocus]").trigger("focus");
	});
	modal.on("hidden.bs.modal", () => {
		modal.remove();
	});
}
