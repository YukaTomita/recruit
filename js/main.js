// ヘッダーを読み込み、挿入する
fetch('/recruit/include/header.html')
  .then(response => {
    if (!response.ok) {
      throw new Error('ヘッダーファイルを読み込めませんでした。');
    }
    return response.text();
  })
  .then(headerHTML => {
    const header = document.querySelector('#header');
    header.insertAdjacentHTML('afterbegin', headerHTML);
  })
  .catch(error => {
    console.error(error);
  });

// フッターを読み込み、挿入する
fetch('/recruit/include/footer.html')
  .then(response => {
    if (!response.ok) {
      throw new Error('フッターファイルを読み込めませんでした。');
    }
    return response.text();
  })
  .then(footerHTML => {
    const footer = document.querySelector('#footer');
    footer.insertAdjacentHTML('beforeend', footerHTML);
  })
  .catch(error => {
    console.error(error);
  });

// メニューのクリックイベントを処理する（jQueryを使用）
$(document).on('click', '.burger-btn', function () {
  $('.bar').toggleClass('cross');
  $('.header-nav').toggleClass('open');
  $('.burger-musk').fadeToggle(300);
  $('body').toggleClass('noscroll');
});
