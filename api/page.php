<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<section id="end-page" v-if="activeSection === 'endPage'" class="active">
    <div class="container top flex">
      <div @click="toggleLang()" id="lang-btn">
        <img :src="currentHomePage.lang_btn_img" alt="">
      </div>
      <img id="logo" src="src/assets/img/logo.png" alt="">
      <h2 v-html="currentHomePage.thank_text"></h2>
    </div>


    <div class="container flex">
      <h2 class="white">{{ currentHomePage.promo_code }}</h2>
      <div id="copy-area">
        <div id="promocode"><span id="code">{{ promoteCode }}</span></div>
        <img id="copy-btn" src="src/assets/img/copy-btn.png" alt="" @click="copyPromoCode" />
      </div>
    </div>

    <div class="container" id="end-page-desc">
      <p class="white">{{ currentHomePage.valid_date }}<br></p>
      <div class="white tc" v-html="currentHomePage.terms_conditions"></div>

    </div>

    <div class="container">
      <a href="https://www.esprit.hk/zh" target="_blank"><button id="shop-btn" type="button">{{
        currentHomePage.shop }}</button></a>
    </div>
  </section>
</body>
</html>