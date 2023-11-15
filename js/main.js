// ヘッダーを読み込み、挿入する
fetch('./include/header.html')
  .then(response => {
    if (!response.ok) {
      throw new Error('ヘッダーファイルを読み込めませんでした。');
    }
    return response.text();
  })
  .then(headerHTML => {
    const header = document.querySelector('#header');
    header.insertAdjacentHTML('afterbegin', headerHTML);

    // 画像のパスを設定
    const imgPath = './img/';
    const logoImg = header.querySelector('.logo');
    if (logoImg) {
      logoImg.src = imgPath + 'mist_logo.png';
    }
  })
  .catch(error => {
    console.error(error);
  });

// エントリーエリアを読み込み、挿入する
fetch('./include/entry-space.html')
  .then(response => {
    if (!response.ok) {
      throw new Error('エントリースペースを読み込めませんでした。');
    }
    return response.text();
  })
  .then(entryHTML => {
    // 画像のパスを設定
    const imgPath = './img/';
    const entryImg = document.querySelector('#entry-space .more-img');

    if (entryImg) {
      entryImg.src = imgPath + 'link1.png';
    }

    const entrySpace = document.querySelector('#entry-space');
    if (entrySpace) {
      entrySpace.insertAdjacentHTML('afterbegin', entryHTML);
    } else {
      console.error('エントリースペースの要素が見つかりません。');
    }
  })
  .catch(error => {
    console.error(error);
  });

// フッターを読み込み、挿入する
fetch('./include/footer.html')
  .then(response => {
    if (!response.ok) {
      throw new Error('フッターファイルを読み込めませんでした。');
    }
    return response.text();
  })
  .then(footerHTML => {
    const footer = document.querySelector('#footer');
    footer.insertAdjacentHTML('afterbegin', footerHTML);
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
