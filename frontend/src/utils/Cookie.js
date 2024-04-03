export default class Cookie {
  static get(name) {
    const regex = new RegExp(`^${name}=`)
    const cookies = document.cookie.split(';').map(string => string.trim())
    let cookie = cookies.find(string => regex.test(string))
    if(cookie) cookie = cookie.replace(regex, "")
    return cookie
  }
}