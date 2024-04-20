import { initializeApp } from 'firebase/app';
import { getMessaging, onMessage, getToken} from "firebase/messaging";

// TODO: Replace the following with your app's Firebase project configuration
const firebaseConfig = {
    apiKey: "AIzaSyDW-lIn8IF4Lm0tXDZMMbZRZ2GevXutcQY",
    authDomain: "connect-77842.firebaseapp.com",
    databaseURL: "https://connect-77842-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "connect-77842",
    storageBucket: "connect-77842.appspot.com",
    messagingSenderId: "644304410198",
    appId: "1:644304410198:web:475c9162c5cfb13112cdf7",
    measurementId: "G-E4TMQMVN8Q"
};

const app = initializeApp(firebaseConfig);

const messaging = getMessaging(app);

function requestPermission() {
    console.log('Requesting permission...');
    Notification.requestPermission().then((permission) => {
      if (permission === 'granted') {
        console.log('Notification permission granted.');
      }
    });
}

// Add the public key generated from the console here.
getToken(messaging, {vapidKey: "BHMdog2KsUHmdaEd0zBRBE2fYordStoM-nQ-BVQF0C57m8_wBJaKRUtjbVklSSo7NiXQTe6p-FgjqTQkOhmdEBs"}).then((currentToken) => {
    if (currentToken) {
        storeToken(currentToken);
    } else {
      // Show permission request UI
      console.log('No registration token available. Request permission to generate one.');
      requestPermission();
      // ...
    }
  }).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
    // ...
  });

  async function storeToken(token) {
    let response=await fetch ('/store/token', {
        method : 'post',
        headers:{
          'Content-Type': 'application/json;charset=utf-8',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'ACCEPT': 'application/json'
        },
        body : JSON.stringify({ 'token' : token})
      });
  
      if(response.ok)
      {
        console.log('stored token');
      }
      else
        alert("error");
  }

  onMessage(messaging, (payload) => {
    console.log('Message received. ', payload);
    // ...
  });