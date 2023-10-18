function toggleRanking() {
    var rankingSection = document.getElementById("rankingSection");
    var rankingButton = document.getElementById("rankingButton");
    if (rankingSection.style.display === "none") {
        rankingSection.style.display = "block";
        rankingButton.textContent = "× 閉じる";
        rankingButton.style.backgroundColor = "#f0f0f0";
    } else {
        rankingSection.style.display = "none";
        rankingButton.textContent = "ランキングに参加する";
        rankingButton.style.backgroundColor = "#8B2022";
    }
    return false; 
}   


// ポップアップを表示する関数
function showPopup(popupClass) {
    var popup = document.querySelector("." + popupClass);
    if (popup) {
        popup.style.display = "block";
    }
}

// ポップアップを非表示にする関数
function hidePopup(popupClass) {
    var popup = document.querySelector("." + popupClass);
    if (popup) {
        popup.style.display = "none";
    }
}
function togglePopup(popupClass) {
    var popup = document.querySelector("." + popupClass);
    if (popup) {
        if (popup.style.display === "block") {
            popup.style.display = "none";
        } else {
            popup.style.display = "block";
        }
    }
}

