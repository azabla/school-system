importScripts('https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.14.6/firebase-messaging.js');

const firebaseConfig = {

  apiKey: "AIzaSyCBxBMOS0fQ6coEbAJ59EbG5C85UyRAAzY",

  authDomain: "gsmessaging-6f8fe.firebaseapp.com",

  projectId: "gsmessaging-6f8fe",

  storageBucket: "gsmessaging-6f8fe.appspot.com",

  messagingSenderId: "824088762395",

  appId: "1:824088762395:web:f3b82d6aa4ee86210d00be",

  measurementId: "G-QY7YF042HW"

};

firebase.initializeApp(firebaseConfig);
const messaging=firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log(payload);
    const notification=JSON.parse(payload);
    const notificationOption={
        body:notification.body,
        icon:notification.icon
    };
    return self.registration.showNotification(payload.notification.title,notificationOption);
});