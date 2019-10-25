<?php


namespace Model;

use Entity\Post;
use http\Exception\RuntimeException;
use OpenFram\Manager;

abstract class PostManager extends Manager
{

    abstract public function getList($options = []);

    abstract public function getById($value);

    abstract public function count();

    public function save(Post $post)
    {
        if ($post->isValid()) {
            return $post->isNew() ? $this->add($post) : $this->update($post);
        } else {
            throw new RuntimeException('L\'article doit être valide pour être enregistré');
        }
    }
    abstract public function add(Post $post);
}
