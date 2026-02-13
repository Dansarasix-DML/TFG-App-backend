
export default function Card({key, post, blogs}) {
    return (
        <li key={key} className={post.id%2 == 0 ? 'par' : ''}>
            <div>
                <h3>{post.title}</h3>
                <p>{post.subtitle}</p>
                {blogs.map(blog => (
                    blog.id === post.blog_id ? <p key={blog.id}>{'(' + blog.title + ')'}</p> : null
                ))}
            </div>
        </li>
    )
}