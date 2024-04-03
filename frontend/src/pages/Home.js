import AddPost from "../components/posts/AddPost";
import PostList from "../components/posts/PostList";

export default function Home(props) {
  return (
    <div className="home">
      {props.username && <AddPost />}
      <PostList post_num={7} />
    </div>
  )
}