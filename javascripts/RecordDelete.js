

function showConfirmationPopup() {
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
