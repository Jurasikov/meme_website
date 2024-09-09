import { useEffect, useState } from "react"
import PostComponent from "./PostComponent";
import { useNavigate } from "react-router-dom";

export default function PostList(props) {
  const navigate = useNavigate();
  const [posts, setPosts] = useState(null);
  const [pageNum, setPageNum] = useState(0);

  function selectPage(event) {
    navigate(`/${props.filter ? props.filter + '/' + props.filterKey + '/' : ""}${event.target.value}`);
  }

  function nextPage() {
    navigate(`/${props.filter ? props.filter + '/' + props.filterKey + '/' : ""}${props.page+1+1}`);
  }

  function previousPage() {
    navigate(`/${props.filter ? props.filter + '/' + props.filterKey + '/' : ""}${props.page-1+1}`);
  }

  useEffect(() => {
    const options = {
      method: 'GET',
      credentials: 'include',
      cache: 'no-store'
    }
    let resource;
    if(props.filter === "user") resource = `${process.env.REACT_APP_API}/posts?page=${props.page}&post_num=${props.post_num}&user=${props.filterKey}`;
    else if(props.filter === "tag") resource = `${process.env.REACT_APP_API}/posts?page=${props.page}&post_num=${props.post_num}&tag=${props.filterKey}`;
    else resource = `${process.env.REACT_APP_API}/posts?page=${props.page}&post_num=${props.post_num}`;
    fetch(encodeURI(resource), options)
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
  }, [props.page, props.post_num, props.filter, props.filterKey])

  return (
    <div>
      {posts ? posts.map((post, i) => <PostComponent {...post} key={i} username={props.username}/>) : (<div>pusto</div>)}
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