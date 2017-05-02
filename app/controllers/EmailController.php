<?php

class EmailController extends \BaseController
{
    
    /* Sends out an Email when there is a new post or comment.  $post will contain the post object,
     * $type is a string of either Post or Comment that will determine the proper User query and
     * mail template to use in the email.
     */
    public function send($post, $type)
    {
        if ($type == 'Post') {
            $users = User::where('email_new_post', '=', 1)->get();
            $template = 'emails.newpostemail';
            $subject = 'New Postrr Post!';
        } elseif ($type == 'Comment') {
            $users = User::where('email_new_comment', '=', 1)->get();
            $template = 'emails.newcommentemail';
            $subject = 'New Postrr Comment!';
        }

        foreach ($users as $user) {
            $data = array('email' => $user->email, 
                          'username' => $user->username, 
                          'post' => $post->id,
                          'subject' => $subject);
            Mail::send($template, array('data' => $data), function ($message) use ($data) {
                $message->to($data['email'])->subject($data['subject']);
            });
        }
    }
}
