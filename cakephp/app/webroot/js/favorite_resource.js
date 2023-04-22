$(document).on("click", ".toggle-favorite-btn", function (event) {
	const btn = $(event.currentTarget);
	const resourceId = btn.closest("[data-id]").data("id");

	$.ajax({
		type: "POST",
		url: `/resources/favorite/${resourceId}`,
		data: `data%5B_Token%5D%5Bkey%5D=${csrfToken}`,
		success: (response) => {
			btn.html(
				`<i class="bi bi-heart${
					response.data.isFavorite ? "-fill" : ""
				} text-danger"></i>`
			);
		},
		error: (xhr) => {
			if (xhr.status === 403) {
				window.location.href = "/users/login";
			} else {
				console.error(xhr);
			}
		},
	});
});
