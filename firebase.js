    
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyAprSmnfiNa4m11VEYuCBZ3sHT6507JH70",
    authDomain: "peerlift-62aa6.firebaseapp.com",
    databaseURL: "https://peerlift-62aa6.firebaseio.com",
    projectId: "peerlift-62aa6",
    storageBucket: "peerlift-62aa6.appspot.com",
    messagingSenderId: "1051284298081"
  };
  firebase.initializeApp(config);

  //Authentication
    function googleLogin() {
      if (!firebase.auth().currentUser) {
        var provider = new firebase.auth.GoogleAuthProvider();
        //provider.addScope('https://www.googleapis.com/auth/plus.login');
        provider.addScope('profile');
        provider.addScope('email');
        firebase.auth().signInWithRedirect(provider);
      }
    }

    function signOut() {
        firebase.auth().signOut();
    }

    var uid = "";
    function initApp() {
      firebase.auth().getRedirectResult().then(function(result) {
        if (result.credential) {
          var token = result.credential.accessToken;
        }
        var user = result.user;
      }).catch(function(error) {
        var errorCode = error.code;
        var errorMessage = error.message;
        var email = error.email;
        var credential = error.credential;
        if (errorCode === 'auth/account-exists-with-different-credential') {
          alert('You have already signed up with a different auth provider for that email.');
        } else {
          console.error(error);
        }
      });

      firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
          // User is signed in.
          var displayName = user.displayName;
          var email = user.email;
          var emailVerified = user.emailVerified;
          var photoURL = user.photoURL;
          var isAnonymous = user.isAnonymous;
          uid = user.uid;
          var refreshToken = user.refreshToken;
          var providerData = user.providerData;

          getData();
            
          $("#loginBTN button").text("Profile");
          document.getElementById('loginBTN').href = "profile.html";
          $("#loginMobile div").text("Profile");
          document.getElementById('loginMobile').href = "profile.html";
          if (window.location.href.indexOf("login.html") > -1) {
              window.location.href = 'profile.html';
          }
        } else {
          // User is signed out.
          uid = "none";
          $("#loginBTN button").text("Sign Up");
          document.getElementById('loginBTN').href = "login.html";
          $("#loginMobile div").text("Sign Up / Login");
          document.getElementById('loginMobile').href = "login.html";
          if (window.location.href.indexOf("profile.html") > -1) {
              window.location.href = 'login.html';
          }
        }
      });
    }
    window.onload = function() {
      initApp();
    };


    //Database
    var db = firebase.database();
    var data;
    function getData() {
        if (uid != "") {
            var userRef = db.ref('/oppList/' + uid);
            var query = userRef.orderByChild('metricDate');
            query.on('child_added', function(snapshot) {
                data = snapshot.val();
                displayOpp(data)
            });
            userRef.on('value', function(snapshot) {
                if (snapshot.val() == null) {
                    displayOpp(null);
                }
            });
        }
     }

    
    function addOpp(oppName, deadline, type, link, metricDate) {
        db.ref('/oppList/' + uid + "/" + oppName).update({
            name: oppName,
            deadline: deadline,
            type: type,
            link: link,
            metricDate: metricDate
        });
    }

    function removeOpp(oppName) {
        var ref = db.ref('/oppList/' + uid + "/" + oppName);
        ref.remove();
    }





