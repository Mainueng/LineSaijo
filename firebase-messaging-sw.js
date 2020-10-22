importScripts("https://www.gstatic.com/firebasejs/7.14.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/7.14.1/firebase-messaging.js");

const firebaseConfig = {
  apiKey: "AIzaSyBKp4os0Uv8PXH4LrCbyfXw_K9Hhod22Eg",
  authDomain: "saijo-denki-e-service-8af8a.firebaseapp.com",
  databaseURL: "https://saijo-denki-e-service-8af8a.firebaseio.com",
  projectId: "saijo-denki-e-service-8af8a",
  storageBucket: "saijo-denki-e-service-8af8a.appspot.com",
  messagingSenderId: "880968949880",
  appId: "1:880968949880:web:76eb3c73b33c9dede8cd8d",
  measurementId: "G-L8E87D6L3P"
};
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

  const messaging = firebase.messaging();