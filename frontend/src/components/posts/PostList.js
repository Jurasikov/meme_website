import { useEffect, useState } from "react"
import Post from "./Post";
import { useNavigate } from "react-router-dom";

export default function PostList(props) {
  const navigate = useNavigate()
  const [posts, setPosts] = useState(null)
  const [pageNum, setPageNum] = useState(0)

  function selectPage(event) {
    navigate(`/${event.target.value}`)
  }

  function nextPage() {
    navigate(`/${props.page+1+1}`)
  }

  function previousPage() {
    navigate(`/${props.page-1+1}`)
  }

  useEffect(() => {
    const options = {
      method: 'GET',
      credentials: 'include',
      cache: 'no-store'
    }
    fetch(`${process.env.REACT_APP_API}/posts?page=${props.page}&post_num=${props.post_num}`, options)
    .then((response) => {
      if(!response.ok) {
        throw new Error(`${response.status} ${response.statusText}`)
      }
      return response.json()
    })
    .then((data) => {
      if(data['data'].length > 0) setPosts(data['data'])
      setPageNum(Math.ceil(data['total_post_number']/props.post_num))
    })
    .catch(err => console.log(err))
  }, [props.page])

  return (
    <div>
      {posts ? posts.map((post, i) => <Post {...posts[i]} key={i} username={props.username}/>) : (<div>pusto</div>)}
      <div>
        {props.page > 0 && <button onClick={previousPage}>Poprzednia</button>}
        <select value={props.page+1} onChange={selectPage}>
          {Array.from(Array(pageNum).keys()).map((i) => <option key={i}>{i+1}</option>)}
        </select>
        {props.page < pageNum-1 && <button onClick={nextPage}>Następna</button>}
      </div>
    </div>
  )
}