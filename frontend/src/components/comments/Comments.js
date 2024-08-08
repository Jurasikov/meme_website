import { useEffect, useState } from "react";
import Comment from "./Comment";

export default function Comments(props) {
  const [comments, setComments] = useState();

  useEffect(() => {
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
      console.log(anchors);
    })
    .catch(err => console.log(err));
  }, []);

  return (
    <div className="commentSection">
      {comments ? comments.map((comment, i) => <Comment {...comments[i]} key={i} commentType="mainComment"/>) : "pusto"}
    </div>
  )
}