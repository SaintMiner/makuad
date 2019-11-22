function deleteConfirm(index) {
    var element = document.getElementById("confirm");
    element.classList.add("is-active");
    var apply = document.getElementById("apply");
    apply.setAttribute("onClick", "javascript: deleteTrue(" + index + ");");
}

function deleteTrue(index) {
    // alert(index);
    window.location.href = window.location.href + "?delete=" + index;
}

function deleteFalse() {
    var element = document.getElementById("confirm");
    element.classList.remove("is-active");
}

function getDetails(adID) {
    window.location.href = "/adInfo?id=" + adID;
    // console.log(adID);
}

function openProfile(userID) {
    window.location.href = "profile?user="+userID
}

function banUserConfirm(userID, page, tab) {
    var element = document.getElementById("banUserConfirm");
    element.classList.add("is-active");
    var apply = document.getElementById("applyUser");
    // var href = "adminpanel?banUser=" + userID + "&page="+ page +"&tab="+ tab;
    var href = "'adminpanel?banUser="+userID+"&page="+page+"&tab"+tab+"'";
    console.log(href);
    apply.setAttribute("onClick", "javascript: banUserTrue("+href+")");
}

function banUserFalse() {
    var element = document.getElementById("banUserConfirm");
    element.classList.remove("is-active");
}

function banUserTrue(href) {
    console.log(href);
    window.location.href = href;
}