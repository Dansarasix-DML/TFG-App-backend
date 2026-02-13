import React, {useState, useEffect} from 'react';
import Axios from 'axios';
import Card from './Card';
import Card2 from './Card2';

function Posts() {
    const [blogs, setBlogs] = useState([]);
    const [posts, setPosts] = useState([]);

    useEffect(() => {
        async function fetchBlogs() {
            try {
                const response = await Axios.get('/lastBlogs');
                setBlogs(response.data.blogs);
                setPosts(response.data.posts);
            } catch (error) {
                console.error(error);
            }
        }

        fetchBlogs();
    }, []);

    return (
        <div className='margin1'>
            <ul className='list2'>
                {posts.map(post => (
                    <Card key={post.id} post={post} blogs={blogs} />
                ))}
            </ul>
        </div>
    )
    
}

function Blogs() {
    const [blogs, setBlogs] = useState([]);
    const [users, setUsers] = useState([]);

    useEffect(() => {
        async function fetchBlogs() {
            try {
                const response = await Axios.get('/lastBlogs');
                setBlogs(response.data.blogs);
                setUsers(response.data.users);
            } catch (error) {
                console.error(error);
            }
        }

        fetchBlogs();
    }, []);

    return (
        <div className='margin1'>
            <ul className='list1'>
                {blogs.map(blog => (
                    <Card2 blog={blog} users={users} />
                ))}
            </ul>
        </div>
    )
}

export default function Index() {
    return (
        <>
            <div className='divFlex1'>
                <hr />
                <h2>Last Posts</h2>
                <hr />
            </div>
            <p>Last posts.</p>
            <Posts />
            <div className='divFlex1'>
                <hr />
                <h2>Last Blogs</h2>
                <hr />
            </div>
            <p>Last blogs updated.</p>
            <Blogs />
        </>
    )
}