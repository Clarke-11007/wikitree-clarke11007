/*
 * Stolen from Kay
 * who had
 * Liberally stolen from 
 * wikitree-dynamic-tree tree.js WtLoginManager
 *
 * what is different about GPC version ?
 * changes from class to straight functions - or tries to !
 *
 * what is different about Kay version ?
 * - brings the cookie code from dynamic tree
 * - does not use WikiTreeAPI because it brings in other unneeded things
 * - preserves the URL with any ?abc=xyz arguments
 * - provide the option to continue without logging in
 *
 * On WtLoginManager the document element wt-api-login is used for login
 * which is in the upper right corner does look like the rest of WikiTree
 * but if we change the look/feel of the app will users be upset?
 * what about the option to use the app without logging in
 *
 */



  const C_WT_USERNAME = "WikiTreeAPI_userName";
  const C_WT_USER_ID = "WikiTreeAPI_userId";
  const C_WT_START_URL = "WikiTreeAPI_startUrl";
  var loginMessage = '';
  const MY_API_URL = "https://api.wikitree.com/api.php";

  
    thisEvents = null;
    thisUserName = null;
    thisUserId = null;
    thisUserName = cookie(C_WT_USERNAME) || null;
    thisUserId = cookie(C_WT_USER_ID) || null;
    thisUserIsLoggedIn = false;
  

  function saveCookies() {
    cookie(C_WT_USERNAME, thisUserName, { path: "/" });
    cookie(C_WT_USER_ID, thisUserId, { path: "/" });
  }

  function getUserName() {
    return thisUserName;
  }
  function getUserId() {
    return thisUserId;
  }

  // we save the starting url for the arguments
  function restoreStartUrl() {
    history.replaceState("", "", localStorage.getItem(C_WT_START_URL));
  }

  function getLoginMessage() {
    return loginMessage;
  }
  
  // Use API to see if user is logged in
  async function isLoggedInNow() {
    let isLogged = false;
    if (thisUserId) {
     let args = { action: "clientLogin", checkLogin: thisUserId, appId: appID };
      let formData = new FormData();
      formData.append('action', 'clientLogin');
      formData.append('checkLogin', thisUserId);
      formData.append('appId', appID);
      let options = {
        method: "POST",
        credentials: "include",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams(formData),
      }
      let fetchResponse = await fetch(MY_API_URL, options);
      if (!fetchResponse.ok) {
        console.log('error from fetch cannot tell if logged in');
      } else {
        let theJson = await fetchResponse.json();  
        if (theJson.clientLogin.result == 'ok') {
          isLogged = true;
          thisUserIsLoggedIn = true;

          if (thisUserName == "" && cookie("WikiTreeAPI_userName") > "") {
            thisUserName = cookie("WikiTreeAPI_userName");
          }
          
        }
      }
    }
    return;
  }

  async function login() {
    const searchParams = new URLSearchParams(window.location.search);
    const authcode = searchParams.get("authcode") ? searchParams.get("authcode") : null;
    await isLoggedInNow();
    console.log(this);
    if (thisUserIsLoggedIn) {
      saveCookies();
      thisEvents?.onLoggedIn(thisUserName);
    } else if (authcode) {
      // user is not logged in yet, but we've received authcode
      wikiTreeLogin({ action: "clientLogin", authcode: authcode });
    } else {
      console.log("doing ELSE");
      localStorage.setItem(C_WT_START_URL, window.location.href);
      thisEvents?.onUnlogged();
    }
    return;
  }

  async function wikiTreeLogin(loginData) {
    const API_URL = "https://api.wikitree.com/api.php";

    let formData = new FormData();
    for (var key in loginData) {
      formData.append(key, loginData[key]);
    }
    formData.append('appId', appID);
    let options = {
      method: "POST",
      credentials: "include",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams(formData),
    };
    fetch(API_URL, options)
      .then((response) => {
        if (!response.ok) {
          thisLoginMessage = 'login response ' + response.status + ': ' + response.statusText;
          console.log('login response ' + response.status + response.statusText);
          restoreStartUrl();
          throw new Error(`HTTP error! Status: ${response.status}: ${response.statusText}`);
        }
        return response.json();
      })
      .then((theJson) => {
        if (theJson.clientLogin.result === "Success") {
          thisUserName = theJson.clientLogin.username;
          thisUserId = theJson.clientLogin.userid;
          saveCookies();
          // In this case we need to force a window reload
          window.location.href = localStorage.getItem(C_WT_START_URL);
        }
      })
      .catch((error) => {
        console.error('Error: ', error);
        restoreStartUrl();
      });

  }


  function cookie(key, value, options) {
    if (options === undefined) {
        options = {};
    }

    // If we have a value, we're writing/setting the cookie.
    if (value !== undefined) {
        if (value === null) {
            options.expires = -1;
        }
        if (typeof options.expires === "number") {
            var days = options.expires;
            options.expires = new Date();
            options.expires.setDate(options.expires.getDate() + days);
        }
        value = String(value);
        return (document.cookie = [
            encodeURIComponent(key),
            "=",
            value,
            options.expires ? "; expires=" + options.expires.toUTCString() : "",
            options.path ? "; path=" + options.path : "",
            options.domain ? "; domain=" + options.domain : "",
            options.secure ? "; secure" : "",
        ].join(""));
    }

    // We're not writing/setting the cookie, we're reading a value from it.
    var cookies = document.cookie.split("; ");

    var result = key ? null : {};
    for (var i = 0, l = cookies.length; i < l; i++) {
        var parts = cookies[i].split("=");
        var name = parts.shift();
        name = decodeURIComponent(name.replace(/\+/g, " "));
        let value = parts.join("=");
        value = decodeURIComponent(value.replace(/\+/g, " "));

        if (key && key === name) {
            result = value;
            break;
        }
        if (!key) {
            result[name] = value;
        }
    }
    return result;
  }
