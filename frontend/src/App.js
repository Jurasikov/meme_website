import NavBar from "./components/NavBar";
import Router from "./components/Router";
import { useState, useEffect } from "react"
import Cookie from './utils/Cookie'

function App() {
  const [username, setUsername] = useState("")
  useEffect(() => {
    const userData = Cookie.get('user_data')
    if(userData){
      setUsername(JSON.parse(decodeURIComponent(userData))['username'])
    }
    else setUsername("")
  }, [username])

  return (
    <>
      <NavBar username={username} setUsername={setUsername}/>
      <div className="main">
        <Router username={username} setUsername={setUsername}/>
      </div>
    </>
  );
}

export default App;