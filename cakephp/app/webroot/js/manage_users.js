const addUserButton = $("#add-user-btn");
const usersTable = $("#users-table");

addUserButton.click(() => initializeModal(createUserModal()));

usersTable.on("click", ".toggle-approved-btn", handleUserApprove);
usersTable.on("click", ".edit-user-btn", (event) => {
	const userData = $(event.currentTarget).closest("tr").data("user");
	initializeModal(createUserModal(userData));
});
usersTable.on("click", ".delete-user-btn", (event) => {
	const userData = $(event.currentTarget).closest("tr").data("user");
	initializeModal(
		createConfirmationModal({
			submitUrl: `/admin/users/delete/${userData.id}`,
			message: `Are you sure you want to delete the user "${userData.id} - ${userData.username}"?`,
		})
	);
});

handleAjaxFormSubmit("#user-form", handleUserSuccess, handleUserError);
handleAjaxFormSubmit(
	"#confirm-form",
	handleUserDeleteSuccess,
	handleUserDeleteError
);

function handleUserSuccess(form, response) {
	if (response.success) {
		const item = $(`tr[data-id="${response.data.id}"]`);
		const newItem = createUserRow(response.data);
		if (item.length === 0) {
			const container = $("#users-table tbody");
			const currentNumberOfItems = updateTotalNumberOfItems(1);
			if (currentNumberOfItems === 1) {
				container.html(newItem);
			} else {
				container.prepend(newItem);
			}
			initializeToast(
				`User (id: ${response.data.id}, username: ${response.data.username}) successfully added.`
			);
		} else {
			item.replaceWith(newItem);
			initializeToast(
				`User (id: ${response.data.id}, username: ${response.data.username}) successfully updated.`
			);
		}
		$("#user-modal").modal("hide");
	} else {
		resetErrors(form);
		markErrors(form, response.errors);
	}
}

function handleUserError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#user-modal").modal("hide");
	console.error(xhr.responseJSON.message);
}

function handleUserDeleteSuccess(form, response) {
	if (response.success) {
		const item = $(`tr[data-id="${response.data.id}"]`);
		const currentNumberOfItems = updateTotalNumberOfItems(-1);
		if (currentNumberOfItems === 0) {
			item.replaceWith(createEmptyRow("There are no users."));
		} else {
			item.remove();
		}
		initializeToast(`User (id: ${response.data.id}) successfully deleted.`);
	} else {
		initializeToast(
			`User (id: ${response.data.id}) wasn't successfully deleted.`
		);
	}
	$("#confirm-modal").modal("hide");
}

function handleUserDeleteError(form, xhr) {
	initializeToast(
		`An error occurred (${xhr.responseJSON.message}). Please try again.`
	);
	$("#confirm-modal").modal("hide");
	console.error(xhr);
}

function handleUserApprove(event) {
	const userId = $(event.currentTarget).closest("tr").data("id");

	$.ajax({
		type: "POST",
		url: `/admin/users/approve/${userId}`,
		data: `data%5B_Token%5D%5Bkey%5D=${csrfToken}`,
		success: (response) => {
			if (response.success) {
				const item = $(`tr[data-id="${response.data.id}"]`);
				item.find("td:nth-child(6)").text(response.data.approved);
				$(event.currentTarget).html(
					response.data.approved
						? '<i class="bi bi-check2-all"></i>'
						: '<i class="bi bi-x-lg"></i>'
				);
				initializeToast(
					`User (id: ${response.data.id}) was successfully ${
						!response.data.approved ? "un" : ""
					}approved!`
				);
			} else {
				initializeToast(
					`User (id: ${response.data.id}) wasn't successfully approved!`
				);
			}
		},
		error: (xhr) => {
			initializeToast("An error occurred. Please try again.");
			console.error(xhr);
		},
	});
}

function createUserModal(userData = null) {
	const submitUrl = `/admin/users/${userData ? "edit/" + userData.id : "add"}`;

	return `
		<div id="user-modal" class="modal fade">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">${userData ? "Edit" : "Add"} User</h5>
						<button type="button" class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="user-form" action="${submitUrl}" method="POST">
							<input type="hidden" name="data[_Token][key]" value="${csrfToken}" autocomplete="off"/>
							<div class="form-group required">
								<label for="username">Username</label>
								<input name="data[User][username]"
									   class="form-control"
									   placeholder="Enter a username..."
									   maxlength="32"
									   type="text"
									   id="username"
									   required="required"
									   autofocus="autofocus"
									   value="${userData ? userData["username"] : ""}"/>
							   	<div class="invalid-feedback"></div>
							</div>

							<div class="form-group required">
								<label for="email">Email</label>
								<input name="data[User][email]"
								       class="form-control"
								       placeholder="Enter an email..."
								       maxlength="255"
								       type="email"
								       id="email"
								       required="required"
									   value="${userData ? userData["email"] : ""}"/>
							   	<div class="invalid-feedback"></div>
							</div>

							<div class="form-group required">
								<label for="role">Role</label>
								<select name="data[User][role]"
										id="role"
										class="selectpicker"
										data-width="100%"
										required="required">
									<option value="user"${
										userData && userData["role"] === "user" ? " selected" : ""
									}>User</option>
									<option value="admin"${
										userData && userData["role"] === "admin" ? " selected" : ""
									}>Admin</option>
								</select>
							   	<div class="invalid-feedback"></div>
							</div>

							<div class="form-group${!userData ? " required" : ""}">
								<label for="password">${userData ? "New " : ""}Password</label>
								<input name="data[User][${userData ? "new_password" : "password"}]"
									   class="form-control"
									   placeholder="Enter a password..."
									   type="password"
									   id="password"
									   ${!userData ? "required" : ""}/>
								<div class="invalid-feedback"></div>
							</div>

							<div class="form-group${!userData ? " required" : ""}">
								<label for="password_confirm">Confirm ${userData ? "New " : ""} Password</label>
								<input name="data[User][${
									userData ? "new_password_confirm" : "password_confirm"
								}]"
									   class="form-control"
									   placeholder="Confirm the password..."
									   type="password"
									   id="password_confirm"
									   ${!userData ? "required" : ""}/>
								<div class="invalid-feedback"></div>
							</div>

							<div class="form-group form-check">
								<input type="hidden" name="data[User][approved]" value="0">
								<input type="checkbox" class="form-check-input" id="approved" name="data[User][approved]" value="1"${
									userData && userData.approved ? "checked" : ""
								}>
								<label class="form-check-label" for="approved">Mark the user as approved</label>
						  	</div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" form="user-form">${
							userData ? "Edit" : "Add"
						}</button>
					</div>
				</div>
			</div>
		</div>
	`;
}

function createUserRow(user) {
	return `
		<tr data-id="${user.id}" data-user='${JSON.stringify(user)}'>
			<td class="text-center">${user.id}</td>
			<td>${user.username}</td>
			<td><pre class="m-0">${user.email}</pre></td>
			<td class="text-center">${user.role}</td>
			<td class="text-center">
				${user.created}
				${
					user.created !== user.modified
						? `<br>(<span class="font-italic">${user.modified}</span>)`
						: ""
				}
			</td>
			<td class="text-center">${user.approved}</td>
			<td class="text-center d-flex justify-content-center align-items-center cg-1">
				<button class="toggle-approved-btn btn btn-light btn-sm">
					<span>
					${
						user.approved
							? '<i class="bi bi-check2-all"></i>'
							: '<i class="bi bi-x-lg"></i>'
					}
					</span>
				</button>
				<button class="edit-user-btn btn btn-warning btn-sm">
					<span>
						<i class="bi bi-pencil-fill"></i>
					</span>
				</button>
				<button class="delete-user-btn btn btn-danger btn-sm">
					<span>
						<i class="bi bi-trash-fill"></i>
					</span>
				</button>
			</td>
		</tr>
	`;
}
