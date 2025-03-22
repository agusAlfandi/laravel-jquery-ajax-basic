$.ajax({
    url: "/blog",
    type: "GET",
    dataType: "json",
    success: function (response) {
        if ((obj = JSON.parse(JSON.stringify(response)))) {
            obj.forEach((element) => {
                $("#data-table-body").append(`
                <tr>
                    <td>${element.id}</td>
                    <td>${element.title}</td>
                    <td>
                        <button class="btn btn-primary edit-btn" data-id="${element.id}" data-title="${element.title}" data-body="${element.body}">Edit</button>
                    </td>
                </tr>
            `);
            });
        } else {
            console.error("Invalid response format:", response);
        }
    },
    error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
    },
});
