

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
document.addEventListener("DOMContentLoaded", function() {
    let button = document.getElementById('submitButton');
    //acquire the url of the present page
    let currentURL = window.location.href;

    let verifyEditMode = currentURL.includes('edit_id=')
    if (verifyEditMode) {
        function triggerButtonClick() {
            button.click();
        }
        //make function globally available in DOM
        window.triggerButtonClick = triggerButtonClick

    }else {
        //run when there is not edited id
        document.getElementsByName('edit_id')[0].disabled = true;
    }
    //delay automatic trigger of the edit button so edited data can be available on form fields this after page is reloaded
    setTimeout(triggerButtonClick, 50)

});
//reload page to initial url when user click close or cancel
function goBack() {
    window.location.href = "http://savegeerp.test/egg_RearingHouses.php";
}
// function retrievePage() {
//     window.goBack()
// }

//search bar for table
document.getElementById('searchInput').addEventListener('input', function() {
    let searchValue = this.value.toLowerCase();
    let rows = document.querySelectorAll('#selected tbody tr');

//iterate over table rows to for items
    rows.forEach(function(row) {
        let cells = row.getElementsByTagName('td');
        let visible = false;

        for (let i = 0; i < cells.length; i++) {
            let cellValue = cells[i].textContent.toLowerCase();

            if (cellValue.includes(searchValue)) {
                visible = true;
                break;
            }
        }

        if (visible) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
// let tag_id = document.getElementById('tag');
