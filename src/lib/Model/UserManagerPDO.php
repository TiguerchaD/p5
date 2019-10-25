<?php


namespace Model;

use Entity\User;
use GuzzleHttp\Psr7\ServerRequest;
use PDO;
use function OpenFram\escape_to_html as h;

class UserManagerPDO extends UserManager
{

    public function getList($option = [])
    {
        $sql = 'SELECT * FROM User ';

        $sql .= (isset($options['limit'])) ? ' LIMIT :limit ' : ' ';
        $sql .= (isset($options['offset'])) ? ' OFFSET :offset ' : ' ';

        $query = $this->dao->prepare($sql);

        (isset($options['offset'])) ? $query->bindValue(':offset', $options['offset'], PDO::PARAM_INT) : null;
        (isset($options['limit'])) ? $query->bindValue(':limit', $options['limit'], PDO::PARAM_INT) : null;


        $query->execute();
        $query = $this->dao->query($sql);
        $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User');

        $usersList = $query->fetchAll();

        $query->closeCursor();

        $roleManager = new RoleManagerPDO($this->dao);

        foreach ($usersList as $user) {
            $user->setRole($roleManager->getById($user->roleId));
        }

        return $usersList;
    }

    public function count()
    {
        $sql = 'SELECT count(*) FROM User';
        $query = $this->dao->query($sql);

        return $query->fetchColumn();
    }


    public function getById($value)
    {
        $sql = 'SELECT * FROM User ';
        $sql .= 'WHERE id = :id ';

        $query = $this->dao->prepare($sql);

        $query->bindValue(':id', $value, PDO::PARAM_INT);

        $query->execute();


        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\User');

        $roleManager = new RoleManagerPDO($this->dao);

        if ($user = $query->fetch()) {
            $query->closeCursor();

            $user->setRole($roleManager->getById($user->roleId));


            return $user;
        }

        return null;
    }

    public function getByUserName($value)
    {
        $sql = 'SELECT * FROM User ';
        $sql .= 'WHERE userName = :userName ';

        $query = $this->dao->prepare($sql);


        $query->bindValue(':userName', $value, PDO::PARAM_STR);

        $query->execute();


        $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, '\Entity\User');

        $roleManager = new RoleManagerPDO($this->dao);

        if ($user = $query->fetch()) {
            $query->closeCursor();

            $user->setRole($roleManager->getById($user->roleId));

            return $user;
        }

        return null;
    }

    public function add(User $user)
    {
        $sql = 'INSERT INTO User (firstName, lastName, userName, email, hashedPassword, description, roleId ) VALUES ';
        $sql .= '(:firstName, :lastName, :userName, :email, :hashedPassword, :description, :roleId ) ';

        $query = $this->dao->prepare($sql);
        $query->bindValue(':firstName', $user->getFirstName());
        $query->bindValue(':lastName', $user->getLastName());
        $query->bindValue(':userName', $user->getUserName());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':hashedPassword', $user->getHashedPassword());
        $query->bindValue(':description', $user->getDescription());
        $query->bindValue(':roleId', $user->getRole()->getId());

        $query->execute();

        return  $this->dao->lastInsertId();
    }


    public function update(User $user)
    {
        $sql = "UPDATE User SET ";
        $sql .= "firstName = :firstName, ";
        $sql .= "lastName = :lastName, ";
        $sql .= "userName = :userName, ";
        $sql .= "email = :email, ";
        $sql .= "description = :description, ";
        if ($user->isPasswordRequired()) {
            $sql .= "hashedPassword = :hashedPassword, ";
        }
        $sql .= "roleId = :roleId ";
        $sql .= "WHERE id = :id ";


        $query = $this->dao->prepare($sql);

        $query->bindValue(':id', $user->getId(), \PDO::PARAM_INT);
        $query->bindValue(':firstName', $user->getFirstName());
        $query->bindValue(':lastName', $user->getLastName());
        $query->bindValue(':userName', $user->getUserName());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':description', $user->getDescription());
        $query->bindValue(':roleId', $user->getRole()->getId());

        if ($user->isPasswordRequired()) {
            $query->bindValue(':hashedPassword', $user->getHashedPassword());
        }

        $query->execute();

        return $user->getId();
    }

    public function delete($id)
    {

        $sql = 'DELETE FROM user ';
        $sql .= 'WHERE id=:id ';

        $query = $this->dao->prepare($sql);
        $query->bindValue(':id', $id, \PDO::PARAM_INT);

        $query->execute();

        return $id;
    }
}
