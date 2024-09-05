import { useParams, useNavigate } from "react-router-dom";
import AddPost from "../components/posts/AddPost";
import PostList from "../components/posts/PostList";
import { useEffect, useState } from "react";

export default function Home(props) {
  const navigate = useNavigate()
  const params = useParams()
  const [page, setPage] = useState()

  useEffect(() => {
    if(!params.page) setPage(1)
    const regex = new RegExp("^[0-9]+$")
    if(regex.test(params.page) && params.page > 0) setPage(params.page)
    else navigate("/")
  }, [params.page])

  return (
    <div className="home">
      {props.username && <AddPost/>}
      {page && <PostList page={page-1} post_num={3} username={props.username}/>}
    </div>
  )
}