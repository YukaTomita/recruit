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



//入力項目を取得
document.getElementById('entryButton').addEventListener('click', function() {
  // お名前（漢字）
  const lastNameKanji = document.querySelector('input[name="last-name"][placeholder="みすと"]').value;
  const firstNameKanji = document.querySelector('input[name="first-name"][placeholder="太郎"]').value;

  // フリガナ（全角カタカナ）
  const lastNameKana = document.querySelector('input[name="klast-name"][placeholder="ミスト"]').value;
  const firstNameKana = document.querySelector('input[name="kfirst-name"][placeholder="タロウ"]').value;

  // 経験年数
  const experience = document.querySelector('input[name="experience"]:checked').value;

  // メールアドレス
  const email = document.querySelector('input[name="email"]').value;

  // 再入力（メールアドレス）再入力項目は取得不要
  // const confirmEmail = document.querySelector('input[name="confirm-email"]').value;

  // 希望面談形式
  const interviewType = document.querySelector('input[name="interview"]:checked').value;

  // 希望種別
  const role = document.querySelector('input[name="role"]:checked').value;

  // スキルシートのiframe内スクリプトからスキルデータを取得
  const parentframe = document.getElementById('parentframe');
  const skillData = parentframe.contentWindow.getSkillData(); // 仮の関数名

  // 備考欄
  const notes = document.querySelector('textarea').value;

  // フォームデータをオブジェクトにまとめる
  const formData = {
    lastNameKanji,
    firstNameKanji,
    lastNameKana,
    firstNameKana,
    experience,
    email,
    // confirmEmail, (Mail再入力)
    interviewType,
    role,
    skillData,
    notes
  };

  // フォームデータをJSON形式に変換
  const jsonData = JSON.stringify(formData);

  // confirm.phpに遷移
  window.location.href = "../entry/confirm.php?data=" + encodeURIComponent(jsonData);
});
