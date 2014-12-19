<?php

/*
 * The MIT License
 *
 * Copyright 2014 Vincent Quatrevieux <quatrevieux.vincent@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\form;

/**
 * Description of Register
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Register extends \system\form\Form {
    private $pseudo;
    private $pass;
    private $pass2;
    private $email;
    private $firstName;
    private $lastName;
    private $gender;
    
    /**
     *
     * @var \app\model\Account
     */
    private $model;
    
    public function __construct(\system\input\Input $input, \system\helper\Url $url, \app\model\Account $model) {
        parent::__construct($input, $url);
        $this->model = $model;
        
        $this->pseudo = new \system\form\field\Input($this, 'pseudo');
        $this->pass = new \system\form\field\Input($this, 'pass');
        $this->pass2 = new \system\form\field\Input($this, 'pass2');
        $this->email = new \system\form\field\Input($this, 'email');
        $this->firstName = new \system\form\field\Input($this, 'first_name');
        $this->lastName = new \system\form\field\Input($this, 'last_name');
        $this->gender = new \system\form\field\Select($this, 'gender', array(
            'Civilité' => '',
            'Homme' => 'MALE',
            'Femme' => 'FEMALE'
        ));
        $this->makeFields();
    }
    
    private function makeFields(){
        //rules
        $required = new \system\form\rule\Required();
        $this->pseudo->addRule($required);
        $this->pass->addRule($required);
        $this->pass2->addRule($required);
        $this->email->addRule($required);
        $this->firstName->addRule($required);
        $this->lastName->addRule($required);
        
        $this->pseudo->addRule(new \system\form\rule\Length(3, 32));
        $this->pseudo->addRule(new \system\form\rule\Regex('/^[a-z0-9-]{3,32}$/i', 'Le pseudo ne peut contenir que des chiffres des lettres et des tirets (-)'));
        $this->pseudo->addRule(new \system\form\rule\Callback(function($value){
            return !$this->model->pseudoExists($value);
        }, 'Le pseudo existe déjà'));
        
        $this->pass->addRule(new \system\form\rule\Length(6, 32));
        $this->pass2->addRule(new \system\form\rule\EqualOther($this->pass, 'Les mots de passe doivent être identiques'));
        
        $this->email->addRule(new \system\form\rule\Regex('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/i', 'L\'e-mail doit être valide'));
        $this->email->addRule(new \system\form\rule\Callback(function($value){
            return !$this->model->mailExists($value);
        }, 'L\'e-mail a déjà été enregistré'));
        
        $name_regex = new \system\form\rule\Regex('/^[a-zéàèäëïöâêîôûçù -]{3,32}$/i', 'Le nom doit être valide');
        $this->firstName->addRule($name_regex);
        $this->lastName->addRule($name_regex);
        
        //attributes
        $this->email->setAttribute('type', 'email');
        $this->pass->setAttribute('type', 'password');
        $this->pass2->setAttribute('type', 'password');
        $this->pseudo->setAttribute('placeholder', 'Pseudo');
        $this->pass->setAttribute('placeholder', 'Mot de passe');
        $this->pass2->setAttribute('placeholder', 'Confirmation');
        $this->email->setAttribute('placeholder', 'E-Mail');
        $this->firstName->setAttribute('placeholder', 'Prénom');
        $this->lastName->setAttribute('placeholder', 'Nom');
        
        //add fields
        $this->addField($this->pseudo);
        $this->addField($this->pass);
        $this->addField($this->pass2);
        $this->addField($this->email);
        $this->addField($this->firstName);
        $this->addField($this->lastName);
        $this->addField($this->gender);
    }
    
    public function getID() {
        return 'reg_form';
    }

    public function getSubmitURL() {
        return 'register/submit/ajax.json';
    }

    public function getValidationURL() {
        return 'register/validate';
    }

    /**
     * 
     * @return \system\form\field\Field
     */
    public function getPseudo() {
        return $this->pseudo;
    }

    /**
     * 
     * @return \system\form\field\Field
     */
    public function getPass() {
        return $this->pass;
    }

    /**
     * 
     * @return \system\form\field\Field
     */
    public function getPass2() {
        return $this->pass2;
    }

    /**
     * 
     * @return \system\form\field\Field
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * 
     * @return \system\form\field\Field
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * 
     * @return \system\form\field\Field
     */
    public function getLastName() {
        return $this->lastName;
    }
    
    /**
     * 
     * @return \system\form\field\Field
     */
    public function getGender() {
        return $this->gender;
    }

}
