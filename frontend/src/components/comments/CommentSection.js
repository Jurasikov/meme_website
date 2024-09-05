import { useEffect, useState } from "react";
import Comment from "./Comment";
import AddComment from "./AddComment";

export default function CommentSection(props) {
  const [comments, setComments] = useState();
  const [refresh, setRefresh] = useState();

  useEffect(() => {
    setRefresh(false);
    const options = {
      method: 'GET',
      credentials: 'include'
    };
    fetch(`${process.env.REACT_APP_API}/posts/${props.id}/comments`, options)
    .then((response) => {
      if(!response.ok) {
        throw new Error(`${response.status} ${response.statusText}`)
      }
      return response.json();
    })
    .then((data) => {
      const anchors = new Map();
      const mainComments = [];
      for(let i=0; i < data.length; i++) {
        if(data[i].reply_to === null) {
          anchors.set(data[i].id, i);
          data[i].subcomments = [];
          mainComments.push(data[i]);
        }
        else{
          let anchor = anchors.get(data[i].reply_to);
          anchors.set(data[i].id, anchor);
          data[anchor].subcomments.push(data[i]);
        }
      }
      setComments(mainComments);
      //console.log(anchors);
    })
    .catch(err => console.log(err));
  }, [refresh]);

  return (
    <div className="commentSection">
      <div className="comments">
        {comments ? comments.map((comment, i) => <Comment {...comments[i]} key={i} commentType="mainComment" postId={props.id} setRefresh={setRefresh}/>) : "pusto"}
      </div>
      <AddComment postId={props.id} reply={null} setRefresh={setRefresh}/>
    </div>
  )
}