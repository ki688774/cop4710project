const minutes = 30;

function saveCookie (userData) {
    let date = new Date();
    date.setTime(date.getTime() + minutes * 60000);
    document.cookie = "current_user=" + userData.uid + ",firstName=" + userData.firstName
        + ",lastName=" + userData.lastName + ",email=" + userData.email 
        + ",username=" + userData.username + ";expires=" + date.toGMTString();
}

function isCookieExpired () {
    
}

function readCookie () {

}