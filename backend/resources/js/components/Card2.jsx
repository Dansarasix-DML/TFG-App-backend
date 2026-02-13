
export default function Card2({blog, users}) {
    return (
        <li key={blog.id}>
            <div>
                <h3>{blog.title}</h3>
                <p>{blog.description}</p>
                {users.map(user => (
                    // <p>{(user.id === blog.blogger) && user.username}</p>
                    user.id === blog.blogger ? <p key={user.id}>{'(' + user.username + ')'}</p> : null
                ))}
            </div>
        </li>
    );
}