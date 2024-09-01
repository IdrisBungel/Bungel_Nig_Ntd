
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.22.1/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyBVfgXHD5YPWmECrxJqgmjKPfW2wzEun94",
    authDomain: "bungelapp.firebaseapp.com",
    projectId: "bungelapp",
    storageBucket: "bungelapp.appspot.com",
    messagingSenderId: "70623626836",
    appId: "1:70623626836:web:f7672589bb958c4f59cfc1",
    measurementId: "G-W36PMES89M"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
