<?php


namespace Model;

use Entity\Comment;
use Entity\Post;
use http\Exception\RuntimeException;
use OpenFram\Manager;

abstract class CommentManager extends Manager
{
    abstract protected function add(Comment $comment);

    abstract protected function getListOf(Post $post);

    public function save(Comment $comment)
    {
        if ($comment->isValid()) {
            return $this->add($comment);
        } else {
            throw new RuntimeException('Le commentaire doit être valid pour être enregistré');
        }
    }
}
