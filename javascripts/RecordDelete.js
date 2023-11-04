

function showConfirmationPopup() {
    console.log('this button is working');
    document.getElementById("confirmationPopup").style.display = "block";
}

function hideConfirmationPopup() {
    document.getElementById("confirmationPopup").style.display = "none";
}

function deleteRecord() {
    document.getElementById("confirmationPopup").style.display = "none";
    // Proceed with form submission for delete
    document.forms[0].submit();
}

// let button = document.getElementById('submitButton');
// button.disabled = true;
// setTimeout(function () {
//     button.disabled = false;
// },100)
//
//
// function triggerButtonClick() {
//     let linkHtml = document.getElementById('editLink');
//     console.log(linkHtml.href);
//     var url = new URL(linkHtml.href);
//     var params = new URLSearchParams(url.search);
//     var paramValue = params.get("edit_id");
//     console.log(paramValue);
//     console.log(typeof (paramValue));
//     if(linkHtml.href.includes(paramValue)){
//         button.click();
//         console.log('complete url')
//     }
//
// }
//


document.addEventListener("DOMContentLoaded", function() {
    let button = document.getElementById('submitButton');
    var currentURL = window.location.href;
    console.log(currentURL);
    console.log(currentURL.slice(-2))
   let id = Number(currentURL.slice(-2))
    console.log(id)
    // console.log(typeof (id))
    // console.log(Number.isInteger(id));
    if (Number.isInteger(id)) {
        function triggerButtonClick() {
            console.log("Condition is true. This code will execute.");
            button.click();
        }
        window.triggerButtonClick = triggerButtonClick

    }
    setTimeout(triggerButtonClick, 50)
});
function goBack() {
    window.location.href = "http://savegeerp.test/egg_RearingHouses.php";
}

// console.log(id)
// function triggerButtonClick() {
// }
// setTimeout(triggerButtonClick, 50);


