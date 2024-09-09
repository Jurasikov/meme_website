import { useParams, useNavigate } from "react-router-dom";
import PostList from "../components/posts/PostList";
import { useEffect, useState } from "react";

export default function PostsByUser(props) {
  const navigate = useNavigate();
  const params = useParams();
  const [page, setPage] = useState();

  useEffect(() => {
    if(!params.page) setPage(1);
    else{
      const regex = new RegExp("^[0-9]+$");
      if(regex.test(params.page) && params.page > 0) setPage(params.page);
      else navigate(`/tag/${params.username}`);
    }
  }, [params.page])

  return (
    <div className="home">
      {page && <PostList page={page-1} post_num={3} username={props.username} filter={"user"} filterKey={params.username}/>}
    </div>
  )
}