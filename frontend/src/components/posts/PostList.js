import { useEffect, useState } from "react"
import Post from "./Post";

export default function PostList(props) {
  const [posts, setPosts] = useState(null);
  const [page, setPage] = useState(0);
  const [pageNum, setPageNum] = useState(0);

  function nextPage() {
    setPage((previous) => previous + 1)
  }

  function previousPage() {
    setPage((previous) => previous -1)
  }

  useEffect(() => {
    const options = {
      method: 'GET',
      cache: 'no-store'
    }
    fetch(`${process.env.REACT_APP_API}/posts?page=${page}&post_num=${props.post_num}`, options)
    .then((response) => {
      if(!response.ok) {
        throw new Error(`${response.status} ${response.statusText}`)
      }
      return response.json()
    })
    .then((data) => {
      setPosts(data['data'])
      setPageNum(Math.ceil(data['total_post_number']/props.post_num))
    })
    .catch(err => console.log(err))
  }, [page, pageNum, props])

  return (
    <div>
      {posts ? posts.map((post, i) => <Post post={posts[i]} key={i}/>) : (<div>pusto</div>)}
      <div>
        {page > 0 && <button onClick={previousPage}>Poprzednia</button>}
        {page < pageNum-1 && <button onClick={nextPage}>Następna</button>}
      </div>
    </div>
  )
}