<?php

namespace Model;

use Entity\Post;
use GuzzleHttp\Psr7\ServerRequest;
use PDO;
use function OpenFram\escape_to_html as h;

class PostManagerPDO extends PostManager
{

    /**
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function getList($options = [])
    {
        $sql = 'SELECT * FROM Post ';

        if (isset($options['userId'])) {
            $sql .= ' WHERE userId = :userId ';
            $sql .= (isset($options['visible'])) ?  ' AND visible = :visible ' : ' ';
        } else {
            $sql .= (isset($options['visible'])) ?  ' WHERE visible = :visible ' : ' ';
        }
        $sql .= 'ORDER BY publicationDate DESC ';
        $sql .= (isset($options['limit'])) ? ' LIMIT :limit ' : ' ';
        $sql .= (isset($options['offset'])) ? ' OFFSET :offset ' : ' ';

        $query = $this->dao->prepare($sql);

        (isset($options['userId'])) ? $query->bindValue(':userId', $options['userId'], PDO::PARAM_INT) : null;
        (isset($options['visible'])) ? $query->bindValue(':visible', $options['visible'], PDO::PARAM_INT) : null;
        (isset($options['offset'])) ? $query->bindValue(':offset', $options['offset'], PDO::PARAM_INT) : null;
        (isset($options['limit'])) ? $query->bindValue(':limit', $options['limit'], PDO::PARAM_INT) : null;


        $query->execute();


        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\Post');


        $postsList = $query->fetchAll();

        $query->closeCursor();
        $userManager = new UserManagerPDO($this->dao);

        foreach ($postsList as $post) {
            $post->setUser($userManager->getById( $post->userId));
            $post->setPublicationDate(new \DateTime($post->getPublicationDate()));
            $post->setModificationDate(new \DateTime($post->getModificationDate()));

            $imagePath = ServerRequest::fromGlobals()->getServerParams()['DOCUMENT_ROOT'] . '/images/post/post-' . htmlspecialchars($post->getId()) . '.jpg';



            $url = file_exists($imagePath) ? '/images/post/post-' . htmlspecialchars($post->getId()) . '.jpg' : '/images/post/post-default.jpg';

            $post->setFeaturedImage($url);
        }


        return $postsList;
    }


    /**
     * @param $postId
     * @param array $options
     * @return Post | null
     * @throws \Exception
     */
    public function getById($postId, $options = [])
    {
        $sql = 'SELECT * FROM Post ';
        $sql .= 'WHERE id = :id ';
        $sql .= (isset($options['visible'])) ?  ' AND visible = :visible ' : ' ';


        $query = $this->dao->prepare($sql);

        $query->bindValue(':id', $postId, PDO::PARAM_INT);
        isset($options['visible']) ? $query->bindValue(':visible', $options['visible'], PDO::PARAM_INT) : null;

        $query->execute();

        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\Post');


        if ($post = $query->fetch()) {
            $query->closeCursor();

            $post->setUser((new UserManagerPDO($this->dao))->getById( $post->userId));
            $post->setPublicationDate(new \DateTime($post->getPublicationDate()));
            $post->setModificationDate(new \DateTime($post->getModificationDate()));

            $imagePath = ServerRequest::fromGlobals()->getServerParams()['DOCUMENT_ROOT'] . '/images/post/post-' . htmlspecialchars($post->getId()) . '.jpg';

            $url = file_exists($imagePath) ? '/images/post/post-' . htmlspecialchars($post->getId()) . '.jpg' : '/images/post/post-default.jpg';

            $post->setFeaturedImage($url);


            return $post;
        }

        return null;
    }

    public function count($options = [])
    {
        $sql = 'SELECT count(*) FROM Post ';
        if (isset($options['userId'])) {
            $sql .= ' WHERE userId = :userId ';
            $sql .= (isset($options['visible'])) ?  ' AND visible = :visible ' : ' ';
        } else {
            $sql .= (isset($options['visible'])) ?  ' WHERE visible = :visible ' : ' ';
        }

        $query = $this->dao->prepare($sql);

        (isset($options['userId'])) ? $query->bindValue(':userId', $options['userId'], PDO::PARAM_INT) : null;
        (isset($options['visible'])) ? $query->bindValue(':visible', $options['visible'], PDO::PARAM_INT) : null;

        $query->execute();



        return $query->fetchColumn();
    }

    public function add(Post $post)
    {
        $sql = 'INSERT INTO Post (title, subtitle, content, userId, visible, publicationDate, modificationDate ) ';
        $sql .= 'VALUES (:title, :subtitle, :content, :userId, :visible, NOW(), NOW()) ';

        $query = $this->dao->prepare($sql);

        $query->bindValue(':title', $post->getTitle());
        $query->bindValue(':subtitle', $post->getSubTitle());
        $query->bindValue(':content', $post->getContent());
        $query->bindValue(':visible', $post->isVisible(), PDO::PARAM_INT);
        $query->bindValue(':userId', $post->getUser()->getId(), PDO::PARAM_INT);

        $query->execute();


        return $this->dao->lastInsertId();
    }

    public function update(Post $post)
    {
        $sql = 'UPDATE Post SET ';
        $sql .= 'title=:title, ';
        $sql .= 'subtitle=:subtitle, ';
        $sql .= 'content=:content, ';
        $sql .= 'userId=:userId, ';
        $sql .= 'visible=:visible, ';
        $sql .= 'modificationDate= NOW()  ';
        $sql .= 'WHERE id = :id';

        $query = $this->dao->prepare($sql);

        $query->bindValue(':id', $post->getId(), PDO::PARAM_INT);
        $query->bindValue(':title', $post->getTitle());
        $query->bindValue(':subtitle', $post->getSubTitle());
        $query->bindValue(':content', $post->getContent());
        $query->bindValue(':userId', $post->getUser()->getId(), PDO::PARAM_INT);
        $query->bindValue(':visible', $post->isVisible(), PDO::PARAM_INT);

        $query->execute();

        return $post->getId() ;
    }

    /**
     * @param $id
     */
    public function delete($id)
    {

        $sql = 'DELETE FROM post ';
        $sql .= 'WHERE id=:id ';

        $query = $this->dao->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        $query->execute();

    }
}
