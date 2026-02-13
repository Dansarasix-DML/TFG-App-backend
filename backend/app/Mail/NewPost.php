<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Post;
use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPost extends Mailable
{
    use Queueable, SerializesModels;
    protected User $user;
    protected Post $post;
    protected Blog $blog;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Post $post) {
        $this->user = $user;
        $this->post = $post;

        $this->blog = Blog::where("id", $post->blog_id)->first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {
        return new Envelope(
            subject: 'Nuevo contenido!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {
        return new Content(
            view: 'emails.newpost',
            with: [
                'name' => $this->user->name,
                'postTitle' => $this->post->title,
                'postDate' => $this->post->created_at,
                'postAuthor' => $this->blog->title,
                'postExcerpt' => $this->post->summary,
                'postUrl' => "https://gameverseproject.tech/blog/".$this->blog->slug."/".$this->post->slug,
            ]
        );
    }
}
