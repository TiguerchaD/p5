<?php


namespace Model;

use Entity\Comment;
use Entity\Post;

class CommentManagerPDO extends CommentManager
{


    public function add(Comment $comment)
    {
        $sql = 'INSERT INTO Comment (content, postId, userId, publicationDate ) VALUES ';
        $sql .= '(:content, :postId, :userId, NOW()) ';

        $query = $this->dao->prepare($sql);
        $query->bindValue(':content', $comment->getContent(), \PDO::PARAM_STR);
        $query->bindValue(':postId', $comment->getPost()->getId(), \PDO::PARAM_INT);
        $query->bindValue(':userId', $comment->getUser()->getId(), \PDO::PARAM_INT);

        $query->execute();


        $comment->setId($this->dao->lastInsertId());
        return $this->dao->lastInsertId();
    }

    public function getListOf(Post $post)
    {
        if (!ctype_digit($post->getId())) {
            throw new \InvalidArgumentException('L\'identifiant de l\'article doit d\'$etre un entier');
        }
        $sql = 'SELECT * FROM Comment ';
        $sql .= 'WHERE Comment.postId = :postId ';
        $sql .= (isset($options['valid'])) ? ' AND visible = :valid ' : ' ';
        $sql .= 'ORDER BY publicationDate DESC ';
        $sql .= (isset($options['limit'])) ? ' LIMIT :limit ' : ' ';
        $sql .= (isset($options['offset'])) ? ' OFFSET :offset ' : ' ';

        $query = $this->dao->prepare($sql);

        (isset($options['valid'])) ? $query->bindValue(':valid', $options['valid'], \PDO::PARAM_INT) : null;
        (isset($options['offset'])) ? $query->bindValue(':offset', $options['offset'], \PDO::PARAM_INT) : null;
        (isset($options['limit'])) ? $query->bindValue(':limit', $options['limit'], \PDO::PARAM_INT) : null;
        $query->bindValue(':postId', $post->getId(), \PDO::PARAM_INT);

        $query->execute();

        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments = $query->fetchALL();

        $userManager = new UserManagerPDO($this->dao);

        foreach ($comments as $comment) {
            $comment->setUser($userManager->getById( $comment->userId));
            $comment->setPost($post);
            $comment->setPublicationDate(new \DateTime($comment->getPublicationDate()));
        }

        return $comments;
    }


    public function getList($options = [])
    {
        $sql = 'SELECT * FROM Comment ';
        $sql .= (isset($options['valid'])) ? ' WHERE visible = :valid ' : ' ';
        $sql .= 'ORDER BY publicationDate DESC ';
        $sql .= (isset($options['limit'])) ? ' LIMIT :limit ' : ' ';
        $sql .= (isset($options['offset'])) ? ' OFFSET :offset ' : ' ';

        $query = $this->dao->prepare($sql);

        (isset($options['valid'])) ? $query->bindValue(':valid', $options['valid'], \PDO::PARAM_INT) : null;
        (isset($options['offset'])) ? $query->bindValue(':offset', $options['offset'], \PDO::PARAM_INT) : null;
        (isset($options['limit'])) ? $query->bindValue(':limit', $options['limit'], \PDO::PARAM_INT) : null;


        $query->execute();

        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');


        $commentsList = $query->fetchAll();

        $query->closeCursor();

        $userManager = new UserManagerPDO($this->dao);
        $postManager = new PostManagerPDO($this->dao);

        foreach ($commentsList as $comment) {
            $comment->setUser($userManager->getById( $comment->userId));
            $comment->setPublicationDate(new \DateTime($comment->getPublicationDate()));
            $comment->setPost($postManager->getById( $comment->postId));
        }

        return $commentsList;
    }

    public function count($options = [])
    {
        $sql = 'SELECT count(*) FROM Comment ';

        if (isset($options['userId'])) {
            $sql .= 'INNER JOIN Post on Comment.postId = Post.id ';
            $sql .= 'WHERE Post.userId = :userId ';
            $sql .= (isset($options['valid'])) ? ' AND Comment.valid = :valid ' : ' ';

        } elseif (isset($options['valid'])) {
            $sql .= ' WHERE valid = :valid ';
        }

        $query = $this->dao->prepare($sql);

        (isset($options['valid'])) ? $query->bindValue(':valid', $options['valid'], \PDO::PARAM_INT) : null;
        (isset($options['userId'])) ? $query->bindValue(':userId', $options['userId'], \PDO::PARAM_INT) : null;

        $query->execute();

        return $query->fetchColumn();
    }

    public function getById($value, $options = [])
    {
        $sql = 'SELECT * FROM Comment ';
        $sql .= 'WHERE id = :value ';
        $sql .= (isset($options['valid'])) ? ' AND Comment.valid = :valid ' : ' ';

        $query = $this->dao->prepare($sql);

        (isset($options['valid'])) ? $query->bindValue(':valid', $options['valid'], \PDO::PARAM_INT) : null;
        $query->bindValue(':value', $value, \PDO::PARAM_INT);

        $query->execute();



        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');


        if ($comment = $query->fetch()) {
            $query->closeCursor();

            $userManager = new UserManagerPDO($this->dao);
            $postManager = new PostManagerPDO($this->dao);

            $comment->setUser($userManager->getById( $comment->userId));
            $comment->setPublicationDate(new \DateTime($comment->getPublicationDate()));
            $comment->setPost($postManager->getById( $comment->postId));

            return $comment;
        }

        return null;
    }

    public function validate($commentId)
    {

        $sql = "UPDATE Comment SET ";
        $sql .= "valid = :valid ";
        $sql .= "WHERE id = :id ";

        $query = $this->dao->prepare($sql);

        $query->bindValue(':id', $commentId, \PDO::PARAM_INT);
        $query->bindValue(':valid', 1, \PDO::PARAM_INT);

        $query->execute();
    }

    public function invalidate($commentId)
    {

        $sql = "UPDATE Comment SET ";
        $sql .= "valid = :valid ";
        $sql .= "WHERE id = :id ";

        $query = $this->dao->prepare($sql);

        $query->bindValue(':id', $commentId, \PDO::PARAM_INT);
        $query->bindValue(':valid', 0, \PDO::PARAM_INT);

        $query->execute();
    }

    public function delete($commentId)
    {
        $sql = 'DELETE FROM Comment ';
        $sql .= 'WHERE id=:id ';

        $query = $this->dao->prepare($sql);
        $query->bindValue(':id', $commentId, \PDO::PARAM_INT);

        $query->execute();
    }
}
