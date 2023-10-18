const includeHeader = new XMLHttpRequest();
includeHeader.open("GET", "../../include/header.html", true);
includeHeader.onreadystatechange = function () {
  if (includeHeader.readyState === 4 && includeHeader.status === 200) {
    const headerHTML = includeHeader.responseText;
    const header = document.querySelector("#header");
    header.insertAdjacentHTML("afterbegin", headerHTML);
  }
};
includeHeader.send();

$(document).on('click', '.burger-btn', function () {
  $('.bar').toggleClass('cross');
  $('.header-nav').toggleClass('open');
  $('.burger-musk').fadeToggle(300);
  $('body').toggleClass('noscroll');
});


const includeFooter = new XMLHttpRequest();
includeFooter.open("GET", "../../include/footer.html", true);
includeFooter.onreadystatechange = function () {
  if (includeFooter.readyState === 4 && includeFooter.status === 200) {
    const footerHTML = includeFooter.responseText;
    const footer = document.querySelector("#footer"); // フッターを表示したい要素を指定
    footer.insertAdjacentHTML("beforeend", footerHTML); // フッターを要素の末尾に挿入
  }
};
includeFooter.send();