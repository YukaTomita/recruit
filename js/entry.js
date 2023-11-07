// アコーディオンメニューの動作を制御するJavaScript
document.addEventListener("DOMContentLoaded", function () {
  var accordionHeaders = document.querySelectorAll(".accordion-header");
  var firstAccordionItem = document.querySelector(".accordion-item");

  // 最初のアコーディオンを開く
  firstAccordionItem.classList.add("active");

  accordionHeaders.forEach(function (header) {
    header.addEventListener("click", function () {
      var accordionItem = this.parentElement;

      if (!accordionItem.classList.contains("active")) {
        // クリックされたアコーディオン以外のコンテンツを閉じる
        accordionHeaders.forEach(function (otherHeader) {
          var otherAccordionItem = otherHeader.parentElement;
          if (otherHeader !== header && otherAccordionItem.classList.contains("active")) {
            otherAccordionItem.classList.remove("active");
          }
        });
      }

      accordionItem.classList.toggle("active");
    });
  });
});

//レベルの〇をつける
function toggleCheckmark(element) {
    var isSelected = element.classList.contains("selected");

    // すでに選択されている場合は「〇」を削除する
    if (isSelected) {
        element.innerHTML = "";
        element.classList.remove("selected");
    } else {
        var selectedCells = document.getElementsByClassName("selected");

        // 同じ行の他のセルが選択されている場合は選択を解除する
        for (var i = 0; i < selectedCells.length; i++) {
            var selectedCell = selectedCells[i];
            if (selectedCell.parentNode === element.parentNode) {
                selectedCell.innerHTML = "";
                selectedCell.classList.remove("selected");
            }
        }

        // 選択されたセルに〇を追加する
        element.innerHTML = "〇";
        element.classList.add("selected");
    }
}


// radio button on/off
const radioButtons = document.querySelectorAll('input[type="radio"]');

const clearRadioButton = (radioButton) => {
  setTimeout(func =()=>{
    radioButton.checked = false;
  },100)
}

radioButtons.forEach(radioButton => {
  let queryStr = 'label[for="' + radioButton.id + '"]'
  let label = document.querySelector(queryStr)

  radioButton.addEventListener("mouseup", func=()=>{
    if(radioButton.checked){
      clearRadioButton(radioButton)
    }
  });

  if(label){
    label.addEventListener("mouseup", func=()=>{
      if(radioButton.checked){
        clearRadioButton(radioButton)
      }
    });
  }
});


selectedFilesContainer.addEventListener('click', (event) => {
  if (event.target.classList.contains('delete-button')) {
    const fileItem = event.target.parentNode;
    selectedFilesContainer.removeChild(fileItem);
  }
});


//iframeデータ内容の取得
function sendSelectedData() {
    // 選択されたセル情報を配列に格納
    var selectedCells = [];
    var cells = document.querySelectorAll('td[data-language][data-level]');
    cells.forEach(function (cell) {
        var language = cell.getAttribute('data-language');
        var level = cell.getAttribute('data-level');
        var experience = cell.nextElementSibling.querySelector('input[type="radio"]:checked');
        if (experience) {
            experience = experience.value;
            selectedCells.push({ language, level, experience });
        }
    });

    // 選択されたセル情報を JSON 形式に変換
    var selectedCellsJSON = JSON.stringify(selectedCells);

    // フォームを作成し、データを送信
    var form = document.createElement('form');
    form.method = 'post';
    form.action = 'confirm.php';

    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'selectedCells';
    input.value = selectedCellsJSON;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

// 送信ボタンがクリックされたときにデータを送信
var submitButton = document.getElementById('submit-button');
submitButton.addEventListener('click', sendSelectedData);



