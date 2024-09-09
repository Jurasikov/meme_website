import { Route, Routes} from "react-router-dom";
import Home from "../pages/Home";
import Login from "../pages/Login";
import Register from "../pages/Register";
import Logout from "./Logout";
import Post from "../pages/Post";
import PostsByTag from "../pages/PostsByTag";
import PostsByUser from "../pages/PostsByUser";

export default function Router(props) {
  return(
    <Routes>
      <Route path="/:page?" element={<Home username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/login" element={<Login username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/rejestracja" element={<Register username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/logout" element={<Logout username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/post/:number" element={<Post username={props.username}/>}/>
      <Route path="/tag/:tagname/:page?" element={<PostsByTag username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/user/:username/:page?" element={<PostsByUser username={props.username} setUsername={props.setUsername}/>}/>
    </Routes>
  )
}