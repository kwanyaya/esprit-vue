<script>
import axios from 'axios';
import QrcodeVue from 'qrcode.vue'
import { vShow } from 'vue';


export default {
  data() {
    return {
      display: true,
      activeSection: 'submitForm',
      promoteCode: '',
      qrCodeData: "http://192.168.1.175:5174/page?section=endPage",
      email: "",
      gender: "",
      showErrorMessage: false,
      isQrCode: true,


      tcHomePage: {
        lang_btn_img: "src/assets/img/eng-btn.png",
        title_img: "src/assets/img/sub-title.png",
        email: "請填寫有效電郵地址",
        error_msg: "此電郵地址不正確，請檢查後重新輸入。",
        choice_female: "女性",
        choice_male: "男性",
        desc_title: "提交表格即表示你同意以下事項：",
        desc_text:
          "ESPRIT 儲存了你的電郵地址，並將發送了以下通訊給你：<br><ul><li> 最新活動、全新商品系列信息</li><li>資訊事件</li><li>ESPRIT 特價促銷</li></ul>我們根據你的興趣為你定制時事通訊。為此，我們使用你使用者個人資料中的資料（尤其是商品偏好）。<br/>你可以在 <a href='https://www.esprit.hk/zh/privacy-policy.html?utm_source=Offline-Event&utm_medium=QRcode&utm_campaign=202211-HKGNA&utm_content=202211-HKGNA-promo-code-privacy&utm_term=na' target='_blank' rel='noopener noreferrer'>私隱政策中找到更多信息</a>",
        submit: "送出",
        title: "請掃瞄此二維碼以獲取電子優惠碼，<br>可在ESPRIT.HK購物時作七折使用。",
        valid_date: "有限期由2022年11月18日至2022年12月18日",
        terms_conditions:
          "<a href='https://www.esprit.hk/zh/terms-and-conditions.html'>條款及細則 : BIT.LY/ESPRIT-HKGNA-ZHTC</a>",
        show: "顯示代碼",
        back: "返回",
        promo_code: "你的優惠碼",
        shop: "往ESPRIT.HK選購",
        error_msg: {
          invalid_email: "此電郵地址不正確，請檢查後重新輸入。",
          exist_email: "此電郵地址已被登記",
        },
        thank_text: "感謝支持！<br>截圖存下此優惠碼，<br>可在ESPRIT.HK購物時作七折使用。",
      },

      enHomePage: {
        lang_btn_img: "src/assets/img/tc-btn.png",
        title_img: "src/assets/img/eng-sub.png",
        email: "Please Enter Your Email",
        error_msg: "The email is invalid, please check and try again.",
        choice_female: "Female",
        choice_male: "Male",
        desc_title: "By submitting the form, you agree to the following:",
        desc_text:
          "ESPRIT saves your email address and sends the newsletter to it with:<br><ul><li>News about the latest styles</li><li>Info events</li><li>Special promotions and competitions from ESPRIT</li></ul>We tailor the newsletter to your interests. For this we use data from your user profile (especially product preferences).<br>We process your personal data in accordance with our <a href='https://www.esprit.hk/en/privacy-policy.html?utm_source=Offline-Event&utm_medium=QRcode&utm_campaign=202211-HKGNA&utm_content=202211-HKGNA-promo-code-privacy&utm_term=na'  target='_blank' rel='noopener noreferrer'>privacy policy</a>",
        submit: "Submit",
        title: "PLEASE SCAN THE QR CODE AND USE THE <br> PROMO CODE FOR 30% OFF UPON CHECKOUT <br>AT ESPRIT.HK",
        valid_date: "VALID FROM 18 NOVEMBER 2022 TO 18 DECEMBER 2022",
        terms_conditions:
          "<a href='https://www.esprit.hk/zh/terms-and-conditions.html'>TERMS AND CONDITIONS : BIT.LY/ESPRIT-HKGNA-ENTC</a>",
        show: "SHOW CODE",
        back: "BACK",
        promo_code: "YOUR PROMO CODE",
        shop: "SHOP NOW AT ESPRIT.HK",
        error_msg: {
          invalid_email: "The email is invalid, please check and try again.",
          exist_email: "This email address has been registered ",
        },
        thank_text: "THANK YOU FOR JOINING US!<br>COPY THE CODE BELOW TO DISCOVER<br>AND ENJOY 30% OFF AT OUR E-SHOP.",
      },
      currentHomePage: {},
    };
  },
  components: {
    QrcodeVue,
    vShow,
  },
  methods: {
    toggleLang() {
      this.display = !this.display;
      if (this.display) {
        this.currentHomePage = this.tcHomePage;
      } else {
        this.currentHomePage = this.enHomePage;
      }
    },
    toggleSection() {
      this.activeSection = this.activeSection === 'submitForm' ? 'qrCodePage' : 'endPage'
    },
    handleSubmit() {
      const formData = new FormData(document.getElementById('emailForm'));

      axios.post('http://192.168.1.175/esprit_vue/esprit/api/post.php', formData)
        .then(response => {
          console.log(response.data);
          if (response.data.status === 'error' || response.data.message === 'Email already exists in the database.') {
            console.log('Email already exists in the database');
            this.showErrorMessage = true;
          } else {
            this.promoteCode = response.data;
            this.activeSection = 'qrCodePage';
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    },
    backPage(event) {
      event.preventDefault();
      this.activeSection = 'submitForm';
    },
    showEndpage() {
      this.activeSection = 'endPage';
    },
    copyPromoCode() {
      const promoCodeText = this.promoteCode;

      // Create a temporary input element
      const tempInput = document.createElement('input');
      tempInput.setAttribute('value', promoCodeText);
      document.body.appendChild(tempInput);

      // Select the text in the input element
      tempInput.select();
      tempInput.setSelectionRange(0, 99999);

      // Copy the selected text to the clipboard
      document.execCommand('copy');

      // Remove the temporary input element
      document.body.removeChild(tempInput);

      // Optionally, provide feedback to the user
      alert('Promo code copied: ' + promoCodeText);
    },

    handleQRCodeScan(result) {
      if (result && result.includes('#end-page')) {
        const element = document.getElementById('end-page');
        if (element) {
          element.scrollIntoView({ behavior: 'smooth' });
        }
      }
    },
    generateSectionQRCode(sectionId) {
      const url = `${window.location.href}#${sectionId}`;
      return url;
    },
    scrollToSectionFromQueryParams() {
      const urlParams = new URLSearchParams(window.location.search);
      const section = urlParams.get('section');

      if (section) {
        this.activeSection = section;
        this.scrollIntoView(section);
      }
    },
    scrollIntoView(sectionId) {
      const sectionElement = document.getElementById(sectionId);
      if (sectionElement) {
        sectionElement.scrollIntoView({ behavior: 'smooth' });
      }
    },
  },
  mounted() {
    this.currentHomePage = this.tcHomePage;
    this.scrollToSectionFromQueryParams();
  },
};
</script>

<template>
  <section id="submit-form" v-if="activeSection === 'submitForm'" class="active">
    <div class="container">
      <div @click="toggleLang()" id="lang-btn">
        <img class="blocktouch" :src="currentHomePage.lang_btn_img" alt="">
      </div>
    </div>
    <div class="container">
      <div id="logo">
        <img src="src/assets/img/logo.png" alt="">
      </div>
      <div id="sub-title">
        <img class="blocktouch" :src="currentHomePage.title_img" alt="">
      </div>
    </div>


    <form id="emailForm" method="POST">
      <p>{{ currentHomePage.email }}</p>
      <input v-model="email" type="email" name="email" id="email" :placeholder="currentHomePage.email" required>
      <div id="error">
        <h5 v-if="showErrorMessage">{{ currentHomePage.error_msg.exist_email }}</h5>
      </div>
      <div id="gender-section">
        <input v-model="gender" type="radio" name="gender" id="gender-female" value="female" checked>
        <label for="gender-female">{{ currentHomePage.choice_female }}</label>
        <input v-model="gender" type="radio" name="gender" id="gender-male" value="male">
        <label for="gender-male">{{ currentHomePage.choice_male }}</label>
      </div>
    </form>


    <div class="container">
      <div id="desc">
        <h3>{{ currentHomePage.desc_title }}</h3>
        <div v-html="currentHomePage.desc_text">
        </div>
      </div>
    </div>

    <div id="submit" class="container">
      <div class="flex">
        <button id="submit-btn" @click="handleSubmit()">
          <input type="submit" form="email" :value="currentHomePage.submit">
        </button>
      </div>
    </div>
  </section>

  <!-- qr-code-page -->

  <section id="qr-code-page" v-if="activeSection === 'qrCodePage'" class="active">
    <div class="container top flex">
      <div @click="toggleLang()" id="lang-btn">
        <img class="blocktouch" :src="currentHomePage.lang_btn_img" alt="">
      </div>
      <img id="logo" src="src/assets/img/logo.png" alt="">
      <h2 v-html="currentHomePage.title"></h2>
    </div>

    <div class="container qr-code">
      <div v-show="isQrCode" @click="showEndpage">
        <qrcode-vue :value="qrCodeData" :size="250" />
      </div>

      <div v-show="!isQrCode" id="promocode">
        <h2 class="white promo_code">{{ currentHomePage.promo_code }}</h2>
        <span id="code">{{ promoteCode }}</span>
      </div>

      <div class="container">
        <p style="color: white;" class="white">{{ currentHomePage.valid_date }}<br></p>
        <div class="white tc" v-html="currentHomePage.terms_conditions"></div>
      </div>
    </div>

    <div class="container flex">
      <button @click="isQrCode = !isQrCode" id="show-btn" type="button">{{ currentHomePage.show }}</button>
      <button @click="backPage" id="back-btn" type="button">{{ currentHomePage.back }}</button>
    </div>

  </section>

  <!-- End Page -->

  <section id="end-page" v-if="activeSection === 'endPage'" class="active">
    <div class="container top flex">
      <div @click="toggleLang()" id="lang-btn">
        <img :src="currentHomePage.lang_btn_img" alt="">
      </div>
      <img class="blocktouch" id="logo" src="src/assets/img/logo.png" alt="">
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
</template>
