

$("#copylink-actionbtn").on('click', function(){
    let u = $(this).data('url')
    // Create a temporary input element
    var tempInput = $("<input>");
    $("body").append(tempInput);

    // Set its value to the URL
    tempInput.val(u);

    // Select the text in the input
    tempInput.select();

    // Copy the text to the clipboard
    document.execCommand("copy");

    // Remove the temporary input
    tempInput.remove();


    $(this).text('Copied');
    setTimeout( () => {
        $(this).text('Copy Link');
    }, 2000)
})
