function postAjax(url, data, auth = true) {
    let headers = {};
    if (auth) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log(csrfToken);
        if (!csrfToken) {
            console.error('CSRF token not found');
        }
        headers = {
            "X-CSRF-TOKEN": csrfToken,
        };
    }
    return new Promise(resolve => {
        $.ajax({
            headers: headers,
            url: url,
            method: "POST",
            dataType: "json",
            data: data,
            success: function (response) {
                resolve(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });
}

function getAjax(url, auth = true) {
    return new Promise( resolve => {
        let headers = {}
        if(auth){
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            headers = {
                "X-CSRF-TOKEN": csrfToken,
            }
        }
        $.ajax({
            headers: headers,
            url: url,
            method: "GET",
            dataType: "json",
            success: function (response) {
                resolve(response)

            },
        });
    })
}
function slugify(text) {
    return text
        .toString() // Convert to string
        .normalize('NFD') // Normalize to decompose diacritical marks
        .replace(/[\u0300-\u036f]/g, '') // Remove diacritical marks
        .toLowerCase() // Convert to lowercase
        .trim() // Remove whitespace from both sides
        .replace(/[^a-z0-9 -]/g, '') // Remove invalid characters
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(/-+/g, '-'); // Replace multiple - with single -
}

