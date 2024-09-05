import { useEffect, useState } from "react";

export default function VoteBar(props) {
  const [vote, setVote] = useState(props.vote);
  const [ratio, setRatio] = useState(props.ratio);

  // useEffect(() => {
  //   console.log(props);
  // })
  
  function fvote(event) {
    if(!props.username) {
      console.log("Tu powinna wyświetlać się toast że trzeba się zalogować");
      return 0;
    }

    if(vote === (event.target.className === "upVote" ? 1 : -1)) {
      fetch(`${process.env.REACT_APP_API}/posts/${props.id}/reactions`, {method: 'DELETE', credentials: 'include'})
      .then((response) => {
        if(!response.ok) {
          throw new Error(`${response.status} ${response.statusText}`);
        }
        return response.json();
      })
      .then((data) => {
        setRatio(data['ratio']);
        setVote(data['vote']);
      });
    }
    else {
      const options = {
        method: 'PUT',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({vote: event.target.className === "upVote" ? 1 : -1})
      };
      fetch(`${process.env.REACT_APP_API}/posts/${props.id}/reactions`, options)
      .then((response) => {
        if(!response.ok) {
          throw new Error(`${response.status} ${response.statusText}`);
        }
        return response.json();
      })
      .then((data) => {
        setRatio(data['ratio']);
        setVote(data['vote']);
      });
    }
  }

  return (
    <span className="voteBar">
      <span className="upVote" onClick={fvote} style={{color: vote===1 ? "green" : "inherit", cursor: "pointer"}}>+</span>
      <span className="ratio">{ratio}</span>
      <span className="downVote" onClick={fvote} style={{color: vote===-1 ? "red" : "inherit", cursor: "pointer"}}>-</span>
    </span>
  )
}