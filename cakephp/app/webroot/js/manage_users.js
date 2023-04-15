const addUserButton = $("#add-user-btn");
addUserButton.click(() => {
	initializeModal(createUserModal());
});

const usersTable = $("#users-table");
usersTable.on("click", ".toggle-approved-btn", (event) => {
	const userId = $(event.currentTarget).closest("tr").data("user-id");

	$.ajax({
		type: "POST",
		url: `/admin/users/approve/${userId}`,
		data: `data%5B_Token%5D%5Bkey%5D=${csrfToken}`,
		success: (response) => {
			if (response.success) {
				const userRow = $(`tr[data-user-id="${response.user_id}"]`);
				userRow.find("td:nth-child(6)").text(response.approved);
				$(event.currentTarget).html(
					response.approved
						? '<i class="bi bi-check2-all"></i>'
						: '<i class="bi bi-x-lg"></i>'
				);
				initializeToast(
					`User was successfully ${!response.approved ? "un" : ""}approved!`
				);
			} else {
				initializeToast(`User wasn't successfully approved!`);
			}
		},
		error: (error) => {
			initializeToast(`User wasn't successfully approved!`);
			console.error(error);
		},
	});
});
usersTable.on("click", ".edit-user-btn", (event) => {
	const userData = $(event.currentTarget).closest("tr").data("user");
	initializeModal(createUserModal(userData));
});
usersTable.on("click", ".delete-user-btn", (event) => {
	const userData = $(event.currentTarget).closest("tr").data("user");
	initializeModal(createDeleteModal(userData));
});

$(document).on("submit", "#user-form", (event) => {
	event.preventDefault();
	const form = $(event.currentTarget);

	$.ajax({
		type: "POST",
		url: form.attr("action"),
		data: form.serialize(),
		success: (response) => {
			if (response.success) {
				const userRow = $(`tr[data-user-id="${response.user.id}"]`);
				if (userRow.length !== 0) {
					userRow.replaceWith(createUserRow(response.user));
					initializeToast(
						`User "${response.user.id} - ${response.user.username}" was successfully updated!`
					);
				} else {
					$("#users-table tbody").prepend(createUserRow(response.user));
					const totalNumberOfUsers = $("#total-number-of-users");
					totalNumberOfUsers.text(
						`(${Number(totalNumberOfUsers.text().slice(1, -1)) + 1})`
					);
					initializeToast(
						`User "${response.user.id} - ${response.user.username}" was successfully added!`
					);
				}
				$("#user-modal").modal("hide");
			} else {
				resetErrors(form);
				markErrors(form, response.errors);
			}
		},
		error: (error) => {
			initializeToast(`User wasn't successfully added/updated!`);
			$("#user-modal").modal("hide");
			console.error(error);
		},
	});
});
$(document).on("submit", "#delete-form", (event) => {
	event.preventDefault();
	const form = $(event.currentTarget);

	$.ajax({
		type: "POST",
		url: form.attr("action"),
		data: form.serialize(),
		success: (response) => {
			if (response.success) {
				const userRow = $(`tr[data-user-id="${response.user_id}"]`);
				userRow.remove();
				const totalNumberOfUsers = $("#total-number-of-users");
				totalNumberOfUsers.text(
					`(${Number(totalNumberOfUsers.text().slice(1, -1)) - 1})`
				);
				initializeToast(`User was successfully deleted!`);
			} else {
				initializeToast(`User wasn't successfully deleted!`);
			}
		},
		error: (error) => {
			initializeToast(`User wasn't successfully deleted!`);
			console.error(error);
		},
		complete: () => {
			$("#delete-modal").modal("hide");
		},
	});
});

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
									   autofocus="autofocus"
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
								<select name="data[User][role]" id="role" class="form-control">
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

function createDeleteModal(userData) {
	const submitUrl = `/admin/users/delete/${userData.id}`;

	return `
		<div id="delete-modal" class="modal fade">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete confirmation</h5>
						<button type="button" class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="delete-form" action="${submitUrl}" method="POST">
							<input type="hidden" name="data[_Token][key]" value="${csrfToken}" autocomplete="off"/>
						</form>
						<p>Are you sure you want to delete the user "${userData.id} - ${userData.username}"?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-danger" form="delete-form">Delete</button>
					</div>
				</div>
			</div>
		</div>
	`;
}

function createUserRow(user) {
	return `
		<tr data-user-id="${user.id}" data-user='${JSON.stringify(user)}'>
			<td class="text-center">${user.id}</td>
			<td>${user.username}</td>
			<td><pre class="m-0">${user.email}</pre></td>
			<td class="text-center">${user.role}</td>
			<td class="text-center">${user.created}</td>
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
		const field = form.find(`[name="data[User][${fieldName}]"]`);
		field.addClass("form-error");
		field.closest(".form-group").addClass("error");
		field.siblings(".invalid-feedback").text(fieldErrors[0]);
	}
}
