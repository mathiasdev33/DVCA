<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MaestroController extends AbstractController implements PasswordHasherInterface
{

    private $request;
    private $em;
    private $authenticationUtils;


    public function secureInput($data, $full=true)
    {

        // get type
        $type = gettype($data);

        $data = trim($data);
        $data = stripslashes($data);
        if($full){
            $data = htmlspecialchars($data, ENT_NOQUOTES, 'UTF-8');
        }

        switch($type){

            case "integer":
                $data = intval($data);
                break;

            case "double":
                $data = floatval($data);
                break;

            case "boolean":
                $data = boolval($data);
                break;

            case "NULL":
                $data = null;
                break;

        }


        return $data;
    }

    

    // DEPENDENCE : $secureInput
    public function getFormHTML($form)
    {
        $result = [];

        // check if it's an array
        if(is_array($form)){

            foreach($form as $key => $value){
                // secure input
                $key = $this->secureInput($key);
                if(!is_array($value)){
                    $value = $this->secureInput($value, false);
                }
                $result[$key] = $value;

            }

        }else{
            return false;
        }

        return $result;


    }

      /**
     * Hashes a plain password.
     *
     * @throws InvalidPasswordException When the plain password is invalid, e.g. excessively long
     */
    public function hash(string $plainPassword): string
    {
        // TODO: Implement hash() method.
        return password_hash($plainPassword,PASSWORD_BCRYPT);
    }

    /**
     * Verifies a plain password against a hash.
     */
    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        // TODO: Implement verify() method.
    }

    /**
     * Checks if a password hash would benefit from rehashing.
     */
    public function needsRehash(string $hashedPassword): bool
    {
        // TODO: Implement needsRehash() method.
    }

}