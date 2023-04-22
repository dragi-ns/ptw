const resourceContainer = $("#resource");

$(document).on("click", "#next-resource-btn", () => {
	resourceContainer.html(createMessage("Loading next resource..."));
	$.get(location.href, (response) => {
		if (response.data.length === 0) {
			resourceContainer.html(
				createMessage("There are no more random resources.")
			);
		} else {
			resourceContainer.html(createResourceCard(response.data));
		}
	});
});

function createResourceCard(data) {
	return `
	<div class="resource d-flex flex-column justify-content-md-center h-md-100 pb-lg-5">
		<div class="d-flex flex-wrap justify-content-start align-items-center rg-1 cg-1 mb-2">
			<span class="badge badge-pill badge-primary">${data.Type.name}</span>
			${data.Category.map(
				(category) =>
					`<span class="badge badge-pill badge-info">${category.name}</span>`
			).join("")}
			</div>
		<h2>
			<a href="${data.Resource.url}" target="_blank">
				${data.Resource.title}
			</a>
		</h2>
		<p class="mb-4">${data.Resource.description}</p>
		<div class="controls d-flex justify-content-end rg-2 cg-2">
			<button class="toggle-favorite-btn btn btn-light btn-lg">
				<i class="bi bi-heart text-danger"></i>
			</button>
			<button id="next-resource-btn" class="btn btn-light btn-lg">
				<i class="bi bi-shuffle"></i>
			</button>
		</div>
	</div>
	`;
}

function createMessage(message) {
	return `
		<div class="d-flex justify-content-center align-items-center h-md-100">
			<p class="text-center" style="font-size: 1.5rem;">${message}</p>
		</div>
	`;
}
