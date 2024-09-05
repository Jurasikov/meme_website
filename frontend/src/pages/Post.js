import { useEffect, useState } from "react"
import PostComponent from "../components/posts/PostComponent"
import CommentSection from "../components/comments/CommentSection";
import { useParams } from "react-router-dom";

export default function Post(props) {
  const [post, setPost] = useState();
  const params = useParams();

  useEffect(() => {
    const options = {
      method: 'GET',
      credentials: 'include'
    };
    fetch(`${process.env.REACT_APP_API}/posts/${params.number}`, options)
    .then((response) => {
      if(!response.ok) {
        throw new Error(`${response.status} ${response.statusText}`)
      }
      return response.json();
    })
    .then((data) => {
      setPost(data);
    })
    .catch(err => console.log(err));
  }, [])

  return (
    <div>
      <PostComponent {...post} username={props.username}/>
      <CommentSection id={params.number} />
    </div>
  )
}