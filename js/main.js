// HEADER JS //
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

// ENTRY JS //
// エントリーエリアを読み込み、挿入する
fetch('/recruit/include/entry-space.html')
  .then(response => {
    if (!response.ok) {
      throw new Error('エントリースペースを読み込めませんでした。');
    }
    return response.text();
  })
  .then(headerHTML => {
    const header = document.querySelector('#entry-space');
    header.insertAdjacentHTML('afterbegin', headerHTML);
  })
  .catch(error => {
    console.error(error);
  });

// FOOTER JS //
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

    // コンテンツが少ないときに最下部に固定するためのコードを追加
    const pageContent = document.querySelector('#content'); // ページのコンテンツ要素に対応するセレクタを使用
    const windowHeight = window.innerHeight;
    const contentHeight = pageContent.offsetHeight;

    if (contentHeight < windowHeight) {
      // コンテンツがウィンドウの高さよりも小さい場合、フッターを最下部に固定
      footer.style.position = 'fixed';
      footer.style.bottom = '0';
      footer.style.left = '0';
      footer.style.right = '0';
    }
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
