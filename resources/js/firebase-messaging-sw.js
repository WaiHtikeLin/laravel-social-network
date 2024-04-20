import { initializeApp } from "firebase/app";
import { getMessaging } from "firebase/messaging/sw";

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
const firebaseApp = initializeApp({
    apiKey: "AIzaSyDW-lIn8IF4Lm0tXDZMMbZRZ2GevXutcQY",
    authDomain: "connect-77842.firebaseapp.com",
    databaseURL: "https://connect-77842-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "connect-77842",
    storageBucket: "connect-77842.appspot.com",
    messagingSenderId: "644304410198",
    appId: "1:644304410198:web:475c9162c5cfb13112cdf7",
    measurementId: "G-E4TMQMVN8Q"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = getMessaging(firebaseApp);

// import { onBackgroundMessage } from "firebase/messaging/sw";

// onBackgroundMessage(messaging, (payload) => {
//   console.log('[firebase-messaging-sw.js] Received background message ', payload);
//   // Customize notification here
//   const notificationTitle = 'Background Message Title';
//   const notificationOptions = {
//     body: 'Background Message body.',
//     icon: '/firebase-logo.png'
//   };

//   self.registration.showNotification(notificationTitle,
//     notificationOptions);
// });